<?php

// ?appName=SOMEAPPNAME&appKey=SOMEKEY&appFolderName=SOMEFOLDERNAME
include_once "config.php";

$appName 	= $_GET['appName'];
$appKey 	= $_GET['appKey'];
$appFolder 	= $_GET['appFolderName'];

$actual_appkey = $PMWRAP->exequery_return_single_val("select app_key from tbl_apps where app_name ='{$appName}' ");

if($actual_appkey){

	echo "FAIL : Duplicate App Key";
	exit();

}

// insert this get value into table tbl_apps
// create this folder

$PMWRAP->insert_row('tbl_apps' , array(
	'app_name' => $appName,
	'app_key' => $appKey,
	'app_homedir' => $appFolder
	) );

if( ! is_dir( $FS_MAINFOLDER . $appFolder ) ){
	mkdir( $FS_MAINFOLDER . $appFolder );
}

echo "success";

?>