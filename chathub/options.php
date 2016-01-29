<?php

include_once "include_db.php";
if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CUSTOM_STYLES ="";

include_once "include_functions.php";
include_once "include_header.php";
include_once "include_header_links.php";


?>
<script>

	$(document).ready(function() {

	});


	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('<?php echo getCurrentScriptFileName();?>');
	};


	
	var updatePwd = function(){
		var CP_cpwd = _$('CP_cpwd').value;
		var CP_npwd = _$('CP_npwd').value;
		var CP_rnpwd = _$('CP_rnpwd').value;
		
		if(CP_npwd != CP_rnpwd){
			alert("ERROR: Passwords do not match !");
			return;
		}
		
		if(CP_npwd.length <= 4){
			alert("ERROR: Password must be at least 5 characters long !");
			return;
		}
		
		CJS_AJAX( 'updateUserPassword' , {
			oldPassword: CP_cpwd,
			newPassword: CP_npwd,
			callback:function(a){
				if(a){
					alert("Password Updated \n\n You will now be asked to login using your new password.");
					window.location.href = "logout.php";
					return;
				}else{
					My_JsLibrary.showErrMsg();
				}
			}
		});
	};
</script>


	<table align=center class="manageLRS" cellpadding=5 cellspacing=5 width="500" style='margin-top:30px;'>
		<tbody>
		<TR class="firstRow">
			<Td colspan=2 id='pwdTitleRow' align='center' style='cursor:pointer;'>Change Password</Td>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD align="right">Current Password:</TD>
			<TD><input type='password' id='CP_cpwd' size=16></TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD align="right">New Password:</TD>
			<TD><input type='password' id='CP_npwd' size=16></TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD align="right">Repeat New Password:</TD>
			<TD><input type='password' id='CP_rnpwd' size=16></TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD style="padding:10px;" align="center" colspan=2>
				<span class="bluebuttonSmall" style='font-size:14px;' onClick="updatePwd()">Update Password</span>
			</TD>
		</TR>
		</tbody>
	</TABLE>
	


<?php
include_once "include_footer.php";
?>
