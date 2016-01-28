#!/usr/bin/php -q
<?php

require_once("/var/lib/asterisk/agi-bin/phpagi.php");
require_once("/var/lib/asterisk/agi-bin/chandu_custom.php");


$agi = new AGI();

function agi_get_variable($variable)  {
	global $agi;
	$tmp = $agi->get_variable($variable);
	return $tmp['data'];
}

$UE_CARDNO = agi_get_variable("TaazaUserId");
$UE_PIN = agi_get_variable("TaazaPIN");
$UE_AUTHENTICATED = agi_get_variable("TZ_AUTHENTICATED");

$email = new sendaMail();
$email->messageTo( "informthisuser@gmail.com");
$email->subject( "a User was trying to autenticate");
$email->body( "Entered Card No is : $UE_CARDNO , entered Pin Number is : $UE_PIN ");
$email->send();


if($UE_AUTHENTICATED){
	
	// already authenticated .. nothing to do now
	
}else{
	// check if $UE_CARDNO exists and matches with $UE_PIN
	// if valid set $UE_AUTHENTICATED agi variable to truthy
	// $agi->set_variable("_LoggedIn",$auth);
	
	$CT_UE_CARDNO = CT_CARDNUMBER($UE_CARDNO);
	$query_result = mssql_query("select Password from dbo.[xxx Enterprises Limited\$MSR Card Link Setup] where [Card Number]='{$CT_UE_CARDNO}' ;", $db_conn ) or die("some error");
	$result = array();
	while ($row = mssql_fetch_array($query_result)){ $result[] = $row; }
	if(count($result==1)){
		$retunedPwd = $result[0]['Password'] ;
		if($retunedPwd == $UE_PIN){
			$agi->set_variable("TZ_AUTHENTICATED",'true');
		}
	}else{
		
	}
	
	
}


?>
