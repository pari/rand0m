<?php
include_once "include_db.php";

if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CUSTOM_STYLES ="";

include_once "include_functions.php";
include_once "include_header.php";
include_once "include_header_links.php";

if( !($AM_I_ADMIN || $GMU->has_Privilege('Can Invite Other Users')) ){ 
	include_once "include_footer.php";
	exit();
}

?>
<script>

	$(document).ready(function() {

	});

	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('<?php echo getCurrentScriptFileName();?>');
	};

	
	var sendInvitation = function(){
		var IU_fname = _$('IU_fname').value;
		var IU_lname = _$('IU_lname').value;
		var IU_email = _$('IU_email').value;
		var IU_msg = _$('IU_msg').value;
		
		if(IU_fname == ''){
			alert("ERROR: No First Name !");
			return;
		}
		
		if(IU_lname == ''){
			alert("ERROR: No Last Name !");
			return;
		}
		
		if(IU_email == ''){
			alert("ERROR: No email !");
			return;
		}
		
		$('#ajaxstatus').show();
		
		CJS_AJAX( 'sendUserInvitation' , {
			fname: IU_fname,
			lname: IU_lname,
			email: IU_email,
			msg: IU_msg,
			callback:function(a){
				if(a){
					$('#ajaxstatus').hide();
					alert("Invitation Sent.");
					window.location = "/";
					return;
				}else{
					$('#ajaxstatus').hide();
					My_JsLibrary.showErrMsg();
				}
			}
		});
	};
</script>


	<TABLE align=center class="manageLRS" cellpadding=5 cellspacing=5 width="500" style='margin-top:30px;'>
		<tbody>
		<TR class="firstRow">
			<Td colspan=2 id='pwdTitleRow' align='center' style='cursor:pointer;'>Invite a new user to your Chat account</Td>
		</TR>
		
		<TR class="firstRow">
			<Td colspan=2 id='' align='center'>
The person you invite will receive an email with an invitation link. When they click the link they can choose their own username and password. Then they will be part of your account!</Td>
	   </TR>
		
		<TR class='PasswordSecondaryRows'>
			<TD align="right">First Name:</TD>
			<TD><input type='text' id='IU_fname' size=36></TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD align="right">Last Name:</TD>
			<TD><input type='text' id='IU_lname' size=36></TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD align="right">Email:</TD>
			<TD><input type='text' id='IU_email' size=36></TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD align="right"> Message:</TD>
			<TD><textarea id='IU_msg' cols="36" rows="6"></textarea></TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD style="padding:10px;" align="center" colspan=2>
				<span class="bluebuttonSmall" style='font-size:14px;' onClick="sendInvitation()">Send the invitation mail</span>
			</TD>
		</TR>
		</tbody>
	</TABLE>


<?php
include_once "include_footer.php";
?>