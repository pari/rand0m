<?php
include_once "include_db.php";
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
include_once "include_functions.php";

if( !$_SESSION["uname"] ){ exit(); }

$ACTION = @$_GET["action"] ;
$USERNAME = $_SESSION["uname"];

switch( $ACTION ) {

	case 'getcomments':

		$taskid = get_GET_var("taskid");
		$manageWorks = new manageWorks();
		$manageWorks->get_workComments($taskid, true);

		exit();
	break;


	default:
	break;
}






?>