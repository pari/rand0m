<?php

define("APPURL", "http://".$_SERVER["HTTP_HOST"] . dirname($_SERVER["REQUEST_URI"]) ); // This URL will be mentioned in email invites, so update it properly , no trailing slash

define("DBHOST", "localhost");
define("DBUSER", "root");
define("DBPASS", "");
define("MASTERDB", 'chathub' ); // this database will be created automatically if does not exist. DBUSER should have privilege to create this database
define("SUPERADMIN_EMAIL", "yourAdminEmail@example.com"); // Email Id where you want to receive any alerts
define("YOUR_GOOGLEAPP_EMAILID", "yourAdminEmail@example.com"); // This app sends emails from this email id , must be a gmail or google app email id
define("YOUR_GOOGLEAPP_EMAILID_PASS", "YOUR_GOOGLEAPP_EMAILID_PASSWORD");


define("APP_INSTALLPATH", dirname($_SERVER["SCRIPT_FILENAME"])."/" ); // Ex: '/var/www/freetalk/'  , has a trailing slash
define("UPLOAD_PATH", "files/chat_files/"); // has a trailing slash
define("CHAT_REFRESH_INTERVAL", 7 * 1000 );
define("APPNAME", "ChatHub");
define("APPVERSION", "0.9.1");
include_once APP_INSTALLPATH."include_custom.php";
define("JSASSETS_URL" , '/freetalk/js/' ); // has a trailing slash
// $DEVELOPMENT_MODE = 0;
putenv("TZ=GMT"); // Set APP TIME ZONE TO GMT
ini_set('upload_max_filesize', 20000000 );
ob_start("ob_gzhandler");


$db_link = mysql_connect(DBHOST, DBUSER, DBPASS) or die("Error Connecting to DB Host. Invalid db credentials !");

function tryCreatingDb(){
	include_once "db.php" ;
	$queries = explode("[#]" , $sql_str );
	foreach($queries as $this_qry){
		$result = mysql_query( trim($this_qry) );
		if (!$result) {
		    echo mysql_error()."<BR>";
		}
	}
	echo "<script> setTimeout(function(){ window.location.reload(); }, 2000); </script>Created Database. Now Reloading....";
}


IF (!@mysql_select_db(MASTERDB)){
	tryCreatingDb();
	exit();
}

mysql_query("SET time_zone = '0:00' ;"); // set MYSQL TIME ZONE TO GMT - for the php-mysql session
@session_start();

$GLOBAL_PRIVILEGE_DEFNS = array(
	'Can Create New Rooms' => '1' ,
	'Can Invite Other Users' => '2' ,
	'Can Send Direct Messages to Others' => '3',
	'Can Access all Rooms' => '4' // has access to all Rooms
);

define("isMobileBrowser", false);

// if($_SERVER['HTTP_HOST'] == 'prj99.mobi'){
// 	define("isMobileBrowser", true);
// }

?>
