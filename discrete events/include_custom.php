<?php

/*
	Custom PHP functions for CenterLimit Applications
	Copyrights 2009 CenterLimit
	version 1.0.1
*/

function month_short_toNumber($mon){
	$months = array(
		'jan' => '01',
		'feb' => '02',
		'mar' => '03',
		'apr' => '04',
		'may' => '05',
		'jun' => '06',
		'jul' => '07',
		'aug' => '08',
		'sep' => '09',
		'oct' => '10',
		'nov' => '11',
		'dec' => '12'
	);
	
	return (int)$months[$mon];
}

function month_short_toLong($mon){
	$months = array(
		'jan' => 'January',
		'feb' => 'February',
		'mar' => 'March',
		'apr' => 'April',
		'may' => 'May',
		'jun' => 'June',
		'jul' => 'July',
		'aug' => 'August',
		'sep' => 'September',
		'oct' => 'October',
		'nov' => 'November',
		'dec' => 'December'
	);
	return $months[$mon];
}

function week_short_toLong($week){
	$weekdays = array(
		'mon' => 'Monday',
		'tue' => 'Tuesday',
		'wed' => 'Wednesday',
		'thu' => 'Thursday',
		'fri' => 'Friday',
		'sat' => 'Saturday',
		'sun' => 'Sunday',
	);
	return $weekdays[$week];
}

function dayofmonth_to_daywithsuffix($d){
	$d = (int)$d;
	$str_st = array(1, 21, 31);
	$str_nd = array(2, 22);
	$str_rd = array(3 , 23);
	$str_th = array(4, 5, 6, 7, 8, 9, 10 , 11, 12, 13,14, 15, 16,17,18,19,20, 24, 25, 26, 27, 28, 29, 30);
	if (in_array( $d , $str_st)) {
		return "{$d} st";
	}
	if (in_array( $d , $str_nd)) {
		return "{$d} nd";
	}
	if (in_array( $d , $str_rd)) {
		return "{$d} rd";
	}
	if (in_array( $d , $str_th)) {
		return "{$d} th";
	}
	return $d;
}

function hex2bin($h)
  {
  if (!is_string($h)) return null;
  $r='';
  for ($a=0; $a<strlen($h); $a+=2) { $r.=chr(hexdec($h{$a}.$h{($a+1)})); }
  return $r;
}


function format_makeLinks($string){
	$string = preg_replace("/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i", "$1http://$2",$string);
	$string = preg_replace("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i","<a target=\"_blank\" href=\"$1\">$1</A>",$string);
	$string = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<A HREF=\"mailto:$1\">$1</A>",$string);
	return $string;
}



function formatBytesToHumanReadable($bytes, $precision = 1) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
  
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
  
    $bytes /= pow(1024, $pow);
  
    return round($bytes, $precision) . ' ' . $units[$pow];
}


function getTomorrowCaldate($n=1){
	// returns tomorrows caldate
	$unixtimestamp = @mktime(0, 0, 0, date("m") , date("d") , date("Y"));
	$unixtimestamp_tomorrow = $unixtimestamp + ($n*60*60*24) ;
	return @date("Y-m-d" , $unixtimestamp_tomorrow );
}


function getTomorrowDayofWeek($n=1 , $longformat=true){
	// returns tomorrows caldate
	$unixtimestamp = @mktime(0, 0, 0, date("m") , date("d") , date("Y"));
	$unixtimestamp_tomorrow = $unixtimestamp + ($n*60*60*24) ;
	
	return ($longformat) ? date( "l" , $unixtimestamp_tomorrow ) : date( "D", $unixtimestamp_tomorrow ) ;
}


function getTomorrowDayofMonth(){
	// returns tomorrows day of month
	$unixtimestamp = @mktime(0, 0, 0, date("m") , date("d") , date("Y"));
	$unixtimestamp_tomorrow = $unixtimestamp + (60*60*24) ;
	return @date("d" , $unixtimestamp_tomorrow );
}


function getTomorrowMonthofYear(){
	// returns tomorrows month of year
	$unixtimestamp = @mktime(0, 0, 0, date("m") , date("d") , date("Y"));
	$unixtimestamp_tomorrow = $unixtimestamp + (60*60*24) ;
	return @date("m" , $unixtimestamp_tomorrow );
}


function getTomorrowYear(){
	// returns tomorrows year
	$unixtimestamp = @mktime(0, 0, 0, date("m") , date("d") , date("Y"));
	$unixtimestamp_tomorrow = $unixtimestamp + (60*60*24) ;
	return @date("Y" , $unixtimestamp_tomorrow );
}

function caldate_isInPast($caldate){
	list($year, $month, $day) = split ("-", $caldate);
	list( $todayYear, $todayMonth, $todayDay ) = split ("-", date("Y-m-d") );
	$mktimeCaldate = mktime(0, 0, 0, $month, $day, $year);
	$mktimeToday = mktime(0, 0, 0, $todayMonth, $todayDay, $todayYear);
	return $mktimeCaldate < $mktimeToday ;
}


function mynl2br($text) {
  return strtr($text, array( '\\n' => '<br>', '\n' => '<br>', "\n" => '<br>', "\r\n" =>'<br>'));
}


function strBetweenXY( $inputStr, $delimeterLeft, $delimeterRight ){
    $posLeft=strpos($inputStr, $delimeterLeft);
    if ( $posLeft===false ){ return $inputStr; }
    $posLeft+=strlen($delimeterLeft);
    $posRight=strpos($inputStr, $delimeterRight, $posLeft);
    if ( $posRight===false ){ return $inputStr; }
    return substr($inputStr, $posLeft, $posRight-$posLeft);
} 



function getEmailIdFromString($mystring){ // $mystring may be 'Chandu Nannapaneni <chandu@chandu.org>' or 'chandu@chandu.org'
	$pos = strpos($mystring, '<' );
	if ($pos === false) {
		return trim($mystring);
	} else {
		// get string between '<' and '>'
		return strBetweenXY( $mystring , '<' , '>' );
	}
	return trim($mystring);
}




function getNwordsFromString($string, $n){
	if(!$string){ return ''; }
	$pieces = array_chunk(explode(" ", $string), $n);
	return implode(" ", $pieces[0]);
}



function getNwordsOfLengthFromString($sentence, $length){
	return $sentence;
	// TODO fix this: [IMP] 's span html are interfering
	if( strlen($sentence) <= $length ){ return $sentence; }
	$toReturn = '' ;

	$words = str_word_count($sentence, 1, '�') ; // explode (' ', $sentence) is acting wiered
	for( $i=0; $i < count($words); $i++ ){
		$toReturn = $toReturn . " " . $words[$i] ; 
		if( strlen($toReturn) > ($length-2) && $i < count($words)-1 ){
			return $toReturn . " ..." ;
		}
	}

	return $toReturn ;
}


function getLastMonth_FromToDates(){
	$tmp_thisyear = date("Y");
	$tmp_today = date("d");
	$tmp_thismonth = date("m");

	if( $tmp_thismonth == "01" ){
		$tmp_lastyear = $tmp_thisyear - 1;
		$lastmonth_fromdate = $tmp_lastyear."-12-01" ;
		$lastmonth_todate = $tmp_lastyear."-12-31" ;
	}else{
		$tmp_lastmonth = $tmp_thismonth - 1;
		$lastmonth_fromdate = $tmp_thisyear."-".addZeroToNumber($tmp_lastmonth)."-01" ;
		$lastmonth_todate = $tmp_thisyear."-".addZeroToNumber($tmp_lastmonth)."-31" ;
	}
	return array($lastmonth_fromdate, $lastmonth_todate);
}


function addZeroToNumber($n){
	if($n < 10){
		return "0".strval($n);
	}return $n;
}

function getCurrentScriptFileName(){
	$break = Explode('/', $_SERVER["SCRIPT_NAME"]);
	return $break[count($break) - 1]; 
}

function getaRandomString($length){
	if(!$length){$length=16;}
	for ($s = '', $i = 0, $z = strlen($a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')-1; $i != $length; $x = rand(0,$z), $s .= $a{$x}, $i++);
	return $s;
}


function filename_extension($filename) {
    $pos = strrpos($filename, '.');
    if($pos===false) {
        return false;
    } else {
        return substr($filename, $pos+1);
    }
}


function html_array2selectboxOptions_selected( $somearray, $selected ){
	for ($i=0; $i < count( $somearray ) ; $i++) { 
		if( $selected == $somearray[$i] ){
			echo "<option value='". $somearray[$i] ."' selected>". $somearray[ $i ] . "</option>";
		}else{
			echo "<option value='". $somearray[$i] ."'>". $somearray[ $i ] . "</option>";
		}
	}
}

function html_array2selectboxOptions( $somearray ){
	for ($i=0; $i < count( $somearray ) ; $i++) { 
		echo "<option value='". $somearray[$i] ."'>". $somearray[ $i ] . "</option>";
	}
}

function html_array2selectbox( $somearray , $someid ){
	echo "<select id='" . $someid . "'>";
	html_array2selectboxOptions( $somearray );
	echo "</select>";
}


function caldateTS_to_humanWithOutTS( $caldatets ){ // $caldatets = 'YYYY-mm-dd hr:m:s'
	if(!$caldatets){ return ''; }
	$pieces = explode(" ", $caldatets);
	$tmp_datepart = $pieces[0] ;
	$tmp_timepart = $pieces[1] ;
	return caldate_to_human( $tmp_datepart );
}


function caldateTS_to_icalDate( $caldateTS , $second=false){ //// $caldateTS = 'YYYY-mm-dd hr:m:s' returns 20091028T143000Z
	if( !$caldateTS ){ return ''; }
	$pieces = explode(" ", $caldateTS);
	$tmp_datepart = $pieces[0] ;
	$tmp_timepart = $pieces[1] ;
	$ymd = implode('', explode("-", $tmp_datepart));
	if(!$second){
		$hms = implode('', explode(":", $tmp_timepart));
	}else{
		$hms = explode(":", $tmp_timepart);
		$hms[2] = (int)$hms[2];
		$hms[2]++;
		$hms[2] = addZeroToNumber($hms[2]);
		$hms = implode('', $hms);
	}
	
	return $ymd.'T'.$hms;
}

function caldateTS_to_humanWithTS( $caldatets , $shorthour=false){ // $caldatets = 'YYYY-mm-dd hr:m:s'
	if( !$caldatets ){ return ''; }
	$pieces = explode(" ", $caldatets);
	$tmp_datepart = $pieces[0] ;
	$tmp_timepart = $pieces[1] ;
	
	$hms = explode(":", $tmp_timepart);
	if($shorthour){
		$tmp_timestring = @date(" ga", mktime($hms[0], $hms[1], 0, 1, 1, 2000)) ;
	}else{
		$tmp_timestring = @date(" g:i a", mktime($hms[0], $hms[1], 0, 1, 1, 2000)) ;
	}
	
	return caldate_to_human($tmp_datepart) . ', ' . $tmp_timestring; //
}



function caldate_to_human($caldate, $shortmonth = false){
	list($year, $month, $day) = split ("-", $caldate);
	list( $todayYear, $todayMonth, $todayDay ) = split ("-", date("Y-m-d") , @mktime (0,0,0,$month,$day,$year));
	$monthstr = ($shortmonth) ? 'M j' : 'F jS' ;

	if( $todayYear == $year && $todayMonth == $month){
		if( $todayDay == $day ){ return "Today"; }
		if( $todayDay == $day+1 ){ return "Yesterday"; }
		if( $todayDay == $day-1 ){ return "Tomorrow"; }
	}

	if( $todayYear == $year ){ // do not display year if current.
		return @date ("$monthstr", @mktime (0,0,0,$month,$day,$year));
	}

	return @date ("$monthstr".", Y", @mktime (0,0,0,$month,$day,$year));
}



function get_currentPHPTimestamp(){
	// use this inplace of mysql timestamp - to get the current timestamp according to the php environment timezone
	return date("Y-m-d H:i:s");
}


function get_durationSecondsInHumanFormat($dseconds) {
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'Yr'),
        array(60 * 60 * 24 * 30 , 'Month'),
        array(60 * 60 * 24 * 7, 'Week'),
        array(60 * 60 * 24 , 'Day'),
        array(60 * 60 , 'Hr'),
        array(60 , 'Min')
    );
	for ($i = 0, $j = count($chunks); $i < $j; $i++) {
	    $seconds = $chunks[$i][0];
	    $name = $chunks[$i][1];
	    if (($count = floor($dseconds/$seconds)) != 0) {
	        break;
	    }
	}
	
	if($i >= 5){
		if($count <= 5){
			$print = 'less than 5 Mins' ;
		}elseif($count > 5 && $count <= 12 ){
			$print = 'about 10 Mins';
		}elseif($count > 12 && $count <= 17 ){
			$print = 'about 15 Mins';
		}elseif($count > 17 && $count <= 24 ){
			$print = 'about 20 Mins';
		}elseif($count >= 25 && $count <= 35 ){
			$print = 'about 30 Mins';
		}elseif($count >= 36 && $count <= 42 ){
			$print = 'about 40 Mins';
		}elseif($count >= 43 && $count <= 50 ){
			$print = 'about 45 Mins';
		}elseif($count > 50 && $count <=59 ){
			$print = 'about 1 Hr';
		}else{
			$print = "$count {$name}s";
		}
	}else{
		$print = ($count == 1) ? '1 '.$name : "$count {$name}s";
	}
	
	if ($i + 1 < $j) {
		$seconds2 = $chunks[$i + 1][0];
		$name2 = $chunks[$i + 1][1];
		$count2 = floor(($dseconds - ($seconds * $count)) / $seconds2);
		if( $i == 4 ){
			if($count2 <= 10){
				$print .= '' ;
			}elseif($count2 > 10 && $count2 <= 20 ){
				$print .= ', 15 Mins';
			}elseif($count2 > 20 && $count2 <= 35 ){
				$print .= ', 30 Mins';
			}elseif($count2 > 35 && $count2 <= 48 ){
				$print .= ', 45 Mins';
			}elseif($count2 > 48 && $count2 <=59 ){
				$print = ($count+1)." {$name}s";
			}
		}elseif( $count2 != 0 ) {
			$print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
		}
	}
    return $print;
}



function get_durationSince_timeStamp($sinceTimestamp, $toTimestamp = NULL){ // $sinceTimestamp is expected as "YYYY-mm-dd hr:m:s" (mysql timestamp)
	// mktime(hr, min, sec, mon, day, year);
	if(!$sinceTimestamp ){ return ''; }
	list($tmp_datepart , $tmp_timepart) = explode(" ", $sinceTimestamp);
	list($stYear, $stMonth, $stDay) = split( "-", $tmp_datepart );
	list($stHr, $stMin, $stSec) = split( ":", $tmp_timepart );
	$since_Unixstamp = @mktime( $stHr, $stMin, $stSec, $stMonth, $stDay, $stYear );

	if(is_null($toTimestamp)){
		$current_Unixstamp = time();
	}else{
		$pieces = explode(" ", $toTimestamp); $tmp_datepart = $pieces[0] ; $tmp_timepart = $pieces[1] ;
		list($ttYear, $ttMonth, $ttDay) = split( "-", $tmp_datepart );
		list($ttHr, $ttMin, $ttSec) = split( ":", $tmp_timepart );
		$current_Unixstamp = @mktime( $ttHr, $ttMin, $ttSec, $ttMonth, $ttDay, $ttYear );
	}
	
	$diff = $current_Unixstamp - $since_Unixstamp;
	return get_durationSecondsInHumanFormat($diff);
}




//function caldate_to_human_withHour($caldate){
//	list($year, $month, $day) = split ("-", $caldate);
//	return date ("M j, Y g:i A", mktime (0,0,0,$month,$day,$year));
//}


function get_GET_var($varname){
	$t = '';
	if( @$_GET[$varname] ){
		$t = $_GET[$varname] ;
	}
	return mysql_real_escape_string($t);
}

function get_POST_var($varname){
	$t = '';
	if( @$_POST[$varname] ){
		$t = $_POST[$varname] ;
	}
	return mysql_real_escape_string($t);
}


function execute_sqlInsert($my_table, $my_array) {
   // Insert values into a MySQL database
   //	execute_sqlInsert("tablename", array(col1=>$val1, col2=>$val2, col3=>"val3", col4=>720, col5=>834.987));
   // Sends the following query:
   //	INSERT INTO 'tablename' (col1, col2, col3, col4, col5) values ('foobar', 495, 'val3', 720, 834.987)
	global $db_link;
	$columns = array_keys($my_array);
	$values = array_values($my_array); // Find all the values from the array $my_array
	$values_number = count($values); // quote_smart the values
	for ($i = 0; $i < $values_number; $i++){
		$value = $values[$i];
		if (get_magic_quotes_gpc()) { $value = stripslashes($value); }
		if (!is_numeric($value))    { 
			if( $value == "CURRENT_TIMESTAMP" ){ // we do not want "CURRENT_TIMESTAMP" in SQL, just CURRENT_TIMESTAMP 
			
			}else{
				$value = "'" . mysql_real_escape_string($value, $db_link) . "'"; 
			}
		}

		$values[$i] = $value;
	}
	// Compose the query
	$sql = "INSERT INTO $my_table ";
	$sql .= "(" . implode(", ", $columns) . ")";
	$sql .= " values ";
	$sql .= "(" . implode(", ", $values) . ")";
	$result = @mysql_query ($sql) 
		 OR die ("ERROR #ESI001"); // for debugging : die ("ERROR #ESI001 : $sql")
		//OR die('Invalid query: ' . mysql_error());
   return ($result) ? true : false;
}



function execute_sqlUpdate($my_table, $update_array, $where_array) {
   // update values in table
   //	execute_sqlUpdate("tablename", array(col1=>$val1, col2=>$val2) , array(col3=>"val3", col4=>720) );
   // Sends the following query:
   //	update 'tablename' set col1='$val1', col2='$val2' where col3='val3' and col4=720 ;
	$tmp_setArray = array();
	$tmp_whereArray = array();
	foreach ($update_array as $key => $value) {
		if (!is_numeric($value)){
			$tmp_setArray[] = $key . "='" . $value . "' " ;
		}else{
			$tmp_setArray[] = $key . "=" . $value ;
		}
	}
	foreach ($where_array as $key => $value) {
		if (!is_numeric($value)){
			$tmp_whereArray[] = $key . "='" . $value . "' " ;
		}else{
			$tmp_whereArray[] = $key . "=" . $value ;
		}
	}
	// Compose the query
	$sql = "update $my_table ";
	$sql .= "set " . implode(", ", $tmp_setArray ) . " " ;
	$sql .= " where " . implode(" and ", $tmp_whereArray ) ;

	$result = @mysql_query($sql) OR send_Action_Response('Error' , "#ESU001 $sql");
	return ($result) ? true : false;
}





function executesql_returnAssocArray($sqlquery){
	// use this function to get a single row of values
	$result = mysql_query($sqlquery);
	IF (@mysql_num_rows($result)!=1){
		return NULL;
	}
	return mysql_fetch_assoc($result); 
}





function executesql_returnArray($sqlquery){
	// use this function to get one value or one column values as array
	$vars = array();
	$result = mysql_query($sqlquery);
	IF (@mysql_num_rows($result)==0){
		return '';
	}
	while( $row=mysql_fetch_assoc($result) ) {
		foreach($row as $key=>$value) {
			array_push($vars, $value);
		}
	}
	if(count($vars)==1){
		return $vars[0];
	}
	return $vars;
}


function executesql_returnStrictArray($sqlquery){
	// use this function to get one column values
	$vars = array();
	$result = mysql_query($sqlquery);
	IF (@mysql_num_rows($result)==0){
		return $vars;
	}
	while( $row=mysql_fetch_assoc($result) ) {
		foreach($row as $key=>$value) {
				array_push($vars, $value);
		}
	}
	return $vars;
}


function getDirectorySize($path){
	return array('size'=>0);
	/*
  $totalsize = 0;
  $totalcount = 0;
  $dircount = 0;

  if( !file_exists($path) ){
	  $total['size'] = 0 ;
	  $total['count'] = 0 ;
	  $total['dircount'] = 0 ;
	  return $total;  
  }

  if ($handle = opendir ($path)) {
    while (false !== ($file = readdir($handle))){
      $nextpath = $path . '/' . $file;
      if ($file != '.' && $file != '..' && !is_link ($nextpath)){
        if (is_dir ($nextpath)){
          $dircount++;
          $result = getDirectorySize($nextpath);
          $totalsize += $result['size'];
          $totalcount += $result['count'];
          $dircount += $result['dircount'];
        }elseif (is_file ($nextpath)){
          $totalsize += filesize ($nextpath);
          $totalcount++;
        }
      }
    }
  }
  closedir ($handle);
  $total['size'] = $totalsize;
  $total['count'] = $totalcount;
  $total['dircount'] = $dircount;
  return $total;
*/
}



function sendaSMS($sendername, $to_number , $message){
	$message = urlencode($message);
	
	$handle = fopen("http://bulksms.mysmsmantra.com:8080/WebSMS/SMSAPI.jsp?username=badhri&password=1117716095&sendername=TAAZA&mobileno=91".$to_number."&message=".$message, "r");
}



class sendaMail{
	// :: Usage ::
	// $email = new sendaMail();
	// $email->messageTo( "chandu@chandu.org" );
	// $email->subject( "Some Subject" );
	// $email->body( "Some Body" );
	// $email->AddAttachment( $filename ); // Optional
	// $email->send();
	var $Attachments = array();
	var $AttachmentNames = array();
	var $CCS = array();
	var $BCCS = array();
	
	public function messageTo( $to ){
		$this->mailto = $to ;
	}

	public function subject( $subject ){
		$this->subject = $subject ;
	}

	public function body( $message ){
		$this->textmsg = $message ;
		$this->htmlmsg = nl2br($message) ;
	}

	public function AddAttachment( $filename , $name = ''){
		array_push($this->Attachments, $filename);
		$attachmentNumber = count($this->Attachments) - 1;
		if($name){
			$this->AttachmentNames[$attachmentNumber] = $name;
		}
	}

	public function AddCC( $ccemailid ){
		if( $ccemailid != SUPERADMIN_EMAIL ){
			$this->CCS[] = $ccemailid ;
		}
	}
	
	public function AddBCC( $bccemailid ){
		if( $bccemailid != SUPERADMIN_EMAIL ){
			$this->BCCS[] = $bccemailid ;
		}
	}

	public function asFrom( $asFrom ){
		$this->asFrom = $asFrom ;
	}

    public function send() {
		global $DEVELOPMENT_MODE ;
		if($DEVELOPMENT_MODE){return;}

		try{
			if(!$this->textmsg){
				$this->textmsg = ' ';
			}
			if(!$this->htmlmsg){
				$this->htmlmsg = ' ';
			}
		require_once( APP_INSTALLPATH . 'phpmailer/class.phpmailer.php' );
		
		$mail = new PHPMailer();
		//$mail->IsSendmail();
		$mail->IsSMTP();
		//$mail->Host       = "localhost" ;
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
		$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
		$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mail->Username   = "noreply@discreteevents.com";  // GMAIL username
		$mail->Password   = "deadmin123";            // GMAIL password
		//$mail->Host       = "expensescentral.com" ;
		//$mail->SMTPAuth   = true;
		//$mail->SMTPDebug = false;
		//$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
		//$mail->Username   = 'phpmailer@expensescentral.com' ; // SMTP account username
		//$mail->Password   = 'asd123'; //SMTP_PASSWORD ;        // SMTP account password
		
		if( $this->asFrom ){
			$mail->SetFrom('noreply@'.MAINDOMAIN, APPNAME );
			//$mail->SetFrom($this->asFrom, $this->asFrom);
			$mail->AddReplyTo($this->asFrom, $this->asFrom);
		}else{
			$mail->SetFrom('noreply@'.MAINDOMAIN, APPNAME );
			$mail->AddReplyTo('noreply@'.MAINDOMAIN, APPNAME );
		}

		if( count($this->Attachments) ){
			$attachmentCount = count($this->Attachments);
			for( $t=0; $t< $attachmentCount; $t++){
				if( @$this->AttachmentNames[$t] ){
					$mail->AddAttachment( $this->Attachments[$t] , $this->AttachmentNames[$t] );
				}else{
					$mail->AddAttachment( $this->Attachments[$t] );
				}
			}
		}

		if( count($this->BCCS) ){
			foreach($this->BCCS as $bccid ){
				$mail->AddBCC( $bccid );
			}
		}
		
		if( count($this->CCS) ){
			foreach($this->CCS as $cc_id ){
				$mail->AddCC( $cc_id );
			}
		}

		$mail->AddAddress( $this->mailto );
		$mail->Subject = $this->subject ;
		$mail->AltBody = $this->textmsg ;
		$mail->MsgHTML( $this->htmlmsg );
		if( $this->mailto != SUPERADMIN_EMAIL ){
			$mail->AddBCC( SUPERADMIN_EMAIL );
		}

		$mail->Send();
		}catch (phpmailerException $e) {
			echo $e->errorMessage();
		}catch (Exception $e) {
			echo $e->getMessage();
		}
    }

} // End of 'sendaMail' class


/// SIMPLE WRAPPER FOR ABOVE 'sendaMail' CLASS
function simpleEmail($to, $subject, $message){
	$email = new sendaMail();
	$email->messageTo( $to );
	$email->subject( $subject );
	$email->body( $message );
	$email->send();
}




function alertAppAdmin($msg){
	if( $_SESSION["subdomain"] == TESTSUBDOMAIN ){ return; }
	if(ALERTAPPADMIN){
		$email = new sendaMail();
		$email->messageTo( SUPERADMIN_EMAIL );
		$email->subject( 'New Alert: '.$msg."  ".$_SERVER["HTTP_HOST"] );
		$email->body( " " );
		$email->send();
	}
}



function makecomma($input){
	$CURRENCY = $_SESSION["CURRENCY"];
	if( $CURRENCY == 'Rs'){
		return formatInIndianStyle($input).' Rs';
	}else{
		return $CURRENCY.' '.number_format($input,2);
	}
}


function makecomma2($input){
	if(strlen($input)<=2){ return $input; }
	$length=substr($input,0,strlen($input)-2);
	$formatted_input = makecomma2($length).",".substr($input,-2);
	return $formatted_input;
}

function formatInIndianStyle($num){
	if($num=="" || $num==0){return "0.00";}
	$pos = strpos((string)$num, ".");
	if ($pos === false) { $decimalpart="00";}
	else { $decimalpart= substr($num, $pos+1, 2); $num = substr($num,0,$pos); }
	if(strlen($decimalpart)=="1"){$decimalpart=$decimalpart."0";}

	if(strlen($num)>3 & strlen($num) <= 12){
		$last3digits = substr($num, -3 );
		$numexceptlastdigits = substr($num, 0, -3 );
		$formatted = makecomma2($numexceptlastdigits);
		$stringtoreturn = $formatted.",".$last3digits.".".$decimalpart ;
	}elseif(strlen($num)<=3){
		$stringtoreturn = $num.".".$decimalpart ;
	}elseif(strlen($num)>12){
		$stringtoreturn = number_format($num, 2);
	}

	if(substr($stringtoreturn,0,2)=="-,"){$stringtoreturn = "-".substr($stringtoreturn,2 );}
	return $stringtoreturn;
}



function get_DateInstances_inTimeRange($inputInfo){
	/*
	$inputInfo = array(
		'StartDate'=>'2009-12-01',
		'EndDate'=>'2010-11-25',
		'RT_EVERYNTHDAYOFMONTH'=>'22' ,
		'RT_EVERYDAYOFYEAR_MONTH '=>'jun' ,
		'RT_EVERYNDAYS'=> '90' ,
		'RT_EVERYXWEEKDAY' => 'tue',
		'nrt_type' => 'W' // W, M , N , Y
	);
	*/
	
	$caldate_begin = $inputInfo['StartDate'] ;
	$caldate_end = $inputInfo['EndDate'] ;
	$DATE_INSTANCES = array();
	$ALLMONTHS = array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');

	$caldate_begin_parts = explode("-", $caldate_begin);
	$unixtime_begin = mktime(0, 0, 0, $caldate_begin_parts[1], $caldate_begin_parts[2], $caldate_begin_parts[0] );

	$caldate_end_parts = explode("-", $caldate_end);
	$unixtime_end = mktime(0, 0, 0, $caldate_end_parts[1], $caldate_end_parts[2], $caldate_end_parts[0] );

	switch ($inputInfo['nrt_type']) {
		case "W":
			$unixtime_instance = strtotime( "next ". week_short_toLong($inputInfo['RT_EVERYXWEEKDAY']) , $unixtime_begin );
			$first_instance = $unixtime_instance - ( (24 * 60 * 60) * 7) ;
			if( $first_instance <= $unixtime_instance && $first_instance <= $unixtime_end  ){
				$DATE_INSTANCES[] = date("Y-m-d" , $first_instance ) ;
			}
			while( $unixtime_begin <= $unixtime_instance && $unixtime_instance <= $unixtime_end ){
				$DATE_INSTANCES[] = date("Y-m-d" , $unixtime_instance ) ;
				$unixtime_instance = $unixtime_instance + ( (24 * 60 * 60) * 7) ;
			}
			return $DATE_INSTANCES ;
		break;

		case "M": //  on 5th of every month 
		case "Y": //  on 5th of every January 
			// get all years in the range
			$YEARSINRANGE = array();
			$TMP_YEAR = (int)$caldate_begin_parts[0] ;
			$TMP_FINALYEAR = (int)$caldate_end_parts[0] ;
			while( $TMP_YEAR <= $TMP_FINALYEAR ){
				$YEARSINRANGE[] = $TMP_YEAR ;
				$TMP_YEAR ++;
			}
			// push into instances array if xth of Januray in each year falls in our range
			foreach($YEARSINRANGE as $TMP_YEAR){
				if($inputInfo['nrt_type'] == 'M'){
					foreach($ALLMONTHS as $FORTHISMONTH){
						$unixtime_instance = mktime(0, 0, 0, $FORTHISMONTH, $inputInfo['RT_EVERYNTHDAYOFMONTH'] , $TMP_YEAR );
						if( $unixtime_begin <= $unixtime_instance && $unixtime_instance <= $unixtime_end ){
							$DATE_INSTANCES[] = date("Y-m-d" , $unixtime_instance ) ;
						}
					}
				}else{
					$unixtime_instance = mktime(0, 0, 0, month_short_toNumber($inputInfo['RT_EVERYDAYOFYEAR_MONTH']), $inputInfo['RT_EVERYNTHDAYOFMONTH'] , $TMP_YEAR );
					if( $unixtime_begin <= $unixtime_instance && $unixtime_instance <= $unixtime_end ){
						$DATE_INSTANCES[] = date("Y-m-d" , $unixtime_instance ) ;
					}
				}
			}
			return $DATE_INSTANCES;
		break;

		case "N":
			// $unixtime_instance = $unixtime_begin
			// while ($unixtime_instance is in range)
				// push $unixtime_instance into instances array 
				// $unixtime_instance = Add n days number of seconds to $unixtime_instance
			//
			$RT_EVERYNDAYS = (int)$inputInfo['RT_EVERYNDAYS'];
			$unixtime_instance = $unixtime_begin ;
			while( $unixtime_begin < $unixtime_instance && $unixtime_instance < $unixtime_end ){
				$DATE_INSTANCES[] = date("Y-m-d" , $unixtime_instance ) ;
				$unixtime_instance = $unixtime_instance + ( (24 * 60 * 60) * $RT_EVERYNDAYS);
			}
			return $DATE_INSTANCES ;
		break;
	}
} // End of get_DateInstances_inTimeRange



?>
