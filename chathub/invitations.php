<?php
include_once "include_db.php";

$CUSTOM_STYLES ="
	#pwdTitleRow{
	font-size: 15px;
	font-weight : bold ;
	color : #718DA1;
	}

	span.bluebuttonSmall , div.bluebuttonSmall {
	background-color: #EBE9E9;
	border: 1px outset #B6C7E5;
	color: #445A80;
	line-height: 1.4em;
	padding: 2px 4px;
	cursor: pointer;
	font-size: 85%;
	}
";

include_once "include_functions.php";
include_once "include_header.php";
//include_once "include_header_links.php";

if(get_GET_var('id'))
{
	$id = get_GET_var('id');
	$key = get_GET_var('key');
	
	$invi_Id = $id;
	
	$sql = "SELECT * FROM tbl_Invitations WHERE invi_Id='$invi_Id' AND invi_key='$key'";
	$invi_details = executesql_returnAssocArray($sql);
	
	
	$invi_sent_by = $invi_details['invi_sent_by'];
	$userFName = executesql_returnArray("select emplFullName from tblAppUsers where emplUsername='{$invi_sent_by}'");
	
	
	
	if($invi_details['invi_status'] != '0')
	{
		echo "Invalid Invitation. May be the invitation already used.";
		exit();
	}
}
else
{
	echo "Invalid Invitation. May be the invitation already used.";
	exit();
}
?>
<script>
	
	var localajaxinit = function(){
		
	};

	var checkInvitation = function(){
		var IC_uname = _$('IC_uname').value;
		var IC_pwd = _$('IC_pwd').value;
		var IC_re_pwd = _$('IC_re_pwd').value;
		var IC_mobile = _$('IC_mobile').value;
		var IC_des = _$('IC_des').value;
		var invi_Id = _$('invi_Id').value;
		var invi_fName = _$('invi_fName').value;
		var invi_email = _$('invi_email').value;
		var Timezone = _$('Timezone').value;
		
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
		
		CJS_AJAX( 'checkInvitation' , {
			uname: IC_uname,
			pwd: IC_pwd,
			mobile: IC_mobile,
			des: IC_des,
			invi_id: invi_Id,
			invi_fName: invi_fName,
			invi_email: invi_email,
			Timezone: Timezone,
			callback:function(a){
				if(a){
					$('#ajaxstatus').hide();
					alert("Your account has been created.");
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

	<table align=center class="manageLRS" cellpadding=5 cellspacing=5 width="500" style='margin-top:30px;'>
		<tbody>
		<TR class="firstRow">
			<Td colspan=2 id='pwdTitleRow' align='center' style='cursor:pointer;'>Hi, <?php echo $invi_details['invi_firstName'].' '.$invi_details['invi_lastName'];?>.</Td>
		</TR>
		
		<TR class="firstRow">
			<Td colspan=2 id='' align='center'>
			<?php echo $userFName;?> just set up an account for you on <?php echo APPNAME; ?>, our group chat system. All you need to do is choose a username and password.</Td>
		</TR>
		<input type="hidden" id="invi_Id" value="<?php echo $invi_details['invi_Id'];?>" />
		<input type="hidden" id="invi_fName" value="<?php echo $invi_details['invi_firstName'].' '.$invi_details['invi_lastName'];?>" />
		<input type="hidden" id="invi_email" value="<?php echo $invi_details['invi_email'];?>" />

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
			<TD style="padding:10px;" align="center" colspan=2>
				<span class="bluebuttonSmall" style='font-size:14px;' onClick="checkInvitation()">Create your account</span>
			</TD>
		</TR>
		</tbody>
	</TABLE>
<?php
//include_once "include_footer.php";
?>
</BODY>
</HTML>

