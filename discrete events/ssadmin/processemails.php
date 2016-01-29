#!/usr/bin/php -q 
<?php
// We Expect 'TO' Email id of the format
//	tasks_centerlimit@discreteevents.com
//	reminders_centerlimit@discreteevents.com
//	notes_centerlimit@discreteevents.com
//	comments_centerlimit@discreteevents.com  (with subject TN1234 to add a comment on task 1234 )


error_reporting(E_ALL);
ini_set('display_errors', '1');

$APP_PATH = "/www_apps/devents/" ;
$ALLOWED_ACTIONS = array('tasks' , 'comments', 'reminders' ,'notes');
include_once $APP_PATH."include_variables.php" ; IF (!@mysql_select_db(MASTERDB)){ exit(0); }
include_once $APP_PATH."include_functions.php" ;
include_once $APP_PATH."ssadmin/processemails_include.php";
include_once $APP_PATH."phpmailer/mimemailparser/MimeMailParser.class.php" ;

$Parser = new MimeMailParser();
$Parser->setPath($argv[1]);
$thisemail_toAddress = getEmailIdFromString($Parser->getHeader('to'));
$thisemail_ccAddress = getEmailIdFromString($Parser->getHeader('cc'));
$thisemail_fromAddress = getEmailIdFromString($Parser->getHeader('from'));
$thisemail_subject = $Parser->getHeader('subject');
// $thisemail_htmlbody = $Parser->getMessageBody('html');
// $thisemail_attachments = $Parser->getAttachments();

if($Parser->getHeader('content-transfer-encoding') == 'base64'){
	$thisemail_body = base64_decode($Parser->getMessageBody('text'));
}else{
	$thisemail_body = $Parser->getMessageBody('text');
}


$pos = strpos( strtolower($thisemail_toAddress), '@discreteevents.com');
if( $pos === false){
	$pos = strpos( strtolower($thisemail_ccAddress), '@discreteevents.com');
	if( $pos === false ){
		// wonder what to do with this email ? calling quits
		exit(0);
	}else{
		list ($before_underscore) = explode( '_', $thisemail_ccAddress );
		$SUBDOMAIN = strBetweenXY( $thisemail_ccAddress, '_' , '@' );
	}
}else{
	list($before_underscore) = explode( '_', $thisemail_toAddress );
	$SUBDOMAIN = strBetweenXY( $thisemail_toAddress, '_' , '@' );
}

// check for valid subdomain
	$query = mysql_query("select dbname as CLIENTDBNAME, timezone as myTimeZone, package as pkgid, status as subdomainStatus from subdomains where subdomain='$SUBDOMAIN'");
	if (@mysql_num_rows($query)==0){ 
		$processemail_debugoutput = "Invalid Subdomain: Failed adding Task for \n $thisemail_subject";
		exit(0);
	}
	WHILE ($row = @mysql_fetch_array($query)){ extract($row); } // $CLIENTDBNAME, $pkgid, $subdomainStatus, $myTimeZone
// Set TimeZone
	putenv("TZ=".$myTimeZone ); 
// Connect to this subdomain's database
	IF (!@mysql_select_db($CLIENTDBNAME)){ 
		$processemail_debugoutput = "Unable to connect to client database";
		exit(0);
	}
// see if this user is active
	$SUBDOMAIN_USER = executesql_returnArray("select username from users where user_primaryEmail='".$thisemail_fromAddress."' and user_status='A'");
	if(!$SUBDOMAIN_USER){ 
		$processemail_debugoutput = "No user found with this emailid under this subdomain ";
		exit(0);
	}

if ( $before_underscore == 'tasks' ){
	list( $task_deadline, $task_hour, $task_briefDescription ) = parseReminderSubject( $thisemail_subject ); // (caldate, timeofday , subject)
	$manageWorks = new manageWorks();
	$manageWorks->newWork( array(work_userAssigned=>$SUBDOMAIN_USER, work_addedBy=>$SUBDOMAIN_USER, work_deadLine=>$task_deadline, work_briefDesc=>$task_briefDescription , work_Notes=>'', work_status=>$DE_GLOBALS_WORK_FROMEMAIL, work_priority=>'N', work_projectName=>DEFAULTPROJECT, work_isPrivate =>'N'));
	$this_taskid = mysql_insert_id();

	simpleEmail($thisemail_fromAddress, "Added Task {$this_taskid}", "Added Task $this_taskid for \n $task_briefDescription");
	$processemail_debugoutput = "Added Task $this_taskid for \n $thisemail_subject" ;
}

if ( $before_underscore == 'reminders' ){
	list( $reminder_emailWhen, $reminder_emailHour, $reminder_emailBody ) = parseReminderSubject( $thisemail_subject ); // (caldate, timeofday , subject)
	$reminder_emailTo = $thisemail_fromAddress ;
	$reminder_emailSubject = "[Reminder] $reminder_emailBody" ;

	if( $DEVELOPMENT_MODE ){
		echo "
			<PRE>
			Incoming Subject : $thisemail_subject
			-------------------------------------------
			Reminder to : $reminder_emailTo
			Reminder email body : $reminder_emailBody
			Reminder email Subject : $reminder_emailSubject
			Reminder email when : $reminder_emailWhen
			Reminder email Hour : $reminder_emailHour
			Subdomain User : $SUBDOMAIN_USER
			</PRE>
		" ;
		scheduleNewEmail_temp( $reminder_emailTo , $reminder_emailBody , $reminder_emailSubject, $reminder_emailWhen , $reminder_emailHour , $SUBDOMAIN_USER);
	}else{
		scheduleNewEmail_temp( $reminder_emailTo , $reminder_emailBody , $reminder_emailSubject, $reminder_emailWhen , $reminder_emailHour , $SUBDOMAIN_USER);
	}

	simpleEmail($thisemail_fromAddress, "Reminder Added", "Added Reminder on $reminder_emailWhen");
	$processemail_debugoutput = "Added Reminder on $reminder_emailWhen \n for $reminder_emailBody";
}

if ( $before_underscore == 'comments' ){
	if (preg_match( '/TN(?<digit>\d+)(.*)/i' , $thisemail_subject, $matches) ) {
		$manageWorks = new manageWorks();
		// $matches[2] = trim($matches[2]); rest of the subject after 'TNXXX'
		$WORK_ID = $matches[1];
		$manageWorks->addComment( $WORK_ID, $SUBDOMAIN_USER , $thisemail_body );
		$processemail_debugoutput = "Added Comment to Task {$WORK_ID} \n Comment: $thisemail_body " ;
		
		// If the email received has attachments , put attach those files to this task
		$attachments = $Parser->getAttachments();
		if(count($attachments)){
			$tmp_uploadedOn = get_currentPHPTimestamp();
			foreach($attachments as $attachment) {
				$upload_filename = $attachment->filename ;
				$TMP_SOMENAME = '/tmp/'.getaRandomString(9) ;
				if ($fp = fopen( $TMP_SOMENAME, 'w')) {
					while($bytes = $attachment->read()) { fwrite($fp, $bytes); } fclose($fp);
					$fp = fopen( $TMP_SOMENAME , 'r');
					$TMP_SOMENAME_SIZE = filesize($TMP_SOMENAME);
					$TMP_SOMENAME_CONTENT = bin2hex(fread($fp, $TMP_SOMENAME_SIZE));
					$success = execute_sqlInsert( 'attachments',
						array( 
							'workid'=>$WORK_ID ,
							'uploadname'=>$upload_filename ,
							'uploadedby'=>$SUBDOMAIN_USER,
							'filecontent'=>$TMP_SOMENAME_CONTENT,
							'filesize'=>$TMP_SOMENAME_SIZE ,
							'uploadedOn'=>$tmp_uploadedOn
						)
					);
					fclose($fp);
					unlink($TMP_SOMENAME);
				}
			}
		}
	}
}


if ( $before_underscore == 'notes' ){
	$manageNotes = new manageNotes();
	$manageNotes->USERNAME = $SUBDOMAIN_USER ;
	$note_body =  "$thisemail_subject \n --------------------- \n $thisemail_body ";
	$manageNotes->insertNote( $note_body );
}

$processemail_debugoutput = " \n before_underscore is $before_underscore ";

// email $processemail_debugoutput to your email id to see the debug information
exit(0);

?>
