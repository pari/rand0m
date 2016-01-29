<?php
include_once "include_db.php";
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
include_once "include_functions.php";

function checkLogin_B4_Action(){
	if( !$_SESSION["uname"] ){
		session_unset();
		session_destroy();
		send_Action_Response('Fail' , 'Invalid Session!');
		exit();
	}
}

$ACTION = @$_POST["action"] ;
$USERNAME = $_SESSION["uname"];

if( $ACTION == 'doLogin' || $ACTION == 'sendLoginDetails' ){
	// Do not check "if logged in" for these actions
}else{
	checkLogin_B4_Action();
}


switch( $ACTION ) {

	case 'Logout':
		setcookie ( USERCOOKIENAME , "", time() - 3600 );
		session_unset();
		session_destroy();
		logUserEvent( 'User Logged Out' );
		send_Action_Response('Success' , 'Logged Out!');
		exit();
	break;

	case 'doLogin':
		$uname = get_POST_var("uname");
		$uepwd = get_POST_var("uepwd");
		loginUser( $uname, $uepwd );
		exit();
	break;


	case 'updateUserPassword' :
			$upwd_cpass = get_POST_var("upwd_cpass");
			$upwd_nupass = get_POST_var("upwd_nupass");

			$current_password = executesql_returnArray("select password from users where username='$USERNAME'");
			if($current_password <> $upwd_cpass){
				send_Action_Response('Fail' , 'Invalid Current Password!');
			}

			$success = execute_sqlUpdate("users" , array(password=>$upwd_nupass), array(username=>$USERNAME) );

			setcookie ( USERCOOKIENAME , "", time() - 3600 );
			session_unset();
			session_destroy();
			send_Action_Response('Success' , 'Password updated successfully!');
		exit();
	break;

	
	case 'addUserToProject':
			if(!IsSadmin()){ send_Action_Response('Fail' , 'Invalid Session!');}
			$user = get_POST_var('user');
			$project = get_POST_var('project');
			$manageProjects = new manageProjects();
			$manageProjects->addUserToProject( $user, $project );
			send_Action_Response('Success' , 'User added to Project!');
		exit();
	break;

	case 'ping':
		send_Action_Response('Success' , 'pong !');
		exit();
	break;
	
	case 'removeUserFromProject':
			if(!IsSadmin()){ send_Action_Response('Fail' , 'Invalid Session!');}
			$user = get_POST_var('user');
			$project = get_POST_var('project');
			$manageProjects = new manageProjects();
			$manageProjects->deleteUserfromProject( $user, $project );
			send_Action_Response('Success' , 'User deleted from Project!');
		exit();
	break;

	case 'createUser':
		if(!IsSadmin()){ send_Action_Response('Fail' , 'Invalid Session!');}
		$username = get_POST_var("username");
		$password = get_POST_var("password");
		$emailid = get_POST_var("emailid");
		$alertEmail = get_POST_var("alertEmail");
		$phoneNo = get_POST_var("phoneNo");
		$mobileNo = get_POST_var("mobileNo");
		$userstatus = get_POST_var("userstatus");

		//create new user
		$manageUsers = new manageUsers();
		$manageUsers->addUser( array(username=>$username , password=>$password,  user_reportsTo=>'sadmin' , user_primaryEmail=>$emailid, user_alertEmail=>$alertEmail, user_phNo=>$phoneNo , user_mobileNo=>$mobileNo , user_type=>'NU', user_status=>$userstatus ));

		//Add every new user to 'daily tasks' project by default
		$manageProjects = new manageProjects();
		$manageProjects->addUserToProject( $username, DEFAULTPROJECT );

		send_Action_Response('Success' , 'User Created!');
		exit();
	break;


	case 'updateUser':
		if(!IsSadmin()){ send_Action_Response('Fail' , 'Invalid Session!');}
		$username = get_POST_var("username");
		$password = get_POST_var("password");
		$emailid = get_POST_var("emailid");
		$alertEmail = get_POST_var("alertEmail");
		$phoneNo = get_POST_var("phoneNo");
		$mobileNo = get_POST_var("mobileNo");
		$userstatus = get_POST_var("userstatus");

		$manageUsers = new manageUsers();
		$manageUsers->updateUser( $username, array( password=>$password,  user_primaryEmail=>$emailid, user_alertEmail=>$alertEmail, user_phNo=>$phoneNo , user_mobileNo=>$mobileNo , user_status=>$userstatus));
		send_Action_Response('Success' , 'Updated User!');
		exit();
	break;



	case 'update_LRCI':
		$username = get_POST_var("username");
		$lrci = get_POST_var("lrci");
		$manageUsers = new manageUsers();
		$manageUsers->updateLastReadCommentIndex($username, $lrci);
		send_Action_Response('Success' , 'updated!');
		exit();
	break;


	case 'createNewProject':
		if(!IsSadmin()){ send_Action_Response('Fail' , 'Invalid Session!');}
		$projectName = get_POST_var('projectName');
		$projectDesc = get_POST_var('projectDesc');

		$manageProjects = new manageProjects();
		$manageProjects->createNewProject( $projectName, $projectDesc );

		send_Action_Response('Success' , 'Project Created !' );
		exit();
	break;

	case 'updateProjectDesc':
		if(!IsSadmin()){ send_Action_Response('Fail' , 'Invalid Session!');}
		$projectName = get_POST_var('projectName');
		$projectDesc = get_POST_var('projectDesc');
		$manageProjects = new manageProjects();
		$manageProjects->setProjectDescription( $projectName, $projectDesc );
		send_Action_Response('Success' , 'Project Description updated !' );
		exit();
	break;

	case 'closeProject':
		if(!IsSadmin()){ send_Action_Response('Fail' , 'Invalid Session!');}
		$projectName = get_POST_var('projectName');
		$manageProjects = new manageProjects();
		$manageProjects->closeProject( $projectName );
		send_Action_Response('Success' , 'Project Closed !' );
		exit();
	break;

	case 'deleteProject':
		if(!IsSadmin()){ send_Action_Response('Fail' , 'Invalid Session!');}
		$projectName = get_POST_var('projectName');
		$manageProjects = new manageProjects();
		$manageProjects->deleteProject( $projectName );
		send_Action_Response('Success' , 'Project Deleted !' );
		exit();
	break;

	case 'scheduleAnEmail':
		$emailTo = get_POST_var('emailTo');
		$emailSubject = get_POST_var('emailSubject');
		$emailBody = get_POST_var('emailBody');
		$emailWhen = get_POST_var('emailWhen');
		$emailHour = get_POST_var('emailHour');
		$scheduledEmails = new scheduledEmails();
		$scheduledEmails->scheduleNewEmail( $emailTo , $emailBody , $emailSubject, $emailWhen , $emailHour );
		send_Action_Response('Success' , 'Email scheduled !' );
		exit();
	break;

	case 'updateScheduledEmail':
		$reminderId = get_POST_var('reminderId');
		$email_to = get_POST_var('emailTo');
		$email_content = get_POST_var('emailBody');
		$subject = get_POST_var('emailSubject');
		$caldate_day = get_POST_var('emailWhen');
		$hour = get_POST_var('emailHour');

		$scheduledEmails = new scheduledEmails();
		$scheduledEmails->updateScheduledEmail( $reminderId, $email_to , $email_content , $subject, $caldate_day , $hour );

		send_Action_Response('Success' , 'Updated Scheduled Email !');
		exit();
	break;


	case 'get_scheduledEmailDetails':
		$emailId = get_POST_var('emailId');
		$scheduledEmails = new scheduledEmails();
		$result = $scheduledEmails->getSheduledEmail($emailId);
		send_Action_Response('Success' , $result );
		exit();
	break;

	case 'deleteScheduledEmail':
		$schid = get_POST_var('schid');
		$scheduledEmails = new scheduledEmails();
		$scheduledEmails->deleteScheduleEmail( $schid );
		send_Action_Response('Success' , 'Scheduled Email deleted!' );
		exit();
	break;



	case 'rescheduleTask':
		$taskId = get_POST_var('taskId');
		if( !checkPermissions_canUserEditTask( $USERNAME, $taskId ) ){
			send_Action_Response('Fail' , 'insufficient privilege !');
			return;
		}
		$deadline = get_POST_var('reschedule_date');
		$daysb4deadline = get_POST_var('reschedule_daysb4'); $tmp_daysb4deadline = (int)$daysb4deadline;
		$appearAfterTask = get_POST_var('reschedule_afterTask'); $tmp_appearAfterTask = (int)$appearAfterTask;
		// TODO: some logic to check whether appearAfterTask is this user's and its state (not already closed)
		// discuss with venu

		$update_arr = array( work_deadLine => $deadline );

		$COMMENTLOGMESSAGE = "$USERNAME Updated Deadline to $deadline" ;

		if($tmp_daysb4deadline > 0){
			$update_arr['work_status'] = $DE_GLOBALS_WORK_SCHEDULED;
			$update_arr['daysb4deadline'] = $tmp_daysb4deadline ;
			 if($tmp_daysb4deadline){
				 $COMMENTLOGMESSAGE .= "\n Appear in task list $tmp_daysb4deadline days before deadline";
			 }
		}
		if( $tmp_appearAfterTask > 0){
			$update_arr['work_status'] = $DE_GLOBALS_WORK_TASKONTASK;
			$update_arr['afterCompletionID'] = $tmp_appearAfterTask ;
			 if($tmp_appearAfterTask){
				 $COMMENTLOGMESSAGE .= "\n Appear in task list after completion of task $tmp_appearAfterTask";
			 }
		}

		$manageWorks = new manageWorks();
		$manageWorks->updateWork( $taskId, $update_arr );
		$manageWorks->addComment( $taskId, APPNAME , $COMMENTLOGMESSAGE );

		send_Action_Response('Success' , 'Task ReScheduled!' );
		exit();
	break;


	case 'editEmailTasktoNew':
		$editTaskId = get_POST_var('editemailtaskId');
		$projectName = get_POST_var('projectName');
		$briefDesc = get_POST_var('briefDesc');
		$notes = get_POST_var('notes');
		$userassigned = get_POST_var('userassigned');
		$deadline = get_POST_var('deadline');
		$isprivate = get_POST_var('isprivate');
		$priority = get_POST_var('priority');
		$daysb4deadline = get_POST_var('daysb4deadline');
		$onCompletionOf = get_POST_var('onCompletionOf');

		$daysb4deadline = (int)$daysb4deadline;
		$onCompletionOf = (int)$onCompletionOf;

		$work_status = $DE_GLOBALS_WORK_NEW ;

		if($daysb4deadline > 0){
			$work_status = $DE_GLOBALS_WORK_SCHEDULED ;
		}
		if( $onCompletionOf > 0){
			$work_status = $DE_GLOBALS_WORK_TASKONTASK ;
		}

		$manageWorks = new manageWorks();
		$manageWorks->updateWork( $editTaskId, array(work_userAssigned=>$userassigned, work_addedBy=>$USERNAME, work_deadLine=>$deadline, work_briefDesc=>$briefDesc,
			work_Notes=>$notes, work_status=>$work_status, work_priority=>$priority, work_projectName=>$projectName, work_isPrivate => $isprivate, daysb4deadline=>$daysb4deadline, afterCompletionID=>$onCompletionOf ) );

		$COMMENTLOGMESSAGE = "<B>{$USERNAME}</B> Created New Task -- User Assigned:{$userassigned}, DeadLine:{$deadline}, Project:{$projectName}, Appear {$daysb4deadline} days before deadline, Appear after completion of task {$onCompletionOf}";
		$manageWorks->addComment( $editTaskId, APPNAME , $COMMENTLOGMESSAGE );

		send_Action_Response('Success' , "New task $editTaskId Created !" );
		exit();
	break;


	case 'deleteRecurringTask':
		$RTID = get_POST_var('RTID');
		$result = mysql_query( "delete from RECCURING_TASKS where RTID='$RTID' and RT_createdby='$USERNAME' " );
		$result = mysql_query( "delete from WORKS where work_RTID='$RTID' and work_status='$DE_GLOBALS_WORK_SCHEDULED' " );
		send_Action_Response('Success' , "deleted Recurring task !" );
		exit();
	break;

	case 'newRecurringTask':
		
		$nrt_details = array();
		$nrt_details['nrt_project'] = get_POST_var('nrt_project') ;
		$nrt_details['nrt_desc'] = get_POST_var('nrt_desc') ;
		$nrt_details['nrt_assignTo'] = get_POST_var('nrt_assignTo') ;
		$nrt_details['nrt_startDate'] = get_POST_var('nrt_startDate') ;
		$nrt_details['nrt_endDate'] = get_POST_var('nrt_endDate') ;
		$nrt_details['nrt_type'] = get_POST_var('nrt_type') ;
		$nrt_details['nrt_frq_EVERYDAYOFYEAR_day'] = get_POST_var('nrt_frq_EVERYDAYOFYEAR_day') ;
		$nrt_details['nrt_frq_EVERYDAYOFYEAR_month'] = get_POST_var('nrt_frq_EVERYDAYOFYEAR_month') ;
		$nrt_details['nrt_frq_weekday'] = get_POST_var('nrt_frq_weekday') ;
		$nrt_details['nrt_frq_ndays'] = get_POST_var('nrt_frq_ndays') ;
		$nrt_details['nrt_frq_dayofmonth'] = get_POST_var('nrt_frq_dayofmonth') ;
		$nrt_details['nrt_isPublic'] = get_POST_var('nrt_isPublic') ;
		$nrt_details['nrt_createdby'] = $_SESSION["uname"] ;
		$nrt_details['nrt_deadlineDays'] = get_POST_var('nrt_deadlineDays');
		
		$manageWorks = new manageWorks();
		$manageWorks->newRecurringTask($nrt_details);
		
		send_Action_Response('Success' , "New Recurring task created !" );
		exit();
	break;
	
	case 'createNewTask':
		$projectName = get_POST_var('projectName');
		$briefDesc = get_POST_var('briefDesc');
		$notes = get_POST_var('notes');
		$userassigned = get_POST_var('userassigned');
		$deadline = get_POST_var('deadline');
		$isprivate = get_POST_var('isprivate');
		$priority = get_POST_var('priority');
		$daysb4deadline = get_POST_var('daysb4deadline');
		$onCompletionOf = get_POST_var('onCompletionOf');
		$nutask_notify = get_POST_var('notifyAssigned');
		
		$daysb4deadline = (int)$daysb4deadline;
		$onCompletionOf = (int)$onCompletionOf;

		$work_status = $DE_GLOBALS_WORK_NEW ;

		if($daysb4deadline > 0){
			$work_status = $DE_GLOBALS_WORK_SCHEDULED ;
		}
		if( $onCompletionOf > 0){
			$work_status = $DE_GLOBALS_WORK_TASKONTASK ;
		}

		$manageWorks = new manageWorks();
		$manageWorks->newWork( array(work_userAssigned=>$userassigned, work_addedBy=>$USERNAME, work_deadLine=>$deadline, work_briefDesc=>$briefDesc,
			work_Notes=>$notes, work_status=>$work_status, work_priority=>$priority, work_projectName=>$projectName, work_isPrivate => $isprivate, daysb4deadline=>$daysb4deadline, afterCompletionID=>$onCompletionOf ) );
		$this_taskid = mysql_insert_id();

		$COMMENTLOGMESSAGE = "<B>{$USERNAME}</B> Created New Task -- User Assigned:{$userassigned}, DeadLine:{$deadline}, Project:{$projectName}, Appear {$daysb4deadline} days before deadline, Appear after completion of task {$onCompletionOf}";
		$manageWorks->addComment( $this_taskid, APPNAME , $COMMENTLOGMESSAGE );

		if($nutask_notify == 'Y'){
			$tmp_notify_subject = "[#DE New Task] TN{$this_taskid} created by user $USERNAME under project '{$projectName}'" ;
			$tmp_email_body = array();
			$tmp_email_body[] = "Task Description: $briefDesc";
			$tmp_email_body[] = "Task Deadline: ".caldate_to_human($deadline) ;
			$tmp_email_body[] = "Task URL : http://{$_SESSION['subdomain']}.discreteevents.com/taskdetails.php?taskid=$this_taskid ";
			if($notes){ $tmp_email_body[] = "Task Notes: $notes"; }
			 $tmp_notify_body = implode("<BR>", $tmp_email_body) ;
			NotifyEventEmail( $userassigned, $USERNAME, $tmp_notify_subject , $tmp_notify_body );
		}
		
		send_Action_Response('Success' , "Task number '$this_taskid' Created !" );
		exit();
	break;


	case 'startTask':
		$workid = get_POST_var('workid');
		$manageWorks = new manageWorks();
		$manageWorks->startWork( $workid );
		$manageWorks->updateWork( $workid, array(work_hasbeenReset=>'N') );
		$manageWorks->addComment( $workid, APPNAME , "<B>{$USERNAME}</B> moved task to 'In Progress'" );
		send_Action_Response('Success' , "Task $workid marked as 'In Progress' !");
		exit();
	break;
	
	case 'deleteTask':
		$workid = get_POST_var('workid');
		if(!checkPermissions_canUserDeleteTask($USERNAME, $workid) ){
			send_Action_Response('Fail' , 'insufficient privilege !'); return;
		}
		$manageWorks = new manageWorks();
		$manageWorks->deleteTask( $workid );
		$manageWorks->addComment( $workid, APPNAME , "<B>{$USERNAME}</B> deleted task" );
		send_Action_Response('Success' , "Task $workid deleted !");
		exit();
	break;


	case 'setBgColor':
			$newcolor = get_POST_var('newColor');
			$manageUsers = new manageUsers();
			$manageUsers->updateUser( $USERNAME, array(user_bgcolor=>$newcolor));
			send_Action_Response('Success' , "background color updated !");
		exit();
	break;


	case 'setUpcomingTask':
		$workid = get_POST_var('workid');
		$isNext = get_POST_var('isNext');
		if(!checkPermissions_canUserEditTask($USERNAME, $workid) ){
			send_Action_Response('Fail' , 'insufficient privilege !'); return;
		}
		$manageWorks = new manageWorks();
		$manageWorks->updateWork( $workid, array(work_isNext=>$isNext) );
		send_Action_Response('Success' , "Task $workid marked as 'Upcoming' !");
		exit();
	break;


	case 'getAllDetailsOfaTask':
		$workid = get_POST_var('workid');
		if(!checkPermissions_canUserViewTask($USERNAME, $workid) ){
			send_Action_Response('Fail' , 'insufficient privilege !'); return;
		}
		$manageWorks = new manageWorks();
		$tmp_workDetails = $manageWorks->get_workDetails( $workid );
		$TORETURN = array();
		$TORETURN['bdesc'] = base64_encode($tmp_workDetails['work_briefDesc'] );
		$TORETURN['notes'] = base64_encode($tmp_workDetails['work_Notes'] );
		send_Action_Response('Success' , json_encode($TORETURN) );
		exit();
	break;

	case 'task_UnSchedule':
	case 'resetWorkToNew':
		$workid = get_POST_var('workid');
		if(!checkPermissions_canUserEditTask($USERNAME, $workid) ){
			send_Action_Response('Fail' , 'insufficient privilege !'); return;
		}
		// work_hasbeenReset
		$manageWorks = new manageWorks();
		$manageWorks->resetWorkToNew( $workid );
		$tmp_workDetails = $manageWorks->get_workDetails( $workid );
		if( $tmp_workDetails['work_addedBy'] <> $USERNAME ){
			$manageWorks->updateWork( $workid, array(work_hasbeenReset=>'Y') );
		}
		
		if($ACTION=='task_UnSchedule'){
			$manageWorks->addComment( $workid, APPNAME , "<B>{$USERNAME}</B> moved task to 'New Task' from 'Scheduled Tasks' " );
		}else{
			$manageWorks->addComment( $workid, APPNAME , "<B>{$USERNAME}</B> moved task to 'New Task'" );
		}
		send_Action_Response('Success' , "Task $workid marked as 'New Task' !");
		exit();
	break;

	case 'completeWork':
		$workid = get_POST_var('workid');
		if(!checkPermissions_canUserEditTask($USERNAME, $workid) ){
			send_Action_Response('Fail' , 'insufficient privilege !'); return;
		}
		$manageWorks = new manageWorks();
		$manageWorks->completeWork( $workid );
		$manageWorks->addComment( $workid, APPNAME , "<B>{$USERNAME}</B> marked task as 'Completed' " );
		send_Action_Response('Success' , "Task $workid marked as <b>Complete</b> !");
		exit();
	break;

	case 'closeWork':
		$workid = get_POST_var('workid');
		if(!checkPermissions_canUserEditTask($USERNAME, $workid) ){
			send_Action_Response('Fail' , 'insufficient privilege !'); return;
		}
		$manageWorks = new manageWorks();
		$manageWorks->closeWork( $workid );
		$manageWorks->addComment( $workid, APPNAME , "<B>{$USERNAME}</B> 'Closed' task " );
		send_Action_Response('Success' , "Closed Task $workid !");
		exit();
	break;


	case 'emailTaskDetails':
		$workid = get_POST_var('workid');
		$toemailId = get_POST_var('emailId');
		$includeDetails = get_POST_var('includeDetails');
		$includeAttachments = get_POST_var('attachments');

		$manageWorks = new manageWorks();
		$taskDetails = $manageWorks->get_workDetails( $workid );
		$responseEmail = '';

		$comments_result = mysql_query("select comment_date, comment_by, comment from COMMENTS where workID='$workid' and comment_by != '".APPNAME. "' ORDER BY commentID DESC");
		$COMMENTLOGMESSAGE = "<B>{$USERNAME}</B> emailed task details to {$toemailId} " ;

		if( $includeDetails == 'yes' ){
			$COMMENTLOGMESSAGE .= "\n Included Details" ;
			$responseEmail .= "\n"."Description : ".$taskDetails["work_briefDesc"] ;
			$responseEmail .= "\n"."Notes : ".$taskDetails["work_Notes"] ;
			$responseEmail .= "\n"."Added By : ".$taskDetails["work_addedBy"] ;
			$responseEmail .= "\n"."Assigned to : ".$taskDetails["work_userAssigned"] ;
			$responseEmail .= "\n"."Task Created On : ".$taskDetails["work_dateAdded"] ;
			$responseEmail .= "\n"."Task Deadline : ".$taskDetails["work_deadLine"] ;
			$responseEmail .= "\n\n"."Comments : ";
			while ($row = mysql_fetch_assoc($comments_result)) {
				$responseEmail .= "\n"."----------------------------------------------------";
				$responseEmail .= "\n".$row['comment_by']." on ".$row['comment_date'] ;
				$responseEmail .= "\n".$row['comment'];
			}
			$responseEmail .= "\n\n";
		}else{
			$responseEmail = $taskDetails["work_briefDesc"];
		}
			$tmp_manageUsers = new manageUsers();
			$user_fromEMailId = $tmp_manageUsers->get_userSingleDetail( $USERNAME , 'user_primaryEmail' );

			$email = new sendaMail();
			$email->asFrom( $user_fromEMailId );
			$email->messageTo( $toemailId );
			$email->subject( "Details of task - " . $workid );
			$email->body( $responseEmail );
			if($includeAttachments == 'yes'){
				$COMMENTLOGMESSAGE .= "\n Included Attachments" ;
				$attachments = mysql_query("select diskfilename, uploadname from attachments where workid='$workid' ");
				while ($row = mysql_fetch_assoc($attachments)) {
					$email->AddAttachment( APP_INSTALLPATH.'attachments/'.$_SESSION["subdomain"].'/'.$row['diskfilename'] , $row['uploadname'] );
				}
			}
			$email->send();
		$manageWorks->addComment( $workid, APPNAME , $COMMENTLOGMESSAGE );


		send_Action_Response('Success' , "Details emailed !");
		exit();
	break;


	case 'AddComment':
		$comment = htmlentities($_POST['newComment']);
		$workid = get_POST_var('workid');
		$notifyAssigned = get_POST_var('notifyAssigned');
		
		if(!checkPermissions_canUserViewTask($USERNAME, $workid) ){
			send_Action_Response('Fail' , 'insufficient privilege !'); return;
		}
		
		$manageWorks = new manageWorks();
		$manageWorks->addComment( $workid, $USERNAME , $comment );
		
		if($notifyAssigned == 'Y'){
			$ThisWorkDetails = $manageWorks->get_workDetails($workid);
			$userassigned = $ThisWorkDetails['work_userAssigned'];
			$userOwner = $ThisWorkDetails['work_addedBy'];
			$work_briefDesc = $ThisWorkDetails['work_briefDesc'];
			$OtherPerson = ( $USERNAME == $userassigned ) ? $userOwner : $userassigned ;
			
			$tmp_notify_subject = "[#DE] new comment by {$USERNAME} on  '{$work_briefDesc}' " ;
			
			$tmp_notify_body = " <BR> New Comment :<BR> <B> $comment </B>
								<BR> -------------------------------------------
								<BR> Quick link to task : http://{$_SESSION['subdomain']}.discreteevents.com/taskdetails.php?taskid=$workid " ;

			NotifyEventEmail( $OtherPerson, $USERNAME, $tmp_notify_subject , $tmp_notify_body );
		}
		
		send_Action_Response('Success' , "Comment Added !");
		exit();
	break;
	
	case 'AddNote':
		$newNote = get_POST_var('NewNoteText');
		$manageNotes = new manageNotes();
		$manageNotes->insertNote($newNote);
		send_Action_Response('Success' , "Note Added !");
		exit();
	break;

	case 'DeleteNote':
		$nid = get_POST_var('noteId');
		$manageNotes = new manageNotes();
		$manageNotes->deleteNoteWithID($nid);
		send_Action_Response('Success' , "Note Deleted !");
		exit();
	break;

	case 'EmailNote':
		$nid = get_POST_var('noteId');
		$emailTo = get_POST_var('emailTo');
		$manageNotes = new manageNotes();
		$note_content = $manageNotes->getNoteWithID($nid);
			$email = new sendaMail();
			$email->messageTo( $emailTo );
			$email->subject( "Note from Discrete Events" );
			$email->body( $note_content );
			$email->send();
		send_Action_Response('Success' , "Note Emailed !");
		exit();
	break;


	case 'UpdateNote':
		$nid = get_POST_var('noteId');
		$updatedContent = get_POST_var('updatedContent');
		$manageNotes = new manageNotes();
		$manageNotes->updateNote($nid , $updatedContent);
		send_Action_Response('Success' , "Note Updated !");
		exit();
	break;
	
	case 'editTaskField':
		$workid = get_POST_var('workid');
		$fieldName = get_POST_var('fieldName');
		$fieldValue = get_POST_var('fieldValue');
		if(!checkPermissions_canUserEditTask($USERNAME, $workid) ){
			send_Action_Response('Fail' , 'insufficient privilege !'); return;
		}
		switch ($fieldName) {
			case 'userAssigned': $actualFieldName = 'work_userAssigned'; break;
			case 'deadLine': $actualFieldName = 'work_deadLine'; break;
			case 'projectName': $actualFieldName = 'work_projectName'; break;
			case 'priority': $actualFieldName = 'work_priority'; break;
			case 'briefDesc': $actualFieldName = 'work_briefDesc'; break;
			case 'Notes': $actualFieldName = 'work_Notes'; break;
			case 'isPrivate': $actualFieldName = 'work_isPrivate'; $fieldValue = ($fieldValue=='yes') ? 'Y':'N'; break;
		}
		$somearray = array();
		$somearray[$actualFieldName] = $fieldValue;

		$manageWorks = new manageWorks();
		$manageWorks->updateWork( $workid, $somearray );
		$manageWorks->addComment( $workid, APPNAME , "<B>{$USERNAME}</B> updated field {$fieldName} to $fieldValue" );
		send_Action_Response('Success' , "updated Task $workid !");
		exit();
	break;

	case 'newRoutineTask':
		$nrt = get_POST_var('nrt');
		$result = execute_sqlInsert("dailychecklist", array(username=>$USERNAME , task=>$nrt) );
		send_Action_Response('Success' , "Note Routine task added !");
		exit();
	break;
	
	case 'resetRoutineTasksStatus':
		$result = execute_sqlUpdate("dailychecklist", array(status=>'N') , array(username=>$USERNAME) );
		send_Action_Response('Success' , "Status Reset !");
		exit();
	break;
	
	case 'updateTasksStatus':
		$dclid = get_POST_var('dclid');
		$task_status = get_POST_var('task_status');
		$result = execute_sqlUpdate("dailychecklist", array(status=>$task_status) , array(username=>$USERNAME, dclid=>$dclid) );
		send_Action_Response('Success' , "Status Reset !");
		exit();
	break;

	case 'deleteDailyRoutineTask':
		$dclid = get_POST_var('dclid');
		$result = mysql_query("delete from dailychecklist where username='$USERNAME' and dclid='$dclid' ");
		send_Action_Response('Success' , "deleted daily routine task !");
		exit();
	break;


	case 'newIcalURL':
		$new_Key = getaRandomString(32);
		$result = execute_sqlUpdate("users", array('remindersicalkey'=>$new_Key) , array('username'=>$USERNAME) );
		send_Action_Response('Success' , "ical key was reset !");
		exit();
	break;
	
	
	
	
	case 'getJsonEventsForCalendarView':
		$tasks = array();
		$twomonths_ago = date("Y-m-d", strtotime("-2 month"));
		$fourmonths_fromnow = date("Y-m-d", strtotime("+4 month"));
		$sqlquery = "select work_deadLine, workID, work_briefDesc, work_RTID from works_todolist where work_deadLine > '$twomonths_ago' and work_deadLine < '$fourmonths_fromnow' and work_userAssigned = '$USERNAME' " ;
		$query = mysql_query($sqlquery) or die("Invalid query: " . mysql_error()) ;
		WHILE ($row = @mysql_fetch_array($query)){
			// extract($row) ; // $work_deadLine, workID, work_briefDesc, work_RTID
			list( $evYear , $evMonth, $evDay ) = explode( '-' , $row['work_deadLine'] );
			$ntask = array();
			$ntask['tY'] = $evYear; $ntask['tM'] = $evMonth; $ntask['tD'] = $evDay;
			$ntask['tid'] = $row['workID'];
			$ntask['tdesc'] = $row['work_briefDesc'];
			$ntask['trtid'] = $row['work_RTID'];
			
			$tasks[] = $ntask;
		}

		send_Action_Response('Success' , "MYCALTASKS = " . json_encode($tasks) . " ; ");
		exit();
	break;
	
	
	
	
	default:
	break;

}



?>