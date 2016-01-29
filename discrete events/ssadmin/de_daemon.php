<?php

set_time_limit(0);
include_once "../include_variables.php" ;
include_once "../include_functions.php" ;

// In each database
	// for each scheduled task
	//	if today == number of days before the task 
	//	unschedule the task

	function email_before_shutdown(){
	    $email = new sendaMail();
		$email->messageTo( SUPERADMIN_EMAIL );
		$email->subject( 'DE DAEMON HAS TERMINATED' );
		$email->body( "DE DAEMON HAS TERMINATED " );
		$email->send();
	}

	register_shutdown_function('email_before_shutdown');
	
	
	
function isTodayXdaysBeforeDeadline( $x, $deadline ){ // $x = integer , $deadline is '2009-06-29'
	$x = (int)$x ;
	$dl_split = explode("-", $deadline);
	$date1 = time();
	$date2 = mktime(0,0,0, $dl_split[1] , $dl_split[2] , $dl_split[0] );
	$dateDiff = $date2 - $date1;
	$daysdifference = floor($dateDiff/(60*60*24)) + 1 ;
	if( $daysdifference == $x ){ return true; }

	return false;
}

$FOREVER = true;
while($FOREVER){

$ST_emailText = array();
$mainResult = mysql_query("select dbname as thisdb, timezone as thisTimeZone from " . MASTERDB . ".subdomains where status='Y' ORDER BY pid ");

WHILE( $mainrow = @mysql_fetch_array($mainResult) ){
	extract($mainrow) ; // $thisdb , $thisTimeZone
	putenv("TZ=".$thisTimeZone );

	// Scheduled Tasks
		$thisdb_result = mysql_query("select workID, work_userAssigned, work_addedBy, work_dateAdded, work_deadLine, work_startDate, work_completeDate, work_briefDesc, work_Notes, work_status, work_priority, work_projectName, work_isPrivate, daysb4deadline , work_RTID from ".$thisdb.".WORKS where work_status='".$DE_GLOBALS_WORK_SCHEDULED."'");
		WHILE( $row = @mysql_fetch_array($thisdb_result) ){
			extract($row) ;
			// workID, work_userAssigned, work_addedBy, work_dateAdded, work_deadLine, work_startDate, work_completeDate, 
			// work_briefDesc, work_Notes, work_status, work_priority, work_projectName, work_isPrivate, daysb4deadline
			if( isTodayXdaysBeforeDeadline( $daysb4deadline, $work_deadLine) ){
				$somequery = mysql_query("update ".$thisdb.".WORKS set work_status = '".$DE_GLOBALS_WORK_NEW."' where workID='$workID' ");	
				$ST_emailText[] = "Task $workID of $thisdb is added to tasks from scheduled list - <BR> Work Description : $work_briefDesc <BR> Deadline: $work_deadLine <BR> Days Before : $daysb4deadline ";
				
				$tmp_manageUsers = new manageUsers();
				$email_userAssigned = $tmp_manageUsers->get_userSingleDetail( $work_userAssigned, 'user_primaryEmail' );
				$email_taskOwner = $tmp_manageUsers->get_userSingleDetail( $work_addedBy, 'user_primaryEmail' );
				
				$scheduledtask_invoked_email = new sendaMail();
				if( $email_userAssigned ){
					$scheduledtask_invoked_email->messageTo( $email_userAssigned );
				}
				if( $email_taskOwner && $email_userAssigned <> $email_taskOwner ){
					$scheduledtask_invoked_email->messageTo( $email_taskOwner );
				}

				if($work_RTID > 0){
					$scheduledtask_invoked_email->subject( "Added Recurring Task $workID - $work_briefDesc  " );
				}else{
					$scheduledtask_invoked_email->subject( "Moved Scheduled Task $workID - $work_briefDesc  " );
				}

				$scheduledtask_invoked_email->body("Task Description: $work_briefDesc <BR> Task Deadline: $work_deadLine ");
				$scheduledtask_invoked_email->send();
			}
		}
	// End of "Scheduled Tasks"
	
	// Scheduled Emails
		$thisMonth = date("m");	$thisYear = date("Y"); $thisDay = date("d");	$thisHour = date("H");
		$result_scheduledEmails = mysql_query("select * from ".$thisdb.".scheduledmails where email_sent='N' and DAYOFMONTH(email_scheduledon)='$thisDay' and MONTH(email_scheduledon)='$thisMonth' and YEAR(email_scheduledon)='$thisYear' and HOUR(email_scheduledon)='$thisHour' " );
		WHILE( $row = @mysql_fetch_array($result_scheduledEmails) ){
			extract($row) ; // sch_emailid, emailby_user, emailby_from, email_to, email_content, email_subject, email_scheduledon, email_sent
			// send this email from this user to the destination, send a copy to the user
			$email = new sendaMail();
				$containsAt =strpos($emailby_from, '@');
				if( $containsAt ){
					$email->asFrom( $emailby_from );
				}
			$email->messageTo( $email_to );
			$email->subject( $email_subject );
			$email->body( $email_content );
			if( $email_to <> $emailby_from ){
				$email->AddBCC( $emailby_from );
			}

			$email->send();
			//echo "sending email to ".$email_to ;
			// mark this email as sent
			$updated = mysql_query("update ".$thisdb.".scheduledmails set email_sent='Y' where sch_emailid='$sch_emailid' ");
		}
	// End of "Scheduled Emails"
}


// email daily schedule report to serveradmin
	if( count($ST_emailText) ){
		$email = new sendaMail();
		$email->messageTo( SUPERADMIN_EMAIL );
		$email->subject( 'Daily cron - scheduled tasks for '.date("d-m-Y") );
		$email->body( implode("<BR>", $ST_emailText) );
		$email->send();
	}

sleep(60*5); // sleep for 5 minutes
} // $FOREVER loop

?>
