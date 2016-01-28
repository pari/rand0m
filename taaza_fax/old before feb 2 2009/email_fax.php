#!/usr/bin/php -q
<?php
require_once("/var/lib/asterisk/agi-bin/phpagi.php");
require_once("/var/lib/asterisk/agi-bin/chandu_custom.php");

$agi = new AGI();
$THISFAXFILENAME = "/recvd_faxes/".agi_get_variable("THISFAXFILENAME") . ".pdf" ;
$FAX_FROM = agi_get_variable("CallerIDString") 

	// email this_faxfilename
	$email = new sendaMail();
	$email->messageTo( "paripurnachand@gmail.com" );
	$email->subject( "New Fax from $FAX_FROM" );
	$email->body( "You have received a new fax from : $FAX_FROM " );
	$email->AddAttachment( $THISFAXFILENAME ); // Optional
	$email->send();


?>
