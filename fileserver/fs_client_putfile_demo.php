<?php
session_start();



include_once "fs_client_apps_config.php" ;
//include_once "Snoopy.class.php" ;
$snoopy = new Snoopy;
$submit_url = $FILESERVER_CONFIGURATION['BASEURL']."putfile.php" ;
$submit_vars = array();
$submit_vars["appName"] = "MYAPP1";
$submit_vars["appKey"] = $FILESERVER_CONFIGURATION['MYAPPS'][$submit_vars["appName"]] ;
$submit_vars["fileName"] = "Somefile.txt";
$submit_vars["fileContent"] = base64_encode(file_get_contents( $submit_vars["fileName"] ));
$submit_vars["client_appUsername"] = $_SESSION['uname'];
$snoopy->submit( $submit_url, $submit_vars );
$result = json_decode( $snoopy->results , true ) ;
if( $result['result'] == 'success' ){
	IMPORTANT : insert $result['filetoken'] againt your CNS or invoice 
}



// BELOW IS CODE IGNITER EXAMPLE
include_once "fs_client_apps_config.php" ;
$this->load->library('Snoopy');
$snoopy = new Snoopy;

$submit_vars = array();
$submit_vars["appName"] = "plutohr_storecash";
$submit_vars["appKey"] = $FILESERVER_CONFIGURATION['MYAPPS'][ $submit_vars["appName"] ] ;
$submit_vars["fileName"] = $_FILES['userfile']['name'][0] ;
$submit_vars["fileContent"] = base64_encode(file_get_contents( $_FILES['userfile']['tmp_name'][0] ));
$submit_vars["client_appUsername"] = $this->my_usession->userdata('uname') ;
$submit_url = $FILESERVER_CONFIGURATION['BASEURL']."putfile.php" ;

$snoopy->submit( $submit_url, $submit_vars );
$result = json_decode( $snoopy->results , true ) ;

if( $result['result'] == 'success' ){
	$this->db->insert('hr_exmod_attachments', array('TRID' => $_POST['trid_upload'], 'fileToken' => $result['filetoken'] , 'filename' => $_FILES['userfile']['name'][0]  ));
	$this->my_usession->set_flashdata('notify_bar', 'Attachment uploaded successfully...');
	redirect( $_SERVER['HTTP_REFERER'] );
}else{
	echo "There was an error uploading this file! Try again !"; exit();
}
?>