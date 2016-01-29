<?php

include ("../include_variables.php");
include ("../include_functions.php") ;

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 

$ACTION = $_POST["action"] ;
switch( $ACTION ){

	case 'createNewClient':
		$fullname = @$_POST["fullName"] ;
		$subDomain = @$_POST["subDomain"] ;
		$packageid = @$_POST["packageid"] ;
		$adminEmail = @$_POST["adminEmail"] ;
		$adminPass = @$_POST["adminPass"] ;
		createNewClientAccount($fullname, $subDomain, $packageid, $adminEmail, $adminPass );
		send_Action_Response('Success' , 'new Client Created');
		exit();
	break;

	case 'deleteClient' :
		$subdomain = @$_POST["subdomain"] ;
		deleteClientAccount( $subdomain );
		send_Action_Response('Success' , 'deleted');
		exit();
	break;

	case 'suspendActivate' :
		$subdomain = @$_POST["subdomain"] ;
		changeClientStatus( $subdomain );
		send_Action_Response('Success' , 'updated');
		exit();
	break;

	default:
	break;

}


?>