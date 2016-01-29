<?php

include_once "include_db.php";
include_once "include_functions.php";
checkUserSessionandCookie();

$username = $_SESSION["uname"];
$workid = @$_POST["fileupload_workid"];
$uploadname = basename( $_FILES['uploadedfile']['name'] ) ;
$ruri = @$_POST["fileupload_requestURI"] ;
$tmp_uploadedOn = get_currentPHPTimestamp();

$tmp = getDirectorySize($target_path);
	$currentsize = $tmp['size'] ; 
	$maxallowed = $_SESSION["pkgSpaceMb"] * 1024 * 1024 ; // convert Mb into bytes
	if($currentsize > $maxallowed){
	    echo "<h1>You do not have enough free space for uploading any new files ! </h1>";
		exit();
	}

Task_LogSystemComment($workid, "<B>{$username}</B> has uploaded file '{$uploadname}' {$thisfilesize}" );
logUserEvent( 'Uploaded attachment to '.$workid );

	$fp      = fopen($_FILES['uploadedfile']['tmp_name'], 'r');
	$fp_size = filesize($_FILES['uploadedfile']['tmp_name']);
	$somefile = bin2hex(fread($fp, $fp_size));
	$success = execute_sqlInsert(
		'attachments',
		array( 'workid'=>$workid , 'uploadname'=>$uploadname , 'uploadedby'=>$username, 'filecontent'=>$somefile, 'filesize'=>$fp_size , 'uploadedOn'=>$tmp_uploadedOn )
	);

	header("Location: ".$ruri);
?>