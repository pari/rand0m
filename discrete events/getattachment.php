<?php

include_once "include_db.php";
include_once "include_functions.php";
checkUserSessionandCookie();
$username = $_SESSION["uname"];
$target_path = "./attachments/".$_SESSION["subdomain"]."/" ;
$attachId = $_GET["attachId"];

$query = mysql_query( "select workid, uploadname, filesize, filecontent from attachments where Id='$attachId'" ) or die("Invalid query: " . mysql_error()); 
IF( @mysql_num_rows($query) == 0 ){ exit();}
WHILE ( $row = @mysql_fetch_array($query) ){ extract($row); } // $workid, $uploadname, filesize, filecontent
if( checkPermissions_canUserViewTask( $username, $workid ) == false ){ exit(); }
	$output = hex2bin($filecontent);
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.$uploadname.'"');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
	header("Content-Length: ".strlen($output));
	echo $output;
	exit();
?>