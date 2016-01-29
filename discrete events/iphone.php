<?php
if( !isset($_SERVER['PHP_AUTH_USER']) ){
	Header("WWW-Authenticate: Basic realm=\"You must Log In!\"");
	Header("HTTP/1.0 401 Unauthorized");
	exit;
}

include_once "include_db.php";
include_once "include_functions.php";

$USERNAME = $_SERVER["PHP_AUTH_USER"];
$USERPASS = $_SERVER["PHP_AUTH_PW"];

if ( authenticateUser( $USERNAME, $USERPASS ) ){
	$_SESSION["uname"] = $USERNAME ;
}else{
	echo "Invalid Username or Password!";
	unset($_SERVER);
	exit();
}


$GLOBAL_VAR_SUBDOMAIN = $_SESSION["subdomain"] ;
$GLOBAL_VAR_SUBDOMAINUSER = $_SESSION["uname"] ;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>
		<?php 
			$CLIENTNAME = getVariableFromMasterSubdomainRow('clientName');
			echo $CLIENTNAME." - ".APPNAME;
//	<meta name="apple-touch-fullscreen" content="YES" />
		?>
	</title>
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
	<link rel="apple-touch-icon" href="./iui/de-logo-iphone.png" />
	<style type="text/css" media="screen">@import "./iui/iui.css";</style>
	<script type="application/x-javascript" src="./iui/iui.js"></script>
</head>
<body>
    <div class="toolbar">
        <h1 id="pageTitle"></h1>
        <a id="backButton" class="button" href="#"></a>
    </div>

	<ul id="home" title="Home" selected="true">
        <li><a href="#maintasks">Tasks</a></li>
		<li><a href="#scheduledtasks">Scheduled Tasks</a></li>
		<li><a href="#comments">Comments</a></li>
	</ul>

		<ul id="maintasks" title="Tasks">
			<li><a href="#maintasks_newtasks">New Tasks</a></li>
			<li><a href="#maintasks_inprogresstasks">In Progress</a></li>
			<li><a href="#maintasks_completed">Completed</a></li>
		</ul>

		<ul id="scheduledtasks" title="Scheduled">
			<li><a href='#scheduled_tasks'>Scheduled On Date</a></li>
		</ul>

	<?php
		$ureport = new taskReports();
		$ureport->showOnlyMyTasks = false;
		//$ureport->PersonalTasks = false;
		$ureport->doNotIncludePersonalCondition = true;
		$ureport->listWorks_iPhone($DE_GLOBALS_WORK_NEW, 'maintasks_newtasks', 'New' );
		$ureport->listWorks_iPhone($DE_GLOBALS_WORK_PROGRESS, 'maintasks_inprogresstasks', 'InProgress');
		$ureport->listWorks_iPhone($DE_GLOBALS_WORK_COMPLETED, 'maintasks_completed', 'Completed');

//		$ureport_personal = new taskReports();
//		$ureport_personal->showOnlyMyTasks = false;
//		$ureport_personal->PersonalTasks = true;
//		$ureport_personal->listWorks_iPhone($DE_GLOBALS_WORK_NEW, 'personal_newtasks', 'New' );
//		$ureport_personal->listWorks_iPhone($DE_GLOBALS_WORK_PROGRESS, 'personal_inprogresstasks', 'InProgress');
//		$ureport_personal->listWorks_iPhone($DE_GLOBALS_WORK_COMPLETED, 'personal_completed', 'Completed');

		$ureport_scheduled = new taskReports();

		$ureport_scheduled->showOnlyMyTasks = false;
		$ureport_scheduled->listWorks_iPhone($DE_GLOBALS_WORK_SCHEDULED, 'scheduled_tasks', 'Schld OnDate' );
		// * TODO * global $DE_GLOBALS_WORK_TASKONTASK;

		$manageUsers = new manageUsers();
		$manageUsers->getUnreadCommentsiPhone($_SESSION["uname"]);

	?>
</body>
</html>