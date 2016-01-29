<?php
include_once "include_db.php";
include_once "include_functions.php";

if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

if(!get_GET_var('fc')){
	echo "Invalid usage.";
	exit();
}

$fileId = get_GET_var('fc');
$MF = new ManageFiles();
$FILEINFO = $MF->get_file_Info( $fileId , $_SESSION["empl_id"] );
if(!count($FILEINFO)){
	echo "Invalid file or privilege";
	exit();
}

header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-type: {$FILEINFO['fileType']}");
header("Content-length: {$FILEINFO['fileSize']}");
header("Content-disposition: attachment; filename=\"{$FILEINFO['fileName']}\"");
readfile(UPLOAD_PATH.$FILEINFO['fileRandomName']);
exit;
?>