<?php
session_start();

include_once "fs_client_apps_config.php" ;

$file_token = $_GET['fileToken'];
$fs_appName = $_GET['appName'];
$gfffs_vk = $_GET['gfffs_vk'] ;
$fs_appkey = $FILESERVER_CONFIGURATION['MYAPPS'][$fs_appName] ;

$genrated_gfffs_vk = md5( $file_token.$_SESSION['uname'] );

if( ($genrated_gfffs_vk !== $gfffs_vk ) ||  $gfffs_vk == '' ){
	echo "Invalid user key or token "; 
	exit();
}

// get file from fileserver and then server it to user broser 
$file_info = file_get_contents("{$FILESERVER_CONFIGURATION['BASEURL']}getfile.php?appName={$fs_appName}&appKey={$fs_appkey}&fileToken={$file_token}");
$file_info = json_decode( $file_info , true );
/*
print_r($file_info);
Array
(
    [result] => success
    [msg] => success
    [filedata] => Array
        (
            [fid] => 1
            [file_app_name] => whm_invoices
            [file_diskName] => hg8fpm1M3dpcMlFyicaARK4HoXi5ioIF.php
            [file_diskFolder] => /fileserver/plutokmVendorInvoices/02_2013
            [accesstoken] => c4ca4238a0b923820dcc509a6f75849b
            [fileSize] => 37815
            [fhash] => 0d9a9662a498176148720c4893ef58f9
            [originalFileName] => Snoopy.class.php
            [uploadedOn] => 2013-02-19 16:35:11
            [uploadedByAppusername] => CommandPrompt
            [fileType] => php
            [download_count] => 1
            [file_b64_content] => 'some b64 garbage'
        )

)
*/


header('Content-Description: File Transfer');
if( @$_GET['forcedld'] == '1' ){
	header('Content-Type: application/octet-stream'); // image/gif , image/png
}else{
	$file_extension = strtolower( $file_info['filedata']['fileSize'] ) ;
	if( in_array( $file_extension , array( "png", 'gif', 'jpg', 'jpeg', 'pdf' ) ) ){
		switch ( $file_extension ) {
			case "png": 
			case "gif":
				header('Content-Type: image/'.$file_extension ); 
				break;
			case "jpg": 
			case "jpeg": 
				header('Content-Type: image/jpeg'); 
			break;
			case "pdf": 
				header( 'Content-Type: application/pdf' ); 
			break;
		}
	}else{
		header('Content-Type: application/octet-stream');
	}
}


header("Content-Length: ".$file_info['filedata']['fileSize']);
header('Content-Disposition: attachment; filename="'.$file_info['filedata']['originalFileName'].'"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
echo base64_decode($file_info['filedata']['file_b64_content']) ;
exit();

?>