<?php
	include_once "include_db.php";
	// Do not allow accessing http://www.expensescentral.com/expenses/
	// this file (db.php) is only used by the expenses application which is meant to be
	// accessed in the format http://subdomain.expensescentral.com
	
	if( $DEVELOPMENT_MODE ){
		
	}else{
		$urltolc = strtolower($_SERVER["HTTP_HOST"]);
		if ( $urltolc == 'www.'.MAINDOMAIN || $urltolc == MAINDOMAIN ){
				header("Location: /site/");
		  exit;
		}
	}

include_once "include_functions.php";
checkUserSessionandCookie();


if( @$_SESSION["uname"] ){
	if(IsSadmin()){
		header("Location: users.php");
	}else{
		header("Location: welcome.php");
	}
	exit();
}

session_unset();
session_destroy();

?>
<HTML>
	<HEAD>
		<TITLE><?php echo APPNAME ; ?></TITLE>
		<script type="text/javascript" src="<?php echo JSASSETS_URL;?>jquery.js"></script>
		<script type="text/javascript" src="<?php echo JSASSETS_URL;?>custom.js"></script>
		<link rel="stylesheet" href="css/global.css" type="text/css" charset="utf-8">
	</HEAD>
<BODY>
<script>

var loginMisc = {
	sendLoginDetails: function(){ // loginMisc.sendLoginDetails();
		var eml = _$('ForgotPassword_Form_EmailId').value;

		DE_USER_action( 'sendLoginDetails' , {
			email : eml,
			callback:function(a){
				if(a){
					My_JsLibrary.showfbmsg( My_JsLibrary.errormsg , 'green' );
					alert( 'Login details were sent to your email address.'+ '\n' +' Please check your email' );
					$('#ForgotPassword_Form_link').hide();
					$('#ForgotPassword_Form').hide();
				}else{
					My_JsLibrary.showfbmsg( My_JsLibrary.errormsg , 'red' );
				}
			}
		});
	},

	focusPass: function(e){ // loginMisc.focusPass();
		if(e.keyCode == 13){
			_$('uepwd').focus();
			return false;
		}
	},

	doLoginOnEnter: function(e){ // loginMisc.doLoginOnEnter();
		if(e.keyCode == 13){
			doLogin();
			return false;
		}
	}
};



	var localajaxinit = function(){
		$('#loginTable').show();
		$('#ForgotPassword_Form_link').click(function(){
			$('#ForgotPassword_Form_link').hide();
			$('#ForgotPassword_Form').show();
			_$('ForgotPassword_Form_EmailId').focus();
		});
		_$('uname').focus();
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



var doLogin = function(){
	var uname = _$('uname').value;
	var uepwd = _$('uepwd').value;
	var setcuky = _$('remeber_chk').checked ;

	$("#span_loading").show();
	$("#span_loginbutton").hide();
	DE_USER_action( 'doLogin' , {
		uname : uname,
		uepwd : uepwd,
		setcuky : setcuky,
		callback:function(a){
			if(a){
				_$('uname').disabled = true;
				_$('uepwd').disabled = true;
				var rurl = My_JsLibrary.parseGETparam( 'rurl');
				window.location.href = (rurl) ? rurl : 'welcome.php' ;
				return;
			}else{
				$("#span_loading").hide();
				$("#span_loginbutton").show();
				My_JsLibrary.showfbmsg( My_JsLibrary.errormsg , 'red' );
				_$('uname').focus();
				return;
			}
		}
	});
};


</script>
<CENTER>
	<BR>
		<CENTER>
			<IMG src="/images/delogo_login.png" border=0 class="defaultcursor">
		</CENTER>
	<BR>

	<div id='feedbackmsg'> </div>

	<div id="ACTIVE_CONTENT"><noscript><BR><BR><B>This application needs a Javascript enabled browser.<BR>Please enable Javascript !!</B><BR><BR><BR></noscript></div>

	<TABLE id="loginTable" style="display: none;">
		<TR>
			<TD align=right>Username:</TD>
			<TD>&nbsp;<input type="text" id="uname" size=14 class='big' onKeyUp="loginMisc.focusPass(event)"></TD>
		</TR>
		<TR>
			<TD align=right>Password:</TD>
			<TD>&nbsp;<input type="password" id="uepwd" size=14  class='big' onKeyUp="loginMisc.doLoginOnEnter(event)"></TD>
		</TR>
		<TR>
			<TD align=right>
				<input type="checkbox" id="remeber_chk" name="remeber_chk">
			</TD>
			<TD><label FOR="remeber_chk">&nbsp;Remember Login</label></TD>
		</TR>
		<TR>
			<TD colspan=2 align="center" valign="middle">
				<br>
				<span style='display:none' id='span_loading'><img src='images/tick.gif' border=0> Loading ...</span>
				<span class="bluebutton" id='span_loginbutton' onclick="doLogin()">&nbsp;Login&nbsp;</span>
			</TD>
		</TR>
		<TR>
			<TD colspan=2 align="center">
					<BR>
					<SPAN id="ForgotPassword_Form" style="display:none">
						Enter your Email Id :
						<input type="text" id="ForgotPassword_Form_EmailId" size=20>
						<button type="button" onclick="loginMisc.sendLoginDetails();">Go</button>
					</SPAN>
					<span id="ForgotPassword_Form_link"><B>Help:</B> <span style="cursor:pointer; color: #0066FF">Forgot password ?</span></span>
			</TD>
		</TR>
	</TABLE>

	<DIV class='userfooter'>
		<?php echo APPNAME.", ver ". APPVERSION ; ?>
		&copy; 2009. <A href='http://www.centerlimit.com'>CenterLimit LLC</A>
	</DIV>


</CENTER>

