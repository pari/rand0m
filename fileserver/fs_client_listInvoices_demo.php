<?php
// list Invoices.php


$invoicedata = InvoiceDataFrom("select invoiceid, invoiceNumber , invoiceFS_TOken from tblInvoices")
foreach($invoicedata as $this_invoice){
	
	$link = "fs_client_getfile.php?fileToken={$this_invoice['invoiceFS_TOken']}&appName=MYAPP1&forcedld=1&gfffs_vk=".md5( $this_invoice['invoiceFS_TOken'].$_SESSION['uname'] ) ;
	OR
	$link = "fs_client_image.php/".md5($fileToken)."?height=800&filetoken={$fileToken}&appname=plutohr_storecash" ;
		
		
	echo "<A href='{$link}'>{$this_invoice['invoiceNumber']}</A>";
	
}


?>

OR
following code in Code igniter controller methods
<?php

public function get_attachment_links($trid){
	$this_trid_links = chanduHelpers::execute_sql_and_return_multiAssocArray( "select * from hr_exmod_attachments where TRID = '{$trid}' " );
	// $("a.aInv").lightBox();
	$links_str = "" ;
	foreach($this_trid_links as $this_link ){
		if( chanduHelpers::is_file_anImage( $this_link['filename'] ) ){
			$links_str .= "<A rel='facebox' href='#' imagesrc='/storecash/viewAttachmentPreview/{$this_link['fileId']}'><img src='/images/attach.png' width=16 height=16></A>" ;
		}else{
			$links_str .= "<A href='/storecash/downloadAttachment/{$this_link['fileId']}'><img src='/images/Download.png' width=16 height=16></A>" ;
		}
	}
	return $links_str ;
}



public function viewAttachmentPreview( $fileId ){
	$fileToken = chanduHelpers::execute_sql_and_return_single_value( "select fileToken from hr_exmod_attachments where fileId='{$fileId}' " , 'fileToken' );
	$fileToken_md5 = md5($fileToken);
	$thandle = fopen( "http://{$_SERVER['SERVER_NAME']}/fs_client_image.php/{$fileToken_md5}?height=800&filetoken={$fileToken}&appname=plutohr_storecash" , "rb" );
	$tcontents = stream_get_contents( $thandle );
	fclose($thandle);
	echo $tcontents;
}

public function downloadAttachment( $fileId ){
	$fileToken = chanduHelpers::execute_sql_and_return_single_value("select fileToken from hr_exmod_attachments where fileId='{$fileId}' " , 'fileToken' );
	include_once "fs_client_apps_config.php" ;
	$file_token = $fileToken ;
	$fs_appName = 'plutohr_storecash';
	$fs_appkey = $FILESERVER_CONFIGURATION['MYAPPS'][$fs_appName] ;
	// get file from fileserver and then server it to user broser 
	$file_info = file_get_contents("{$FILESERVER_CONFIGURATION['BASEURL']}getfile.php?appName={$fs_appName}&appKey={$fs_appkey}&fileToken={$file_token}");
	$file_info = json_decode( $file_info , true );
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream'); // image/gif , image/png
	header("Content-Length: ".$file_info['filedata']['fileSize']);
	header('Content-Disposition: attachment; filename="'.$file_info['filedata']['originalFileName'].'"');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	echo base64_decode($file_info['filedata']['file_b64_content']) ;
	exit();
}


?>