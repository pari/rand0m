<?php
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
	header("Cache-Control: post-check=0, pre-check=0", false); 
	header("Pragma: no-cache"); // HTTP/1.0
?>
<html>
<head>
	<title>Discrete Events - SuperAdmin</title>
	<script type="text/javascript" src="actions.js"></script>
	<script type="text/javascript" src="<?php echo JSASSETS_URL; ?>jquery.js"></script>
	<script type="text/javascript" src="<?php echo JSASSETS_URL; ?>custom.js"></script>
	<link rel="stylesheet" href="../css/global.css" type="text/css" charset="utf-8">
</head>
<BODY topmargin=0 leftmargin=0>

<div class="topHeaderImages">
Discrete Events
</div>
<div class="mainMenu">
	<SPAN class='username'> <B>APP CONTROLLER</B> </SPAN>
	<SPAN goto="index.php">Event Log</SPAN>
	<SPAN goto="clients.php">Clients</SPAN>
	<SPAN goto="dbchanges.php">DB Changes</SPAN>
	<SPAN>  <B><?=date("d M Y");?></B> </SPAN>
</div>

<div id='feedbackmsg'> </div>

<div id='ajaxstatus' style='display:none;'>Loading..</div>

<script>
	$(document).ready(function(){
		$("div.mainMenu span").click(function(){
				var turl = $(this).attr('goto') ;
				if(turl) window.location.href= turl ;
		});
	});



var DE_SSADMIN_action = function(action, argsObject){
	// DE_SSADMIN_action( 'suspendActivate' , {
	//		subdomain:subdomain,
	//		callback:function(){
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


</script>
