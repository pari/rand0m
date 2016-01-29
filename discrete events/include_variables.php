<?php

$DEVELOPMENT_MODE = FALSE ; 
$DE_GLOBALS_WORK_SCHEDULED = '0'; // Task will appear in list on a scheduled date
$DE_GLOBALS_WORK_NEW = '1';
$DE_GLOBALS_WORK_PROGRESS = '2';
$DE_GLOBALS_WORK_COMPLETED = '3';
$DE_GLOBALS_WORK_CLOSED = '4';
$DE_GLOBALS_WORK_DELETED = '5'; // Task is deleted
$DE_GLOBALS_WORK_FROMEMAIL = '8'; // Task created from email, waiting for user confirm on login
$DE_GLOBALS_WORK_TASKONTASK = '9'; // Task will appear in list on closing of another task

ini_set('upload_max_filesize', 9000000 );

define("APPNAME", "Discrete Events");
define("APPVERSION", "0.9.1");
define("CPANELUSERNAME", "devents"); // this would be the prefix for all the subdomain databases;

define("DEFAULTPROJECT", "DailyTasks");
define("DEFAULTPERSONALPROJECT", "PersonalTask");
define("DEFAULTCURRENCY", "$");
define("HASBEENRESETAPPENDSTRING","<span style='color:#F89930; font-weight:bold;'>[Sent Back] </span>");
define("HIGHPRIORITYAPPENDSTRING","<span style='color:#F42C20; font-weight:bold;'>[IMP] </span>");
define("LOWPRIORITYAPPENDSTRING","<span style='color:#8292C6; font-weight:bold;'>[LOW] </span>");
define("MASTERDB", "devents_master");
define("REFRESHTIME", '900000'); // 15 mins
define("SMTP_USERNAME", "abcde@gmail.com");
define("SMTP_PASSWORD", "asd123");
define("SUPERADMIN_EMAIL", "superadminxxxxx@gmail.com");
define("SUPPORT_EMAIL", "support@discreteevents.com");
define("USERCOOKIENAME", "userlogincookie");
define("YOUSTRING","<span style='color:#B04734'>You</span>");
if( $DEVELOPMENT_MODE ){
	define("ALERTAPPADMIN", FALSE);
	define("APP_INSTALLPATH", "/Users/pari/Desktop/Dropbox/Dicrete Events/code/");
	define("DBHOST", "localhost");
	define("DBUSER", "root");
	define("DBPASS", "nokia");
	define("JSASSETS_URL", "http://127.0.0.1/tmpjs/");
	define("MAINDOMAIN", "localdiscreteevents.com");
	define("DEVELOPMENT_SUBDOMAIN", "centerlimit");
}else{
	define("ALERTAPPADMIN", TRUE);
	define("APP_INSTALLPATH", "/www_apps/devents/");
	define("DBHOST", "localhost");
	define("DBUSER", "root");
	define("DBPASS", "terminator8099");
	define("JSASSETS_URL", "http://www.discreteevents.com/js/");
	define("MAINDOMAIN", "discreteevents.com");
}

$DE_GLOBALS_USERLOGINERR = "Invalid username or password! <BR> Please note that your username and password are case sensitive";
include_once APP_INSTALLPATH."include_custom.php";
$db_link = mysql_connect(DBHOST, DBUSER, DBPASS) or die("Could not connect to Database");

?>
