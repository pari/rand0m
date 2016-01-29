<?php


function some_prettyPicture_JsCrap(){
	?>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
			$(".umsg:first a[rel^='prettyPhoto']").prettyPhoto({animationSpeed:'slow',slideshow:2000, autoplay_slideshow: false});
			$(".umsg:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animationSpeed:'fast',slideshow:10000});

			$("#custom_content a[rel^='prettyPhoto']:first").prettyPhoto({
				custom_markup: '<div id="map_canvas" style="width:260px; height:265px"></div>',
				changepicturecallback: function(){ initialize(); }
			});

			$("#custom_content a[rel^='prettyPhoto']:last").prettyPhoto({
				custom_markup: '<div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
				changepicturecallback: function(){ _bsap.exec(); }
			});
		});
	</script>
	<?php
}



function USERID_TO_USERNAME ($USERID){
	return executesql_returnArray( "SELECT emplUsername FROM tblAppUsers WHERE empl_id='{$USERID}'" );
}

function USERID_TO_USERPIC ($USERID){
	$uimage = executesql_returnArray( "SELECT userImage FROM tblAppUsers WHERE empl_id='{$USERID}'" );
	if(!$uimage){ $uimage = 'avatar.png' ;}
	return $uimage ;
}


function register_LastPingAt(){
	// record emplLastPingAt 
	$USERNAME = $_SESSION["uname"];
	$gmt_time = executesql_returnArray("SELECT NOW() as curgmttime");
	execute_sqlUpdate( 'tblAppUsers' , array('emplLastPingAt'=>$gmt_time ), array('emplUsername'=>$USERNAME) );
}


function getAppVariable($varName){
	return executesql_returnArray("SELECT var_value FROM tblApp_Variables WHERE var_name='{$varName}'") ;
}


function setAppVariable($varName,$varValue){
	return execute_sqlUpdate('tblApp_Variables', array('var_value'=>$varValue) , array('var_name'=>$varName ) ) ;
}


function get_ChatroomQuery_forUser_RoomId( $userId , $roomId , $LastXMessages = 0 , $messagesNewerThan=0 ){
	$newer_than_sql = ''; $lastX_sql = '';
	
	if($LastXMessages){
		$lastX_sql = " AND (`tbl_ChatRooms`.`msgid` >= (select max(msgid) from tbl_ChatRooms) - $LastXMessages ) " ;
	}
	if($messagesNewerThan){
		$newer_than_sql = " AND (msgid > {$messagesNewerThan})" ;
	}
	
	
	$result = "select `tbl_ChatRooms`.`msgid` AS `msgid`, `tbl_ChatRooms`.`saidBy_username` AS `saidBy_username` , `tbl_ChatRooms`.`saidBy_empl_id` AS `saidBy_empl_id` ,`tbl_ChatRooms`.`chatRoom` AS `chatRoom` ,`tbl_ChatRooms`.`message_base64` AS `message_base64` ,`tbl_ChatRooms`.`message_plain_mysqlescaped` AS `message_plain_mysqlescaped` , `tbl_ChatRooms`.`msgtime` AS `msgtime` , `tbl_ChatRooms`.`msgType` AS `msgType` , `tbl_ChatRooms`.`fileId` AS `fileId` , `tbl_ChatRooms`.`bookmark` AS `bookmark` , `tbl_BookMarks`.`bkm_id` AS `bkm_id` , `tbl_BookMarks`.`bkm_empl_id` AS `bkm_empl_id` , `tbl_BookMarks`.`bkm_roomId` AS `bkm_roomId`,`tbl_BookMarks`.`bkm_date` AS `bkm_date` from (`tbl_ChatRooms` left join `tbl_BookMarks` on (((`tbl_ChatRooms`.`msgid` = `tbl_BookMarks`.`bkm_msgId`) and (`tbl_BookMarks`.`bkm_empl_id` = '{$userId}') ))) where (`tbl_ChatRooms`.`chatRoom`='{$roomId}') $lastX_sql $newer_than_sql ORDER BY `tbl_ChatRooms`.`msgid`";
	return $result ;
}



function sendMailUsingMailer($to_emails, $subject, $body, $attachment_name='', $actual_attachment=''){
	$mail = new PHPMailer();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "tls";
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587;
	$mail->Username = "support.loktantra@gmail.com";
	$mail->Password = "loktlokt";
	$mail->From = 'support.loktantra@gmail.com';
	$mail->FromName = 'Cigniti Chats';
	$mail->IsHTML(true);
	$mail->Subject = $subject;
	
	$valid_email_cnt = 0;
	$invalid_emails = '';
	$valid_email_adds = '';
	$to_list = explode(',',$to_emails);
	
	for($c=0;$c<count($to_list);$c++){	
		if(isValidEmail(trim($to_list[$c]))){
			$valid_email_cnt++;
			$to_email = trim($to_list[$c]);
			$mail->AddAddress($to_email);			
		}else{
			$invalid_emails .= $to_list[$c].",";
		}
	}
	
	if($attachment_name){
		$mail->AddAttachment($attachment_name, $actual_attachment); // attach files/invoice-user-1234.pdf, and rename it to invoice.pdf
	}
	
	$mail->Body = nl2br($body);
	if(!$mail->Send()){
	   return "Error sending: " . $mail->ErrorInfo;
	}else{
		return "Letter is sent";
	}
	
	$mail->ClearAllRecipients();
}





class ManageUsers{
	public $userId = 0 ;
	public function isActiveUser(){
		
	}
	
	public function getAllUserIdsInDomain(){
		return executesql_returnStrictArray("SELECT empl_id FROM tblAppUsers ORDER BY emplUsername");
	}
	
	
	public function getUserProfile($uid=0){
		if(!$uid){ 
			$uid = $this->userId ;
		}
		return executesql_returnAssocArray("SELECT * FROM tblAppUsers WHERE empl_id='$uid'");
	}
	
	public function getUserAttribute($attribute){ // $attribute = 'TimeZone'
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		return executesql_returnArray("SELECT $attribute FROM tblAppUsers WHERE empl_id='{$userId}'");
	}
	
	public function updateUserAttributes( $attribute , $attibValue ){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		
		$updates = array();
		$updates[$attribute] = $attibValue ;
		$result = execute_sqlUpdate('tblAppUsers', $updates , array('empl_id'=>$userId ) ) ;
	}
	
	public function getUser_AllowedChatRooms(){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		
		if( $this->isPrivilagedUser($userId) || $this->isAdminUser($userId) ){
			return executesql_returnStrictArray("SELECT roomId FROM tblRooms where roomActive='Y' order by roomName") ;
		}else{
			// $sql = "SELECT r.* FROM tblRooms r, tbl_RoomPrivilages rp WHERE r.roomId=rp.rid AND rp.uid='$uid'";
			return executesql_returnStrictArray("SELECT roomId FROM tblRooms where roomActive='Y' and roomId IN (select rid from tbl_RoomPrivilages where uid='{$userId}' )") ;
		}
	}
	
	public function getUser_AllowedChatRooms_IncludeDepricatedOnes(){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		
		if( $this->isPrivilagedUser($userId) || $this->isAdminUser($userId) ){
			return executesql_returnStrictArray("SELECT roomId FROM tblRooms order by roomName") ;
		}else{
			return executesql_returnStrictArray("SELECT roomId FROM tblRooms where roomId IN (select rid from tbl_RoomPrivilages where uid='{$userId}' )") ;
		}
	}
	
	public function get_LIstOfDepricatedRooms(){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		
		if( $this->isPrivilagedUser($userId) || $this->isAdminUser($userId) ){
			return executesql_returnArray("SELECT roomId FROM tblRooms where roomActive='N' order by roomName") ;
		}else{
			return executesql_returnArray("SELECT roomId FROM tblRooms where  roomActive='N' and roomId IN (select rid in tbl_RoomPrivilages where uid='{$userId}' )") ;
		}
	}
	
	public function Allow_AccessToRoom($roomId){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		
		$result = execute_sqlInsert('tbl_RoomPrivilages', array('rid'=>$roomId , 'uid'=>$userId ) ) ;
	}
	
	public function Remove_AccessToRoom($roomId){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		$result = mysql_query("delete from tbl_RoomPrivilages where rid='{$roomId}' and uid='{$userId}' ");
	}
	
	public function getUserLastLogin(){
		return $this->getUserAttribute('emplLastLoginAt');
	}
	
	public function getUsertLastActiveOn(){
		return $this->getUserAttribute('emplLastPingAt');
	}
	
	public function getUserTimeZone(){
		return $this->getUserAttribute('TimeZone');
	}
	
	public function convert_to_UsersTimeZone($timestamp){ // FROM GMT 
		$offset = (int)$this->getUserTimeZone('TimeZone');
		// get unix time stamp of this timestamp
		list($tmp_datepart , $tmp_timepart) = explode(" ", $timestamp);
		list($stYear, $stMonth, $stDay) = explode( "-", $tmp_datepart );
		list($stHr, $stMin, $stSec) = explode( ":", $tmp_timepart );
		$Unixstamp = @mktime( $stHr, $stMin, $stSec, $stMonth, $stDay, $stYear );
		// add timezone offset
		$timezoneoffset = $offset* 60*60; // Get offset between local and GMT 
		$Unixstamp = $Unixstamp + $timezoneoffset ;
		// convert back to system time stamp
		return date("Y-m-d H:i:s" , $Unixstamp );
	}
	
	public function convert_from_UsersTimeZone($timestamp){ // to GMT 
		$offset = $this->getUserTimeZone('TimeZone');
		// get unix time stamp of this timestamp
		list($tmp_datepart , $tmp_timepart) = explode(" ", $timestamp);
		list($stYear, $stMonth, $stDay) = explode( "-", $tmp_datepart );
		list($stHr, $stMin, $stSec) = explode( ":", $tmp_timepart );
		$Unixstamp = @mktime( $stHr, $stMin, $stSec, $stMonth, $stDay, $stYear );
		// add timezone offset
		$timezoneoffset = $offset* 60*60; // Get offset between local and GMT 
		$Unixstamp = $Unixstamp - $timezoneoffset ;
		// convert back to system time stamp
		return date("Y-m-d H:i:s" , $Unixstamp );
	}
	
	public function isAdminUser(){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		return ($userId == 1);
	}
	
	public function isPrivilagedUser(){
		return $this->has_Privilege('Can Access all Rooms');
	}
	
	public function has_AccessToRoom($roomId){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }

		if( $this->isAdminUser($userId) || $this->isPrivilagedUser($userId)){
			return true;
		}else{
			$thisUser_Rooms = $this->getUser_AllowedChatRooms_IncludeDepricatedOnes();
			return in_array( $roomId , $thisUser_Rooms );
		}
	}
	
	public function has_AccessToFile($fileId){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		
		// see which chatRoom or directMessage this fileId belongs to
		// check if the user has access to that ChatRoom OR is a participant of that directmessage
		$chatRoomId = executesql_returnArray("select chatRoom from tbl_ChatRooms where fileId='{$fileId}' ");
		if($chatRoomId){
			return $this->has_AccessToRoom($chatRoomId);
		}else{ // file does not belond to a chat room, must be a direct message File
			$isUser_relatedToFile = executesql_returnStrictArray(" select dmsgid from tbl_DirectMessages where from_uid='{$userId}' OR to_uid='{$userId}' ");
			return (count($isUser_relatedToFile)) ? true : false ;
		}
	}
	
	public function get_LoggedInRooms(){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		return executesql_returnStrictArray("SELECT rid FROM tbl_CurrentSessions WHERE uid='{$userId}' ") ;
	}
	
	public function isUserCurrently_InRoom( $roomId ){
		$loggedInRooms = $this->get_LoggedInRooms();
		return in_array($roomId , $loggedInRooms );
	}

	public function LogIn_ToRoom($roomId){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		if( !$this->has_AccessToRoom($roomId) ){
			return false;
		}
		if( $this->isUserCurrently_InRoom($roomId)){ return ; }
		$success = execute_sqlInsert( 'tbl_CurrentSessions',  array( 'uid'=>$userId , 'rid'=>$roomId ) ) ;
		$tempnewmsg = "has entered the room";
		$success = execute_sqlInsert( 'tbl_ChatRooms', 
			array( 
				'saidBy_username'=>USERID_TO_USERNAME($userId) ,
				'saidBy_empl_id'=>$userId ,
				'message_base64' => base64_encode( htmlentities( $tempnewmsg, ENT_QUOTES ) ),
				'message_plain_mysqlescaped' => $tempnewmsg,
				'chatRoom' => $roomId,
				'msgType' => 'L'
			)
		);
		return true;
	}
	
	
	public function Logout_fromRoom($roomId){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		
		if( !$this->has_AccessToRoom($roomId) ){ return ; }
		if( !$this->isUserCurrently_InRoom($roomId)){ return ; }
		
		$result = mysql_query("delete from tbl_CurrentSessions where uid='{$userId}' and rid='{$roomId}' ");
		$tempnewmsg = "has left the room";
		$success = execute_sqlInsert( 'tbl_ChatRooms', 
			array( 
				'saidBy_username'=>USERID_TO_USERNAME($userId) ,
				'saidBy_empl_id'=> $userId ,
				'message_base64' => base64_encode( htmlentities( $tempnewmsg, ENT_QUOTES ) ),
				'message_plain_mysqlescaped' => $tempnewmsg,
				'chatRoom' => $roomId,
				'msgType' => 'L'
			)
		);
		
		return $result;
	}
	
	public function Logout_fromAllRooms(){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		$LoggedInRooms = $this->get_LoggedInRooms();
		foreach($LoggedInRooms as $this_Room){
			$this->Logout_fromRoom($this_Room) ;
		}
	}
	
	public function set_status($message){
		$this->updateUserAttributes( 'statusMsg' , $message );
	}
	
	public function get_status(){
		return $this->getUserAttribute( 'statusMsg' );
	}
	
	public function has_Privilege($privilegeDescription){
		global $GLOBAL_PRIVILEGE_DEFNS ; 
		$privilegeDescription_Id = $GLOBAL_PRIVILEGE_DEFNS[$privilegeDescription];
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		$uid_count = executesql_returnStrictArray( "select uid from tbl_userPrivilages where uid='{$userId}' and pid='{$privilegeDescription_Id}' ");
		return ( count($uid_count) ) ? true : false ;
	}
	
	public function has_PrivilegeID($privilegeID){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		$uid_count = executesql_returnStrictArray( "select uid from tbl_userPrivilages where uid='{$userId}' and pid='{$privilegeID}' ");
		return ( count($uid_count) ) ? true : false ;
	}
	
	public function get_Privileges(){
		// returns PrivilegeIds allowed to current user
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		return executesql_returnStrictArray( "select pid from tbl_userPrivilages where uid='{$userId}' ");
	}
	
	public function get_All_Privileges_in_App(){
		// returns PrivilegeIds allowed to current user
		return executesql_returnStrictArray( "select pid from tbl_privilages ");
	}
	
	public function get_Privilege_Name_By_Id($prevId){
		return executesql_returnArray( "select ptitle from tbl_privilages WHERE pid='$prevId' ");
	}
	
	public function update_Privilege( $PrivilegeId , $giveOrTake ){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		if($giveOrTake){
			if($this->has_PrivilegeID($PrivilegeId)){
				// priviege alredy exists .. nothing to do
				return ;
			}else{
				$result = execute_sqlInsert('tbl_userPrivilages', array('pid'=>$PrivilegeId , 'uid'=>$userId ) ) ;
			}
		}else{
			$result = mysql_query("delete from tbl_userPrivilages where uid = '$userId' AND pid='$PrivilegeId' ");
		}
	}
	
	// public function get_ListOfInvitedUsers_WhoHaveNotSignedUp(){ }
	
	public function send_DirectMessageTo( $ToUserId, $directMessage ){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		$success = execute_sqlInsert( 'tbl_DirectMessages', 
			array( 
				'to_uid'=>$ToUserId ,
				'from_uid'=>$userId ,
				'msg_base64' => base64_encode( htmlentities( base64_decode($directMessage), ENT_QUOTES ) ),
				'msg_plain' => base64_decode($directMessage)
			)
		);
	}
	
	public function get_ListOfDirectMessages(){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		return executesql_returnMultiArray("SELECT * FROM tbl_DirectMessages WHERE (from_uid='{$userId}' OR  to_uid='{$userId}') limit 200");
	}
	
	public function get_newDirectMessageCount(){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		return executesql_returnArray("select count(dmsgid) from tbl_DirectMessages where msgStatus='N' and to_uid='{$userId}' ");
	}
	
	public function mark_DirectMessages_MarkAsRead(){
		$userId = $this->userId ;
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }
		return executesql_returnArray("update tbl_DirectMessages set msgStatus='N' where msgStatus='Y' and to_uid='{$userId}' ");
	}
	
	public function createUnique_InviteID($no_of_digits){
		$pass = getaRandomString($no_of_digits) ;
		$check_fid = mysql_num_rows(mysql_query("SELECT invi_key FROM tbl_Invitations WHERE invi_key='$pass'"));
		return ($check_fid > 0) ? $this->createUnique_InviteID($no_of_digits) : $pass ;
	}
	
	public  function checkFor_User_Email_Existence($email){
		$existingEmail = executesql_returnArray("select emplEmail_id from tblAppUsers where emplEmail_id='{$email}'");
		if($existingEmail)
		return true;
		else
		return false;
	}
	
	public  function checkFor_UserName_Existence($uname){
		$existingUser = executesql_returnArray("select emplUsername from tblAppUsers where emplUsername='{$uname}'");
		if($existingUser)
		return true;
		else
		return false;
	}
	
	public  function insert_Invitaion($fName, $lName, $msg, $eMail, $USERNAME, $roomid, $invi_key){
		$success = execute_sqlInsert( 'tbl_Invitations', 
				array( 
					'invi_firstName'=>$fName ,
					'invi_lastName'=>$lName ,
					'invi_msg'=>$msg ,
					'invi_email'=>$eMail ,
					'invi_sent_by'=>$USERNAME ,
					'invi_room' => $roomid,
					'invi_key'=>$invi_key
				)
			);
		
		$invi_id = mysql_insert_id();
		
		if($invi_id)
		return $invi_id;
		else
		return false;
		
	}	
	
	
	public  function insert_newUser($uName, $pwd, $invi_email, $invi_fName, $mobile, $des, $timezone){
		
		$success = execute_sqlInsert( 'tblAppUsers', 
				array( 
					'emplUserName'=>$uName ,
					'emplPassword'=>md5($pwd) ,
					'emplEmail_id'=>$invi_email ,
					'emplFullName'=>$invi_fName ,
					'emplMobileNo'=>$mobile ,
					'emplDesignation'=>$des,
					'TimeZone'=>$timezone			
				)
			);
		
		$user_id = mysql_insert_id();
		
		if($user_id)
		return $user_id;
		else
		return false;
		
	}	

}



class ManageChatRooms{
	
	public function get_ListOfActiveUsersIn_Application(){
		$five_mins_ago = getCaldate_Of_n_SecondsFrom_TimeX ( -300 , get_currentPHPTimestamp());
		return executesql_returnStrictArray("select distinct u.empl_id from tblAppUsers u where u.emplLastPingAt > '{$five_mins_ago}' ");
	}
	
	public function get_ListOfActiveUsersIn_AnyChatRoom(){
		$five_mins_ago = getCaldate_Of_n_SecondsFrom_TimeX ( -300 , get_currentPHPTimestamp());
		return executesql_returnStrictArray("select distinct u.empl_id from tblAppUsers u, tbl_CurrentSessions cs where u.empl_id=cs.uid AND u.emplLastPingAt > '{$five_mins_ago}' ");
	}
	
	public function get_ListOfActiveUsersIn_ChatRoom( $rid ){
		$five_mins_ago = getCaldate_Of_n_SecondsFrom_TimeX ( -300 , get_currentPHPTimestamp());
		return executesql_returnStrictArray("select distinct u.empl_id from tblAppUsers u, tbl_CurrentSessions cs where cs.rid='$rid' AND u.empl_id=cs.uid AND u.emplLastPingAt > '{$five_mins_ago}' ");
	}

	public function get_ListOfActiveUserNamesIn_ChatRoom($rid){
		$AUIDS = $this->get_ListOfActiveUsersIn_ChatRoom($rid);
		$AUNAMES = array();
		foreach($AUIDS as $THIS_UID){
			$AUNAMES[] = USERID_TO_USERNAME($THIS_UID);
		}
		return $AUNAMES ;
	}
	
	public function get_ListOfAllowedUsers($roomId){
		global $GLOBAL_PRIVILEGE_DEFNS;
		
		// CREATE TEMPORARY TABLE alluids_thisRoom ENGINE = MEMORY (select 1 as uid) 
		// union
		// (select uid from tbl_RoomPrivilages where rid ='6')
		// union
		// (select uid from tbl_userPrivilages where pid='4') ;
		// select distinct uid from alluids_thisRoom;

		$this_query = "CREATE TEMPORARY TABLE alluids_thisRoom ENGINE = MEMORY (select 1 as uid) union (select uid from tbl_RoomPrivilages where rid ='{$roomId}') union (select uid from tbl_userPrivilages where pid='{$GLOBAL_PRIVILEGE_DEFNS['Can Access all Rooms']}')";
		mysql_query($this_query);
		return executesql_returnStrictArray( "select distinct uid from alluids_thisRoom" );
	}

	public function get_ListOfAllUsersInRoom_andStatus($roomId){
		// get list of all users 
		// get list of active users
		$ALLUSERS_STATUSS = array();
		$allusers = $this->get_ListOfAllowedUsers($roomId);
		$activeUsers_inRoom = $this->get_ListOfActiveUsersIn_ChatRoom($roomId);
		
		foreach($allusers as $this_userId){
			$active_status = (in_array($this_userId , $activeUsers_inRoom ) ) ? 'active' : 'inactive';
			$ALLUSERS_STATUSS[] = array('userid'=>$this_userId , 'username'=>USERID_TO_USERNAME($this_userId) , 'status'=>$active_status ) ;
		}
		
		
		return $ALLUSERS_STATUSS;
	}
	
	public function get_ListOfAllRooms(){
		return executesql_returnMultiArray( "select * from tblRooms" );
	}
	

	public function get_roomTitle($rid){
		return executesql_returnArray("SELECT roomName FROM tblRooms WHERE roomId='$rid' ");
	}

	public function get_roomDescription($rid){
		return executesql_returnArray("SELECT roomDesc FROM tblRooms WHERE roomId='$rid' ");
	}
	
	public function get_archive_for_user_onDay( $date , $roomId , $userId ){
		$conditions = array();
		$start_time_fixed = $date . ' 00:00:00';
		$end_time_fixed = $date . ' 23:59:59';
		$MU = new ManageUsers();
		$MU->userId = $userId;
		if( !$MU->has_AccessToRoom($roomId) ){ return array(); }
		$start_time_GMT = $MU->convert_from_UsersTimeZone( $start_time_fixed );
		$end_time_GMT = $MU->convert_from_UsersTimeZone( $end_time_fixed );
		$conditions[] = " chatRoom='{$roomId}' " ;
		$conditions[] = " (msgtime between '{$start_time_GMT}' and '{$end_time_GMT}' ) " ;
		$conditions_string = implode(" and " , $conditions );
		return executesql_returnMultiArray("SELECT * FROM tbl_ChatRooms WHERE $conditions_string ORDER BY msgid");
	}
	
	public function create_Room( $title, $Description, $AllowedUsers ){
		
		// TODO for sagar
		// sagar this function should return false on error and return true on successfull creation of a Room
		// And You Should do a Validation for $title to make sure it does not contain invalid characters
		// the only Characters allowd in a room title should be A-Za-z0-9 and a space
		
		// TODO for sagar
		// we need a similar class method to update the existing room's title, description and members
		
		$MU = new ManageUsers();
		$MU->userId = $_SESSION['empl_id'];
		// check if user can create New Rooms
			if( ! ($MU->isAdminUser() || $MU->has_Privilege('Can Create New Rooms') ) ){ return false; }
		// Create the room
			$success = execute_sqlInsert( 'tblRooms', 
				array( 
					'roomName'=> $title ,
					'roomDesc'=> $Description,
					'roomCreatedBy'=> $_SESSION['empl_id']
				)
			);
			$newRoomId = mysql_insert_id();
		// add given users to the created room
			foreach($AllowedUsers as $this_user){
				$MU->userId = $this_user;
				$MU->Allow_AccessToRoom($newRoomId);
			}
		return true;
	}
	
	// Todo: Lock_Room()
	// Todo: UnLock_Room()
	public function Depricate_Room($roomId){
		$MU = new ManageUsers();
		$MU->userId = $_SESSION['empl_id'];
		if(!$MU->isAdminUser()){ return false; }
		$result = execute_sqlUpdate('tblRooms', array('roomActive'=>'N') , array( 'roomId'=>$roomId ) ) ;
		return true;
	}
	
	public function Restore_Room($roomId){
		$MU = new ManageUsers();
		$MU->userId = $_SESSION['empl_id'];
		if(!$MU->isAdminUser()){ return false; }
		$result = execute_sqlUpdate('tblRooms', array('roomActive'=>'Y') , array( 'roomId'=>$roomId ) ) ;
		return true;
	}

	public function get_NewMessages_fromRoom( $fetchBy , $roomId , $UserId ){
		$this_query = '';
		$NEW_MESSAGES = array();
		$MU = new ManageUsers();
		$MU->userId = $UserId;
		$TMP_MF = new ManageFiles();
		
		if( $fetchBy['condition'] == 'lastXMessages' ){
			$LASTFETCHEDMSGID = 0;
			$this_query = get_ChatroomQuery_forUser_RoomId( $UserId , $roomId , $fetchBy['value'] , 0 );
		}
		if( $fetchBy['condition'] == 'NewerThan' ){
			$LASTFETCHEDMSGID = $fetchBy['value'];
			$this_query = get_ChatroomQuery_forUser_RoomId( $UserId , $roomId , 0 , $fetchBy['value'] );
		}
		
		$result = mysql_query($this_query) ;
		while ($row = mysql_fetch_array($result)) {
			$LASTFETCHEDMSGID = $row['msgid'];
			$converted_date = $MU->convert_to_UsersTimeZone($row['msgtime']);
			
			if($row['msgType'] == 'F'){
				$fileInfo = $TMP_MF->get_file_Info( $row['fileId'], $UserId );
				
				$tmp_preview_str = "{$row['saidBy_username']} has uploaded a file <a href='chatfiledownload.php?fc={$fileInfo['fileId']}'>{$fileInfo['fileName']}</a>  <span style='color: #A9A9A9;'>".formatBytesToHumanReadable($fileInfo['fileSize'])."</span>&nbsp;&nbsp;&nbsp;<a rel='prettyPhoto[iframes]' href='filemail.php?fid={$fileInfo['fileId']}&iframe=true&width=800&height=400'>Email</a>" ;
				
				if( in_array($fileInfo['fileExt'] , array('jpg', 'jpeg', 'gif', 'png', 'bmp')) ){
					$tmp_preview_str .= "<br/><img src=files/chat_files/thumbs/{$fileInfo['fileRandomName']}>";
				}
				$row['message_base64'] = base64_encode($tmp_preview_str);
			}
			
			if( $row['msgType'] == 'L' ){
				$row['msgid'] = 0;
			}
			
			if($row['msgType'] == 'E'){
				$row['msgid'] = 0;
				$tmp_preview_str = $row['message_plain_mysqlescaped'];
				$row['message_base64'] = base64_encode($tmp_preview_str);
			}

			$NEW_MESSAGES[] = array(
									'msgid'=>$row['msgid'] ,
									'msgBy'=>$row['saidBy_username'] ,
									'msgTime'=>$converted_date ,
									'msg_base64'=>$row['message_base64'] ,
									'bookmark'=>$row['bkm_id'],
									'msgType'=>$row['msgType'] 
								); 
		}
		return array( 'NEW_MESSAGES' =>$NEW_MESSAGES , 'LASTFETCHEDMSGID'=>$LASTFETCHEDMSGID );
	}
	
	
	
	
	
	public function get_Archives_fromRoom( $date , $roomId , $UserId ){
		$this_query = '';
		$NEW_MESSAGES = array();
		$MU = new ManageUsers();
		$MU->userId = $UserId;
		$TMP_MF = new ManageFiles();
		
		if(!$MU->has_AccessToRoom($roomId))
		{
			return ;
		}
		
		
		
		list($stDay, $stMonth, $stYear) = explode( "-", $date );
		$start_time = $stYear.'-'.$stMonth.'-'.$stDay.' 00:00:00';
		$end_time = $stYear.'-'.$stMonth.'-'.$stDay.' 23:59:59';
		
		$GMT_start_time = $MU->convert_from_UsersTimeZone($start_time);
		$GMT_end_time = $MU->convert_from_UsersTimeZone($end_time);
		
		$this_query = "SELECT * FROM tbl_ChatRooms WHERE chatRoom='{$roomId}' AND msgtime between '{$GMT_start_time}' and '{$GMT_end_time}'";
		
		
		$result = mysql_query($this_query) ;
		while ($row = mysql_fetch_array($result)) {
			$LASTFETCHEDMSGID = $row['msgid'];
			$converted_date = $MU->convert_to_UsersTimeZone($row['msgtime']);
			
			if($row['msgType'] == 'F'){
				$fileInfo = $TMP_MF->get_file_Info( $row['fileId'], $UserId );
				
				$tmp_preview_str = "{$row['saidBy_username']} has uploaded a file <a href='chatfiledownload.php?fc={$fileInfo['fileId']}'>{$fileInfo['fileName']}</a>  <span style='color: #A9A9A9;'>".formatBytesToHumanReadable($fileInfo['fileSize'])."</span>&nbsp;&nbsp;&nbsp;<a rel='prettyPhoto[iframes]' href='filemail.php?fid={$fileInfo['fileId']}&iframe=true&width=800&height=400'>Email</a>" ;
				
				if( in_array($fileInfo['fileExt'] , array('jpg', 'jpeg', 'gif', 'png', 'bmp')) ){
					$tmp_preview_str .= "<br/><img src=files/chat_files/thumbs/{$fileInfo['fileRandomName']}>";
				}
				$row['message_base64'] = base64_encode($tmp_preview_str);
			}
			
			if( $row['msgType'] == 'L' ){
				$row['msgid'] = 0;
			}
			
			if($row['msgType'] == 'E'){
				$row['msgid'] = 0;
				$tmp_preview_str = $row['message_plain_mysqlescaped'];
				$row['message_base64'] = base64_encode($tmp_preview_str);
			}

			$NEW_MESSAGES[] = array(
									'msgid'=>$row['msgid'] ,
									'msgBy'=>$row['saidBy_username'] ,
									'msgTime'=>$converted_date ,
									'msg_base64'=>$row['message_base64'] ,
									'bookmark'=>$row['bkm_id'],
									'msgType'=>$row['msgType'] 
								); 
		}
		return array( 'NEW_MESSAGES' =>$NEW_MESSAGES , 'LASTFETCHEDMSGID'=>$LASTFETCHEDMSGID );
	}
	
	
}


class ManageBookMarks{
	
	public function ToggleBookMark_ChatRoomMessage( $msgid , $userId , $roomId ){
		// check if this user has access to this roomId
		$MU = new ManageUsers();
		$MU->userId = $userId ;
		if( !$MU->has_AccessToRoom($roomId) ){
			return "user does not have access to room";
		}
		
		$alreadyBookMarked = executesql_returnArray("select bkm_id from tbl_BookMarks where bkm_empl_id='{$userId}' and bkm_msgId='{$msgid}' and bkm_roomId='{$roomId}' ");
		if($alreadyBookMarked){
			$result = mysql_query("delete from tbl_BookMarks where bkm_id='{$alreadyBookMarked}' ");
			return "deleted bookmark";
		}else{
			$success = execute_sqlInsert( 'tbl_BookMarks', 
				array( 
					'bkm_empl_id' => $userId,
					'bkm_msgId' => $msgid,
					'bkm_dmsgid' => 0,
					'bkm_roomId' => $roomId
				)
			);
		}
		return "added bookmark";
	}
	
	public function ToggleBookMark_DirectMessage( $dmsgid , $userId ){
		$alreadyBookMarked = executesql_returnArray("select bkm_id from tbl_BookMarks where bkm_empl_id='{$userId}' and bkm_dmsgid='{$msgid}' ");
		if($alreadyBookMarked){
			$result = mysql_query("delete from tbl_BookMarks where bkm_id='{$alreadyBookMarked}' ");
		}else{
			$success = execute_sqlInsert( 'tbl_BookMarks', 
				array( 
					'bkm_empl_id' => $userId,
					'bkm_msgId' => 0,
					'bkm_dmsgid' => $dmsgid,
					'bkm_roomId' => 0
				)
			);
		}
	}
	
	
	public function get_LastX_BookMarks_query( $userid , $lastX=200 ){
		return "( select bkms.bkm_id, bkms.bkm_empl_id, bkms.bkm_msgId, bkms.bkm_dmsgid, bkms.bkm_roomId, cRoom.message_base64, cRoom.msgType, cRoom.fileId, cRoom.saidBy_empl_id from tbl_BookMarks bkms, tbl_ChatRooms cRoom where bkms.bkm_empl_id='{$userid}' and bkms.bkm_msgId=cRoom.msgid Limit $lastX ) 
			 UNION 
			( select bkms.bkm_id, bkms.bkm_empl_id, bkms.bkm_msgId, bkms.bkm_dmsgid, bkms.bkm_roomId, dmsgs.msg_base64 as message_base64, dmsgs.msgType as msgType, dmsgs.fileId as fileId, dmsgs.from_uid as saidBy_empl_id from tbl_BookMarks bkms, tbl_DirectMessages dmsgs where bkms.bkm_empl_id='{$userid}' and bkms.bkm_dmsgid=dmsgs.dmsgid Limit $lastX ) ";
	}
	
	
	public function get_BookMarks_Search ( $userid , $OnDay, $roomId ){
		// get bookmarks from a chatroom (optional) on a particular day (msgtime .. not book marked time)
		if($roomId){ $room_Conditon = " and bkms.bkm_roomId='$roomId' and " ; }else{ $room_Conditon = ""; }
		$MU = new ManageUsers();
		$MU->userId = $userId;
		$start_time_fixed = $OnDay . ' 00:00:00';
		$end_time_fixed = $OnDay . ' 23:59:59';
		$start_time_GMT = $MU->convert_from_UsersTimeZone( $start_time_fixed );
		$end_time_GMT = $MU->convert_from_UsersTimeZone( $end_time_fixed );
		
		return executesql_returnMultiArray( 
			" select bkms.bkm_id, bkms.bkm_empl_id, bkms.bkm_msgId, bkms.bkm_dmsgid, bkms.bkm_roomId, cRoom.message_base64 from tbl_BookMarks bkms, tbl_ChatRooms cRoom where {$room_Conditon} bkms.bkm_empl_id='{$userid}' and bkms.bkm_msgId=cRoom.msgid and cRoom.msgtime between '{$start_time_GMT}' and '{$end_time_GMT}' "
		);
	}

}


class ManageFiles{
	
	public function createUnique_FID($no_of_digits){
		$pass = getaRandomString($no_of_digits);
		$check_fid = mysql_num_rows(mysql_query("SELECT fileCode FROM tbl_chatFiles WHERE fileCode='$pass'"));
		return ($check_fid > 0) ? $this->createUnique_FID($no_of_digits) : $pass ;
	}
	
	public function get_Last_XFiles_RelatedToUser ( $userId , $lastX=200 ){
		return executesql_returnStrictArray("select tbl_chatFiles.fileId from tbl_chatFiles, tbl_ChatRooms where tbl_ChatRooms.fileId = tbl_chatFiles.fileId and tbl_ChatRooms.chatRoom IN (select rid from tbl_RoomPrivilages where uid='$userId') ORDER BY fileId DESC LIMIT $lastX ");
		// todo : this should include files from direct messages
	}
	
	public function get_Last_XFiles_RelatedToUser_fullDetails_sql ( $userId , $lastX=200 ){
		return "select tbl_chatFiles.fileId, tbl_chatFiles.fileName , tbl_chatFiles.fileRandomName , tbl_chatFiles.fileExt , tbl_chatFiles.fileSize , tbl_chatFiles.fileCode , tbl_chatFiles.fileType from tbl_chatFiles, tbl_ChatRooms where tbl_ChatRooms.fileId = tbl_chatFiles.fileId and tbl_ChatRooms.chatRoom IN (select rid from tbl_RoomPrivilages where uid='$userId') ORDER BY fileId DESC LIMIT $lastX ";
		// todo : this should include files from direct messages
	}
	
	public function isFileRelatedToUser( $fileId , $userId ){
		// get room id for this file
		$roomId = executesql_returnArray("select tbl_ChatRooms.chatRoom from tbl_chatFiles, tbl_ChatRooms where tbl_ChatRooms.fileId = tbl_chatFiles.fileId and tbl_chatFiles.fileId='$fileId'");
		if($roomId){
			$MU = new ManageUsers();
			$MU->userId = $userId ;
			return $MU->has_AccessToRoom($roomId);
		}else{
			$directMessage_UserRelated = executesql_returnArray("select tbl_DirectMessages.dmsgid from tbl_DirectMessages, tbl_chatFiles where tbl_chatFiles.fileId = tbl_DirectMessages.fileId and tbl_chatFiles.fileId='$fileId' and (tbl_DirectMessages.from_uid='{$userId}' OR tbl_DirectMessages.to_uid='{$userId}') ");
			return ($directMessage_UserRelated) ? true : false;
		}
	}
	
	public function get_file_Info ( $fileId , $userId ){
		if( $this->isFileRelatedToUser($fileId , $userId ) ){
			return executesql_returnAssocArray( " SELECT * FROM tbl_chatFiles where tbl_chatFiles.fileId='$fileId' " );
		}else{
			return array();
		}
	}
	

	public function get_file_content( $fileId , $userId ){
		$FILEINFO = $this->get_file_Info($fileId , $userId);
		if(count($FILEINFO)){
			$file = UPLOAD_PATH.$FILEINFO['fileRandomName'];
			readfile($file);
		}
	}
	
	// public function email_file_to( fromuserId , toAddresses , bodyMessage ){
	// 	
	// }
	
	public function get_ListOfUsers_WhoCanSee( $file_id ){
		$roomId = executesql_returnArray("select tbl_ChatRooms.chatRoom from tbl_chatFiles, tbl_ChatRooms where tbl_ChatRooms.fileId = tbl_chatFiles.fileId and tbl_chatFiles.fileId='$fileId'");
		if($roomId){
			$MCR = new ManageChatRooms();
			return $MCR->get_ListOfAllowedUsers($roomId);
		}else{
			$uids = executesql_returnAssocArray("select to_uid, from_uid  from tbl_DirectMessages where fileId='{$file_id}' ");
			if(count($uids)){
				return array( $uids['to_uid'] , $uids['from_uid'] );
			}else{
				return array();
			}
		}
	}
	
	
	
	public function get_ListOfFiles_Search( $userId=0 , $OnDay , $room_id=0 , $uploadBy='' ){
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }

		$MU = new ManageUsers();
		$MU->userId = $userId;
		$start_time_fixed = $OnDay . ' 00:00:00';
		$end_time_fixed = $OnDay . ' 23:59:59';
		$start_time_GMT = $MU->convert_from_UsersTimeZone( $start_time_fixed );
		$end_time_GMT = $MU->convert_from_UsersTimeZone( $end_time_fixed );
		
		$chatRoom_Condition = ($room_id) ? " and tbl_ChatRooms.chatRoom='{$room_id}' " : " and tbl_ChatRooms.chatRoom IN (select rid from tbl_RoomPrivilages where uid='$userId') " ;
		
		return executesql_returnStrictArray("select tbl_chatFiles.fileId from tbl_chatFiles, tbl_ChatRooms where tbl_ChatRooms.fileId = tbl_chatFiles.fileId and (tbl_ChatRooms.msgtime between '{$start_time_GMT}' and '{$end_time_GMT}') $chatRoom_Condition ");

		
	}

	public function get_ListOfFiles_Search_with_details( $userId=0 , $OnDay , $room_id=0 , $uploadBy='' ){
		if(!$userId){ $userId = $_SESSION['empl_id'] ; }

		$MU = new ManageUsers();
		$MU->userId = $userId;
		$start_time_fixed = $OnDay . ' 00:00:00';
		$end_time_fixed = $OnDay . ' 23:59:59';
		$start_time_GMT = $MU->convert_from_UsersTimeZone( $start_time_fixed );
		$end_time_GMT = $MU->convert_from_UsersTimeZone( $end_time_fixed );
		
		$chatRoom_Condition = ($room_id) ? " and tbl_ChatRooms.chatRoom='{$room_id}' " : " and tbl_ChatRooms.chatRoom IN (select rid from tbl_RoomPrivilages where uid='$userId') " ;
		
		//return executesql_returnStrictArray("select tbl_chatFiles.*, tbl_ChatRooms.* from tbl_chatFiles, tbl_ChatRooms where tbl_ChatRooms.fileId = tbl_chatFiles.fileId and (tbl_ChatRooms.msgtime between '{$start_time_GMT}' and '{$end_time_GMT}') $chatRoom_Condition ");

		$this_query = "select * from tbl_chatFiles, tbl_ChatRooms where tbl_ChatRooms.fileId = tbl_chatFiles.fileId and (tbl_ChatRooms.msgtime between '{$start_time_GMT}' and '{$end_time_GMT}') $chatRoom_Condition ";
		
		

		$result = mysql_query($this_query) ;
		while ($row = mysql_fetch_array($result)) {
			$converted_date = $MU->convert_to_UsersTimeZone($row['msgtime']);
			$NEW_FILES[] = array(
				  'fileid'=>$row['fileId'] ,
				  'msgBy'=>$row['saidBy_username'] ,
				  'msgTime'=>$converted_date ,
				  'filename'=>$row['fileName'],
				  'fileRandomName'=>$row['fileRandomName'], 
				  'fileExt'=>$row['fileExt'],
				  'filesize'=>$row['fileSize']
				  ); 
		}
		return array( 'NEW_FILES' =>$NEW_FILES );

	}
	
	public function getChatFileUploadInfo($fileId)
	{
		
		//return executesql_returnAssocArray( " SELECT u.emplUsername as uploadedBy, cr.msgtime as uploadedDate FROM tbl_chatFiles cf, tbl_ChatRooms cr, tblAppUsers u  where cf.fileId = cr.fileId AND cf.fileId='$fileId' AND cr.saidBy_empl_id = u.empl_id " );
		
		return executesql_returnAssocArray( " SELECT u.emplUsername as uploadedBy, cr.msgtime as uploadedDate, r.roomId as roomId  FROM tbl_chatFiles cf, tbl_ChatRooms cr, tblAppUsers u, tblRooms r  where cf.fileId = cr.fileId AND cf.fileId='$fileId' AND cr.saidBy_empl_id = u.empl_id AND r.roomId=cr.chatRoom " );
	}
	
	public function getDiskSpace()
	{
		return getAppVariable('disk_Space');
	}
	
	public function updateDiskSpace($size)
	{
		$existing = getAppVariable('disk_Space');
		$new_size = (int)$existing + $size;
		return setAppVariable('disk_Space',$new_size);
	}

}




class DirectMessages{
	
	function get_AllUnread_Plus_Xread_DirectMessages($userId , $read_CountX=20 ){
		$count_new = executesql_returnArray("select count(dmsgid) from tbl_DirectMessages where msgStatus='N' and to_uid='$userId'");
		if($count_new == 0){
			// if no new messages .. get the last X Desc 
			$LIMIT = $read_CountX ;
		}else{
			// if user has any UnRead Messages (unRead message is not necessarily be the latest message of the user)
			// so we will make sure that we pull atleast all recent messages till the oldest UnRead
			$oldestUnRead_dmsgId = executesql_returnArray("select min(dmsgid) from tbl_DirectMessages where msgStatus='N' and to_uid='$userId' ");
			$messageCountAfter_oldestUnRead = executesql_returnArray("select count(dmsgid) from tbl_DirectMessages where (to_uid='$userId' or from_uid='$userId') and dmsgid > '{$oldestUnRead_dmsgId}' ");
			$LIMIT = ($messageCountAfter_oldestUnRead > $read_CountX) ? $messageCountAfter_oldestUnRead + 3 : $read_CountX ;
		}
		
		return "select dmsgid, from_uid, to_uid, msg_base64, msgtime, msgType, fileId, msgStatus from tbl_DirectMessages where (to_uid='$userId' OR  from_uid='$userId') ORDER BY dmsgid DESC LIMIT $LIMIT ";
		
	}
	
	
	
	public function Mark_As_Read($msgIds, $uId){
		$umsgids = implode(',',$msgIds);
		$sql_up = "UPDATE tbl_DirectMessages SET msgStatus='Y' WHERE to_uid='$uId' AND dmsgid IN ($umsgids)";
		echo "<script> console.log('" . addslashes($sql_up) . "'); </script>" ;
		if(execute_sqlQuery($sql_up))
		return true;
	}
	
	
	public function newMessage( $fromUid, $toUid, $messageInBase64 ){
		$success = execute_sqlInsert( 'tbl_DirectMessages', 
			array( 
				'to_uid' => $toUid ,
				'from_uid' => $fromUid ,
				'msg_base64' => base64_encode( htmlentities( base64_decode($messageInBase64), ENT_QUOTES ) ),
				'msg_plain' => base64_decode( $messageInBase64 ),
				'msgtime' => get_currentPHPTimestamp()
			)
		);
	}
}



?>
