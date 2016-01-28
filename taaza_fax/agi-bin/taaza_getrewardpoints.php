#!/usr/bin/php -q
<?php

require_once("/var/lib/asterisk/agi-bin/phpagi.php");
require_once("/var/lib/asterisk/agi-bin/chandu_custom.php");


$agi = new AGI();

function agi_get_variable($variable)  {
	global $agi;
	$tmp = $agi->get_variable($variable);
	return $tmp[data];
}

$UE_CARDNO = agi_get_variable("TaazaUserId");
//$UE_PIN = agi_get_variable("TaazaPIN");
$UE_AUTHENTICATED = agi_get_variable("TZ_AUTHENTICATED");

if($UE_AUTHENTICATED == 'true'){
	$CT_UE_CARDNO = CT_CARDNUMBER($UE_CARDNO);
	$query_result = mssql_query("select Sum([Transaction Points]) from dbo.[xxx Enterprises Limited\$Loyalty Points Transactions] where [Card No_]='{$CT_UE_CARDNO}' ;", $db_conn ) or die("some error");
	$result = array();
	while ($row = mssql_fetch_array($query_result)){ $result[] = $row; }
	if(count($result==1)){
		$rewardPoints = $result[0]['computed'] ;
		$agi->set_variable("TZ_REWARDPOINTS",$rewardPoints);
	}else{
		
	}
	
}else{
	// NOT authenticated .. you should not have reached here
}


?>
