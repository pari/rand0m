<?php
/*

include_once "Snoopy.class.php" ;
$snoopy = new Snoopy;
$submit_url = "http://192.168.1.102/fs/putfile.php";
$submit_vars["fileName"] = "Somefile.txt";
$submit_vars["fileContent"] = base64_encode(file_get_contents("Somefile.txt"));
$submit_vars["appName"] = "myAppName";
$submit_vars["appKey"] = "myAppKey";
$submit_vars["client_appUsername"] = "someUser";
$snoopy->submit( $submit_url, $submit_vars );
echo $snoopy->results ;

// you have to probably use json_decode( $snoopy->results );

*/


include_once "config.php";

$filename = $_POST["fileName"];
$filecontent_b64 = $_POST["fileContent"];
$file_appName = $_POST['appName'];
$file_appKey = $_POST['appKey'];
$file_uploadedBy = $_POST['client_appUsername']; // or appUserId

$actual_appkey = $PMWRAP->exequery_return_single_val("select app_key from tbl_apps where app_name ='{$file_appName}' ");

if( $actual_appkey !== $file_appKey ){
	echo json_encode(
		array(
			'result' => 'fail',
			'msg' => 'Invalid Appname or Appkey' ,
			'filetoken' => ''
		)
	);
	exit();
}


// make sure there a folder name in MM_YYYY format , if not create it
// write the file to disk
// insert the file in tbl_files
// return accesstoken


$APP_FOLDER = $PMWRAP->exequery_return_single_val("select app_homedir from tbl_apps where app_name ='{$file_appName}' ");


if( ! is_dir( $FS_MAINFOLDER . $APP_FOLDER ) ){
	mkdir( $FS_MAINFOLDER . $APP_FOLDER );
}

$UPload_ToThisFolder = $FS_MAINFOLDER . $APP_FOLDER.'/'.date("m_Y" ) ;

if( !is_dir( $UPload_ToThisFolder ) ){
	mkdir( $UPload_ToThisFolder );
}

$given_file_parts = pathinfo($filename);
$random_filename = generate_newRandomFile() ;

$UPload_ToThisFile = $UPload_ToThisFolder ."/".$random_filename ;	

$fp = fopen( $UPload_ToThisFile , 'w');
fwrite($fp, base64_decode($filecontent_b64) );
fclose($fp);

$fileSize = filesize($UPload_ToThisFile);

$PMWRAP->insert_row('tbl_files' , array(
'file_app_name' => $file_appName ,
'file_diskName' => $random_filename ,
'file_diskFolder' => $UPload_ToThisFolder ,
'fileSize' => $fileSize ,
'fhash' => md5_file( $UPload_ToThisFile ) ,
'originalFileName' => $filename ,
'uploadedOn' => date("Y-m-d H:i:s") ,
'uploadedByAppusername' => $file_uploadedBy ,
'fileType' => $given_file_parts['extension'],
'download_count' => 0
));

$accessToken = md5( $PMWRAP->DB_LAST_INSERT_ID );
$PMWRAP->update_table( 'tbl_files' , array('accesstoken' => $accessToken ) , array('fid' => $PMWRAP->DB_LAST_INSERT_ID ) );


	echo json_encode(
		array(
			'result' => 'success',
			'msg' => 'File Uploaded' ,
			'filetoken' => $accessToken
		)
	);
	
?>