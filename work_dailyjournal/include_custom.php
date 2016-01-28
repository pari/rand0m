<?php

/*
	Custom PHP functions for CenterLimit Applications
	Copyrights 2009 CenterLimit
	version 1.0.1
*/

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

	$words = str_word_count($sentence, 1, 'ã') ; // explode (' ', $sentence) is acting wiered
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





function caldateTS_to_humanWithOutTS( $caldatets ){ // $caldatets = 'YYYY-mm-dd hr:m:s'
	if(!$caldatets){ return ''; }
	$pieces = explode(" ", $caldatets);
	$tmp_datepart = $pieces[0] ;
	$tmp_timepart = $pieces[1] ;
	return caldate_to_human( $tmp_datepart );
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
	$monthstr = ($shortmonth) ? 'M j' : 'M jS' ;

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
	$result = @mysql_query ($sql) OR die ("ERROR #ESI001"); // for debugging : die ("ERROR #ESI001 : $sql")
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
	$result = @mysql_query($sql) OR die ("ERROR #ESU001") ;

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

	public function AddBCC( $bccemailid ){
		//if( $bccemailid != SUPERADMIN_EMAIL ){
		//	$this->BCCS[] = $bccemailid ;
		//}
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
		require_once( 'phpmailer/class.phpmailer.php' );
		
		$mail = new PHPMailer();
		//$mail->IsSendmail();
		$mail->IsSMTP();
		$mail->Host       = "localhost" ;
		$mail->SMTPAuth   = false;
		//$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
		//$mail->Username   = SMTP_USERNAME ; // SMTP account username
		//$mail->Password   = SMTP_PASSWORD ;        // SMTP account password
		if( $this->asFrom ){
			$mail->SetFrom($this->asFrom, $this->asFrom);
			$mail->AddReplyTo($this->asFrom, $this->asFrom);
		}else{
			$mail->AddReplyTo('noreply@cigniti.chandu.org', 'Daily Journal' );
			$mail->SetFrom( 'noreply@cigniti.chandu.org' , 'Daily Journal' );
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

		$mail->AddAddress( $this->mailto );
		$mail->Subject = $this->subject ;
		$mail->AltBody = $this->textmsg ;
		$mail->MsgHTML( $this->htmlmsg );
		
		//if( $this->mailto != SUPERADMIN_EMAIL ){
		//	$mail->AddBCC( SUPERADMIN_EMAIL );
		//}

		$mail->Send();
		}catch (phpmailerException $e) {
			echo $e->errorMessage();
		}catch (Exception $e) {
			echo $e->getMessage();
		}
    }

} // End of 'sendaMail' class




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






function html_array2selectboxOptions_selected( $somearray, $selected , $asKey = false ){
	if( $asKey ){
		foreach( $somearray as $key => $value ){
			if( $selected == $key ){
				echo "<option value='{$key}' selected>{$value}</option>";
			}else{
				echo "<option value='{$key}'>{$value}</option>";
			}
		}
	}else{
		for ($i=0; $i < count( $somearray ) ; $i++) { 
			if( $selected == $somearray[$i] ){
				echo "<option value='". $somearray[$i] ."' selected>". $somearray[ $i ] . "</option>";
			}else{
				echo "<option value='". $somearray[$i] ."'>". $somearray[ $i ] . "</option>";
			}
		}
	}
}


function html_array2selectboxOptions( $somearray , $asKey = false){
	if( $asKey ){
		foreach ($somearray as $key => $value) {
			echo "<option value='{$key}'>{$value}</option>";
		}
	}else{
		for ($i=0; $i < count( $somearray ) ; $i++) { 
			echo "<option value='". $somearray[$i] ."'>". $somearray[ $i ] . "</option>";
		}
	}
}


function html_array2selectbox( $somearray , $someid , $asKey = false){
	echo "<select id='" . $someid . "' name='" . $someid . "'>";
	html_array2selectboxOptions( $somearray , $asKey);
	echo "</select>";
}


function html_query2selectbox($query, $domid){
	// when you want to make a select box out of one or two columns from a table in the database
	html_array2selectbox( executesql_returnKeyValPairs($query) , $domid , true) ;
}

function html_query2selectbox_selectedValue($query, $domid , $selected ){
	echo "<select id='" . $domid . "' name='" . $domid . "'>";
	html_array2selectboxOptions_selected( executesql_returnKeyValPairs($query), $selected , true );
	echo "</select>";
}


function executesql_returnKeyValPairs($sqlquery){
	$result = mysql_query($sqlquery);
	$TORETURN = array();
	while( $row = mysql_fetch_array($result, MYSQL_NUM) ) {
		if( count($row) == 2 ){
			$TORETURN[$row[0]] = $row[1] ;
		}else if( count($row) == 1 ){
			$TORETURN[$row[0]] = $row[0] ;
		}else{
			echo "Un Intended use of executesql_returnKeyValPairs() count is ".count($row)."- select one or two coloumns only";
			// print_r($row);
		}
	}
	return $TORETURN;
}
?>