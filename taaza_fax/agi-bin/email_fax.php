#!/usr/bin/php -q
<?php
require_once("/var/lib/asterisk/agi-bin/phpagi.php");
require_once("/var/lib/asterisk/agi-bin/chandu_custom.php");

/*
$agi->set_variable("_LoggedIn",$auth);
$agi->verbose("Missing list");
exit(1);
*/

$agi = new AGI();

function agi_get_variable($variable)  {
	global $agi;
	$tmp = $agi->get_variable($variable);
	return $tmp[data];
}


$THISFAXFILENAME = "/recvd_faxes/".agi_get_variable("THISFAXFILENAME") . ".pdf" ;
$FAX_FROM = agi_get_variable("CallerIDString"); 

	$email = new sendaMail();
	$email->messageTo( "informthisuser@gmail.com");
	$email->subject( "You should be reeceiving new fax in a minute");
	$email->body( "You have received a new fax from : $FAX_FROM , FileName: $THISFAXFILENAME ");
	$email->send();

	// email this_faxfilename
	$email = new sendaMail();
	$email->messageTo( "informthisuser@gmail.com");
	$email->subject( "New Fax from $FAX_FROM" );
	$email->body( "You have received a new fax from : $FAX_FROM " );
	$email->AddAttachment($THISFAXFILENAME); // Optional
	$email->send();

?>
