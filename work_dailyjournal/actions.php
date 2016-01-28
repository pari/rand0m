<?php

session_start();
include_once "include_db.php" ;
include_once "include_custom.php" ;

function send_Action_Response($RESP , $MSG){
	echo "Response: ".$RESP."\n" ;
	echo "ResponseMessage: ".$MSG ;
	exit();
}


$ACTION = @$_POST["action"] ;
$USERNAME = $_SESSION['loggedinUser'] ;
// $_SESSION['loggedinUser'] = $_SERVER['PHP_AUTH_USER'] ;

switch( $ACTION ) {

	case 'updatePwd':
		$currentPwd = @$_POST["currentPwd"] ;
		$newPwd = @$_POST["newPwd"] ;
		execute_sqlUpdate("users", array(user_pwd=>$newPwd) , array(username=>$USERNAME, user_pwd=>$currentPwd) );
		send_Action_Response('Success' , 'password updated !');
		exit();
	break;


	case 'deleteTask':
		$taskid = @$_POST["taskid"] ;
		$query = mysql_query("delete from journalentries where jid='$taskid' and task_user='$USERNAME'");
		send_Action_Response('Success' , 'Entry deleted !');
		exit();
	break;

	case 'addnewtask':
		$nutask_date = @$_POST["nutask_date"] ;
		$nutask_duration = @$_POST["nutask_duration"] ;
		$nutask_desc = @$_POST["nutask_desc"] ;
		$nutask_project = @$_POST["nutask_project"] ;
		
		// if(  $nutask_date == getTomorrowCaldate(-1) || $nutask_date == getTomorrowCaldate(0) || $nutask_date == getTomorrowCaldate(1) ){
		// 	
		// }else{
		// 	send_Action_Response('Fail' , 'You can enter journal for only today & yesterday !');
		// }

		$success = execute_sqlInsert("journalentries", array('task_day'=>$nutask_date, 'task_mins'=>$nutask_duration, 'task_desc'=>$nutask_desc, 'task_user'=>$USERNAME , 'task_project'=>$nutask_project ) );

		send_Action_Response('Success' , 'Added !');
		exit();
	break;
}

?>