<?php
// script for getting wait times from various airports
// Copyrights 2009, Pari Nannapaneni <paripurnachand@gmail.com>

//$AIRPORTS = array('JFK', 'HSV');
//$WEEKDAYS = array('sunday', 'monday',  'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');

set_time_limit(0);

$host = "localhost";
$db_user = "root";
$db_pwd = "cccc";
$db_name = "airporttimes";


$cn = mysql_connect($host,$db_user,$db_pwd) or die("Unable to connect to database");
mysql_select_db($db_name,$cn);

//$someresult_tmp = mysql_query("update temptable set variablevalue='$airport_id' where variablename='lastparsedairportid' ");
$sql_lastparsedairportid = "SELECT variablevalue FROM temptable WHERE variablename='lastparsedairportid'";
$lastparsedairportid = @mysql_result(mysql_query($sql_lastparsedairportid),0,'variablevalue');

$sql_airports = "SELECT airport_code FROM airport Where airport_id > $lastparsedairportid ";
$qry_airports = mysql_query($sql_airports);
while($res_airports = mysql_fetch_array($qry_airports))
{
	$AIRPORTS[] = $res_airports['airport_code'];
}


$WEEKDAYS = array('sunday', 'monday',  'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
$WEEKDAYS_IDS = array('sunday'=>1, 'monday'=>2,  'tuesday'=>3, 'wednesday'=>4, 'thursday'=>5, 'friday'=>6, 'saturday'=>7);

// --------------------------------------------------------------------------------------
require_once('simpletest/browser.php');

function is_el_empty($var){
	return (trim($var)) ? true : false;
};



foreach( $AIRPORTS as $airport ){
	
	$sql_airport_id = "SELECT airport_id FROM airport WHERE airport_code='{$airport}'";
	$airport_id = @mysql_result(mysql_query($sql_airport_id),0,'airport_id');
	
	foreach($WEEKDAYS as $weekday){
		echo "Now Parsing: $airport - $weekday \n";
		for($i=0; $i<24 ; $i++){
			$browser = &new SimpleBrowser();
			$browser->get('http://mobile.travelocity.com/waittime/waittimes.jsp');
			$browser->setField('airportCode', $airport );
			$browser->setField('dayOfWeek', $weekday );
			$browser->setField('time', $i );
			$browser->click('Lookup');
			$output = $browser->getContent();
			unset($browser);
			//echo $output; 
			
			$pos = strpos($output,'<div id="error">');
			
			if($pos == false){
				$result = split('<div class="alt">',$output);
				$mystring = split('<div id="footer">',$result[1]);
				$mytext ='<div class="alt">'.$mystring[0];
				$gate_times = split('<hr/>' , $mytext);
				foreach($gate_times as $tmpstr){
					$thisgatestr =  strip_tags($tmpstr);
					$pos = strpos($thisgatestr, 'New search');
					
					if ($pos === false) {
						$twoline_withblanks = split("\n", $thisgatestr);
						$twoline = array_merge(array(), array_filter($twoline_withblanks, "is_el_empty")); ;
						$GATE = trim(str_replace('Gate:', '', $twoline[0]));
						$AVG_MAX = split("/" ,str_replace(array('Avg:', 'Max:', '(Minutes)'), '', $twoline[1]));
						$AVG = trim($AVG_MAX[0]);
						$MAX = trim($AVG_MAX[1]);
						
						/*echo $GATE ."\n" ;
						echo $AVG ."\n" ;
						echo $MAX ."\n" ;
						echo "--\n";*/
						$sql_get_gate = "SELECT gate_id FROM airportgates WHERE gate='{$GATE}'";
						$gate_id = @mysql_result(mysql_query($sql_get_gate),0,'gate_id');
						
						if($gate_id){
							$id = $gate_id;
						}else{
							$sql_gate = "INSERT INTO airportgates (gate) values('{$GATE}')";
							@mysql_query($sql_gate);
							$id = @mysql_insert_id();
						}
						
						// $sql_day_id = "SELECT day_id FROM dayofweek WHERE day='{$weekday}'";
						// $day_id = @mysql_result(mysql_query($sql_day_id),0,'day_id');
						$day_id = $WEEKDAYS_IDS[$weekday];
						
						
						$already_exists = @mysql_result(mysql_query("select count(*) as entryalreadyexists from airportsecuritywaittimes where airport_id='$airport_id' and gate_id='$id' and day_id='$day_id' and time_id='$i' "),0,'entryalreadyexists');
						
						if($already_exists){

						}else{
							$sql_waittime = "INSERT INTO airportsecuritywaittimes (airport_id,gate_id,day_id,time_id,avg_wait_time,max_wait_time) values('{$airport_id}','{$id}','{$day_id}','{$i}','{$AVG}','{$MAX}')";
						@mysql_query($sql_waittime);
						}
					}//if
		
				} //for each
			}//if pos!=false
			//exit();
			//sleep(1); // give the poor server a break before making the next request
		}
	}
	
	$someresult_tmp = mysql_query("update temptable set variablevalue='$airport_id' where variablename='lastparsedairportid' ");
}


?>