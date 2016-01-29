<?php 

//ob_start("ob_gzhandler"); 
include_once "include_variables.php";

$domain_pieces = explode(".", $_SERVER["HTTP_HOST"] );
if( $DEVELOPMENT_MODE ){
	IF (!@mysql_select_db(MASTERDB)){ echo "Not able to Connect to DataBase"; exit(); }
	$sqlquery= "select dbname as DB, timezone as myTimeZone, package as pkgid, status as subdomainStatus from subdomains where subdomain='". DEVELOPMENT_SUBDOMAIN . "'"; //
	$query = mysql_query($sqlquery);
	IF (@mysql_num_rows($query)==0){ echo "<BR><BR><center>Error #A103</center><BR>"; mysql_close($link); exit(); }
	WHILE ($row = @mysql_fetch_array($query)){ extract($row); } // $DB, $pkgid, $subdomainStatus, $myTimeZone
	IF ( $subdomainStatus == "N" ){
		echo "<BR><BR><center><H1>Error #A104</H1></center><BR>";
		echo "<BR><BR><center><H2>This Account is suspended</H2></center><BR>";
		exit();
	}

	$sqlquery= "select pkgName, pkgNumberOfUsers, pkgSpaceMb from packages where pkgId = '$pkgid'";
	$query = mysql_query($sqlquery);
	WHILE ($row = @mysql_fetch_array($query)){ extract($row); } // $pkgName, $pkgNumberOfUsers, $pkgSpaceMb
	IF (!@mysql_select_db($DB)){ echo "Not able to Connect to User's DataBase"; exit(); }
	putenv("TZ=".$myTimeZone );
	session_set_cookie_params (0, '/' , MAINDOMAIN );
	session_start();

	$_SESSION["subdomain"] = DEVELOPMENT_SUBDOMAIN ;
	$_SESSION["pkgName"] = $pkgName ;
	$_SESSION["pkgNumberOfUsers"] = $pkgNumberOfUsers ;
	$_SESSION["pkgSpaceMb"] = $pkgSpaceMb ;
}else{
	
	$urltolc = strtolower($_SERVER["HTTP_HOST"]);
	if ( $urltolc == 'www.'.MAINDOMAIN || $urltolc == MAINDOMAIN ){
		header("Location: /site/");
		exit;
	}
	
	if( sizeof($domain_pieces) != 3 ){ // if not in the format xxx.chanu.org
		echo "Error #A101"; exit(); /////////////////////
		exit(); 
	}
	
	if ( $domain_pieces[1].".".$domain_pieces[2] != MAINDOMAIN ){
		echo "Error #A102"; exit(); /////////////////////
		exit();
	}
	IF (!@mysql_select_db(MASTERDB)){
		echo "Not able to Connect to DataBase";
		exit();
	}

	$sqlquery= "select dbname as DB, timezone as myTimeZone, package as pkgid, status as subdomainStatus from subdomains where subdomain='$domain_pieces[0]'"; //
	$query = mysql_query($sqlquery);
	IF (@mysql_num_rows($query)==0){ // subdomain not found
		echo "<BR><BR><center>Error #A103</center><BR>";
		mysql_close($link);
		exit();
	}
	WHILE ($row = @mysql_fetch_array($query)){ extract($row); } // $DB, $pkgid, $subdomainStatus, $myTimeZone
	IF ( $subdomainStatus == "N" ){ // subdomain InActive
		echo "<BR><BR><center><H1>Error #A104</H1></center><BR>";
		echo "<BR><BR><center><H2>This Account is suspended</H2></center><BR>";
		exit();
	}

	$sqlquery= "select pkgName, pkgNumberOfUsers, pkgSpaceMb from packages where pkgId = '$pkgid'";
	$query = mysql_query($sqlquery);
	WHILE ($row = @mysql_fetch_array($query)){ extract($row); } // $pkgName, $pkgNumberOfUsers, $pkgSpaceMb
	IF (!@mysql_select_db($DB)){ // select the subdomain database
		echo "Not able to Connect to User's DataBase";
		exit();
	}

	putenv("TZ=".$myTimeZone );

	session_set_cookie_params (0, '/' , $domain_pieces[0].".".MAINDOMAIN );
	session_start();


	$_SESSION["subdomain"] = ( $DEVELOPMENT_MODE ) ? DEVELOPMENT_SUBDOMAIN : $domain_pieces[0] ;
	$_SESSION["pkgName"] = $pkgName ;
	$_SESSION["pkgNumberOfUsers"] = $pkgNumberOfUsers ;
	$_SESSION["pkgSpaceMb"] = $pkgSpaceMb ;

}



?>