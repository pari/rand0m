<?php

function NotifyEventEmail( $notify_toUser, $notify_fromUser, $notify_subject , $notify_body ){
	$tmp_manageUsers = new manageUsers();
	$assigned_email = $tmp_manageUsers->get_userSingleDetail( $notify_toUser , 'user_primaryEmail');
	$from_email = $tmp_manageUsers->get_userSingleDetail( $notify_fromUser , 'user_primaryEmail');
	
	$email = new sendaMail();
	$email->messageTo( $assigned_email );
	$email->asFrom( $from_email );
	$email->AddCC( "comments_{$_SESSION['subdomain']}@discreteevents.com" );
	$email->subject( $notify_subject );
	$email->body( $notify_body );
	$email->send();
}

function send_Action_Response($RESP , $MSG){
	echo "Response: ".$RESP."\n" ;
	echo "ResponseMessage: ".$MSG ;
	exit();
}

function IsSadmin(){
	return @$_SESSION["uname"] == 'sadmin' ;
}

function getVariableFromSadminTbl($variable){
	return executesql_returnArray("select variablevalue from sadmin where variable='$variable' ;");
}

function getVariableFromMasterSubdomainRow($variable){
	// WARNING: function NOT intented SSADMIN, only intended for client logins
	$subdomain = $_SESSION["subdomain"] ;
	return executesql_returnArray("select ".$variable." from ".MASTERDB.".subdomains where subdomain='$subdomain'");
}

function logUserEvent( $eventdesc ){
	$subdomain = $_SESSION["subdomain"];
	$username = $_SESSION["uname"];	
	$IP = $_SERVER["REMOTE_ADDR"] ;
	$browser = $_SERVER["HTTP_USER_AGENT"] ;
	$tbl = MASTERDB.".userlog";
	$success = execute_sqlInsert($tbl, array(username=>$username, subdomain=>$subdomain, action=>$eventdesc, IP=>$IP, browser=>$browser ) );
}

function checkUserSessionandCookie(){
	if( ! @$_SESSION["uname"] ){
		// if no active session, check for cookie
		$tmp_result = mysql_query("delete from cookies where cookietime <  DATE_SUB(curdate(), INTERVAL 7 DAY)"); // delete cookies older than 7 days
		if (isset($_COOKIE[USERCOOKIENAME])) {
			$tmp_cookie = $_COOKIE[USERCOOKIENAME] ;
			$cookieUser = executesql_returnArray("select username from cookies where cookieid='$tmp_cookie'" );
			if( $cookieUser ){
				$userStillExists = executesql_returnArray("select count(*) as stillexists from users where username='$cookieUser' and user_status='A' ;");
				if($userStillExists == 1 || $userStillExists == '1'){
					$_SESSION["uname"] = "$cookieUser";
					logUserEvent( $uname.' (User) Logged In' );
					return;
				}
			}
		}
		$currentfile =  getCurrentScriptFileName();
		if( $currentfile != 'index.php' ){
			header("Location: index.php?rurl=" . $currentfile );
			exit();
		}
	}
}


function authenticateUser( $uname, $uepwd ){
	global $DE_GLOBALS_USERLOGINERR;
	if( $uname == "sadmin" ){
		$password = getVariableFromSadminTbl('sadminpass');
	}else{
		$password = executesql_returnArray("select password from users where username='$uname' and user_status='A' ;");
	}
	if(!$password || $password != $uepwd){
		return false;
	}
	return true;
}



function loginUser( $uname, $uepwd ){
	global $DE_GLOBALS_USERLOGINERR;
	if( $uname == "sadmin" ){
		$password = getVariableFromSadminTbl('sadminpass');
	}else{
		$password = executesql_returnArray("select password from users where username='$uname' and user_status='A' ;");
	}
	if(!$password || $password != $uepwd){
		send_Action_Response('Fail' , $DE_GLOBALS_USERLOGINERR);
	}

	$_SESSION["uname"] = "$uname";
	if(get_POST_var('setcuky') == 'true'){
		$cookieid = getaRandomString(32);
		setcookie( USERCOOKIENAME , $cookieid, time()+(7*24*3600) );
		$success = execute_sqlInsert('cookies', array(username=>$uname, cookieid=>$cookieid, cookietime=>'CURRENT_TIMESTAMP') );
	}
	logUserEvent( $uname.' (User) Logged In' );
	alertAppAdmin( $uname.' (User) Logged In' );
	send_Action_Response('Success' , 'To Welcome Page');
	exit();
}


function checkPermissions_canUserDeleteTask( $username, $taskID ){
	// only sadmin and the person who added task can delete
	if( strtolower($username) == 'sadmin' ){ return true;}
	$manageWorks = new manageWorks();
	$taskdetails = $manageWorks->get_workDetails($taskID);
	return $username == $taskdetails['work_addedBy'] ;
}


function checkPermissions_canUserViewTask( $username, $taskID ){
	// sadmin, people in the project, (if private task - only who created, and who it was assigned to)
	if( strtolower($username) == 'sadmin' ){ return true;}
	$manageWorks = new manageWorks();
	$taskdetails = $manageWorks->get_workDetails($taskID);
	if( $username == $taskdetails['work_addedBy'] || $username == $taskdetails['work_userAssigned'] ){
		return true;
	}
	if( $taskdetails['work_isPrivate']=='Y' ){
		return false;
	}
	$manageProjects = new manageProjects();
	return $manageProjects->isUserInProject( $username, $taskdetails['work_projectName'] );
}


function checkPermissions_canUserEditTask( $username, $taskID ){
	// sadmin and person who created, who it was assigned to
	if( strtolower($username) == 'sadmin' ){ return true;}
	$manageWorks = new manageWorks();
	$taskdetails = $manageWorks->get_workDetails($taskID);
	if( $username == $taskdetails['work_addedBy'] || $username == $taskdetails['work_userAssigned'] ){
		return true;
	}
	return false;
}


function returnPackagesJSObject(){
	$packages = array();
	$sqlquery= "select pkgId, pkgName, pkgNumberOfUsers, pkgSpaceMb from ".MASTERDB.".packages ORDER BY pkgId";
	$query = mysql_query($sqlquery) or die("Invalid query: " . mysql_error()); 
	WHILE ($row = @mysql_fetch_array($query)){
		extract($row); // $pkgId, $pkgName, $pkgNumberOfUsers, $pkgSpaceMb
		$packages[$pkgId] = array('Name'=>$pkgName, 'Users'=>$pkgNumberOfUsers , 'space'=>$pkgSpaceMb );
	}
	echo "var PACKAGES = ". json_encode($packages) . ";" ;
}


function returnUsersUnderProjectsJSObject($user){
	// this function returns a JS object with the projects (the user is a member of) as properties
	// and members of each project as property value
	$output = array();
	$manageProjects = new manageProjects();
	$myProjects = $manageProjects->getActiveProjectsListOfUser($user);
	foreach( $myProjects as $project ){
		$output[$project] = $manageProjects->getUsersListInProject($project);
	}
	$output[DEFAULTPERSONALPROJECT] = array($user) ;
	echo 'var MYPROJECTS_USERS = '. json_encode($output) . ' ; ' ;
}


function caldate_toHuman_Deadline($deadline){
	if(!$deadline){ return ''; }
	if( getTomorrowCaldate(0) == $deadline ||  getTomorrowCaldate(1) == $deadline || getTomorrowCaldate(2) == $deadline ){
		return "<span style='color: #4C49B7; font-weight: bold;'>".caldate_to_human($deadline, true)."</span>"; 
	}
	if(caldate_isInPast($deadline)){
		return "<span style='color: #F42C20; font-weight: bold;'>".caldate_to_human($deadline, true)."</span>"; 
	}
	return caldate_to_human($deadline, true);
}


function Task_LogSystemComment($workID, $newComment ){
	$tmpwork = new manageWorks();
	$tmpwork->addComment( $workID, APPNAME , $newComment );
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
/////////////////////////// M A I N   DISCRETE EVENT APP Functions & CLASSES /////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

class manageProjects {
	public function listOfAllProjects(){
		$toreturnProjects = executesql_returnStrictArray("select ProjectName from projects where isActive='Y'");
		$toreturnProjects[] = DEFAULTPERSONALPROJECT ;
		return $toreturnProjects;
	}

	public function listOfAllProjectsClosedProjects(){
		return executesql_returnStrictArray("select ProjectName from projects where isActive='N'");
	}

	public function listOfAllProjectsIncludeClosed(){
		$toreturnProjects = executesql_returnStrictArray("select ProjectName from projects");
		$toreturnProjects[] = DEFAULTPERSONALPROJECT ;
		return $toreturnProjects;
	}
	
	public function isProjectActive( $project ){
		if( $project == DEFAULTPROJECT || $project == DEFAULTPERSONALPROJECT ){return true;}
		$isActive = executesql_returnArray("select isActive from projects where ProjectName='$project' ");
		return $isActive == "Y";
	}

	public function canProjectBeDeleted( $project ){
		$projectWorks = executesql_returnArray("select count(workID) from WORKS where work_projectName='$project' ");
		return !($projectWorks > 0);
	}
	
	public function getProjectDescription( $project ){
		return executesql_returnArray("select ProjectDescription from projects where ProjectName='$project' ");
	}
	
	public function setProjectDescription( $project , $projectDescription){
		return executesql_returnArray("update projects set ProjectDescription='$projectDescription' where ProjectName='$project';");
	}
	
	public function getUsersListInProject( $project ){
		return executesql_returnStrictArray("select distinct username from permissions where ProjectName='$project' ");
	}
	
	public function getActiveProjectsListOfUser( $user ){
		// returns the list of all active projects the user is a member of.
		$toreturnProjects = array();
		if($user=='sadmin'){
			$toreturnProjects = $this->listOfAllProjects();
		}else{
			$toreturnProjects = executesql_returnStrictArray( "select distinct permissions.ProjectName from permissions, projects where permissions.username='$user' and projects.isActive='Y' " );
		}
		$toreturnProjects[] = DEFAULTPERSONALPROJECT ;
		return $toreturnProjects;
	}
	
	public function createNewProject( $projectName, $projDesc ){
		if( $projectName == DEFAULTPERSONALPROJECT){ send_Action_Response('Fail' , 'reserved project name!'); return;}
		$success = execute_sqlInsert("projects", array(ProjectName=>$projectName, ProjectDescription=>$projDesc ) );
	}
	
	public function closeProject( $projectName ){
		if( $projectName == DEFAULTPROJECT ){ // do let close the default project
			send_Action_Response('Fail' , 'Can not close default project!');
			return; 
		}
		$query = mysql_query("update `projects` set isActive='N' where `ProjectName` = '$projectName' ") ;
	}
	
	public function deleteProject( $projectName ){
		if( $projectName == DEFAULTPROJECT ){ // do let delete the default project
			// TODO return error
			send_Action_Response('Fail' , 'Can not delete default project!');
			return; 
		}
		if(!$this->canProjectBeDeleted($projectName)){
			send_Action_Response('Fail' , 'Can not delete project with works!');
			return; 
		}
		$query = mysql_query("delete from `projects` where `ProjectName` = '$projectName' ");
	}
	
	public function isUserInProject($username, $project){
		$usersInProject = $this->getUsersListInProject( $project );
		return in_array($username, $usersInProject);
	}
	
	public function addUserToProject( $username, $project ){
		$success = execute_sqlInsert("permissions", array(username=>$username, ProjectName=>$project ) );
	}
	
	public function deleteUserfromProject( $username, $project ){
		$query = mysql_query("delete from `permissions` where `username`='$username' and `ProjectName`='$project' ");
	}
	
} // end of 'manageProjects' class









// USER MANAGEMENT
class manageUsers {
	var $user = '';
	public function listOfAllUsers(){
		return executesql_returnStrictArray("select username from users");
	}
	
	public function listOfAllPeerUsers($username){
		if(strtolower($username) == 'sadmin'){
			return $this->listOfAllUsers();
		}
		return executesql_returnStrictArray("select distinct username from permissions where ProjectName=ANY(select ProjectName from permissions where username='$username') ORDER BY username");
	}
	
	public function get_userDetails( $username ){
		return executesql_returnAssocArray(" select `password`,`user_reportsTo`,`user_primaryEmail`,`user_alertEmail`,`user_phNo`,`user_mobileNo`,`user_type`,`user_status` from users where username='$username' ");
	}
	
	public function get_userSingleDetail( $username, $fieldname ){
		return executesql_returnArray("select ".$fieldname." from users where username='$username'");
	}
	
	public function get_usersActiveProjects($username){
		if( strtolower($username) == 'sadmin' ){
			$manageProjects = new manageProjects();
			return $manageProjects->listOfAllProjects();
		}

		$toreturnProjects = executesql_returnStrictArray(" select permissions.ProjectName from permissions, projects where permissions.username='$username' and permissions.ProjectName=projects.ProjectName and projects.isActive='Y'");
		$toreturnProjects[] = DEFAULTPERSONALPROJECT ;
		return $toreturnProjects;
	}
	public function addUser( $userDetails ){
		// $userDetails = array(username=>'newusername' , password=>'newuserPassword',  user_reportsTo=>'' , user_primaryEmail=>'', user_alertEmail=>'', user_phNo=>'', user_mobileNo=>'', user_type=>'', user_status=>'' );
		$success = execute_sqlInsert("users", $userDetails );
	}
	
	public function deleteUser( $username ){
		$sqlquery= "delete from `users` where `username`='$username' ";
		$query = mysql_query($sqlquery);
	}
	
	public function updateUser( $username, $newDetails ){
		// $newDetails = array(password=>'newuserPassword',  user_reportsTo=>'' , user_primaryEmail=>'', user_alertEmail=>'', user_phNo=>'', user_mobileNo=>'', user_type=>'', user_status=>'' );
		$success = execute_sqlUpdate("users" , $newDetails , array(username=>$username) );
	}

	public function getUnreadCommentsCount($username){
		// TODO: handle case for sadmin
		$lastReadCommentIndex = executesql_returnArray("select user_lastReadCommentIndex from users where username='$username'");
		if(!$lastReadCommentIndex){ $lastReadCommentIndex=0; }
		$result = mysql_query("select COMMENTS.commentID as commentID, COMMENTS.workID as workID, COMMENTS.comment_date as comment_date, COMMENTS.comment_by as comment_by, COMMENTS.comment as comment from COMMENTS, WORKS where COMMENTS.commentID > ".$lastReadCommentIndex." and COMMENTS.comment_by!='$username' and COMMENTS.workID=WORKS.workID and (WORKS.work_userAssigned='$username' or WORKS.work_addedBy='$username' ) ORDER BY COMMENTS.commentID DESC");
		$nwcount = @mysql_num_rows($result);
		return $nwcount;
	}

	public function updateLastReadCommentIndex($username, $lrci){
		$success = execute_sqlUpdate("users" , array(user_lastReadCommentIndex=>$lrci), array(username=>$username) );
	}

	public function getUnreadCommentsiPhone($username){
		$lastReadCommentIndex = executesql_returnArray("select user_lastReadCommentIndex from users where username='$username'");
		if(!$lastReadCommentIndex){ $lastReadCommentIndex=0; }
		$result = mysql_query("select COMMENTS.commentID as commentID, COMMENTS.workID as workID, COMMENTS.comment_date as comment_date, COMMENTS.comment_by as comment_by, COMMENTS.comment as comment from COMMENTS, WORKS where COMMENTS.commentID > ".$lastReadCommentIndex." and COMMENTS.workID=WORKS.workID and (WORKS.work_userAssigned='$username' or WORKS.work_addedBy='$username' ) ORDER BY COMMENTS.commentID DESC");

		$readresult = mysql_query("select COMMENTS.commentID as commentID, COMMENTS.workID as workID, COMMENTS.comment_date as comment_date, COMMENTS.comment_by as comment_by, COMMENTS.comment as comment from COMMENTS, WORKS where COMMENTS.commentID <= ".$lastReadCommentIndex." and COMMENTS.workID=WORKS.workID and (WORKS.work_userAssigned='$username' or WORKS.work_addedBy='$username' ) ORDER BY COMMENTS.commentID DESC LIMIT 15");

		$nwcount = @mysql_num_rows($result);
		$rccount = @mysql_num_rows($readresult);
		IF ( $nwcount==0 && $rccount==0 ){
			//echo "<div id='comments' class=\"panel\" title=\"No Comments\"><h2>No Comments</h2></div>";
			//return ;
		}

		$lastCommentPerson = '';
		$manageWorks = new manageWorks();
		$rowcount = 0 ;

		echo "<div id='comments' class=\"panel\" title=\"Comments\">";
		while ($row = mysql_fetch_assoc($result)) { // $row['commentID'], $row['workID'], $row['comment_date'], $row['comment_by'], $row['comment']
			$thisworkdetails = $manageWorks->get_workDetails($row['workID']);
			$this_bdesc = $thisworkdetails['work_briefDesc'] ;
			if($lastCommentPerson <> $row['comment_by']){ 
				echo "<div class=\"commentBy\">".$row['comment_by']." said:</div>";
			}
			echo "<div class=\"commentBdesc\">";
				echo "Task ".$row['workID'].": ".getNwordsFromString($this_bdesc, 7);
				echo mynl2br($row['comment']);
			echo "</div>";
			echo "<div class=\"commentDate\">".caldateTS_to_humanWithTS( $row['comment_date'] )."</div>";
			$lastCommentPerson = $row['comment_by'];
		}

		while ($row = mysql_fetch_assoc($readresult)) { // $row['commentID'], $row['workID'], $row['comment_date'], $row['comment_by'], $row['comment']
			$thisworkdetails = $manageWorks->get_workDetails($row['workID']);
			$this_bdesc = $thisworkdetails['work_briefDesc'] ;
			if($lastCommentPerson <> $row['comment_by']){ 
				echo "<div class=\"commentBy\">".$row['comment_by']." said:</div>";
			}
			echo "<div class=\"commentBdesc\">";
				echo "Task ".$row['workID'].": ".getNwordsFromString($this_bdesc, 7);
				echo mynl2br($row['comment']);
			echo "</div>";
			echo "<div class=\"commentDate\">".caldateTS_to_humanWithTS( $row['comment_date'] )."</div>";
			$lastCommentPerson = $row['comment_by'];
		}
		echo "</div>";
	}



	public function getUnreadComments($username, $howmany=25){
		// get the lastReadCommentIndex
		// get any comments greater than lastReadCommentIndex  where commentTask is either 'assigned to'  or 'assigned by' $username
		// mark the first one to use for markAllCommentsRead()
		$lastReadCommentIndex = executesql_returnArray("select user_lastReadCommentIndex from users where username='$username'");
		if(!$lastReadCommentIndex){ $lastReadCommentIndex=0; }
		$result = mysql_query("select COMMENTS.commentID as commentID, COMMENTS.workID as workID, COMMENTS.comment_date as comment_date, COMMENTS.comment_by as comment_by, COMMENTS.comment as comment from COMMENTS, WORKS where COMMENTS.commentID > ".$lastReadCommentIndex." and COMMENTS.workID=WORKS.workID and (WORKS.work_userAssigned='$username' or WORKS.work_addedBy='$username' ) ORDER BY COMMENTS.commentID DESC");

		$readresult = mysql_query("select COMMENTS.commentID as commentID, COMMENTS.workID as workID, COMMENTS.comment_date as comment_date, COMMENTS.comment_by as comment_by, COMMENTS.comment as comment from COMMENTS, WORKS where COMMENTS.commentID <= ".$lastReadCommentIndex." and COMMENTS.workID=WORKS.workID and (WORKS.work_userAssigned='$username' or WORKS.work_addedBy='$username' ) ORDER BY COMMENTS.commentID DESC LIMIT {$howmany}");


		$nwcount = @mysql_num_rows($result);
		$rccount = @mysql_num_rows($readresult);
		IF ( $nwcount==0 && $rccount==0 ){ return; }
		$lastCommentPerson = '';
		$manageWorks = new manageWorks();
		?>
		<script>
			var update_LRCI = function(username, lrci){
				DE_USER_action( 'update_LRCI' , {
					username : username,
					lrci : lrci,
					callback:function(a){
						if(a){ window.location.href = 'comments.php'; }else{ My_JsLibrary.showErrMsg() ; }
					}
				});
			};
		</script>
		<table style='margin-top:20px;'>
		<?php
		$rowcount = 0 ;
		while ($row = mysql_fetch_assoc($result)) {
			// $row['commentID'], $row['workID'], $row['comment_date'], $row['comment_by'], $row['comment']
			// get description of this workID
			if($rowcount == 0 && $nwcount > 0){ ?>
				<tr><td></td>
					<td align='right'> <a class='blueTextButton' href='#' onclick="update_LRCI('<?php echo $username; ?>','<?php echo $row['commentID'];?>');">mark as read</a> </td>
				</tr>
				<?
			} $rowcount++;

			$thisworkdetails = $manageWorks->get_workDetails($row['workID']);
			$this_bdesc = stripslashes($thisworkdetails['work_briefDesc']) ;
			?>
			<tr><td valign='top' align='right'>
					<span style='font-weight:bold;'> <?php if($lastCommentPerson <> $row['comment_by']){ echo $row['comment_by']; } ?> </span>
				</td>
				<td><div class='ncs1'>
						<div class='ncd0' onclick="ManageTasksJsFunction.detailsWork('<?php echo $row['workID']; ?>');">
							<?php echo "Task ".$row['workID'].": ".getNwordsFromString($this_bdesc, 7); ?> ... 
						</div>
						<div class='ncd1'><?php echo format_makeLinks(stripslashes(mynl2br($row['comment']))) ;?> </div>
						<div class='nct1'><?php echo caldateTS_to_humanWithTS( $row['comment_date'] ); ?> </div>
					</div>
				</td>
			</tr>
			<?
			$lastCommentPerson = $row['comment_by'];
		}

		while ($row = mysql_fetch_assoc($readresult)) {
			// $row['commentID'], $row['workID'], $row['comment_date'], $row['comment_by'], $row['comment']
			// get description of this workID
			$thisworkdetails = $manageWorks->get_workDetails($row['workID']);
			$this_bdesc = $thisworkdetails['work_briefDesc'] ;
			$this_classname = 'rcs1' ;
			?>
			<tr><td valign='top' align='right'>
					<span style='font-weight:bold; color: #A4A4A4;'> <?php if($lastCommentPerson <> $row['comment_by']){ echo $row['comment_by']; } ?> </span>
				</td>
				<td><div class='rcs1'>
						<div class='ncd0' onclick="ManageTasksJsFunction.detailsWork('<?php echo $row['workID']; ?>');">
							<?php echo "Task ".$row['workID'].": ".getNwordsFromString($this_bdesc, 7); ?> ... 
						</div>
						<div class='ncd1'><?php echo mynl2br($row['comment']);?> </div>
						<div class='nct1'><?php echo caldateTS_to_humanWithTS( $row['comment_date'] ); ?> </div>
					</div>
				</td>
			</tr>
			<?
			$lastCommentPerson = $row['comment_by'];
		}
		echo "</table>";
	}
	
	public function getlast100comments($username){
		// get comments where commentTask is either 'assigned to'  or 'assigned by' $username order by commentID desc limit 100
	}
	public function markAllCommentsRead($username, $commentID){
		// update lastReadCommentIndex to $commentID
	}
} // End of class "manageUsers"


// Scheduled Emails
class scheduledEmails{

	public function getSheduledEmail($emailId){
		$USERNAME = $_SESSION['uname'];

		$result = executesql_returnAssocArray("select email_to, email_subject, email_content as email_body, DATE(email_scheduledon) as email_date, HOUR(email_scheduledon) as email_hour from scheduledmails where sch_emailid='$emailId' and emailby_user='$USERNAME' ");
		if(!$result){ return 'var tmp_thisemail = {}'; }
		$result['email_subject'] = base64_encode($result['email_subject']);
		$result['email_body'] = base64_encode($result['email_body']);

		return "var tmp_thisemail = " . json_encode($result) ;
	}

	public function listScheduledEmails(){
		$USERNAME = $_SESSION['uname'];
		$result = mysql_query("select * from scheduledmails where email_sent='N' and emailby_user='$USERNAME' order by email_scheduledon");
		$scE_Count = @mysql_num_rows($result);
		if($scE_Count == 0){ 
			?>
			<div style='clear:both; margin-top:40px; margin-left: auto; margin-right:auto; width:96%;'>
				<span class='bluebuttonSmall' onclick='show_scheduleEmailForm();'>New Reminder</span>
			</div>
			<div class='nonewtasks'>No Reminders </div>
			<?php
			return ;
		}


		?>
		<div class='listOfScheduledTasks' style='margin-top:40px;'>
			<span class='listofnewtasksSpan' onclick=" $('.scheduledEmailsTable').toggle();">Reminders (<?php echo $scE_Count ; ?>)</span>
			<span class='bluebuttonSmall' onclick='show_scheduleEmailForm();'>New Reminder</span>
		</div>

		<table align=center cellpadding=0 cellspacing=0 class="scheduledEmailsTable">
			<TR>
				<TD class="firstRow">Email To:</TD>
				<TD class="firstRow">Subject</TD>
				<TD class="firstRow">Body</TD>
				<TD class="firstRow">Scheduled On</TD>
				<TD class="firstRow">&nbsp;</TD>
			</TR>
		<?php
		$tdclass = "oddrow" ;
		while ($row = mysql_fetch_assoc($result)) { 
			$tdclass = ( $tdclass == "oddrow" ) ? 'evenrow' : 'oddrow' ;	
			?>
			<tr>
				<td class='<?php echo $tdclass; ?>'><?php echo $row['email_to'] ; ?></td>
				<td class='<?php echo $tdclass; ?> editReminder' emailid='<?php echo $row['sch_emailid'] ; ?>'>
					<span class='linkTextButton'><?php echo $row['email_subject'] ; ?></span>
				</td>
				<td class='<?php echo $tdclass; ?> editReminder' emailid='<?php echo $row['sch_emailid'] ; ?>'>
					<span class='linkTextButton'><?php echo $row['email_content'] ; ?></span>
				</td>
				<td class='<?php echo $tdclass; ?>'><?php echo caldateTS_to_humanWithTS($row['email_scheduledon'], true) ; ?></td>
				<td class='<?php echo $tdclass; ?>'>
					<span class='bluebuttonSmall' onclick="delete_scheduledEmail('<?php echo $row['sch_emailid']; ?>');">Delete</span>
				</td>
			</tr>
			<?php
		}
		echo "</table>";
	}

	public function deleteScheduleEmail( $schid ){
		$USERNAME = $_SESSION['uname'];
		$result = mysql_query("delete from scheduledmails where sch_emailid='$schid' and emailby_user='$USERNAME' ");
	}

	public function updateScheduledEmail( $reminderId, $email_to , $email_content , $subject, $caldate_day , $hour ){
		$delivery_ts = $caldate_day.' '.$hour.':01:00' ; // 'YYYY-MM-DD HH:MM:SS'
		$newDetails = array( email_to=>$email_to, email_content=>$email_content , email_subject=>$subject , email_scheduledon=>$delivery_ts );
		$success = execute_sqlUpdate( 'scheduledmails' , $newDetails , array(sch_emailid=>$reminderId) ) ;
	}

	public function scheduleNewEmail( $email_to , $email_content , $subject, $caldate_day , $hour ){
		$USERNAME = $_SESSION['uname'] ;
		$manageUsers = new manageUsers();
		$thisuserEmailId = $manageUsers->get_userSingleDetail( $USERNAME, 'user_primaryEmail' );
		$delivery_ts = $caldate_day.' '.$hour.':01:00' ; // 'YYYY-MM-DD HH:MM:SS'
		$details = array( emailby_user=>$USERNAME, email_to=>$email_to , email_content=>$email_content , emailby_from=>$thisuserEmailId , email_subject=>$subject , email_scheduledon=>$delivery_ts );
		$success = execute_sqlInsert( "scheduledmails", $details );
	}
}
// End of Scheduled Emails


// SEARCH
class searchReport{
	
	var $search_ftext = '' ;
	
	public function search_ftext($searchString){ 
		$this->search_ftext = $searchString ;
	}
	/*
	public function search_fromdate( $fromdate ){ }
	public function search_todate( $todate ){ }
	public function search_username( $username ) { } 
	public function search_taskid( $taskid );
	$sreport->search_results();
	*/
	public function search_results(){
		$searchTxt = $this->search_ftext ;
		$CURRENT_USER = $_SESSION['uname'];
		
		// Search Comments
		$sqltoExecute="select DISTINCT `workID`, `comment_date`, `comment_by`, `comment` from COMMENTS where (MATCH(`comment`) AGAINST ('{$searchTxt}')) or `comment` like '%{$searchTxt}%' " ;
		//echo '***'."$sqltoExecute".'***';
		$result = mysql_query($sqltoExecute) or die("Invalid query: " . mysql_error());
		$stcount = @mysql_num_rows($result);
		IF ($stcount){ 
			echo "<table align=center width='97%' cellpadding=2 cellspacing=2 border=0 style='font-size: 95%; margin-top:15px;'>";
			while ($row = mysql_fetch_assoc($result)) { 
				echo "
					<tr>
						<td>{$row['comment_by']}</td>
						<td>{$row['comment']}</td>
						<td>{$row['comment_date']}</td>
					</tr>
				" ;
			}
			echo "</table>";
		}

		// Search Notes
		$sqltoExecute="select DISTINCT `note_text` from NOTES where note_user='$CURRENT_USER' and ((MATCH(`note_text`) AGAINST ('{$searchTxt}')) or (`note_text` like '%{$searchTxt}%') ) " ;
		$result = mysql_query($sqltoExecute) or die("Invalid query: " . mysql_error());
		$stcount = @mysql_num_rows($result);
		IF ($stcount){ 
			echo "<table align=center width='97%' cellpadding=2 cellspacing=2 border=0 style='font-size: 95%; margin-top:15px;'>";
			while ($row = mysql_fetch_assoc($result)) { 
				echo "
					<tr>
						<td>".mynl2br(getNwordsFromString($row['note_text'] , 13))."...</td>
					</tr>
				" ;
			}
			echo "</table>";
		}
		
		// Search scheduledEmails
		
		// search Works desc, note
		
	}



}


// NOTES
class manageNotes{
	var $USERNAME = '';
	public function getNoteWithID($nid){
		$USERNAME = $this->USERNAME;
		if(!$USERNAME){ $USERNAME = $_SESSION['uname']; }
		if(!$USERNAME){ return false;}
		return executesql_returnArray("select note_text from NOTES where note_user='$USERNAME' and noteID='$nid' ");
	}
	
	public function deleteNoteWithID($nid){
		$USERNAME = $this->USERNAME;
		if(!$USERNAME){ $USERNAME = $_SESSION['uname']; }
		if(!$USERNAME){ return false;}
		$query = mysql_query("delete from NOTES where note_user='$USERNAME' and noteID='$nid' ");
	}
	
	public function updateNote( $nid , $newNoteText ){
		$USERNAME = $this->USERNAME;
		if(!$USERNAME){ $USERNAME = $_SESSION['uname']; }
		if(!$USERNAME){ return false;}
		$query = mysql_query("update NOTES set note_text='$newNoteText' where note_user='$USERNAME' and noteID='$nid' ");
	}
	
	public function insertNote( $newNote ){
		$USERNAME = $this->USERNAME;
		if(!$USERNAME){ $USERNAME = $_SESSION['uname']; }
		if(!$USERNAME){ return false;}
		$success = execute_sqlInsert( "NOTES", array('note_user'=>$USERNAME, 'note_text'=>$newNote) );
	}
}
//End of Notes

/*
`WORKS` (
  `workID`,
  `work_userAssigned`,
  `work_addedBy` ,
  `work_dateAdded` timestamp ,
  `work_deadLine` date ,
  `work_startDate` timestamp,
  `work_completeDate` timestamp,
  `work_briefDesc` text,
  `work_Notes` text,
  `work_status` '1',
  `work_priority`  'N',
  `work_projectName`,
  `work_isPrivate` 'N',
);
*/


class manageWorks {
	public function newRecurringTask ($nrt_details){
		//`RTID`, `RT_project`, `RT_assignto`, `RT_createdby`, `RT_createdDate`, `RT_Desc`, `RT_isPrivate`, `RT_startdate`, `RT_enddate`, `RT_type`, `RT_EVERYNDAYS`, `RT_EVERYNTHDAYOFMONTH`, `RT_EVERYXWEEKDAY`, `RT_EVERYDAYOFYEAR_MONTH`
		global $DE_GLOBALS_WORK_SCHEDULED ;
		
		$newRTDetails = array();
		$newRTDetails['RT_project'] = $nrt_details['nrt_project'];
		$newRTDetails['RT_Desc'] = $nrt_details['nrt_desc'];
		$newRTDetails['RT_assignto'] = $nrt_details['nrt_assignTo'];
		$newRTDetails['RT_createdby'] = $nrt_details['nrt_createdby'] ;
		$newRTDetails['RT_startdate'] = $nrt_details['nrt_startDate'];
		$newRTDetails['RT_enddate'] = $nrt_details['nrt_endDate'];
		$newRTDetails['RT_type'] = $nrt_details['nrt_type'];
		$newRTDetails['RT_EVERYNDAYS'] = '';
		$newRTDetails['RT_EVERYNTHDAYOFMONTH'] = '';
		$newRTDetails['RT_EVERYXWEEKDAY'] = '';
		$newRTDetails['RT_EVERYDAYOFYEAR_MONTH'] = '';
		$newRTDetails['RT_deadlineDays'] = $nrt_details['nrt_deadlineDays'];
		
		switch ($nrt_details['nrt_type']) {
			case "W": $newRTDetails['RT_EVERYXWEEKDAY'] = $nrt_details['nrt_frq_weekday'] ;	break;
			case "M": $newRTDetails['RT_EVERYNTHDAYOFMONTH'] = $nrt_details['nrt_frq_dayofmonth']; break;
			case "N": $newRTDetails['RT_EVERYNDAYS'] = $nrt_details['nrt_frq_ndays']; break;
			case "Y": 
				$newRTDetails['RT_EVERYNTHDAYOFMONTH'] = $nrt_details['nrt_frq_EVERYDAYOFYEAR_day'];
				$newRTDetails['RT_EVERYDAYOFYEAR_MONTH'] = $nrt_details['nrt_frq_EVERYDAYOFYEAR_month'] ;
			break;
		}
		
		$newRTDetails['RT_isPrivate'] = $nrt_details['nrt_isPublic'];
		//$newRTDetails['RT_LASTRANON'] = '2008-01-01';
		
		$success = execute_sqlInsert("RECCURING_TASKS", $newRTDetails );
		$new_RTID = mysql_insert_id();
		// Created Recurring Task
		
		// Now Create Schedules tasks for all matched instances in this date range
		$MATCHED_DATES = get_DateInstances_inTimeRange( array(
			'StartDate' => $newRTDetails['RT_startdate'],
			'EndDate' => $newRTDetails['RT_enddate'],
			'RT_EVERYNTHDAYOFMONTH' => $newRTDetails['RT_EVERYNTHDAYOFMONTH'] ,
			'RT_EVERYDAYOFYEAR_MONTH ' => $newRTDetails['RT_EVERYDAYOFYEAR_MONTH'] ,
			'RT_EVERYNDAYS'=> $newRTDetails['RT_EVERYNDAYS'] ,
			'RT_EVERYXWEEKDAY' => $newRTDetails['RT_EVERYXWEEKDAY'],
			'nrt_type' => $newRTDetails['RT_type']
		) );
		$newTASKDetails = array();
		$newTASKDetails['work_userAssigned'] = $newRTDetails['RT_assignto'] ;
		$newTASKDetails['work_addedBy'] = $newRTDetails['RT_createdby'] ;
		$newTASKDetails['work_briefDesc'] = $newRTDetails['RT_Desc'] ;
		$newTASKDetails['work_status'] = $DE_GLOBALS_WORK_SCHEDULED ;
		$newTASKDetails['work_Notes'] = '' ;
		$newTASKDetails['work_priority'] = 'N' ;
		$newTASKDetails['work_isPrivate'] = $newRTDetails['RT_isPrivate'] ;
		$newTASKDetails['work_projectName'] = $newRTDetails['RT_project'] ;
		$newTASKDetails['daysb4deadline'] = $newRTDetails['RT_deadlineDays'] ;
		$newTASKDetails['work_RTID'] = $new_RTID ;
		$manageWorks = new manageWorks();
		foreach($MATCHED_DATES as $MATCHEDDATE){
			$newTASKDetails['work_deadLine'] = $MATCHEDDATE ;
			$manageWorks->newWork($newTASKDetails);
		}
	}
	
	public function newWork( $newWorkDetails ){
		/*
			$newWorkDetails = array(
				work_userAssigned => 'user1' ,
				work_addedBy => 'sadmin' ,
				work_deadLine => '2009-01-31' ,
				work_briefDesc => 'briefdesc' ,
				work_Notes => 'notes' ,
				work_status => '1' ,
				work_priority => 'N' ,
				work_projectName => 'Project1' ,
				work_isPrivate => 'N'
			);
		*/
		$newWorkDetails['work_dateAdded'] = get_currentPHPTimestamp();
		$success = execute_sqlInsert("WORKS", $newWorkDetails );
	}

	public function get_workDetails( $workID ){
		return executesql_returnAssocArray("select `work_userAssigned`, `work_addedBy`, `work_dateAdded`, `work_deadLine`, `work_startDate`, `work_completeDate`, `work_briefDesc`, `work_Notes`, `work_status`, `work_priority`, `work_projectName`, `work_isPrivate` from WORKS where workID='$workID' ");
	}

	public function taskHasComments( $workID ){
		$commentsCount = executesql_returnArray("select count(commentID) from COMMENTS where workID='$workID'");
		if($commentsCount=='0' || !$commentsCount){ return false;}
		return true;
	}

	public function taskHasNewComments( $workID ){
		$USERNAME = $_SESSION['uname'];
		$lastReadCommentIndex = executesql_returnArray("select user_lastReadCommentIndex from users where username='$USERNAME'");
		$lastCommentIDforthisWork = executesql_returnArray("select commentID from COMMENTS where workID='$workID' and comment_by!='$USERNAME' ORDER BY `commentID` DESC LIMIT 1");
		if(!$lastCommentIDforthisWork){$lastCommentIDforthisWork = 0;} $lastCommentIDforthisWork = (int)$lastCommentIDforthisWork;
		if(!$lastReadCommentIndex){$lastReadCommentIndex = 0;} $lastReadCommentIndex = (int)$lastReadCommentIndex;
		return $lastCommentIDforthisWork > $lastReadCommentIndex ;
	}

	public function taskHasAttachments( $workID ){
		$result = mysql_query( "select Id as attachId from attachments where workid='$workID'" ); 
		return (@mysql_num_rows($result) == 0) ? false : true ;
	}

	public function get_attachments($workID, $asDiv = true){
		$result = mysql_query("select Id as attachId, uploadname, filesize , uploadedby, uploadedOn from attachments where workid='$workID'"); 
		if(@mysql_num_rows($result) == 0){ return ''; }
		if($asDiv){
			$str = "<div id='fileAttachments_container'>" ;
		}else{
			$str = "<TR><TD valign=top align=right><span style=\"color: #3D4399; padding:1px; margin-right: 5px;font-weight:bold;\">Attachments: </span></TD><TD class='fileAttachments_container'>";
		}
		$target_path = "./attachments/".$_SESSION["subdomain"]."/" ;
		while ($row = mysql_fetch_assoc($result)) { // $row['attachId'] , $row['uploadname']
			if($asDiv){
				$str .= "<div class='filename' attachId=\"" . $row['attachId'] . "\">". $row['uploadname'] ."</div>" ;
			}else{
				$thisfilesize = formatBytesToHumanReadable( $row['filesize'] ) ;
				$str .= "<div style='padding:3px;'>";
				$str .= "<span class='filename' attachId=\"" . $row['attachId'] . "\">". $row['uploadname'] ."</span>" ;
				$str .= "<span> {$thisfilesize} by {$row['uploadedby']} - ".caldateTS_to_humanWithTS($row['uploadedOn'], true)."</span>" ;
				$str .= "</div>"; 
			}
		}
		$str .= ($asDiv) ? "</div>" : "</TD></TR>" ;
		return $str;
	}

	public function updateWork( $workID, $newDetails ){
		$success = execute_sqlUpdate("WORKS" , $newDetails , array(workID=>$workID) );
	}

	public function addComment( $workID, $commentBy , $newComment ){
		$success = execute_sqlInsert("COMMENTS", array(workID=>$workID, comment_by=>$commentBy,comment_date=>get_currentPHPTimestamp(), comment=>$newComment));
	}
	
	public function deleteTask($WORKID){
		global $DE_GLOBALS_WORK_DELETED;
		$this->updateWork( $WORKID, array('work_status'=>$DE_GLOBALS_WORK_DELETED) );
	}

	public function get_workComments( $workID , $foriPhone = false){
		$result = mysql_query("select comment_date, comment_by, comment from COMMENTS where workID='$workID' ORDER BY comment_date DESC");
		$lastCommentPerson = '';

		if( $foriPhone ){
			echo "<div id='taskcomments_" . $workID . "' class='divViewComments'>" ;
			while( $row = mysql_fetch_assoc($result) ){
				if( $lastCommentPerson <> $row['comment_by'] ){ 
					echo '<div class="commentBy">' . $row['comment_by'] . ' said:</div>' ;
				}
				echo '<div class="commentBdesc">' . format_makeLinks(mynl2br($row['comment'])) . '</div>';
				echo '<div class="commentDate">' . caldateTS_to_humanWithTS( $row['comment_date'] ) . '</div>';
				$lastCommentPerson = $row['comment_by'];
			}
			echo "</div>";
			return;
		}

		echo "<table align=center width='97%' cellpadding=2 cellspacing=2 border=0 style='font-size: 95%; margin-top:15px;'>";
		while ($row = mysql_fetch_assoc($result)) { ?>
			<tr><td valign='top' align='right' width='125'>
					<span style='font-weight:bold; color: #65869E;'>
						<?php if($lastCommentPerson <> $row['comment_by']){ echo $row['comment_by']; } ?>
					</span>
				</td>
				<td><div class='rcs1'>
						<div class='ncd1'><?php echo format_makeLinks(mynl2br($row['comment'])) ;?> </div>
						<div class='nct1'><?php echo caldateTS_to_humanWithTS( $row['comment_date'] ); ?> </div>
					</div>
				</td>
			</tr>
			<?php
			$lastCommentPerson = $row['comment_by'];
		}

		echo "</table>";
	}

	public function assignWorkToUser( $workID, $username ){
		$success = execute_sqlUpdate("WORKS" , array(work_userAssigned=>$username ) , array(workID=>$workID) );
	}

	public function resetWorkToNew( $workID ){
		global $DE_GLOBALS_WORK_NEW;
		$success = execute_sqlUpdate("WORKS" , array(work_status=>$DE_GLOBALS_WORK_NEW, work_startDate=>'NULL', work_completeDate=>'NULL') , array(workID=>$workID) );
	}

	public function startWork( $workID ){
		global $DE_GLOBALS_WORK_PROGRESS;
		$cts = get_currentPHPTimestamp();
		$success = execute_sqlUpdate("WORKS" , array(work_startDate=>$cts ,work_status=>$DE_GLOBALS_WORK_PROGRESS) , array(workID=>$workID) );
	}

	public function completeWork( $workID ){
		global $DE_GLOBALS_WORK_COMPLETED;
		$cts = get_currentPHPTimestamp();
		$success = execute_sqlUpdate("WORKS" , array(work_completeDate=>$cts, work_status=>$DE_GLOBALS_WORK_COMPLETED) , array(workID=>$workID) );
	}

	public function closeWork( $workID ){
		global $DE_GLOBALS_WORK_CLOSED;
		global $DE_GLOBALS_WORK_NEW;
		global $DE_GLOBALS_WORK_TASKONTASK;

		$currentTimeStamp = get_currentPHPTimestamp();
		$success = execute_sqlUpdate("WORKS",array(work_status=>$DE_GLOBALS_WORK_CLOSED, work_closedDate=>$currentTimeStamp), array(workID=>$workID));
		// Appear in List any tasks having $workID as 'afterCompletionID'
		$success = execute_sqlUpdate("WORKS",array(work_status=>$DE_GLOBALS_WORK_NEW), array(afterCompletionID=>$workID, work_status=>$DE_GLOBALS_WORK_TASKONTASK));
	}


} // ENd of manageWorks









// ***** Yay, here comes the reports ********** //
class taskReports {
	var $PersonalTasks = false;
	var $showOnlyMyTasks = false;
	var	$sqlselector = '' ;
	var $formedsql = false ;
	var $orderbyfield = '';
	var $doNotIncludePersonalCondition = false;
	var $sql_closedTasks = '';
	var $closedTasksPeriod = '' ;

	public function closedTasks( $period ){
		switch ($period) {
			case 'thismonth':
				$this->closedTasksPeriod = 'in this month' ;
				$currentMonth = date("m"); $currentYear = date("Y");
				$this->sql_closedTasks =  " and MONTH(work_closedDate)='{$currentMonth}' and YEAR(work_closedDate)='{$currentYear}' " ;
			break;

			case 'thisyear':
				$this->closedTasksPeriod = 'in this Year' ;
				$currentYear = date("Y");
				$this->sql_closedTasks =  " and YEAR(work_closedDate)='{$currentYear}' " ;
			break;


			case 'allclosed':
				$this->closedTasksPeriod = ' All closed tasks ' ;
				$this->sql_closedTasks =  " " ;
			break;

			case 'lastmonth':
				$this->closedTasksPeriod = 'in last month' ;
				$tmp_lastmonth_range = getLastMonth_FromToDates();
				$tmp_lastmonth_day1 = $tmp_lastmonth_range[0] ;
				list( $lastMonthYear, $lastMonthMonth, $notneeded) = split ("-", $tmp_lastmonth_day1);
				$this->sql_closedTasks =  " and MONTH(work_closedDate)='{$lastMonthMonth}' and YEAR(work_closedDate)='{$lastMonthYear}' " ;
			break;

			default:
				$this->sql_closedTasks =  "";
		}
	}


	public function formSqlSelector(){
		$USERNAME = $_SESSION["uname"];

		if($this->doNotIncludePersonalCondition){
		
		}else{
			if( $this->PersonalTasks ) {
				// personal tasks is a seperate exclusive report
				if( IsSadmin() ){
					$this->sqlselector .= " and work_projectName = '". DEFAULTPERSONALPROJECT ."' ";
				}else{
					$this->sqlselector .= " and work_projectName = '". DEFAULTPERSONALPROJECT ."' and work_userAssigned='$USERNAME'";
				}

				if( $this->orderbyfield ){
					$tmp_orderby = $this->orderbyfield ;
					$this->sqlselector .= ($tmp_orderby <> 'work_projectName') ? " ORDER BY work_projectName, $tmp_orderby DESC" : " ORDER BY work_projectName DESC" ;
				}else{
					$this->sqlselector .= " ORDER BY work_projectName DESC" ;
				}
				$this->formedsql = true ;
				return ; /* <<<<<=========================== */
			}else{
				// exclude personal tasks
				$this->sqlselector .= " and work_projectName != '". DEFAULTPERSONALPROJECT ."' ";
			}
		}

		if( $this->showOnlyMyTasks ){
			$this->sqlselector .= " and (work_userAssigned='$USERNAME' or work_addedBy='$USERNAME') " ;
		}else{
			// get all tasks from user's projects that are not private
			if( IsSadmin() ){
				// sadmin can see everything 
			}else{
				$this->sqlselector .= " and ((work_projectName=ANY( select ProjectName from permissions where username='$USERNAME' ) and work_isPrivate='N') or (work_userAssigned='$USERNAME') or (work_addedBy='$USERNAME') ) ";
			}
		}

		if( $this->orderbyfield ){
			$tmp_orderby = $this->orderbyfield ;
			$this->sqlselector .= ($tmp_orderby <> 'work_projectName') ? " ORDER BY work_projectName , $tmp_orderby DESC" : " ORDER BY work_projectName DESC" ;
		}else{
			$this->sqlselector .= " ORDER BY work_projectName DESC" ;
		}

		$this->formedsql = true ;
	}

	public function listJSWORKS(){
	?>
		<script>

			ManageTasksJsFunction.allClosedThisYear = function(p){
				if(p && p == 'all'){
					My_JsLibrary.updatePageWithGetVar('ctperiod', 'allclosed');
				}else{
					My_JsLibrary.updatePageWithGetVar('ctperiod', 'thisyear');
				}
			};

			ManageTasksJsFunction.DELETETHISTASK = function(taskid){
				if(!confirm('Are you sure? \n\n Task will be deleted permanently.\n There is no Undo for this action. ')){return;}
				DE_USER_action( 'deleteTask',
				{
					workid : taskid,
					callback:function(a){
						if(a){
							My_JsLibrary.windowReload();
						}else{
							alert(My_JsLibrary.responsemsg);
						}
					}
				});
			};
		
			ManageTasksJsFunction.startTask = function(wId){
				DE_USER_action('startTask', {
					workid : wId,
					callback:function(a){
						if(a){
							My_JsLibrary.windowReload();
						}else{
							My_JsLibrary.showErrMsg() ;
						}
					}
				});
			};

			ManageTasksJsFunction.resetWorkToNew = function(wId){ // ManageTasksJsFunction.resetWorkToNew( taskNo );
				DE_USER_action('resetWorkToNew', {
					workid : wId,
					callback:function(a){
						if(a){
							My_JsLibrary.windowReload();
						}else{
							My_JsLibrary.showErrMsg() ;
						}
					}
				});
			};

			ManageTasksJsFunction.completeWork = function(wId){ // ManageTasksJsFunction.completeWork( taskNo );
				DE_USER_action('completeWork', {
					workid : wId,
					callback:function(a){
						if(a){
							My_JsLibrary.windowReload();
						}else{
							My_JsLibrary.showErrMsg() ;
						}
					}
				});
			};

			ManageTasksJsFunction.closeWork = function(wId){ // ManageTasksJsFunction.closeWork( taskNo );
				DE_USER_action('closeWork', {
					workid : wId,
					callback:function(a){
						if(a){
							My_JsLibrary.windowReload();
						}else{
							My_JsLibrary.showErrMsg() ;
						}
					}
				});
			};


			$(document).ready(function() {
				$("TD.TREDITEMAILWORKS").click(function(){
					_$('nutask_project').selectedIndex = -1;
					_$('nutask_Private').checked = true;
					$('#nutask_Private_explanation').html('Uncheck to make this task public');
					_$('chk_ScheduleTask').checked = false ;
					_$('chk_taskontask').checked = false ;
					_$('text_daysb4deadline').disabled = true;
					_$('text_oncompletionof').disabled = true;
					_$('text_daysb4deadline').value = '' ;
					_$('text_oncompletionof').value = '' ;

					var this_workid = $(this).attr('workid') ; EDITEMAILTASKID = this_workid;
					var this_projectname = $(this).attr('projectName') ;
					var this_userAssigned = $(this).attr('userassigned') ;
					var this_addedBy = $(this).attr('addedBy') ;
					var this_deadline = $(this).attr('deadline') ;							
					// My_JsLibrary.showdeadcenterdiv( 'CreateTask_Form' );
					$('#CreateTask_Form').showWithBg();

					My_JsLibrary.selectbox.selectOption( _$('nutask_priority') ,  'N' );
					My_JsLibrary.selectbox.selectOption( _$('nutask_project') ,  this_projectname );
					update_ProjectUsers();
					My_JsLibrary.selectbox.selectOption( _$('nutask_userassigned') ,  this_userAssigned );
					_$('nutask_deadline').value = this_deadline;
					_$('nutask_bdesc').value = $(this).html().trim();
				});
			});


			ManageTasksJsFunction.createNewTask_form = function(afterid){ // ManageTasksJsFunction.createNewTask_form();
				EDITEMAILTASKID = '';
				NEWTASKFORM_VISIBLE = true;
				// My_JsLibrary.showdeadcenterdiv( 'CreateTask_Form' );
				$('#CreateTask_Form').showWithBg();
				_$('nutask_project').selectedIndex = -1;
				My_JsLibrary.selectbox.clear(_$('nutask_userassigned'));
				_$('nutask_Private').checked = true;
				$('#nutask_Private_explanation').html('Uncheck to make this task public');
				var curl = window.location.href;
				if( curl.contains('list=ptasks') ){
					My_JsLibrary.selectbox.selectOption( _$('nutask_project') ,  '<?php echo DEFAULTPERSONALPROJECT; ?>' );
					update_ProjectUsers();
				}
				_$('chk_ScheduleTask').checked = false ;
				_$('text_daysb4deadline').disabled = true;
				_$('text_oncompletionof').disabled = true;

				if(afterid){
					_$('text_oncompletionof').disabled = false;
					_$('chk_taskontask').checked = true;
					_$('text_oncompletionof').value = afterid;
				}

				_$('nutask_deadline').value = '<?php echo getTomorrowCaldate(1); ?>' ;
			};

			ManageTasksJsFunction.setUpcomingTask = function(wId, isNext){
				DE_USER_action('setUpcomingTask', {
					workid : wId,
					isNext : isNext,
					callback:function(a){
						if(a){
							My_JsLibrary.windowReload();
						}else{
							My_JsLibrary.showErrMsg() ;
						}
					}
				});
			};

		</script>
	<?php
	}


	public function listReccuringTasks(){
		include "include_reccuringtasks_js.php";
		$USERNAME = $_SESSION["uname"];
		
		$sqltoExecute="select * from RECCURING_TASKS where RT_createdby = '$USERNAME' order by RTID desc" ;
		$query = mysql_query($sqltoExecute) or die("Invalid query: " . mysql_error());

		$stcount = @mysql_num_rows($query);
		IF ($stcount==0){ 
		?>
			<div style='clear:both; margin-top:40px; margin-left: auto; margin-right:auto; width:96%;'>
				<span class='bluebuttonSmall' onclick='show_ReccuringTaskForm();'>New Recurring Task</span>
			</div>
			<div class='nonewtasks'>Recurring Tasks </div>
			<?php
			return;
		}
		?>
		<div class='listOfReccuringTasks' style='margin-top:50px;'>
			<span class='listofReccuringTasksSpan' onclick=" $('.ReccuringTasksTable').toggle();">Recurring Tasks (<?php echo $stcount ; ?>)</span>
			<span class='bluebuttonSmall' onclick='show_ReccuringTaskForm();'>New Recurring Task</span>
		</div>
		<table align=center cellpadding=0 cellspacing=0 class="ReccuringTasksTable">
			<TR class="firstRow">
				<TD width="100" align='right' style='cursor:pointer;'>
					<span style='margin-right:20px;'>RTask ID</span>
				</TD>
				<TD width="125" style='cursor:pointer;'>Assigned To</TD>
				<TD width="145" style='cursor:pointer;'>Project </TD>
				<TD style='cursor:pointer;'> Task Description </TD>
				<TD>Occurrence</TD>
				<TD style='cursor:pointer;' width="130">&nbsp;</TD>
			</TR>
			
		<?php
			$tdclass = "oddrow";
		WHILE( $row = @mysql_fetch_array($query) ){
			extract($row) ;
			// `RTID`, `RT_project`, `RT_assignto`, `RT_createdby`, `RT_createdDate`, `RT_Desc`, `RT_isPrivate`, `RT_startdate`,
			// `RT_enddate`, `RT_type`, `RT_EVERYNDAYS`, `RT_EVERYNTHDAYOFMONTH`, `RT_EVERYXWEEKDAY`, `RT_EVERYDAYOFYEAR_MONTH`
			$tdclass = ( $tdclass == "oddrow" ) ? 'evenrow' : 'oddrow' ;

			switch ($RT_type) {
				case "W":
					$tmp_occurance = 'On every '. week_short_toLong($RT_EVERYXWEEKDAY)  ;
					break;
				case "N":
					$tmp_occurance = 'Every <B>'. $RT_EVERYNDAYS. " days</B> starting ".caldate_to_human($RT_startdate);
					break;
				case "M":
					$tmp_occurance = 'On <B>'. dayofmonth_to_daywithsuffix($RT_EVERYNTHDAYOFMONTH). "</B> of every month"  ;
					break;
				case "Y":
					$tmp_occurance = 'On <B>'. month_short_toLong($RT_EVERYDAYOFYEAR_MONTH) . '&nbsp;' . dayofmonth_to_daywithsuffix($RT_EVERYNTHDAYOFMONTH). " </B> of every year"   ;
					break;
				default:
					$tmp_occurance =  ' ? ' ;
					break;
			}
			
			echo "
			<TR class='RCCRT_TR_{$RTID} $tdclass'>
				<TD align='right'><span style='margin-right:20px;'>{$RTID}</span></TD>
				<TD>{$RT_assignto}</TD>
				<TD>{$RT_project}</TD>
				<TD>{$RT_Desc}</TD>
				<TD>{$tmp_occurance}</TD>
				<TD>
					<span class='bluebuttonSmall' onclick=\"delete_recurringtask('{$RTID}')\">Delete</span>
				</TD>
			</TR>";
		}
		
		echo "</table>";
	} // End of 'listReccuringTasks'
	
	
	
	
	
	
	public function listWorksScheduled(){
		global $DE_GLOBALS_WORK_SCHEDULED;
		global $DE_GLOBALS_WORK_TASKONTASK;

		if( !$this->formedsql ){ $this->formSqlSelector(); }
		$sqltoExecute="select * from WORKS where (work_status='".$DE_GLOBALS_WORK_SCHEDULED."' or work_status='".$DE_GLOBALS_WORK_TASKONTASK."') and work_RTID=0 " . $this->sqlselector ;
		$query = mysql_query($sqltoExecute) or die("Invalid query: " . mysql_error());

		$stcount = @mysql_num_rows($query);
		IF ($stcount==0){ 
			echo "<div class='nonewtasks'>No scheduled Tasks </div>";
			return;
		}
		?>
		<div class='listOfScheduledTasks'>
			<span class='listofnewtasksSpan' onclick=" $('.scheduledTasksTable').toggle();">Scheduled Tasks (<?php echo $stcount ; ?>)</span>
		</div>
		<table align=center cellpadding=0 cellspacing=0 class="scheduledTasksTable">
			<TR><TD class="firstRow" width="100" align='right' style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'workID');">
					<span style='margin-right:20px;'>Task ID<?php if($this->orderbyfield =='workID' ){echo ' &darr;'; } ?></span>
				</TD>
				<TD class="firstRow" width="125" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_userAssigned');">
					Assigned To <?php if($this->orderbyfield =='work_userAssigned' ){ echo ' &darr;'; } ?></TD>
				<TD class="firstRow" width="145" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_projectName');">
					Project Name <?php if($this->orderbyfield =='work_projectName' ){ echo ' &darr;'; } ?></TD>
				<TD class="firstRow" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_priority');">
					Task Description <?php if($this->orderbyfield =='work_priority' ){ echo ' &darr;'; } ?></TD>
				<TD class='firstRow' width='110'>Appear in List</TD>
				<TD class="firstRow" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_deadLine');" width="130">
					Deadline<?php if($this->orderbyfield =='work_deadLine' ){ echo ' &darr;'; } ?></TD>
			</TR>
		<?php
			$tdclass = "oddrow";
		WHILE( $row = @mysql_fetch_array($query) ){
			extract($row) ;
			// `workID`, `work_userAssigned`, `work_addedBy`, `work_dateAdded`, `work_deadLine`, `work_startDate`, `work_completeDate`,
			// `work_briefDesc`, `work_Notes`, `work_status`, `work_priority`, `work_projectName`, `work_isPrivate`, afterCompletionID
			if($work_priority=='H'){
				$work_briefDesc = HIGHPRIORITYAPPENDSTRING. $work_briefDesc; 
			}elseif($work_priority=='L'){
				$work_briefDesc = LOWPRIORITYAPPENDSTRING. $work_briefDesc; 
			}
			
			$daysb4deadline = (int)$daysb4deadline;

			$tdclass = ( $tdclass == "oddrow" ) ? 'evenrow' : 'oddrow' ;
		
			echo "<TR class='STT_TR_{$workID}'>";
			?>
				<TD class='<?php echo $tdclass;?>'>
					<span style='float:right; margin-right: 10px;'>
						<?php echo $workID; ?>
					</span>
				</TD>
				<TD class='<?php echo $tdclass;?>' style='cursor:default;'><?php echo $work_userAssigned.'&larr;'.$work_addedBy; ?></TD>
				<TD class='<?php echo $tdclass;?>'><?php echo $work_projectName; ?></TD>
				<TD class='<?php echo $tdclass;?> OMOhilitLink' onclick="ManageTasksJsFunction.detailsWork('<?php echo $workID; ?>');" TITLE="<?php echo $work_Notes ; ?>"><?php echo stripslashes($work_briefDesc); ?></TD>
				<td class='<?php echo $tdclass;?>'>
					<?php 
					if( $daysb4deadline ){
						echo $daysb4deadline." days before" ; 
					}else{

						if( $afterCompletionID ){
							echo "after task ".$afterCompletionID ;
						}
					}
					?>
				</td>
				<TD class='<?php echo $tdclass;?>'>
					<?php
					echo "<img src='images/to_current.png' workid='{$workID}' class='toCurrent' title='UnSchedule this task'>" ;
					echo caldate_toHuman_Deadline($work_deadLine);
					?>
				</TD>
			</TR>
			<?php
		}
		echo "</table>";
	} // End of method 'listWorksScheduled'



	/*********************			I P H O N E			************/

	public function listWorks_iPhone($taskstatus, $ulID , $backTitle){
		$this->orderbyfield = 'work_projectName' ;
		if( !$this->formedsql ){ $this->formSqlSelector(); }
		$sqltoExecute = "select * from WORKS where work_status = '" . $taskstatus . "' " . $this->sqlselector ;
		$query = mysql_query($sqltoExecute) or die("Invalid query: " . mysql_error());
		IF (mysql_num_rows($query)==0){
			echo "<ul id=\"".$ulID."\" title=\"".$backTitle."\"><li>No tasks found !</li></ul>"; 
			return;
		}

		$tmp_manageWorks = new manageWorks();
		$mainUL = array();
		$eachTaskDivs = array();
		$previousProject = '';
		WHILE( $row = @mysql_fetch_array($query) ){ extract($row) ;
			// `workID`, `work_userAssigned`, `work_addedBy`, `work_dateAdded`, `work_deadLine`, `work_startDate`, `work_completeDate`,
			// `work_briefDesc`, `work_Notes`, `work_status`, `work_priority`, `work_projectName`, `work_isPrivate`,
			if($previousProject <> $work_projectName ){
				$mainUL[] = "<li class='group'>$work_projectName</li>\n" ;
			} $previousProject = $work_projectName;
			$mainUL[] = "<li><a href=\"#".$workID."\">".$work_briefDesc."</a></li>\n" ;
			$eachTaskDivs[] = "<div id=\"".$workID."\" class=\"panel\" title=\"Task ".$workID."\">\n";
				$eachTaskDivs[] = "<h2>$work_briefDesc</h2>\n";
				$eachTaskDivs[] = "<h2>Deadline : ".caldate_to_human($work_deadLine)."</h2>\n";
				$eachTaskDivs[] = "<h2>Added By : ".$work_addedBy."</h2>\n";
				$eachTaskDivs[] = "<h2>Added On : ".caldate_to_human($work_dateAdded)."</h2>\n";
				$eachTaskDivs[] = "<h2>Priority : \n";
					switch($work_priority){
						case 'N': $eachTaskDivs[] = "Normal\n"; break;
						case'H': $eachTaskDivs[] = "High\n"; break;
						case'L': $eachTaskDivs[] = "Low\n"; break;
					}
				$eachTaskDivs[] = "</h2>\n";
				if($work_isPrivate=='Y'){
					$eachTaskDivs[] = "<h2>Visibility : Private</h2>\n" ;
				}else{
					$eachTaskDivs[] = "<h2>Visibility : Public</h2>\n" ;
				}
				// ... Other details of this task and comments if needed
				if($work_Notes){
					$eachTaskDivs[] = "<h2>Notes : $work_Notes</h2>\n" ;
				}

				if( $tmp_manageWorks->taskHasComments($workID) ){
					$eachTaskDivs[] = "<div id='taskcomments_{$workID}' class='divViewComments'>
											<span class='ViewCommentsButton' workid='{$workID}'>View Comments</span>
										</div>\n";
				}

			$eachTaskDivs[] = "</div>\n";
		}
		echo "<ul id=\"".$ulID."\" title=\"".$backTitle."\">";
			echo implode("" , $mainUL );
		echo "</ul>";
		echo implode("" , $eachTaskDivs );
	} // End of method 'listNewWorks_iPhone'







	public function listQuickEmailTasks(){ // tasks created via incoming emails "tasks_subdomain@discreteevents.com"
		global $DE_GLOBALS_WORK_FROMEMAIL ;
		$this->orderbyfield = 'work_projectName' ;
		if( !$this->formedsql ){ $this->formSqlSelector(); }
		$sqltoExecute = "select * from WORKS where work_status = '" . $DE_GLOBALS_WORK_FROMEMAIL . "' " . $this->sqlselector ;
		$query = mysql_query($sqltoExecute) or die("Invalid query: " . mysql_error());
		$qatasks = mysql_num_rows($query);
		IF ($qatasks==0){ return; }

		?>
		<div class='listofQuickEmailtasks'>
			<span class='listofquickemailtasksSpan'>New Tasks from Email (<?php echo $qatasks; ?>)</span>
		</div>
		<table align=center cellpadding=0 cellspacing=0 class="QuickTasksTable">
			<TR><TD class="firstRow" width="110" align='right'><span style='margin-right:20px;'>Task ID</span></TD>
				<TD class="firstRow" width="125"> Assigned To</TD>
				<TD class="firstRow" width="145"> Project Name</TD>
				<TD class="firstRow"> Task Description </TD>
				<TD class="firstRow" width="110"> Deadline </TD>
			</TR>
		<?php
		$tmp_manageWorks = new manageWorks();
		$tdclass = "oddrow";
		WHILE( $row = @mysql_fetch_array($query) ){
			extract($row) ;
			// `workID`, `work_userAssigned`, `work_addedBy`, `work_dateAdded`, `work_deadLine`, `work_startDate`, `work_completeDate`,
			// `work_briefDesc`, `work_Notes`, `work_status`, `work_priority`, `work_projectName`, `work_isPrivate`,
			$tdclass = ( $tdclass == "oddrow" ) ? 'evenrow' : 'oddrow' ;
			?>
			<TR><TD class='<?php echo $tdclass;?>'>
					<span style='float:right; margin-right: 10px;cursor:pointer;' onclick="ManageTasksJsFunction.DELETETHISTASK('<?php echo $workID; ?>')" TITLE='Click here to delete this task'>
						<?php echo $workID; ?>
					</span>	
				</TD>
				<TD class='<?php echo $tdclass;?>' style='cursor:default;'><?php echo $work_userAssigned.'&larr;'.$work_addedBy; ?></TD>
				<TD class='<?php echo $tdclass;?>'><?php echo $work_projectName; ?></TD>
				<TD class='<?php echo $tdclass;?> OMOhilitLink TREDITEMAILWORKS' workid='<?php echo $workID; ?>' projectName="<?php echo $work_projectName; ?>" userassigned="<?php echo $work_userAssigned;?>" addedBy="<?php echo $work_addedBy; ?>" deadline="<?php echo $work_deadLine; ?>">
					<?php echo stripslashes($work_briefDesc) ; ?>
				</TD>
				<TD class='<?php echo $tdclass;?>'>
					<?php echo caldate_toHuman_Deadline($work_deadLine); ?>
				</TD>
			</TR>
			<?php
		}
		echo "</table>";
	} // End of listQuickEmailTasks



	public function listNewWorks(){
		global $DE_GLOBALS_WORK_NEW ;
		if( !$this->formedsql ){ $this->formSqlSelector(); }
		$sqltoExecute = "select * from WORKS where work_status = '" . $DE_GLOBALS_WORK_NEW . "' " . $this->sqlselector ;
		$query = mysql_query($sqltoExecute) or die("Invalid query: " . mysql_error());
		$nwcount = @mysql_num_rows($query);
		IF ($nwcount==0){ 
			echo "<div class='listofnewtasks'>
					<span class='bluebutton' onclick=\"ManageTasksJsFunction.createNewTask_form();\" style='margin-left:20px;'>Add Task</span>
				</div>";
			echo "<div class='nonewtasks'>No New Tasks </div>";
			return;
		}
		?>
		<div class='listofnewtasks'>
			<span class='listofnewtasksSpan' onclick=" var ckval = String(jQuery('.NewWorksTable').is(':hidden')); My_JsLibrary.cookies.setCookie( 'NWTBV' , ckval ); $('.NewWorksTable').toggle();">New Tasks (<?php echo $nwcount; ?>)</span>
			<span class='bluebutton' onclick="ManageTasksJsFunction.createNewTask_form();" style='margin-left:20px;'>Add Task</span>
			<span style='float:right;'>
				<label><input type='checkbox' id='chk_showOnlyMyTasks'> Show only My tasks</span></label>
			</span>
		</div>

		<div style='clear:both;'>
		<table align=center cellpadding=0 cellspacing=0 class="NewWorksTable">
			<TR><TD class="firstRow" width="110" align='right' style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'workID');">
					<span style='margin-right:20px;'>Task ID<?php if($this->orderbyfield =='workID' ){echo ' &darr;'; } ?></span>
				</TD>
				<TD class="firstRow" width="125" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_userAssigned');">
					Assigned To<?php if($this->orderbyfield =='work_userAssigned' ){echo ' &darr;'; } ?></TD>
				<?php if( !$this->PersonalTasks ) { ?>
				<TD class="firstRow" width="145" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_projectName');">
					Project Name<?php if($this->orderbyfield =='work_projectName' ){echo ' &darr;'; } ?></TD>
				<?php }?>
				<TD class="firstRow" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_priority');">
					Task Description<?php if($this->orderbyfield =='work_priority' ){echo ' &darr;'; } ?></TD>
				<TD class="firstRow" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_deadLine');" width="110">
					Deadline<?php if($this->orderbyfield =='work_deadLine' ){echo ' &darr;'; } ?></TD>
			</TR>
		<?php
		$tdclass = "oddrow";
		$tmp_manageWorks = new manageWorks();

		WHILE( $row = @mysql_fetch_array($query) ){
			extract($row) ;
			// `workID`, `work_userAssigned`, `work_addedBy`, `work_dateAdded`, `work_deadLine`, `work_startDate`, `work_completeDate`,
			// `work_briefDesc`, `work_Notes`, `work_status`, `work_priority`, `work_projectName`, `work_isPrivate`,
			if($work_priority=='H'){
				$work_briefDesc = HIGHPRIORITYAPPENDSTRING. $work_briefDesc; 
			}elseif($work_priority=='L'){
				$work_briefDesc = LOWPRIORITYAPPENDSTRING. $work_briefDesc; 
			}
			if($work_hasbeenReset=='Y'){
				$work_briefDesc = HASBEENRESETAPPENDSTRING. $work_briefDesc; 
			}
			
			$tdclass = ( $tdclass == "oddrow" ) ? 'evenrow' : 'oddrow' ;
			$work_briefDesc = ($work_briefDesc) ? $work_briefDesc : 'No Description' ;
			?>
			<TR><TD class='<?php echo $tdclass;?>'>
					<span style='float:right; margin-right: 10px;'>
					<?php
					if( $work_addedBy == $_SESSION["uname"] || $work_userAssigned == $_SESSION["uname"] || IsSadmin() ){
						?> <img src="/images/togreendown.png" border=0 onclick="ManageTasksJsFunction.startTask('<?php echo $workID; ?>')"> <?php
					}else{
						?> <img src="/images/totrans.png" border=0 style='cursor:default;'> <?php
					}
					?>
					</span>
					<span style='float:right; margin-right: 10px; cursor:pointer;' onclick="ManageTasksJsFunction.createNewTask_form('<?php echo $workID; ?>')">
						<?php echo $workID; ?>
					</span>	
					<span style='float:right; margin-right:5px;'>
						<img src='/images/letter.png' border=0 class='emailTaskBescImg' title='e-mail task details' taskid='<?php echo $workID; ?>'>
					</span>
					<?php
						if( $tmp_manageWorks->taskHasAttachments($workID) ){
							echo "<img src='/images/attach1.gif' width=15 height=15 border=0 class='image_attachment' taskid='" . $workID . "'>";
						}
					?>
				</TD>
				<TD class='<?php echo $tdclass;?> AssignedUser' style='cursor:default;' assignedto='<?php echo $work_userAssigned; ?>' assignedby='<?php echo $work_addedBy; ?>'>
				<?php
					if( $work_addedBy == $_SESSION["uname"] || $work_userAssigned == $_SESSION["uname"] || IsSadmin() ){
						echo "<span ondblclick=\"ManageTasksJsFunction.setUpcomingTask('$workID', 'Y')\">";
						if ( $work_addedBy == $_SESSION["uname"] && $work_userAssigned == $_SESSION["uname"] ){
							echo "<i>Self</i>";
						}else{
							$str_work_userAssigned = $work_userAssigned;
							$str_work_addedBy = $work_addedBy ;
							if ( $work_addedBy == $_SESSION["uname"] ){ $str_work_addedBy = YOUSTRING; }
							if( $work_userAssigned == $_SESSION["uname"] ){ $str_work_userAssigned = YOUSTRING; }
							echo "{$str_work_userAssigned}&larr;{$str_work_addedBy}";
						}
						echo "</span>";
					}else{
						echo "{$work_userAssigned}&larr;{$work_addedBy}";
					}
				?>
				</TD>
				<?php if( !$this->PersonalTasks ) { 
					echo "<TD class='$tdclass'>$work_projectName </TD>";
					}
				?>
				<TD class='<?php echo $tdclass;?> OMOhilitLink' TITLE="<?php echo $work_Notes ; ?>">
					<?php
					if( $tmp_manageWorks->taskHasNewComments($workID) ){
						echo "<img src='/images/newcommentsblue.png' width=15 height=15 border=0 class='image_newcomments'>";
					}
					if( $work_isNext == 'Y' ){
						if( $work_addedBy == $_SESSION["uname"] || $work_userAssigned == $_SESSION["uname"] || IsSadmin() ){
							echo "<img src='/images/upcoming.png' width=42 height=15 border=0 onclick=\"ManageTasksJsFunction.setUpcomingTask('$workID', 'N')\">";
						}else{
							echo "<img src='/images/upcoming.png' width=42 height=15 border=0>";
						}
					}
					?>
					<span onclick="ManageTasksJsFunction.detailsWork('<?php echo $workID; ?>');">
						<?php 
						if($work_RTID){
							echo "<img src='/images/rt.png' border=0 width='31' height='15'>";
						}
						echo getNwordsOfLengthFromString( stripslashes($work_briefDesc), 100 );
						?>
					</span>
				</TD>
				<TD class='<?php echo $tdclass;?>'>
					<NOBR>
					<span style='margin-right:2px;'>
						<img src='images/reminder_icon.gif' width=12 height=12 title='Add Reminder' class='addreminder_bell' taskid='<?php echo $workID; ?>'>
					</span>
					<?php if( $work_addedBy == $_SESSION["uname"] || $work_userAssigned == $_SESSION["uname"] || IsSadmin() ){ ?>
					<span style='margin-right:2px;'>
							<img src='/images/clock.png' width=14 height=14 border=0 class='image_rescheduleTask' deadline='<?php echo $work_deadLine; ?>' taskid='<?php echo $workID; ?>' title='Reschedule this task' isowner='<?php if( $work_addedBy == $_SESSION["uname"] || IsSadmin() ){ echo "YES"; }else{ echo "NO"; } ?>'>
					</span>
					<?php }else{ ?>
					<span style='margin-right:2px;'>
							<img src='/images/totrans.png' width=14 height=14 border=0>
					</span>
					<?php } ?>
					<?php echo caldate_toHuman_Deadline($work_deadLine); ?>
					</NOBR>
				</TD>
			</TR>
			<?php
		}
		echo "</table>
		</div>
		";
		
	} // End of method "listNewWorks()"



	public function listWorksinProgress(){
		global $DE_GLOBALS_WORK_PROGRESS;
		if( !$this->formedsql ){ $this->formSqlSelector(); }
		$sqltoExecute = "select * from WORKS where work_status = '" . $DE_GLOBALS_WORK_PROGRESS . "' " . $this->sqlselector ;

		$query = mysql_query($sqltoExecute) or die("Invalid query: " . mysql_error());
		$wpcount = @mysql_num_rows($query);
		IF ($wpcount==0){ 
			echo "<div class='noinprogresstasks'>No Tasks in Progress</div>";	
			return; 
		}
		?>
		<div class='listofprogresstasks'><span onclick="var ckval = String(jQuery('.WorksInProgressTable').is(':hidden')); My_JsLibrary.cookies.setCookie( 'WIPT' , ckval ); $('.WorksInProgressTable').toggle();">In Progress (<?php echo $wpcount;?>)</span></div>
		<table align=center cellpadding=0 cellspacing=0 class="WorksInProgressTable">
			<TR><TD class="firstRow" width="110" align='right' style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'workID');">
						<span style='margin-right:20px;'>Task ID<?php if($this->orderbyfield =='workID' ){echo ' &darr;'; } ?></span>
				</TD>
				<TD class="firstRow" width="125" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_userAssigned');">
					Assigned To<?php if($this->orderbyfield =='work_userAssigned' ){echo ' &darr;'; } ?></TD>
				<?php if( !$this->PersonalTasks ) { ?>
				<TD class="firstRow" width="145" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_projectName');">
					Project Name<?php if($this->orderbyfield =='work_projectName' ){echo ' &darr;'; } ?></TD>
				<?php } ?>
				<TD class="firstRow" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_priority');">
					Task Description<?php if($this->orderbyfield =='work_priority' ){echo ' &darr;'; } ?></TD>
				<TD class="firstRow" width="125">Working Since</TD>
				<TD class="firstRow" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_deadLine');" width="110">
					Deadline<?php if($this->orderbyfield =='work_deadLine' ){echo ' &darr;'; } ?></TD>
			</TR>
		<?php
		$tdclass = "oddrow";
		$tmp_manageWorks = new manageWorks();
		WHILE( $row = @mysql_fetch_array($query) ){
			extract($row) ;
			// `workID`, `work_userAssigned`, `work_addedBy`, `work_dateAdded`, `work_deadLine`, `work_startDate`, `work_completeDate`,
			// `work_briefDesc`, `work_Notes`, `work_status`, `work_priority`, `work_projectName`, `work_isPrivate`,
			if($work_priority=='H'){
				$work_briefDesc = HIGHPRIORITYAPPENDSTRING. $work_briefDesc; 
			}elseif($work_priority=='L'){
				$work_briefDesc = LOWPRIORITYAPPENDSTRING. $work_briefDesc; 
			}
			$tdclass = ( $tdclass == "oddrow" ) ? 'evenrow' : 'oddrow' ;
			$work_briefDesc = ($work_briefDesc) ? $work_briefDesc : 'No Description' ;
			?>
			<TR><TD align='center'  class='<?php echo $tdclass;?>'>
					<span style='float:right; margin-right: 10px;'>
						<?php
						if( $work_addedBy == $_SESSION["uname"] || $work_userAssigned == $_SESSION["uname"] || IsSadmin() ){
							?><img src="/images/toblue.png" border=0 onclick="ManageTasksJsFunction.completeWork('<?php echo $workID;?>')"><?php
						}else{
							?><img src="/images/totrans.png" border=0 style='cursor:default;'><?php
						}
						?>
					</span>
					<span style='float:right; margin-right: 5px;'>
						<?php
						if( $work_addedBy == $_SESSION["uname"] || $work_userAssigned == $_SESSION["uname"] || IsSadmin() ){
							?><img src="/images/toyellowup.png" border=0 onclick="ManageTasksJsFunction.resetWorkToNew('<?php echo $workID;?>')"><?php
						}else{
							?><img src="/images/totrans.png" border=0 style='cursor:default;'><?php
						}
						?>
					</span>
					<span style='float:right; margin-right: 10px; cursor:pointer;' onclick="ManageTasksJsFunction.createNewTask_form('<?php echo $workID; ?>')"><?php echo $workID; ?></span>
					<span style='float:right; margin-right:5px;'>
						<img src='/images/letter.png' border=0 class='emailTaskBescImg' title='e-mail task details' taskid='<?php echo $workID; ?>'>
					</span>
					<?php
						if( $tmp_manageWorks->taskHasAttachments($workID) ){
							echo "<img src='/images/attach1.gif' width=15 height=15 border=0 class='image_attachment' taskid='" . $workID . "'>";
						}
					?>
				</TD>
				<TD class='<?php echo $tdclass;?> AssignedUser' style='cursor:default;' assignedto='<?php echo $work_userAssigned; ?>' assignedby='<?php echo $work_addedBy; ?>'>
					<?php
						if( $work_addedBy == $_SESSION["uname"] || $work_userAssigned == $_SESSION["uname"] || IsSadmin() ){
							echo "<span ondblclick=\"ManageTasksJsFunction.setUpcomingTask('$workID', 'Y')\">";
							if ( $work_addedBy == $_SESSION["uname"] && $work_userAssigned == $_SESSION["uname"] ){
								echo "<i>Self</i>";
							}else{
								$str_work_userAssigned = $work_userAssigned;
								$str_work_addedBy = $work_addedBy ;
								if ( $work_addedBy == $_SESSION["uname"] ){ $str_work_addedBy = YOUSTRING; }
								if( $work_userAssigned == $_SESSION["uname"] ){ $str_work_userAssigned = YOUSTRING; }
								echo "{$str_work_userAssigned}&larr;{$str_work_addedBy}";
							}
							echo "</span>";
						}else{
							echo "{$work_userAssigned}&larr;{$work_addedBy}";
						}
					?>
				</TD>
				<?php if( !$this->PersonalTasks ) {  
					echo "<TD class='$tdclass'>$work_projectName</TD>";
					}
				?>
				<TD class='<?php echo $tdclass;?> OMOhilitLink' TITLE="<?php echo $work_Notes ; ?>">
					<?php
						if( $tmp_manageWorks->taskHasNewComments($workID) ){
							echo "<img src='/images/newcommentsblue.png' width=15 height=15 border=0 class='image_newcomments'>";
						}
						
						if( $work_isNext == 'Y' ){
							if( $work_addedBy == $_SESSION["uname"] || $work_userAssigned == $_SESSION["uname"] || IsSadmin() ){
								echo "<img src='/images/upcoming.png' width=42 height=15 border=0 onclick=\"ManageTasksJsFunction.setUpcomingTask('$workID', 'N')\">";
							}else{
								echo "<img src='/images/upcoming.png' width=42 height=15 border=0>";
							}
						}
					?>
					<span onclick="ManageTasksJsFunction.detailsWork('<?php echo $workID; ?>');">
					<?php 
					if($work_RTID){
						echo "<img src='/images/rt.png' border=0 width='31' height='15'>";
					}
					echo getNwordsOfLengthFromString( stripslashes($work_briefDesc), 90 );
					?>
					</span>
				</TD>
				<TD class='<?php echo $tdclass;?>'><?php echo get_durationSince_timeStamp($work_startDate); ?></TD>
				<TD class='<?php echo $tdclass;?>'>
					<NOBR>
					<span style='margin-right:2px;'>
						<img src='images/reminder_icon.gif' width=12 height=12 title='Add Reminder' class='addreminder_bell' taskid='<?php echo $workID; ?>'>
					</span>
					<?php if( $work_addedBy == $_SESSION["uname"] || $work_userAssigned == $_SESSION["uname"] || IsSadmin() ){ ?>
					<span style='margin-right:2px;'>
							<img src='/images/clock.png' width=14 height=14 border=0 class='image_rescheduleTask' deadline='<?php echo $work_deadLine; ?>' taskid='<?php echo $workID; ?>' isowner='<?php if( $work_addedBy == $_SESSION["uname"] || IsSadmin() ){ echo "YES"; }else{ echo "NO"; } ?>' title='Reschedule this task'>
					</span>
					<?php }else{ ?>
					<span style='margin-right:2px;'>
							<img src='/images/totrans.png' width=14 height=14 border=0>
					</span>
					<?php } ?>
					<?php echo caldate_toHuman_Deadline($work_deadLine); ?>
					</NOBR>
				</TD>
			</TR>
			<?php
		}
		echo "</table>";
	}// End of method "listWorksinProgress()"



	public function listWorksCompleted(){
		global $DE_GLOBALS_WORK_COMPLETED;
		if( !$this->formedsql ){ $this->formSqlSelector(); }
		$sqltoExecute = "select * from WORKS where work_status = '" . $DE_GLOBALS_WORK_COMPLETED . "' " . $this->sqlselector ;

		$query = mysql_query($sqltoExecute) or die("Invalid query: " . mysql_error());
		$wcCount = @mysql_num_rows($query);
		IF ($wcCount==0){
			echo "<div class='nocompletedtasks'>No Completed Tasks</div>";	
			return;
		}
		?>
		<div class='listcompletedtasks'><span onclick="var ckval = String(jQuery('.WorksCompletedTable').is(':hidden')); My_JsLibrary.cookies.setCookie( 'WCPT' , ckval ); $('.WorksCompletedTable').toggle();">Completed (<?php echo $wcCount; ?>)</span></div>
		<table align=center cellpadding=0 cellspacing=0 class="WorksCompletedTable">
			<TR><TD class="firstRow" width="110" align='right' style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'workID');">
					<span style='margin-right:20px;'>Task ID<?php if($this->orderbyfield =='workID' ){echo ' &darr;'; } ?></span>
				</TD>
				<TD class="firstRow" width="125" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_userAssigned');">
					Assigned To<?php if($this->orderbyfield =='work_userAssigned' ){echo ' &darr;'; } ?></TD>
				<?php if( !$this->PersonalTasks ) { ?>
				<TD class="firstRow" width="145" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_projectName');">
					Project Name<?php if($this->orderbyfield =='work_projectName' ){echo ' &darr;'; } ?></TD>
				<?php } ?>
				<TD class="firstRow" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_priority');">
					Task Description<?php if($this->orderbyfield =='work_priority' ){echo ' &darr;'; } ?></TD>
				<TD class="firstRow" width="125">Task took</TD>
				<TD class="firstRow" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_deadLine');" width="110">
					Deadline<?php if($this->orderbyfield =='work_deadLine' ){echo ' &darr;'; } ?></TD>
			</TR>
		<?php
		$tdclass = "oddrow";
		$tmp_manageWorks = new manageWorks();
		WHILE( $row = @mysql_fetch_array($query) ){
			extract($row) ;
			$tdclass = ( $tdclass == "oddrow" ) ? 'evenrow' : 'oddrow' ;
			// `workID`, `work_userAssigned`, `work_addedBy`, `work_dateAdded`, `work_deadLine`, `work_startDate`, `work_completeDate`,
			// `work_briefDesc`, `work_Notes`, `work_status`, `work_priority`, `work_projectName`, `work_isPrivate`,
			if($work_priority=='H'){
				$work_briefDesc = HIGHPRIORITYAPPENDSTRING. $work_briefDesc; 
			}elseif($work_priority=='L'){
				$work_briefDesc = LOWPRIORITYAPPENDSTRING. $work_briefDesc; 
			}
			$work_briefDesc = ($work_briefDesc) ? $work_briefDesc : 'No Description' ;
			?>
			<TR><TD align='center'  class='<?php echo $tdclass;?>'>
					<span style='float:right; margin-right:10px;'>
						<?php
						if( $work_addedBy == $_SESSION["uname"] || IsSadmin() ){
							?><img src="/images/toblackdown.png" border=0 onclick="ManageTasksJsFunction.closeWork('<?php echo $workID; ?>')"><?php
						}else{
							?><img src="/images/totrans.png" border=0 style='cursor:default;'><?php
						}
						?>
					</span>
					<span style='float:right; margin-right:5px;'>
						<?php
						if( $work_addedBy == $_SESSION["uname"] || $work_userAssigned == $_SESSION["uname"] || IsSadmin() ){
							?><img src="/images/togreenup.png" border=0 onclick="ManageTasksJsFunction.startTask('<?php echo $workID; ?>')"><?php
						}else{
							?><img src="/images/totrans.png" border=0 style='cursor:default;'><?php
						}
						?>
					</span>
					<span style='float:right; margin-right:10px;'><?php echo $workID; ?></span>
					<span style='float:right; margin-right:5px;'>
						<img src='/images/letter.png' border=0 class='emailTaskBescImg' title='e-mail task details' taskid='<?php echo $workID; ?>'>
					</span>
					<?php
						if( $tmp_manageWorks->taskHasAttachments($workID) ){
							echo "<img src='/images/attach1.gif' width=15 height=15 border=0 class='image_attachment' taskid='" . $workID . "'>";
						}
					?>
				</TD>
				<TD  class='<?php echo $tdclass;?> AssignedUser' style='cursor:default;' assignedto='<?php echo $work_userAssigned; ?>' assignedby='<?php echo $work_addedBy; ?>'>
					<?php 

					if ( $work_addedBy == $_SESSION["uname"] && $work_userAssigned == $_SESSION["uname"] ){
						echo "<i>Self</i>";
					}else{
						$str_work_userAssigned = $work_userAssigned;
						$str_work_addedBy = $work_addedBy ;
						if ( $work_addedBy == $_SESSION["uname"] ){ $str_work_addedBy = YOUSTRING; }
						if( $work_userAssigned == $_SESSION["uname"] ){ $str_work_userAssigned = YOUSTRING; }
						echo "{$str_work_userAssigned}&larr;{$str_work_addedBy}";
					}
					?>
				</TD>
				<?php if( !$this->PersonalTasks ) { 
						echo "<TD  class='$tdclass'>$work_projectName</TD>";
					}
				?>
				<TD  class='<?php echo $tdclass;?> OMOhilitLink' onclick="ManageTasksJsFunction.detailsWork('<?php echo $workID; ?>');"  TITLE="<?php echo $work_Notes ; ?>">
					<?php
						if( $tmp_manageWorks->taskHasNewComments($workID) ){
							echo "<img src='/images/newcommentsblue.png' width=15 height=15 border=0 class='image_newcomments'>";
						}
					?>
					<?php
					if($work_RTID){
						echo "<img src='/images/rt.png' border=0 width='31' height='15'> ";
					}
					echo getNwordsOfLengthFromString( stripslashes($work_briefDesc), 90 );
					?>
				</TD>
				<TD  class='<?php echo $tdclass;?>'>
					<NOBR>
					<?php echo get_durationSince_timeStamp($work_startDate, $work_completeDate); ?>
					</NOBR>
				</TD>
				<TD  class='<?php echo $tdclass;?>'>
					<NOBR>
					<?php echo caldate_to_human($work_deadLine); ?>
					</NOBR>
				</TD>
			</TR>
			<?php
		}
		echo "</table>";
	} // End of method "listWorksCompleted()"


	public function listWorksClosed(){
		global $DE_GLOBALS_WORK_CLOSED;
		if( !$this->formedsql ){ $this->formSqlSelector(); }

		$sqltoExecute = "select * from WORKS where work_status = '{$DE_GLOBALS_WORK_CLOSED}' {$this->sql_closedTasks} {$this->sqlselector}" ;

		//echo "<BR><BR>$sqltoExecute<BR><BR>";

		$query = mysql_query($sqltoExecute) or die("Invalid query: " . mysql_error());
		$wcCount = @mysql_num_rows($query);
		IF ($wcCount==0){ 
			
			echo "<center><div onclick='ManageTasksJsFunction.allClosedThisYear();' class='linkTextButton' style='margin-top:10px;'>Show all tasks closed this year</div></center>";
			
			echo "<center><div onclick=\"ManageTasksJsFunction.allClosedThisYear('all');\" class='linkTextButton' style='margin-top:10px;'>Show all closed tasks </div></center>";
			
			return; 
			
		}
		?>
		<div class='listclosedtasks'><span  onclick="var ckval = String(jQuery('.WorksClosedTable').is(':hidden')); My_JsLibrary.cookies.setCookie( 'WCLT' , ckval ); $('.WorksClosedTable').toggle();"><?php echo "Closed Tasks {$this->closedTasksPeriod} ({$wcCount})" ; ?></span></div>
		<table align=center cellpadding=0 cellspacing=0 class="WorksClosedTable">
			<TR><TD class="firstRow" width="110" align='right' style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'workID');">
					<span style='margin-right:20px;'>Task ID<?php if($this->orderbyfield =='workID' ){echo ' &darr;'; } ?></span>
				</TD>
				<TD class="firstRow" width="125" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_userAssigned');">
					Assigned To<?php if($this->orderbyfield =='work_userAssigned' ){echo ' &darr;'; } ?></TD>
				<?php if( !$this->PersonalTasks ) { ?>
				<TD class="firstRow" width="145" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_projectName');">
					Project Name<?php if($this->orderbyfield =='work_projectName' ){echo ' &darr;'; } ?></TD>
				<?php } ?>

				<TD class="firstRow" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_priority');">
					Task Description<?php if($this->orderbyfield =='work_priority' ){echo ' &darr;'; } ?></TD>
				<TD class="firstRow" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_closedDate');" width="125">Closed On<?php if($this->orderbyfield =='work_closedDate' ){echo ' &darr;'; } ?></TD>
				<TD class="firstRow" style='cursor:pointer;' onclick="My_JsLibrary.updatePageWithGetVar('sortby', 'work_deadLine');" width="110">
					Deadline<?php if($this->orderbyfield =='work_deadLine' ){echo ' &darr;'; } ?></TD>
			</TR>
		<?php
		$tdclass = "oddrow" ;
		$tmp_manageWorks = new manageWorks();

		WHILE( $row = @mysql_fetch_array($query) ){
			extract($row) ;
			$tdclass = ( $tdclass == "oddrow" ) ? 'evenrow' : 'oddrow' ;
			// `workID`, `work_userAssigned`, `work_addedBy`, `work_dateAdded`, `work_deadLine`, `work_startDate`, `work_completeDate`,
			// `work_briefDesc`, `work_Notes`, `work_status`, `work_priority`, `work_projectName`, `work_isPrivate`,
			if($work_priority=='H'){
				$work_briefDesc = HIGHPRIORITYAPPENDSTRING. $work_briefDesc; 
			}elseif($work_priority=='L'){
				$work_briefDesc = LOWPRIORITYAPPENDSTRING. $work_briefDesc; 
			}
			$work_briefDesc = ($work_briefDesc) ? $work_briefDesc : 'No Description' ;
			?>
			<TR><TD align='center'  class='<?php echo $tdclass;?>'>
				<span style='float:right; margin-right:10px;'><?php echo $workID; ?></span>
				<?php
					if( $tmp_manageWorks->taskHasAttachments($workID) ){
						echo "<img src='/images/attach1.gif' width=15 height=15 border=0 class='image_attachment' taskid='" . $workID . "'>";
					}
				?>
				<span style='float:right; margin-right:5px;'>
					<img src='/images/letter.png' border=0 class='emailTaskBescImg' title='e-mail task description' taskid='<?php echo $workID; ?>'>
				</span>
				</TD>
				<TD class='<?php echo $tdclass;?> AssignedUser' style='cursor:default;' assignedto='<?php echo $work_userAssigned; ?>' assignedby='<?php echo $work_addedBy; ?>'>
					<?php
					if ( $work_addedBy == $_SESSION["uname"] && $work_userAssigned == $_SESSION["uname"] ){
						echo "<i>Self</i>";
					}else{
						$str_work_userAssigned = $work_userAssigned;
						$str_work_addedBy = $work_addedBy ;
						if ( $work_addedBy == $_SESSION["uname"] ){ $str_work_addedBy = YOUSTRING; }
						if( $work_userAssigned == $_SESSION["uname"] ){ $str_work_userAssigned = YOUSTRING; }
						echo "{$str_work_userAssigned}&larr;{$str_work_addedBy}";
					}
					?>
				</TD>
				<?php
					if( !$this->PersonalTasks ){
						echo "<TD class='$tdclass'>$work_projectName</TD>";
					}
				?>
				<TD class='<?php echo $tdclass;?> OMOhilitLink' onclick="ManageTasksJsFunction.detailsWork('<?php echo $workID; ?>');"  TITLE="<?php echo $work_Notes ; ?>">
					<?php
						if( $tmp_manageWorks->taskHasNewComments($workID) ){
							echo "<img src='/images/newcommentsblue.png' width=15 height=15 border=0 class='image_newcomments'>";
						}
					?>
					<?php
					if($work_RTID){
						echo "<img src='/images/rt.png' border=0 width='31' height='15'>";
					}
					echo getNwordsOfLengthFromString( stripslashes($work_briefDesc), 90 );
					?>
				</TD>
				<TD class='<?php echo $tdclass;?>'>
					<NOBR>
					<?php echo caldateTS_to_humanWithOutTS($work_closedDate); ?>
					</NOBR>
				</TD>
				<TD class='<?php echo $tdclass;?>'>
					<NOBR>
					<?php echo caldate_to_human($work_deadLine, true); ?>
					</NOBR>
				</TD>
			</TR>
			<?php
		}
		echo "</table>";

		if($this->closedTasksPeriod <> 'in this Year' ){
			echo "<center><div onclick='ManageTasksJsFunction.allClosedThisYear();' class='linkTextButton' style='margin-top:10px;'>Show all tasks closed this year</div></center>";
			
			echo "<center><div onclick=\"ManageTasksJsFunction.allClosedThisYear('all');\" class='linkTextButton' style='margin-top:10px;'>Show all closed tasks </div></center>";
		}
	} // End of method "listWorksClosed()"


	public function list4sections(){
		$this->listJSWORKS();
		$this->listNewWorks();
		$this->listWorksinProgress();
		$this->listWorksCompleted();
		$this->listWorksClosed();
	}
}


// ***** End of awesome reports ********** //

////////////////////////////////////////////////////////////////////////////////////////////////////////////////// //////////////////////////////////////////// 
//////////////////////////////////////////// END OF "M A I N    DISCRETE EVENT APP Functions & CLASSES" ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////// //////////////////////////////////////////// 












//////////////////////////////////////////////////////////////////////////////////////
///// SUBDOMAIN / CLIENT MANAGEMENT FUNCTIONS  ///////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

function deleteTasksForwardAlias($subDomain){ }
function addTasksForwardingMailAlias($subDomain){ }

function deleteClientAccount( $subdomain ){
	$fulldbname = CPANELUSERNAME.'_'.$subdomain ;
	$tmp_dbcreate_result = mysql_query("drop database $fulldbname");
	sleep(1);
	$result2 = deleteTasksForwardAlias($subdomain);
	// Todo: alert ssadmin via email of the event
	sleep(1);
	$sqlquery= "delete from ".MASTERDB.".subdomains where subdomain='$subdomain' ";
	$query = mysql_query($sqlquery);
}


function changeClientStatus( $subdomain ){ // changes the current status of subdomain
	$currentstatus = executesql_returnArray( "select status from ".MASTERDB.".subdomains where subdomain='$subdomain' " );
	$newstatus = ( $currentstatus == "Y" ) ? "N" : "Y" ;
	$result = executesql_returnArray( "update ".$MASTERDB.".subdomains set status='$newstatus' where subdomain='$subdomain' " );
}


function createNewClientAccount($fullname, $subDomain, $packageid, $adminEmail, $adminPass){
	// CREATE NEW DATABASE
	$fulldbname = CPANELUSERNAME.'_'.$subDomain ;
	$tmp_dbcreate_result = mysql_query("create database $fulldbname ");
	$addfwdeml = addTasksForwardingMailAlias( $subDomain );
	$createdby = ( @$_POST["createdby"] ) ? $_POST["createdby"] : "signup";
	$sqlquery= "INSERT INTO ".MASTERDB.".subdomains (pid, subdomain, dbname, clientName, package, status, createdby) VALUES (NULL, '$subDomain', '$fulldbname', '$fullname', $packageid , 'Y', '$createdby')";
	$query = mysql_query($sqlquery) or die("Invalid query: " . mysql_error()) ;
	createDefaultTables( $fulldbname, $adminPass, $adminEmail );
	sendLoginDetailsToSadmin($subDomain);
}


function sendLoginDetailsToUser($userid , $subdomain){
	$sqlquery2= "select password, userEmailId from users where username='$userid'";
	$query2 = mysql_query($sqlquery2) or die("Invalid query: " . mysql_error()); 
	WHILE ($row2 = @mysql_fetch_array($query2)){extract($row2);} // $password, $userEmailId
	if( !$userEmailId ){ return false; }
	$appname = APPNAME ;
	$USERLOGINURL = "http://".$subdomain.".".MAINDOMAIN ;
	$sadminemail = executesql_returnArray(" select variablevalue as sadminemail from sadmin where  variable='sadminemail' ") ;
	$supportemail = SUPPORT_EMAIL ;
$EMAILMESSAGE =<<<AKAM

	Welcome to your $appname account,
	Here are your login details.

	Login URL - $USERLOGINURL
	username : $userid
	Password : $password

	Contact your account administrator at $sadminemail for any further questions.
	Please send any suggestions, feature requests bug reports regarding the application to $supportemail

	regards,
	$appname Team

AKAM;
	$email = new sendaMail();
	$email->messageTo( $userEmailId );
	$email->subject( 'Your '. APPNAME .' account details ' );
	$email->body( $EMAILMESSAGE );
	$email->send();
	return true;
}// End of sendLoginDetailsToUser



function sendLoginDetailsToSadmin( $subdomain ){
	$SDB = executesql_returnArray("select dbname as SDB from ".MASTERDB.".subdomains where subdomain='$subdomain' ") ;
	if(!$SDB){ return false; }
	$pkgdetails = executesql_returnArray("select ".MASTERDB.".packages.pkgName, ".MASTERDB.".packages.pkgNumberOfUsers , ".MASTERDB.".packages.pkgSpaceMb from ".MASTERDB.".packages , ".MASTERDB.".subdomains where  ".MASTERDB.".packages.pkgId = ".MASTERDB.".subdomains.package and ".MASTERDB.".subdomains.subdomain='$subdomain' ");
	$pkgName = $pkgdetails[0] ; $pkgNumberOfUsers = $pkgdetails[1] ; $pkgSpaceMb = $pkgdetails[2] ;
	$to = executesql_returnArray("select variablevalue as sadminemail from ".$SDB.".sadmin where variable='sadminemail' ") ;
	$sadminPass = executesql_returnArray(" select variablevalue as sadminpass from ".$SDB.".sadmin where variable='sadminpass' ") ;
	$subject = 'Admin Login Details for '.APPNAME ;
	$appname = APPNAME ;
	$maindomain = MAINDOMAIN ;
	$USERLOGINURL = "http://".$subdomain.".".MAINDOMAIN ;
	$supportemail = SUPPORT_EMAIL ;
$EMAILMESSAGE =<<<AKAM

	Welcome to your $appname "$pkgName" account,
	You can created upto $pkgNumberOfUsers users, and can use a total of $pkgSpaceMb Mb for attachments.

	Below are your Admin login details for your account.

	Login URL - $USERLOGINURL
	Admin username : sadmin
	Admin Password : $sadminPass

	As an Admin you will create Users, create works and assign works to various users.
	Users will also login via above mentioned URL with the username and password created by Admin.

	Please send suggestions, feature requests bug reports etc to $supportemail

	regards,
	$appname Team

AKAM;
	$email = new sendaMail();
	$email->messageTo( $to );
	$email->subject( $subject );
	$email->body( $EMAILMESSAGE );
	$email->send();
	return true;
} // End of sendLoginDetailsToSadmin


function createDefaultTables( $dbname, $adminpass, $adminEmail ){
	// Create Default tables in $dbname
	IF (!@mysql_select_db($dbname)){ echo "Not able to Connect to DataBase"; exit(); }

	$sqlQueries = array();

	$sqlQueries[] = "CREATE TABLE IF NOT EXISTS `attachments` ( `Id` int(11) NOT NULL AUTO_INCREMENT, `workid` int(11) DEFAULT NULL COMMENT 'expense id', `diskfilename` varchar(255) DEFAULT NULL COMMENT 'File Name on server', `uploadname` varchar(255)  DEFAULT NULL COMMENT 'Uploaded File Name', `uploadedby` varchar(255) DEFAULT NULL, `uploadedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, `filesize` VARCHAR( 20 ) NOT NULL ,  `filecontent` MEDIUMBLOB NOT NULL , PRIMARY KEY (`Id`) )" ;

	$sqlQueries[] = "CREATE TABLE IF NOT EXISTS `cookies` (`username` varchar(32) NOT NULL, `cookieid` varchar(32) NOT NULL, `cookietime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP)";

	$sqlQueries[] = "CREATE TABLE IF NOT EXISTS `COMMENTS` ( `commentID` int(9) NOT NULL AUTO_INCREMENT, `workID` int(9) unsigned NOT NULL, `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, `comment_by` varchar(32) DEFAULT NULL, `comment` text, PRIMARY KEY (`commentID`), FULLTEXT(comment) )" ;

	$sqlQueries[] = "CREATE TABLE IF NOT EXISTS `deletelog` ( `deletedon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, `description` text , `bywhom` varchar(32) DEFAULT 'SADMIN' )" ;

	$sqlQueries[] = "CREATE TABLE IF NOT EXISTS `permissions` ( `username` varchar(32) NOT NULL DEFAULT '0', `ProjectName` varchar(200)  DEFAULT NULL )";

	$sqlQueries[] = "CREATE TABLE IF NOT EXISTS `projects` (  `ProjectName` varchar(200) NOT NULL DEFAULT '0',  `ProjectDescription` text, `isActive` char(1) DEFAULT 'Y',  PRIMARY KEY (`ProjectName`), UNIQUE KEY `ProjectName` (`ProjectName`) )";

	$sqlQueries[] = "INSERT INTO `projects` (`ProjectName`, `ProjectDescription`) VALUES ('".DEFAULTPROJECT."', 'This is the default project under which your new tasks will fall into it.')";

	$sqlQueries[] = "CREATE TABLE IF NOT EXISTS `sadmin` ( `variable` varchar(32) NOT NULL DEFAULT '0', `variablevalue` varchar(32) DEFAULT NULL, PRIMARY KEY (`variable`), UNIQUE KEY `variable` (`variable`), KEY `variable_2` (`variable`) )" ;

	$sqlQueries[] = "INSERT INTO `sadmin` (`variable`, `variablevalue`) VALUES ('sadminpass', '$adminpass'), ('sadminusername', 'sadmin'), ('sadminemail', '$adminEmail'), ('sadminalertemail', '$adminEmail'), ('defaultProject', 'DailyTasks')";

	$sqlQueries[] = "CREATE TABLE IF NOT EXISTS `users` ( `username` varchar(32) NOT NULL,  `password` varchar(32)  DEFAULT NULL, `user_reportsTo` varchar(32) DEFAULT NULL, `user_primaryEmail` varchar(255) DEFAULT NULL, `user_alertEmail` varchar(255) DEFAULT NULL, `user_phNo` varchar(25) DEFAULT NULL, `user_mobileNo` varchar(25) DEFAULT NULL, `user_type` char(2) DEFAULT 'NU', `user_status` char(1) DEFAULT 'A', `user_bgcolor` VARCHAR(7) NOT NULL DEFAULT '#427BC1', `user_lastReadCommentIndex` int(9) DEFAULT NULL, `remindersicalkey` VARCHAR( 32 ) NOT NULL , PRIMARY KEY (`username`), UNIQUE KEY `username` (`username`) )" ;

	$sqlQueries[] = "CREATE TABLE IF NOT EXISTS `WORKS` ( `workID` int(9) unsigned NOT NULL AUTO_INCREMENT, `work_userAssigned` varchar(32) DEFAULT NULL, `work_addedBy` varchar(32) DEFAULT NULL, `work_dateAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, `work_deadLine` date DEFAULT NULL, `work_startDate` timestamp NULL DEFAULT NULL, `work_completeDate` timestamp NULL DEFAULT NULL, `work_closedDate` timestamp NULL DEFAULT NULL , `work_briefDesc` text , `work_Notes` text , `work_status` char(1) DEFAULT '1', `work_priority` char(1) DEFAULT 'N', `work_projectName` varchar(200) DEFAULT NULL, `work_isPrivate` char(1) DEFAULT 'N', `work_hasbeenReset` CHAR(1) NOT NULL DEFAULT 'N' , `work_isNext` char(1) NOT NULL DEFAULT 'N' ,  `daysb4deadline` char(2) DEFAULT '0', `afterCompletionID` INT( 9 ) NULL DEFAULT '0', `work_RTID` int(8) NOT NULL default '0' , PRIMARY KEY (`workID`), UNIQUE KEY `workID` (`workID`), FULLTEXT(work_briefDesc, work_Notes) )" ;

	$sqlQueries[] = "CREATE TABLE IF NOT EXISTS `NOTES` ( `noteID` int(9) unsigned NOT NULL AUTO_INCREMENT, `note_text` text, `note_user` varchar(32), PRIMARY KEY(`noteID`), UNIQUE KEY `noteID` (`noteID`), FULLTEXT(note_text) ) " ;

	$sqlQueries[] = "CREATE TABLE `scheduledmails` ( `sch_emailid` INT( 9 ) NOT NULL AUTO_INCREMENT PRIMARY KEY, `emailby_user` VARCHAR( 32 ) NOT NULL , `emailby_from` VARCHAR( 255 ) NOT NULL , `email_to` VARCHAR( 255 ) NOT NULL DEFAULT 'support@centerlimit.com' ,  `email_content` TEXT NOT NULL , `email_subject` VARCHAR( 255 ) NOT NULL DEFAULT 'reminder', `email_scheduledon` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `email_sent` CHAR( 1 ) NOT NULL DEFAULT 'N', FULLTEXT(email_content, email_subject) )";

	$sqlQueries[] = "CREATE TABLE `dailychecklist` ( `dclid` INT( 8 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , `username` VARCHAR( 32 ) NOT NULL , `task` VARCHAR( 245 ) NOT NULL , `status` CHAR( 1 ) NOT NULL DEFAULT 'N' )" ;

	$sqlQueries[] = " CREATE TABLE `RECCURING_TASKS` ( `RTID` int(8) NOT NULL auto_increment, `RT_project` varchar(200) NOT NULL, `RT_assignto` varchar(32) NOT NULL, `RT_createdby` varchar(32) NOT NULL, `RT_createdDate` timestamp NOT NULL default CURRENT_TIMESTAMP, `RT_Desc` text, `RT_isPrivate` char(1) NOT NULL default 'Y', `RT_startdate` date NOT NULL, `RT_enddate` date NOT NULL, `RT_type` char(1) NOT NULL, `RT_EVERYNDAYS` int(3) default NULL, `RT_EVERYNTHDAYOFMONTH` int(2) default NULL, `RT_EVERYXWEEKDAY` char(3) default NULL, `RT_EVERYDAYOFYEAR_MONTH` char(3) default NULL, `RT_LASTRANON` date NOT NULL, `RT_deadlineDays` INT( 2 ) NOT NULL, PRIMARY KEY  (`RTID`) )" ;

	foreach ($sqlQueries as $thisquery) {
		$query = mysql_query($thisquery);
	}

	@mysql_select_db( MASTERDB );
}


////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////// End of CLIENT MANAGEMENT FUNCTIONS  //////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////


?>