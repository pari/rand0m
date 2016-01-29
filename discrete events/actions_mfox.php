<?php
include_once "include_db.php";
include_once "include_functions.php";

$USERNAME = $_POST['username']; 
$USERPASS = $_POST['password']; 

if ( !authenticateUser( $USERNAME, $USERPASS ) ){
	echo "Invalid Username or Password!";
	exit;
}else{
	$_SESSION["uname"] = $USERNAME ;
}

$projectName = DEFAULTPERSONALPROJECT;
$briefDesc = urldecode($_POST['newtask']);
$deadline = getTomorrowCaldate(2);

$manageWorks = new manageWorks();
$manageWorks->newWork( array(work_userAssigned=>$USERNAME, work_addedBy=>$USERNAME, work_deadLine=>$deadline, work_briefDesc=>$briefDesc, work_Notes=>'', work_status=>$DE_GLOBALS_WORK_NEW, work_priority=>'N', work_projectName=>$projectName, work_isPrivate =>'Y', daysb4deadline=>0, afterCompletionID=>0 ) );
$this_taskid = mysql_insert_id();

$COMMENTLOGMESSAGE = "<B>{$USERNAME}</B> Created New Task -- User Assigned:{$USERNAME}, DeadLine:{$deadline}, Project:{$projectName}";
$manageWorks->addComment( $this_taskid, APPNAME , $COMMENTLOGMESSAGE );

echo "Task '{$this_taskid}' Created !" ;

?>