<?php
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
	header("Cache-Control: post-check=0, pre-check=0", false); 
	header("Pragma: no-cache"); // HTTP/1.0
$CURRENT_USER = $_SESSION["uname"] ;
$CURRENT_USERID = $_SESSION["empl_id"] ;
$CURRENT_SCRIPTNAME = getCurrentScriptFileName();

$GMU = new ManageUsers();
$GMU->userId = $CURRENT_USERID ;
$AM_I_ADMIN = $GMU->isAdminUser() ;
$AM_I_PRIVILEGEDUSER = $GMU->isPrivilagedUser() ;

?>
<html lang="en-US">
<HEAD>
	<TITLE><?php echo APPNAME ;?></TITLE>
	<meta charset="UTF-8">
	<script type="text/javascript" src="js/alljs.php?t=13"></script>
	<link rel="stylesheet" href="css/allcss.php?t=6" type="text/css" charset="utf-8">
	<?php
	if( in_array($CURRENT_SCRIPTNAME , array('chatroom.php','bookmarks.php','directory.php') ) ){ ?>
		<link rel="stylesheet" href="prettyPhoto/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
		<script src="prettyPhoto/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script> <?php
	}
	
	if(isset($CUSTOM_STYLES)){
		echo "<STYLE type='text/css'>
			{$CUSTOM_STYLES}
		</STYLE>";
	}
	?>
</HEAD>
<BODY topmargin=0 leftmargin=0>
	<?php noscript_warning(); ?>
	<div id='feedbackmsg'> </div>
	<div id='ajaxstatus' style='display:none;'><nobr><img src='images/loading1.gif' border=0> Loading.. </nobr></div>
	<?php
	//<div id='refresh_DIV_main' onclick="location.replace(window.location.href);" TITLE="Reload Current Page">Refresh</div>
	?>
	<?php
	if( $_SESSION["uname"] ) { ?>
	<div id='logout_DIV_main' onClick="Logout_Action();" TITLE="Log-out and remove the cookie from your machine">Logout</div>
	<?php } ?>
	<script>
		$(document).ready(function(){
			$("div.mainMenu span").live('click', function(){
				var turl = $(this).attr('goto') ;
				if(turl){
					$('#ajaxstatus').show();
					setTimeout(function(){window.location.href= turl ;}, 50);
				}
			});
		});
	</script>