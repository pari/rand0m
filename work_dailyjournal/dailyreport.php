#!/usr/bin/php
<?php

include_once "include_db.php" ;
include_once "include_custom.php" ;

$REPORTEMAIL = array();
$REPORTEMAIL[] = "Daily Report for ". date("F j, Y")."\n\n\n" ;

$currentUser = '';
$report = mysql_query("select * from journalentries where wasEmailed='N' order by task_user, task_day ");

if(!mysql_num_rows($report)){
	exit();
}

$TMP_TODAY = date("Y-m-d");

WHILE ($row = @mysql_fetch_array($report)){
	if( $row['task_user'] <> $currentUser){
		$REPORTEMAIL[] =  "\n---------------------";
		$REPORTEMAIL[] = $row['task_user']." :";
		$REPORTEMAIL[] =  "---------------------";
	}
	
	if( $row['task_day'] != $TMP_TODAY ){
		$REPORTEMAIL[] = "{$row['task_day']}, {$row['task_mins']} mins -- {$row['task_desc']}\n";
	}else{
		$REPORTEMAIL[] = "{$row['task_mins']} mins -- {$row['task_desc']}\n";
	}
	
	$currentUser = $row['task_user'];
}
$updateWasEmailed = mysql_query("update journalentries set wasEmailed='Y' ");

$REPORTEMAIL_BODY = implode("\n", $REPORTEMAIL) ;
$REPORTEMAIL_SUBJECT = "Daily Report for ". date("F j, Y") ;
$to = 'swdev@cigniti.com';

/*
	$headers = 'From: Daily Journal Report <chandu@cigniti.com>' . "\r\n" .
	'Cc: sanjayj@cigniti.com' . "\r\n" .
	//'Bcc: paripurnachand@gmail.com' . "\r\n" .
    'Reply-To: chandu@cigniti.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	$mailsent = @mail($to, $REPORTEMAIL_SUBJECT, $REPORTEMAIL_BODY, $headers);
*/

$email = new sendaMail();
$email->messageTo( $to );
$email->subject( $REPORTEMAIL_SUBJECT );
$email->body($REPORTEMAIL_BODY );
$email->asFrom('chandu@cigniti.com');
$email->send();



?>