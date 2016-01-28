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

$UE_CARDNO = agi_get_variable("UE_CARDNO");
//$UE_PIN = agi_get_variable("UE_PIN");
$UE_AUTHENTICATED = agi_get_variable("TZ_AUTHENTICATED");


if($UE_AUTHENTICATED){
	$CT_UE_CARDNO =  // Add CT before 6 characters from end
	$newPassword = agi_get_variable("TaazaNEWPIN");
	$query_result = mssql_query("update dbo.[xxx Enterprises Limited\$MSR Card Link Setup] set Password='$newPassword' where [Card Number]='{$CT_UE_CARDNO}' ;", $db_conn ) or die("some error");
}else{
	// NOT authenticated .. you should not have reached here
}


?>
