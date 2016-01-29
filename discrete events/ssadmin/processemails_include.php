<?php


function scheduleNewEmail_temp( $email_to , $email_content , $subject, $caldate_day , $hour, $USERNAME ){
	$manageUsers = new manageUsers();
	$thisuserEmailId = $manageUsers->get_userSingleDetail( $USERNAME, 'user_primaryEmail' );
	$delivery_ts = $caldate_day.' '.$hour.':10:00' ; // 'YYYY-MM-DD HH:MM:SS'
	$details = array( 'emailby_user'=>$USERNAME, 'email_to'=>$email_to , 'email_content'=>$email_content , 'emailby_from'=>$thisuserEmailId , 'email_subject'=>$subject , 'email_scheduledon'=>$delivery_ts );
	$success = execute_sqlInsert( "scheduledmails", $details );
}





function parseReminderSubject( $inputline ){
	// get when - caldate , hr in 00 to 23
	// get rest of the subject with out the 
	// $inputline should be in format "sunday 9am blah blah" , "25th 10am blah blah", "tomorrow 5pm blah blah", "Aug 7th 6am blah blah",
	// if you can not figure out time of day - default 9 am.
	$poscomma = strpos($inputline, ',');
	if( $poscomma !== false){
		$reminder_text = trim(substr(strstr($inputline, ',') , 1));
		list($inputline) = explode(',' , $inputline );
	}else{
		$reminder_text = $inputline ;
	}
	$reminder_caldate = date("Y-m-d"); // default reminder assume today
	
	preg_match('/(\d+(am|pm))/i', strtolower($inputline), $matches);
	if(count($matches)){
		$reminder_timeofday = (int)$matches[0] ;
		if( trim($matches[2]) == 'am' && trim($matches[0]) == '12am' ){
			$reminder_timeofday = '00' ;
		}
		if( trim($matches[2]) == 'pm' && trim($matches[0]) != '12pm' ){
			$reminder_timeofday = (int)$matches[0] + 12 ;
		}
	}else{
		$reminder_timeofday = '08'; // default 9 am
	}

	preg_match('/(today|tomorrow|tommorrow|2maro|2morow|2moro|2morro|2marow|2marrow|2morrow|tomoro|tomaro|tomarrow|tomarow|sunday|monday|tuesday|wednesday|thursday|friday|saturday|sun|mon|tue|wed|thu|fri|sat)/' , strtolower($inputline) , $matches );
	if(count($matches)){
		$tmp_days = array();
		// $tmp_days[getTomorrowDayofWeek(0)] = getTomorrowCaldate(0); // do not consider today
		$tmp_days[strtolower(getTomorrowDayofWeek(1))] = $tmp_days[strtolower(getTomorrowDayofWeek(1 , false))] = getTomorrowCaldate(1); // $tmp_days['Wednesday'] = '2009-07-22' ;
		$tmp_days[strtolower(getTomorrowDayofWeek(2))] = $tmp_days[strtolower(getTomorrowDayofWeek(2 , false))] = getTomorrowCaldate(2);
		$tmp_days[strtolower(getTomorrowDayofWeek(3))] = $tmp_days[strtolower(getTomorrowDayofWeek(3 , false))] = getTomorrowCaldate(3);
		$tmp_days[strtolower(getTomorrowDayofWeek(4))] = $tmp_days[strtolower(getTomorrowDayofWeek(4 , false))] = getTomorrowCaldate(4);
		$tmp_days[strtolower(getTomorrowDayofWeek(5))] = $tmp_days[strtolower(getTomorrowDayofWeek(5 , false))] = getTomorrowCaldate(5);
		$tmp_days[strtolower(getTomorrowDayofWeek(6))] = $tmp_days[strtolower(getTomorrowDayofWeek(6 , false))] = getTomorrowCaldate(6);
		$tmp_days[strtolower(getTomorrowDayofWeek(7))] = $tmp_days[strtolower(getTomorrowDayofWeek(7 , false))] = getTomorrowCaldate(7);
		
		switch ($matches[1]) {
			case "today": $reminder_caldate = date("Y-m-d"); break;
			case "tomorrow": case 'tommorrow': case '2morow': case '2maro': case '2moro': case '2morro': case '2marow': case '2marrow': case '2morrow': case 'tomoro': case 'tomaro': case 'tomarow': case 'tomarrow':
			$reminder_caldate = getTomorrowCaldate(); break;
			default: $reminder_caldate = $tmp_days[$matches[1]]; break;
		}

	}else{
		$reminder_dayofmonth = date("d");
		$reminder_month = date("m"); // default current month
		preg_match('/((jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec|january|february|march|april|june|july|august|september|october|november|december))/i', strtolower($inputline), $matches) ;
		if( count($matches) ){
			switch (trim($matches[0])) {
				case "jan": case "january": $reminder_month = "01"; break;
				case "feb": case "february": $reminder_month = "02"; break;
				case "mar": case "march": $reminder_month = "03"; break;
				case "apr": case "april": $reminder_month = "04"; break;
				case "may": $reminder_month = "05"; break;
				case "jun": case "june": $reminder_month = "06"; break;
				case "jul": case "july": $reminder_month = "07"; break;
				case "aug": case "august": $reminder_month = "08"; break;
				case "sep": case "september": $reminder_month = "09"; break;
				case "oct": case "october": $reminder_month = "10"; break;
				case "nov": case "november": $reminder_month = "11"; break;
				case "dec": case "december": $reminder_month = "12"; break;
			}
		}

		preg_match('/(\d+(st|nd|rd|th))/i', $inputline, $matches);
		if( count($matches) == 0){
			// preg_match('/(\d+)/i', $inputline, $matches); 
			// todo eliminate words with pm/am
		}
		
		if(count($matches)){
			$reminder_dayofmonth = (int)$matches[0];
			$reminder_caldate = date("Y")."-".$reminder_month."-".$reminder_dayofmonth ;
		}
		
	}

	$processemail_debugoutput = "
		trying to parse input line parseReminderSubject(' $inputline ');
		Reminder Cal Date : $reminder_caldate
		Reminder time of Day : $reminder_timeofday ";
	//fwrite($fp, "$processemail_debugoutput \n" );

	// if reminder is for today and hour is already passed, assume next day
	if( date("Y-m-d") == $reminder_caldate &&  (int)date('G') >= (int)$reminder_timeofday ){
		$reminder_caldate = getTomorrowCaldate(1);
	}
	
	return array($reminder_caldate, $reminder_timeofday , $reminder_text );
}

?>