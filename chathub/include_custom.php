<?php


function send_Action_Response($RESP , $MSG){ echo "Response: ".$RESP."\n" ; echo "ResponseMessage: ".$MSG ; exit(); }


function isValidEmail($email){
	$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
	return eregi($pattern, $email);
}


function noscript_warning(){ ?>
	<noscript>
	  <div style='margin: 50px; font-size: 110%;'>
	      <h1>You need to change a setting in your web browser</h1>
	      <p>This application requires a browser feature called <strong>JavaScript</strong>. All modern browsers support JavaScript. You probably just need to change a setting in order to turn it on.</p>
	      <p>Please see: <a href="http://www.google.com/support/bin/answer.py?answer=23852">How to enable JavaScript in your browser</a>.</p>
	      <p>If you use ad-blocking software, it may require you to allow JavaScript from <?php echo APPURL ;?>.</p>
	      <p>Once you've enabled JavaScript you can <a href="">try loading this page again</a>.</p>
	      <p>Thank you.</p>
	  </div>
	</noscript>
<?php
}


function num_to_month($mon){
	$mon = (int)$mon ;
	$mon = (string)$mon;
	
	$tens_sp = array( '1' => 'January' , '2'=>'February', '3'=> 'March', '4'=> 'April', 
		'5'=> 'May' , '6'=> 'June', '7' => 'July' , '8'=> 'August' , '9'=> 'September' ,
		'10'=> 'October', '11' => 'November' , '12' => 'December' );

	return $tens_sp[ $mon ];
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





function getCaldate_Of_n_SecondsFrom_TimeX ($n=1 , $X){
	// $X is a PHP timestamp, $n could be a positive or negative diff of seconds
	// returns timestamp of ( $x with difference of $n seconds ) in PHP timestamp format
	list($tmp_datepart , $tmp_timepart) = explode(" ", $X);
	list($stYear, $stMonth, $stDay) = explode( "-", $tmp_datepart );
	list($stHr, $stMin, $stSec) = explode( ":", $tmp_timepart );
	$X_Unixstamp = @mktime( $stHr, $stMin, $stSec, $stMonth, $stDay, $stYear );
	return @date("Y-m-d H:i:s" , $X_Unixstamp + $n );
}









function strBetweenXY( $inputStr, $delimeterLeft, $delimeterRight ){
    $posLeft=strpos($inputStr, $delimeterLeft);
    if ( $posLeft===false ){ return $inputStr; }
    $posLeft+=strlen($delimeterLeft);
    $posRight=strpos($inputStr, $delimeterRight, $posLeft);
    if ( $posRight===false ){ return $inputStr; }
    return substr($inputStr, $posLeft, $posRight-$posLeft);
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







function caldateTS_to_humanWithTS( $caldatets , $shorthour=false){ // $caldatets = 'YYYY-mm-dd hr:m:s'
	
	if( !$caldatets || $caldatets == '0000-00-00 00:00:00'){ return '-'; }
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
	list($year, $month, $day) = explode("-", $caldate);
	list( $todayYear, $todayMonth, $todayDay ) = explode("-", date("Y-m-d") , @mktime (0,0,0,$month,$day,$year));
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
        // array(60 * 60 * 24 * 365 , 'Yr'),
        // array(60 * 60 * 24 * 30 , 'Month'),
        // array(60 * 60 * 24 * 7, 'Week'),
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
	
	if($i >= 2){ // 5 when Yr , Month & Week are enabled
		if($count <= 5){
			$print = '5 Mins' ;
		}elseif($count > 5 && $count <= 12 ){
			$print = '10 Mins';
		}elseif($count > 12 && $count <= 17 ){
			$print = '15 Mins';
		}elseif($count > 17 && $count <= 24 ){
			$print = '20 Mins';
		}elseif($count >= 25 && $count <= 35 ){
			$print = '30 Mins';
		}elseif($count >= 36 && $count <= 42 ){
			$print = '40 Mins';
		}elseif($count >= 43 && $count <= 50 ){
			$print = '45 Mins';
		}elseif($count > 50 && $count <=59 ){
			$print = '1 Hr';
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
			//$print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
		}
	}
    return $print;
}







function get_GET_var($varname){
	$t = '';
	if( @$_GET[$varname] || @$_GET[$varname] == '0' ){
		$t = $_GET[$varname] ;
	}
	return mysql_real_escape_string($t);
}

function get_POST_var($varname){
	$t = '';
	if( @$_POST[$varname] ){
		$t = $_POST[$varname] ;
	}else{
		if(@$_POST[$varname] == '0'){
			return '0';
		}
		return '';
	}
	return mysql_real_escape_string($t);
}


function execute_sqlQuery($someQuery) {
	global $DEVELOPMENT_MODE;
	global $GLOBAL_STRICT_NOLOGGING_QUERIES;
	if($DEVELOPMENT_MODE){
		$should_Log = !$GLOBAL_STRICT_NOLOGGING_QUERIES;
	}else{
		if($GLOBAL_STRICT_NOLOGGING_QUERIES){
			$should_Log = false;
		}else{
			global $GLOBAL_LOG_QUERIES ; // is this an actions.php like page with update queuries
			if($GLOBAL_LOG_QUERIES){ // even in actions.php ignore logging select queries
				$should_Log	= strtolower(substr(trim($someQuery), 0, 6)) != 'select';
			}else{
				$should_Log = false;
			}
		}
	}
	
	if($should_Log){
		$ts = get_currentPHPTimestamp();
		$username = $_SESSION["uname"];
		global $GLOBAL_requestid;
		$r = mysql_query("insert into `whm_logs`.`queryLog` ( `querystring`, `queryuser`, `querytime`, `requestid`) values ( '".mysql_real_escape_string($someQuery)."', '$username', '$ts', '$GLOBAL_requestid')");		
	}

	return mysql_query($someQuery);
}



function execute_sqlInsert($my_table, $my_array , $doLog = false ) {
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
	if( $doLog == true ){
		echo $sql; exit();
	}
	
	$result = @execute_sqlQuery($sql) OR die ("ERROR #ESI001"); // for debugging : die ("ERROR #ESI001 : $sql")
   return ($result) ? true : false;
}



function execute_sqlUpdate($my_table, $update_array, $where_array, $doLog = false ) {
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
	if( $doLog == true ){
		echo $sql; exit();
	}
	$result = @execute_sqlQuery($sql) OR die ("ERROR #ESU001") ;

   return ($result) ? true : false;
}


function executesql_returnMultiArray($sqlquery){
	$result = execute_sqlQuery($sqlquery);
	$TORETURN = array();
	while( $row = mysql_fetch_assoc($result) ) {
		$TORETURN[] = $row;
	}
	return $TORETURN;
}



function executesql_returnAssocArray($sqlquery){
	// use this function to get a single row of values
	$result = execute_sqlQuery($sqlquery);
	IF (@mysql_num_rows($result)!=1){
		return NULL;
	}
	return mysql_fetch_assoc($result); 
}


function executesql_returnKeyValPairs($sqlquery){
	$result = execute_sqlQuery($sqlquery);
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



function executesql_returnArray($sqlquery){
	// use this function to get one value or one column values as array
	$vars = array();
	$result = execute_sqlQuery($sqlquery);
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
	$result = execute_sqlQuery($sqlquery);
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
	// $email->messageTo( "chandu@example.org" );
	// $email->subject( "Some Subject" );
	// $email->asFrom( "alien007@outerspace.com" );
	// $email->body( "Some Body" );
	// $email->AddAttachment( $filename ); // Optional
	// $email->send();

	var $Attachments = array();
	var $AttachmentNames = array();
	var $CCs = array();

	public function messageTo( $to ){
		$this->mailto = $to ;
	}

	public function CC( $to ){
		$this->CCs[] = $to ;
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

	public function asFrom( $asFrom ){
		$this->asFrom = $asFrom ;
	}
	
	public function asFromName($asFromName){
		$this->asFromName = $asFromName ;
	}

    public function send() {
		try{
			if(!$this->textmsg){
				$this->textmsg = ' ';
			}
			if(!$this->htmlmsg){
				$this->htmlmsg = ' ';
			}
			require_once( APP_INSTALLPATH . 'phpmailer/class.phpmailer.php' );
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPAuth   = true;
			$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
			$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
			$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
			$mail->Username   = YOUR_GOOGLEAPP_EMAILID ;  // GMAIL username
			$mail->Password   = YOUR_GOOGLEAPP_EMAILID_PASS ;            // GMAIL password
			$FROMNAME = ($this->asFromName) ? "[FreeTalk] ".$this->asFromName : APPNAME;
			if( $this->asFrom ){
				$mail->SetFrom( YOUR_GOOGLEAPP_EMAILID, $FROMNAME );
				//$mail->SetFrom($this->asFrom, $this->asFrom);
				$mail->AddReplyTo($this->asFrom, $this->asFrom);
			}else{
				$mail->SetFrom( YOUR_GOOGLEAPP_EMAILID , $FROMNAME );
				//$mail->AddReplyTo('noreply@'.MAINDOMAIN, APPNAME );
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

			$mail->AddAddress( $this->mailto );
			$mail->Subject = $this->subject ;
			$mail->AltBody = $this->textmsg ;
			$mail->MsgHTML( $this->htmlmsg );
			if( $this->mailto != SUPERADMIN_EMAIL ){
				$mail->AddBCC( SUPERADMIN_EMAIL );
			}
			if( count($this->CCs) ){
				foreach($this->CCs as $this_cc){
					$mail->AddCC( $this_cc );
				}
			}
			$mail->Send();
		}catch (phpmailerException $e) {
			echo $e->errorMessage();
		}catch (Exception $e) {
			echo $e->getMessage();
		}
    }

} // End of 'sendaMail' class













?>