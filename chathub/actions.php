<?php

ignore_user_abort(true);
set_time_limit(30);
ob_start();
session_start();

include_once "include_db.php";
include_once "include_functions.php";


if( !isMobileBrowser ){
require_once "phpmailer/class.phpmailer.php";
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
}


function checkLogin_B4_Action(){
	if( !$_SESSION["uname"] ){
		session_unset();
		session_destroy();
		send_Action_Response('Fail' , 'Invalid Session!');
		exit();
	}
}

$ACTION = @$_POST["action"] ;
$USERNAME = @$_SESSION["uname"];
$CURRENT_USERID = $_SESSION["empl_id"] ;

if( $ACTION == 'doLogin' || $ACTION == 'checkInvitation' ){
	// Do not check "if logged in" for these actions
}else{
	checkLogin_B4_Action();
}


switch( $ACTION ) {
	
	
	case 'Logout':
		$MU = new ManageUsers();
		$MU->userId = $CURRENT_USERID ;
		$MU->Logout_fromAllRooms();
		session_unset();
		session_destroy();
		send_Action_Response('Success' , 'Logged Out!');
		exit();
	break;
	
	
	
	case 'doLogin':
		$uname = get_POST_var("uname");
		$uepwd = get_POST_var("uepwd");
		$emp_details = executesql_returnAssocArray("select * from tblAppUsers where BINARY emplUsername='{$uname}' and BINARY emplPassword='".md5($uepwd)."' ");
		
		if( is_null($emp_details) ){
			session_unset();
			session_destroy();
			send_Action_Response('Fail' , 'Invalid username or password !');
		}
		
		$_SESSION["uname"] = $uname ;
		$_SESSION["empl_id"] = $emp_details['empl_id'];
		$_SESSION["emplEmail_id"] = $emp_details['emplEmail_id'];
		$_SESSION["emplFullName"] = $emp_details['emplFullName'];
		$_SESSION["emplLastLoginAt"] = $emp_details['emplLastLoginAt'];
		register_LastPingAt();
		send_Action_Response('Success' , 'Logged In !');
		exit();
	break;


	
	case 'postmessage':
		// This action will be called every X seconds from the chat page
		// to post a new message  (if there is a new message)
		// and then fetch the list of un-seen/new messages since the last fetch
		$newmsg = get_POST_var('newmsg');
		$lmsgid = get_POST_var('LMSGID');
		$roomid = get_POST_var('ROOMID');
		if($newmsg){
			$success = execute_sqlInsert( 'tbl_ChatRooms', 
				array( 
					'saidBy_username'=>$USERNAME ,
					'saidBy_empl_id'=>$CURRENT_USERID ,
					'message_base64' => base64_encode( htmlentities( base64_decode($newmsg), ENT_QUOTES ) ),
					'message_plain_mysqlescaped' => base64_decode($newmsg),
					'chatRoom' => $roomid
				)
			);
		}
		
		register_LastPingAt();
		$newMsgCount = executesql_returnArray("select count(msgid) as newmsgcount from tbl_ChatRooms where chatRoom='$roomid' AND msgid > {$lmsgid}") ;
		if( !$newMsgCount ){
			send_Action_Response('Success' , " NEW_MESSAGES = [] ; LASTFETCHEDMSGID = {$lmsgid} ;" );
		}
		
		$TMC = new ManageChatRooms();
		$NMFR = $TMC->get_NewMessages_fromRoom( array('condition'=> 'NewerThan', 'value'=> $lmsgid ) , $roomid , $CURRENT_USERID );
		send_Action_Response('Success' , 
			" NEW_MESSAGES = ".json_encode($NMFR['NEW_MESSAGES']). "; LASTFETCHEDMSGID = {$NMFR['LASTFETCHEDMSGID']} ;" 
		);
		exit();
	break;






	case 'post_direct_message':
		// Post Direct Messages between users
		$newmsg = get_POST_var('newmsg');
		$to_user = get_POST_var('to_user');
		if($newmsg){
			$MU = new ManageUsers();
			$MU->userId = $_SESSION['empl_id'];
			$success = $MU->send_DirectMessageTo($to_user,$newmsg);
		}
		register_LastPingAt();
		send_Action_Response('Success' , '1' );
		exit();
	break;

	/*case 'fetch_activeUsers':
		$roomid = get_POST_var('ROOMID');
		$MCR = new ManageChatRooms();
		$ACTIVE_USERS = $MCR->get_ListOfActiveUserNamesIn_ChatRoom($roomid);
		send_Action_Response('Success' , " ACTIVE_USERS = ".json_encode($ACTIVE_USERS). " ; " );
		exit();
	break;*/

	case 'fetch_Users_InRoom':
		$roomid = get_POST_var('ROOMID');
		$MCR = new ManageChatRooms();
		$ALL_USERS = $MCR->get_ListOfAllUsersInRoom_andStatus($roomid);
		send_Action_Response('Success' , " ALL_USERS = ".json_encode($ALL_USERS). " ; " );
		exit();
	break;



	case 'updateUserPassword':
		$oldPassword = get_POST_var("oldPassword");
		$newPassword = get_POST_var("newPassword");
		$existingPassword = executesql_returnArray("select emplPassword from tblAppUsers where emplUsername='{$USERNAME}'");;
		
		if( $existingPassword <> md5($oldPassword) ){
			send_Action_Response('Fail' , 'Current Password does not match !');
		}else{
			$result = execute_sqlUpdate('tblAppUsers', array('emplPassword'=>md5($newPassword) ), array('emplUsername'=>$USERNAME) );
			send_Action_Response( 'Success' , 'Updated!' );
		}
		
		exit();
	break;
	
	
	
	
	case 'updateUserInfo':
		$name = get_POST_var("name");
		$email = get_POST_var("email");
		$mobile = get_POST_var("mobile");
		$designation = get_POST_var("designation");
		$Timezone = get_POST_var("Timezone");
		$existingEmail = executesql_returnArray("select emplEmail_id from tblAppUsers where empl_id!='$CURRENT_USERID' AND emplEmail_id='{$email}'");;
		
		if( $existingEmail ){
			send_Action_Response('Fail' , 'Email Already Exists !');
		}else{
			$result = execute_sqlUpdate('tblAppUsers', array('emplEmail_id'=>$email, 'emplFullName'=>$name, 'emplMobileNo'=>$mobile, 'emplDesignation'=>$designation, 'TimeZone'=>$Timezone), array('empl_id'=>$CURRENT_USERID) );
			send_Action_Response( 'Success' , 'Updated!' );
		}
		
		exit();
	break;
	
	
	
	
	
	
	case 'sendUserInvitation':
		$fName = get_POST_var("fname");
		$lName = get_POST_var("lname");
		$eMail = get_POST_var("email");
		$msg = get_POST_var("msg");
		$roomid = '';
		$MU = new ManageUsers();
		$MU->userId = $CURRENT_USERID;
		$existingEmail = $MU->checkFor_User_Email_Existence($eMail);
		if( $existingEmail ){
			send_Action_Response('Fail' , 'Email already exists !');
			exit();
		}
		
		$invi_key = $MU->createUnique_InviteID(32); 
		$invi_id = $MU->insert_Invitaion($fName, $lName, $msg, $eMail, $USERNAME, $roomid, $invi_key);
		
		$invi_url = APPURL."/invitations.php?id=".$invi_id."&key=".$invi_key;
		$subject = "You're invited to join ".APPNAME;
		
		$message = '<h2>You\'re invited to join '.APPNAME.', our group chat system</h2><br/>';
		$message .= 'Hi '.$fName.' '.$lName.', <br/>';
		$message .= $_SESSION["emplFullName"].' just set up an account for you.<br/>';
		$message .= 'All you need to do is choose a username and password.<br/>It only takes a few seconds.';
		$message .= 'Click this link to get started:<br/>';
		$message .= '<a href="'.$invi_url.'">'.$invi_url.'</a><br/>';
		$message .= $_SESSION["emplFullName"].' says:<br/>';
		$message .= nl2br($msg);
		$msg_ack = sendMailUsingMailer($eMail, $subject, $message);
		
		if($msg_ack == 'Letter is sent'){
			send_Action_Response( 'Success' , $mailack );
		}
	break;




	case 'checkInvitation':
		$uName = get_POST_var("uname");
		$pwd = get_POST_var("pwd");
		$mobile = get_POST_var("mobile");
		$des = get_POST_var("des");
		$invi_id = get_POST_var("invi_id");
		$invi_fName = get_POST_var("invi_fName");
		$invi_email = get_POST_var("invi_email");
		$timezone = get_POST_var("Timezone");
		
		$MU = new ManageUsers();
		$MU->userId = $CURRENT_USERID;
		$existingUserName = $MU->checkFor_UserName_Existence($uName);
		//$existingUserName = executesql_returnArray("select emplUsername from tblAppUsers where emplUsername='{$uName}'");;
		if( $existingUserName ){
			send_Action_Response('Fail' , 'Username already exists !');
			exit();
		}
		
		
		$user_id = $MU->insert_newUser($uName, $pwd, $invi_email, $invi_fName, $mobile, $des, $timezone);
		$result = execute_sqlQuery("UPDATE tbl_Invitations SET invi_status='1' WHERE invi_Id='$invi_id'");
		$subject = "Your account has been created";
		$message = '<h2>Thanks for creating an account.</h2><br/>';
		$message .= 'You\'re all set. You\'ll find your username and a link to sign in below.<br/>';
		$message .= '<B>Your new username is:</B><br/>';
		$message .= $uName.'<br/><br/>';
		$message .= 'Access your '.APPNAME.' account now:<br/>';
		$message .= '<a href="'.APPURL.'">'.APPURL.'</a><br/>';
		$msg_ack = sendMailUsingMailer($invi_email, $subject, $message);
		
		if($msg_ack == 'Letter is sent'){
			send_Action_Response( 'Success' , $msg_ack );
		}
	break;


	case 'createUser':
		$uName = get_POST_var("uname");
		$pwd = get_POST_var("pwd");
		$mobile = get_POST_var("mobile");
		$des = get_POST_var("des");
		$timezone = get_POST_var("Timezone");
		$fName = get_POST_var("fname");
		$lName = get_POST_var("lname");
		$eMail = get_POST_var("email");
		$msg = get_POST_var("msg");
		
		$invi_fName = $fName.' '.$lName;
		
		//$existingUserName = executesql_returnArray("select emplUsername from tblAppUsers where emplUsername='{$uName}'");;
		$MU = new ManageUsers();
		$MU->userId = $CURRENT_USERID;
		$existingUserName = $MU->checkFor_UserName_Existence($uName);
		if( $existingUserName ){
			send_Action_Response('Fail' , 'Username already exists !');
			exit();
		}
		
		$existingEmail = $MU->checkFor_User_Email_Existence($eMail);
		if( $existingEmail ){
			send_Action_Response('Fail' , 'Email already exists !');
			exit();
		}
		
		$user_id = $MU->insert_newUser($uName, $pwd, $eMail, $invi_fName, $mobile, $des, $timezone);
		$subject = "Your account has been created";
		$message = '<h2>Your account has been created.</h2><br/>';
		$message .= 'You\'re all set. You\'ll find your login details and a link to sign in below.<br/>';
		$message .= '<B>Your Username is:</B><br/>';
		$message .= $uName.'<br/><br/>';
		$message .= '<B>Your Password is:</B><br/>';
		$message .= $pwd.'<br/><br/>';
		$message .= 'Access your '.APPNAME.' account now:<br/>';
		$message .= '<a href="'.APPURL.'">'.APPURL.'</a><br/><br/>';
		$message .= '<b>'.$_SESSION["emplFullName"].'</b> says:<br/>';
		$message .= nl2br($msg);
		$msg_ack = sendMailUsingMailer($eMail, $subject, $message);
		
		if($msg_ack == 'Letter is sent'){
			send_Action_Response( 'Success' , $msg_ack );
		}else{
			
		}
	break;
	
	

	
	
	
	case 'createRoom':
		$room_name = get_POST_var("room_name");
		$room_desc = get_POST_var("room_desc");
		$room_users = explode( '||', get_POST_var("room_users") );
		$MCR = new ManageChatRooms();
		if($MCR->create_Room( $room_name, $room_desc, $room_users )){
			send_Action_Response('Success' , 'Room Created Successfully !');
		}else{
			send_Action_Response('Fail' , 'There was an error creating the Room !');
		}
	break;
	
	
	
	
	case 'updateUserPriv':
		$priv = get_POST_var("prv");
		$user = get_POST_var("user");
		$checked = get_POST_var("chk");
		$MU = new ManageUsers();
		$MU->userId = $user;
		$MU->update_Privilege( $priv , $checked );
		send_Action_Response('Success' , '1' );
	break;
	
	
	case 'updateRoomPriv':
		$roomid = get_POST_var("roomid");
		$user = get_POST_var("user");
		$checked = get_POST_var("chk");
		$MU = new ManageUsers();
		$MU->userId = $user;
		if($checked == '0'){
			$MU->Remove_AccessToRoom( $roomid );
		}else{
			$MU->Allow_AccessToRoom( $roomid );
		}
		send_Action_Response('Success' , '1' );
	break;
	
	
	
	
	
	case 'LeaveRoom':
		$uid = get_POST_var("uid");
		$rid = get_POST_var("rid");
		$MU = new ManageUsers();
		$MU->userId = $CURRENT_USERID;
		$res = $MU->Logout_fromRoom($rid);
		send_Action_Response('Success' , '1' );
	break;
	
	
	
	
	
	case 'updateChatMessageBookMark':
		$msgid = get_POST_var("msgid");
		$roomId = get_POST_var("roomId");
		$T_MB = new ManageBookMarks();
		$result = $T_MB->ToggleBookMark_ChatRoomMessage( $msgid , $CURRENT_USERID , $roomId ) ;
		send_Action_Response('Success', $result);
	break;
	
	
	
	
	case 'updateStatusMsg':
		$msg = get_POST_var("msg");
		$nmsg = base64_encode( htmlentities($msg, ENT_QUOTES ) );
		$uid = $_SESSION['empl_id'];
		$MU = new ManageUsers();
		$MU->userId = $CURRENT_USERID;
		$MU->set_status($nmsg);
		send_Action_Response('Success',$nmsg);
		exit();
	break;
	
	
	
	
	case 'Register_Last_Ping':
		register_LastPingAt();
		send_Action_Response('Success' , 1 );
		exit();
	break;
	
	
	
	
	case 'Message_Mark_As_Read':
		$TMP_UMDMSGIDS = get_POST_var("UMDMSGIDS");
		$UMDMSGIDS = explode('||',$TMP_UMDMSGIDS);
		$uid = $_SESSION['empl_id'];
		$DM = new DirectMessages();
		$res = $DM->Mark_As_Read( $UMDMSGIDS , $_SESSION['empl_id'] );
		send_Action_Response('Success' , $res );
		exit();
	break;
	
	
	
	
	
	case 'fetchArchives':
		$date = get_POST_var('DATE');
		$roomId = get_POST_var('ROOMID');
		$MCR = new ManageChatRooms();
		$ARMSGS = $MCR->get_Archives_fromRoom($date, $roomId, $_SESSION['empl_id']);
		send_Action_Response('Success' , 
			" NEW_MESSAGES = ".json_encode($ARMSGS['NEW_MESSAGES']). "; LASTFETCHEDMSGID = {$ARMSGS['LASTFETCHEDMSGID']} ;" 
		);
		exit();
	break;
	
	
	case 'getRoomIdsForUser':
		$MU = new ManageUsers();
		$MU->userId = get_POST_var('uid');
		$user_rooms = $MU->getUser_AllowedChatRooms_IncludeDepricatedOnes();
		send_Action_Response('Success' , " USER_ROOMS = ".json_encode($user_rooms). "; " );
		exit();
	break;

      
	case 'searchFiles':
		$date = get_POST_var('DATE');

		list($stDay, $stMonth, $stYear) = explode( "-", $date );
		$newdate = $stYear.'-'.$stMonth.'-'.$stDay;
		

		$MF = new ManageFiles();
		$FILES = $MF->get_ListOfFiles_Search_with_details($_SESSION['empl_id'],$newdate);
		send_Action_Response('Success' , " FILES = ".json_encode($FILES['NEW_FILES']). "; " );
		exit();
	break;
	
	
	
	default:
		send_Action_Response('Fail' , "SC Error #500: No case defined for Action : {$ACTION} " );
	break;
	
	
}

	




?>
