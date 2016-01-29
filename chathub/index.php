<?php
@session_start();

if( @$_SESSION["uname"] ){ 
	header('Location: lobby.php');
	exit(); 
}
include_once "include_db.php";
?>
<html>
<head>
	<title>Login</title>
	<script type="text/javascript" src="js/alljs.php"></script>
	<STYLE type='text/css'>
	body{
		background-color: #EFEFEF;
		font-family: verdana,helvetica,arial,sans-serif;
		font-size: 12px;
	}
	</STYLE>
</head>
<BODY topmargin=0 leftmargin=0>
<?php noscript_warning(); ?>
<script>

	var localajaxinit = function(){
		_$('uname').focus();
	};

	var doLogin = function(){
		var uname = _$('uname').value ;
		var pwd = _$('pwd').value ;
		
		CJS_AJAX( 'doLogin' , {
			uname: uname,
			uepwd: pwd,
			callback:function(a){
				if(a){
					window.location.href = 'lobby.php' ;
				}else{
					My_JsLibrary.showErrMsg()
				}
			}
		});
		
	};


	var loginMisc = {
		focusPass: function(e){ // loginMisc.focusPass();
			if(e.keyCode == 13){ _$('pwd').focus(); return false; }
		},
		doLoginOnEnter: function(e){ // loginMisc.doLoginOnEnter();
			if(e.keyCode == 13){ doLogin(); return false; }
		}
	};

</script>
	<table style='margin-top:40px; border: 4px solid #EFEFEF;' align=center cellpadding=2 cellspacing=14>
		<TR>
			<TD colspan=2 align=center>
				<img src='images/logo.png' border=0>
			</TD>
		</TR>
		<TR>
			<TD align=right>Username:</TD>
			<TD><input id='uname' type="text" size=12 onKeyUp="loginMisc.focusPass(event)"></TD>
		</TR>
		<TR>
			<TD align=right>Password:</TD>
			<TD><input id='pwd' type="password" size=12 onKeyUp="loginMisc.doLoginOnEnter(event)"></TD>
		</TR>
		<TR>
			<TD colspan=2 align=center>
				<input type='button' class="bluebuttonSmall" onClick="doLogin();" value='Login'>
			</TD>
		</TR>
		<TR>
			<TD colspan=2 align=center>
				<div id='feedbackmsg'> </div>
			</TD>
		</TR
	></table>
	
	<div style='margin-left:auto; margin-right:auto; margin-top:20px;' id='loginfeedbackmsg'></div>
</body>
</html>
