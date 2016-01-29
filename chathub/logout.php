<?php
include_once "include_db.php";


if( $_SESSION["uname"] || $_SESSION["empl_id"]){

	$CURRENT_USERID = $_SESSION["empl_id"];
	include_once "include_functions.php";
	$MU = new ManageUsers();
	$MU->userId = $CURRENT_USERID ;
	$MU->Logout_fromAllRooms();
	
	session_unset();
	session_destroy();
}

header('Location: index.php');
exit();

?>