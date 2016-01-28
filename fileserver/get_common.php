<?php

/*

$output = file_get_contents("http://localhost/fs/getfile.php?appName=MYAPPNAME&appKey=MYAPPKEY&fileToken=MYFILETOKEN");
$output = json_decode($output, true);
print_r($output);


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


echo base64_decode( $output['filedata']['file_b64_content'] );


*/

include_once "config.php" ;

$file_appName 	= $_GET['appName'];
$file_appKey 	= $_GET['appKey'];
$file_givenToken = $_GET['fileToken'];

// check whether appKey matches with actual appKey of this appName
// fetch row with this token from table tbl_files
// if this file does not exists on hard disk then echo error
// read this file , add this file content as base64_encoded to fetched row and echo


$actual_appkey = $PMWRAP->exequery_return_single_val("select app_key from tbl_apps where app_name ='{$file_appName}' ");

if( $actual_appkey !== $file_appKey ){
	echo json_encode(
		array(
			'result' => 'fail',
			'msg' => 'Invalid Appname or Appkey' ,
			'filedata' => array()
		)
	);
	exit();
}


$fetched_row = $PMWRAP->exequery_return_single_row_as_AssocArray("SELECT * FROM tbl_files WHERE accesstoken = '{$file_givenToken}' ");

if(! count($fetched_row) ) {

	echo json_encode(
		array(
			'result' => 'fail',
			'msg' => 'File not found with this token' ,
			'filedata' => array()
		)
	);
	exit();

}



$file_total_path = $fetched_row['file_diskFolder']."/".$fetched_row['file_diskName'];

if(!file_exists($file_total_path) ) {

	echo json_encode(
		array(
			'result' => 'fail',
			'msg' => 'File missing on media' ,
			'filedata' => array()
		)
	);
	exit();

}

?>