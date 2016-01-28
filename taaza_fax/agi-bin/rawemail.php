#!/usr/bin/php -q
<?php
require_once("/var/lib/asterisk/agi-bin/phpagi.php");
require_once("/var/lib/asterisk/agi-bin/chandu_custom.php");


	// email this_faxfilename
	$email = new sendaMail();
	$email->messageTo( "informthisuser@gmail.com");
	$email->subject( "New Fax from FAX_FROM" );
	$email->body( "You have received a new fax from : AX_FROM " );
	$email->AddAttachment( '/recvd_faxes/fax_20091213_1608.pdf');//: Optional
	$email->send();
?>
