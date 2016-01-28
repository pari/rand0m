<?php
/*
Simple IPN processing script
based on code from the "PHP Toolkit" provided by PayPal
*/


// https://www.sandbox.paypal.com/cgi-bin/webscr?
// https://www.paypal.com/cgi-bin/webscr


$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';
$postdata = '';
foreach($_POST as $i => $v) {
	$postdata .= $i.'='.urlencode($v).'&';
}
$postdata .= 'cmd=_notify-validate';

$web = parse_url($url);
if ($web['scheme'] == 'https') { 
	$web['port'] = 443;  
	$ssl = 'ssl://'; 
} else { 
	$web['port'] = 80;
	$ssl = ''; 
}
$fp = @fsockopen($ssl.$web['host'], $web['port'], $errnum, $errstr, 30);



if (!$fp) { 
	echo $errnum.': '.$errstr;
} else {
	fputs($fp, "POST ".$web['path']." HTTP/1.1\r\n");
	fputs($fp, "Host: ".$web['host']."\r\n");
	fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
	fputs($fp, "Content-length: ".strlen($postdata)."\r\n");
	fputs($fp, "Connection: close\r\n\r\n");
	fputs($fp, $postdata . "\r\n\r\n");

	while(!feof($fp)) { 
		$info[] = @fgets($fp, 1024); 
	}
	fclose($fp);
	
	$info = implode(',', $info);
	
	$postInfo = "";
	foreach($_POST as $postkey => $postval ){
		$postInfo .= "{$postkey}::{$postval}\n" ;
	}
	$info .= "\n******\n{$postInfo}";
	
	file_put_contents("/tmp/ipn_output.txt", $info);

	if (eregi('VERIFIED', $info)) { 
		// yes valid, f.e. change payment status  
	} else {
		// invalid, log error or something
	}
}




?>