<?php
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
	header("Cache-Control: post-check=0, pre-check=0", false); 
	header("Pragma: no-cache"); // HTTP/1.0

$tmpManageUsers = new manageUsers();

$browser = $_SERVER["HTTP_USER_AGENT"] ;
if( strpos(strtolower($browser), 'msie') !== false ){
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">";
}

?>
<HTML>
<HEAD>
	<TITLE><?php 
		echo APPNAME ;
		echo " - ".getVariableFromMasterSubdomainRow('clientName');	
		?>
	</TITLE>
	<script type="text/javascript" src="<?php echo JSASSETS_URL;?>jquery.js"></script>
	<script type="text/javascript" src="<?php echo JSASSETS_URL;?>jquery.date_input.js"></script>
	<script type="text/javascript" src="<?php echo JSASSETS_URL;?>custom.js"></script>
	<script type="text/javascript" src="<?php echo JSASSETS_URL;?>jquery.hotkeys-0.7.9.min.js"></script>
	<?php
	$tmp_filename = getCurrentScriptFileName();
	if( $tmp_filename == 'welcome.php' ){
	?>
		<script type="text/javascript" src="<?php echo JSASSETS_URL;?>jquery.tablesorter.js"></script> 
		<script type="text/javascript" src="<?php echo JSASSETS_URL;?>jquery.event.drag-1.4.js"></script> 
		<script type="text/javascript" src="<?php echo JSASSETS_URL;?>jquery.kiketable.colsizable-1.1.js"></script>
	<?php 
	}
	
	if( $tmp_filename == 'calendar.php' ){
	?>
		<script type="text/javascript" src="<?php echo JSASSETS_URL;?>ui.core.js"></script> 
		<script type="text/javascript" src="<?php echo JSASSETS_URL;?>ui.draggable.js"></script> 
		<script type="text/javascript" src="<?php echo JSASSETS_URL;?>ui.resizable.js"></script>
		<script type="text/javascript" src="<?php echo JSASSETS_URL;?>fullcalendar.min.js"></script>
		<link rel='stylesheet' type='text/css' href='redmond/theme.css' />
		<link rel='stylesheet' type='text/css' href='<?php echo JSASSETS_URL;?>fullcalendar.css' />
	<?php
	}
	
	?>
	<link rel="stylesheet" href="<?php echo JSASSETS_URL;?>date_input.css" type="text/css" charset="utf-8">
	<link rel="stylesheet" href="css/global.css" type="text/css" charset="utf-8">
</HEAD>
<BODY topmargin=0 leftmargin=0>
<div id='feedbackmsg'> </div>
<div id='ajaxstatus' style='display:none;'><nobr><img src='/images/loading1.gif' border=0> Loading.. </nobr></div>
<div id='search_DIV_main' style='display:none;'>
	<input type=text size=16 id='de_search_text'>
	<span class='bluebuttonSmall' onclick='DiscreteEvents_Search();'>Search</span>
</div>
<div id='logout_DIV_main' onclick="LOGOUT_USER();" TITLE="Log-out and remove the cookie from your machine">Logout</div>

<div class="topHeaderImages" style="<?php
	echo "background-color: ".$tmpManageUsers->get_userSingleDetail( $_SESSION["uname"] , 'user_bgcolor' ) . ";" ;
?>">
	<img src="images/delogo_header.png" border=0>
</div>


<div style='display:none;'>
	<form action='search.php' method='post' name='searchform'>
		<input type='text' id='search_term' name='search_term'>
	</form>
</div>


<script>

	var ManageTasksJsFunction = {};

	ManageTasksJsFunction.detailsWork = function(wId){ // ManageTasksJsFunction.detailsWork( taskNo );
		var day = new Date();
		var id = day.getTime();
		var windowid = 'w'+ id;

		window.open( 'taskdetails.php?taskid=' + wId + '&id=' + id , windowid , 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=0,width=720,height=600,left = 440,top = 225');
	};


	var DiscreteEvents_Search = function(){
		_$('search_term').value = _$('de_search_text').value ;
		document.searchform.submit();
	};



</script>
<div style="position:absolute; top: 40px; right:10px; color: #FFFFFF;"><?php echo date("l jS M"); ?></div>
<div class="mainMenu">
	<SPAN class='username'> <B><?php echo $_SESSION["uname"]; ?></B> </SPAN>
	<?php 
	if(!IsSadmin()){
		// Admin does not have all these options related to tasks
		?>
		<SPAN goto="welcome.php" TITLE="Monitor tasks assigned to you and others in your projects">Tasks</SPAN>
		<SPAN goto="welcome.php?list=ptasks" TITLE="Manage your personal tasks">Personal Tasks</SPAN>
		<?php
			$unreadCommentCount = $tmpManageUsers->getUnreadCommentsCount($_SESSION["uname"]);
		?>
		<SPAN goto="scheduled.php" TITLE="future scheduled tasks">Scheduled Tasks</SPAN>
		<SPAN goto="recurringtasks.php" TITLE="manage recurring tasks">Recurring Tasks</SPAN>
		<SPAN goto="reminders.php" TITLE="Manage Email Reminders to self or others">Reminders</SPAN>
		<SPAN goto="comments.php" TITLE="Recent comments on your tasks"><nobr>Comments <font style='font-size:80%;'>(<?php echo $unreadCommentCount;?>)</font></nobr></SPAN>
		<SPAN goto="notes.php" TITLE="store some quick private notes">Notepad</SPAN>
		<SPAN goto="myfiles.php" TITLE="My Files">My Files</SPAN>
		<?php 
	}
		if(IsSadmin()){
		?>
			<SPAN goto="users.php" TITLE="Add,Edit, Suspend users">Users</SPAN>
			<SPAN goto="projects.php" TITLE="Manage projects and assign users to various projects">Projects</SPAN>
		<?php
		}
	?>

</div>
<script>
	<?php
	if( $DEVELOPMENT_MODE ){
	 echo "var APPDEBUGMODE = true; ";
	}else{
	 echo "var APPDEBUGMODE = false; ";
	}
	?>

	$(document).ready(function(){
		$("div.mainMenu span").click(function(){
				var turl = $(this).attr('goto') ;
				if(turl){
					$('#ajaxstatus').show();
					setTimeout(function(){window.location.href= turl ;}, 50);
				}
		});
	});

	var LOGOUT_USER = function(){
		DE_USER_action('Logout', {callback:function(){ window.location.href = 'index.php';} });
	};
	
	var DE_USER_action = function(action, argsObject){
		// DE_USER_action( 'logout' , {
		//		variable:value,
		//		callback:function(){
		//
		//		}
		//	});
		argsObject.action = action ;
		if( argsObject.hasOwnProperty('callback') ){
			var cb = argsObject.callback ;
			delete argsObject.callback ;
		}else{
			var cb = function(){};
		}
		$.ajax({
				type: "POST",
				 url: 'actions.php',
				data: argsObject,
			 success: function(resp){ My_JsLibrary.callCB(resp, cb); }
		});
	};

	var EDITEMAILTASKID = '';
	var EDIT_SCHEDULEDEMAILID = 0;
</script>

<?php
unset($tmpManageUsers);

if(!IsSadmin()){
	include_once "include_dailytasks.php" ;
}
include_once "include_scheduledtaskform.php" ;
?>