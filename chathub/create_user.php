<?php

include_once "include_db.php";
if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CUSTOM_STYLES ="";

include_once "include_functions.php";
include_once "include_header.php";
include_once "include_header_links.php";

if( !$AM_I_ADMIN ){
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


	var createUser = function(){
		var IU_fname = _$('IU_fname').value;
		var IU_lname = _$('IU_lname').value;
		var IU_email = _$('IU_email').value;
		var IU_msg = _$('IU_msg').value;
		
		var IC_uname = _$('IC_uname').value;
		var IC_pwd = _$('IC_pwd').value;
		var IC_re_pwd = _$('IC_re_pwd').value;
		var IC_mobile = _$('IC_mobile').value;
		var IC_des = _$('IC_des').value;
		var Timezone = _$('Timezone').value;
		
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
		
		if(IC_uname == ''){
			alert("ERROR: No User Name !");
			return;
		}
		
		if(IC_pwd == ''){
			alert("ERROR: No Password !");
			return;
		}
		
		if(IC_pwd != IC_re_pwd){
			alert("ERROR: Passwords do not match !");
			return;
		}
		
		if(IC_pwd.length <= 4){
			alert("ERROR: Password must be at least 5 characters long !");
			return;
		}
		
		if(Timezone == ''){
			alert("ERROR: Not Selected Time Zone !");
			return;
		}
		
		$('#ajaxstatus').show();
		
		CJS_AJAX( 'createUser' , {
			fname: IU_fname,
			lname: IU_lname,
			email: IU_email,
			msg: IU_msg,
			uname: IC_uname,
			pwd: IC_pwd,
			mobile: IC_mobile,
			des: IC_des,
			Timezone: Timezone,
			callback:function(a){
				
				if(a){
					$('#ajaxstatus').hide();
					alert("User Created.");
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
		<Td colspan=2 id='pwdTitleRow' align='center' style='cursor:pointer;'>Create a new user to your Chat account</Td>
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
		<TD width="227" align="right">Choose a username:</TD>
	  <TD width="236"><input type='text' id='IC_uname' size=36></TD>
	</TR>
	<TR class='PasswordSecondaryRows'>
		<TD align="right">Pick a password:</TD>
		<TD><input type='password' id='IC_pwd' size=36></TD>
	</TR>
	<TR class='PasswordSecondaryRows'>
		<TD align="right">Enter the password agian :</TD>
		<TD><input type='password' id='IC_re_pwd' size=36></TD>
	</TR>
	<TR class='PasswordSecondaryRows'>
		<TD align="right">Mobile Number :</TD>
		<TD><input type='text' id='IC_mobile' size=36></TD>
	</TR>
	<TR class='PasswordSecondaryRows'>
		<TD align="right">Designation :</TD>
		<TD><input type='text' id='IC_des' size=36></TD>
	</TR>
	
	<TR class='PasswordSecondaryRows'>
		<TD align="right">Time Zone :</TD>
		<TD>
			<select name="Timezone" id="Timezone">
			<option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
			<option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
			<option value="-10.0">(GMT -10:00) Hawaii</option>
			<option value="-9.0">(GMT -9:00) Alaska</option>
			<option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
			<option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
			<option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
			<option value="-5.0">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
			<option value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
			<option value="-3.5">(GMT -3:30) Newfoundland</option>
			<option value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
			<option value="-2.0">(GMT -2:00) Mid-Atlantic</option>
			<option value="-1.0">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
			<option value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
			<option value="1.0">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
			<option value="2.0">(GMT +2:00) Kaliningrad, South Africa</option>
			<option value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
			<option value="3.5">(GMT +3:30) Tehran</option>
			<option value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
			<option value="4.5">(GMT +4:30) Kabul</option>
			<option value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
			<option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
			<option value="5.75">(GMT +5:45) Kathmandu</option>
			<option value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo</option>
			<option value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
			<option value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
			<option value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
			<option value="9.5">(GMT +9:30) Adelaide, Darwin</option>
			<option value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
			<option value="11.0">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
			<option value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
			</select>
	</TD>
	</TR>
	<TR class='PasswordSecondaryRows'>
		<TD align="right"> Message:</TD>
		<TD><textarea id='IU_msg' cols="36" rows="6"></textarea></TD>
	</TR>
	
	<TR class='PasswordSecondaryRows'>
		<TD style="padding:10px;" align="center" colspan=2>
			<span class="bluebuttonSmall" style='font-size:14px;' onClick="createUser()">Create a User</span>
		</TD>
	</TR>
	</tbody>
</TABLE>

<?php
include_once "include_footer.php";
?>