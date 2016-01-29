<?php

include_once "include_db.php";
include_once "include_functions.php";

$target_path = "./attachments/".$_SESSION["subdomain"]."/" ;
$sqlquery= "select Id as attach_id, workid, diskfilename, uploadname from attachments";
$query = mysql_query($sqlquery) or die("Invalid query: " . mysql_error()); 

WHILE ( $row = @mysql_fetch_array($query) ){
	extract($row);  // $attach_id, $workid, $diskfilename, $uploadname
	$file = $target_path.$diskfilename ;
	$fp      = fopen($file , 'r');
	$fp_size = filesize( $file );
	$filecontent = bin2hex(fread($fp, $fp_size));

	execute_sqlUpdate(
		"attachments" ,
		array( filesize => $fp_size , filecontent => $filecontent ) ,
		array( Id => $attach_id )
	);

	echo " inserted file $uploadname <BR>";
}




?>