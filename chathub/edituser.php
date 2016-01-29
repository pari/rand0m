<?php
include_once "include_db.php";
if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CUSTOM_STYLES ="";

include_once "include_functions.php";
include_once "include_header.php";
include_once "include_header_links.php";

$userInfo = $GMU->getUserProfile($CURRENT_USERID);
?>
<script>
	$(document).ready(function() {

	});

	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('<?php echo getCurrentScriptFileName();?>');
	};

	var updateUserInfo = function(){
		var name = _$('name').value;
		var email = _$('email').value;
		var mobile = _$('mobile').value;
		var designation = _$('designation').value;
		var Timezone = _$('Timezone').value;
		
		if(name == ''){
			alert("ERROR: No Full Name !");
			return;
		}
		
		if(email == ''){
			alert("ERROR: No Email !");
			return;
		}
		
		if(mobile == ''){
			alert("ERROR: No Mobile Number !");
			return;
		}
		
		if(designation == ''){
			alert("ERROR: No Designation !");
			return;
		}
		
		if(Timezone == ''){
			alert("ERROR: No TimeZone !");
			return;
		}
		
		CJS_AJAX( 'updateUserInfo' , {
			name: name,
			email: email,
			mobile: mobile,
			designation: designation,
			Timezone: Timezone,
			callback:function(a){
				if(a){
					alert("User Information Updated");
					window.location.href = "edituser.php";
					return;
				}else{
					My_JsLibrary.showErrMsg();
				}
			}
		});
	};
	
</script>


	<form name="edituser" action="edituser_action.php" method="post" enctype="multipart/form-data">
	<table align=center class="manageLRS" cellpadding=5 cellspacing=5 width="500" style='margin-top:30px;'>
		<tbody>
		<TR class="firstRow">
			<Td colspan=2 id='pwdTitleRow' align='center' style='cursor:pointer;'>Edit User Information</Td>
		</TR>
		<TR class="PasswordSecondaryRows">
			<Td colspan=2 id="errmsg" align="center" style="cursor:pointer;"><?php
			if(isset($_GET['res'])){
				if($_GET['res']){
					echo "User Information Updated Successfully.";
				}else{
					echo "User Information Update Failed!";
					if($_GET['er']=='f'){
						echo "<br/>Email Already Exists!";
					}
				}
			}
			?>
			</Td>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD align="right">Name:</TD>
			<TD><input type='text' id='name' name='name' size=36 value="<?=$userInfo['emplFullName']?>"></TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD align="right">Email:</TD>
			<TD><input type='text' id='email' name='email' size=36 value="<?=$userInfo['emplEmail_id']?>"></TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD align="right">Mobile:</TD>
			<TD><input type='text' id='mobile' name='mobile' size=36 value="<?=$userInfo['emplMobileNo']?>"></TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD align="right">Designation:</TD>
			<TD><input type='text' id='designation' name='designation' size=36 value="<?=$userInfo['emplDesignation']?>"></TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD align="right">Time Zone :</TD>
			<TD>
				<select name="Timezone" id="Timezone">
				<option value="-12.0" <?php if($userInfo['TimeZone'] == '-12.0'){ ?>selected="selected"<?php } ?>>(GMT -12:00) Eniwetok, Kwajalein</option>
				<option value="-11.0" <?php if($userInfo['TimeZone'] == '-11.0'){ ?>selected="selected"<?php } ?>>(GMT -11:00) Midway Island, Samoa</option>
				<option value="-10.0" <?php if($userInfo['TimeZone'] == '-10.0'){ ?>selected="selected"<?php } ?>>(GMT -10:00) Hawaii</option>
				<option value="-9.0" <?php if($userInfo['TimeZone'] == '-9.0'){ ?>selected="selected"<?php } ?>>(GMT -9:00) Alaska</option>
				<option value="-8.0" <?php if($userInfo['TimeZone'] == '-8.0'){ ?>selected="selected"<?php } ?>>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
				<option value="-7.0" <?php if($userInfo['TimeZone'] == '-7.0'){ ?>selected="selected"<?php } ?>>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
				<option value="-6.0" <?php if($userInfo['TimeZone'] == '-6.0'){ ?>selected="selected"<?php } ?>>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
				<option value="-5.0" <?php if($userInfo['TimeZone'] == '-5.0'){ ?>selected="selected"<?php } ?>>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
				<option value="-4.0" <?php if($userInfo['TimeZone'] == '-4.0'){ ?>selected="selected"<?php } ?>>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
				<option value="-3.5" <?php if($userInfo['TimeZone'] == '-3.5'){ ?>selected="selected"<?php } ?>>(GMT -3:30) Newfoundland</option>
				<option value="-3.0" <?php if($userInfo['TimeZone'] == '-3.0'){ ?>selected="selected"<?php } ?>>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
				<option value="-2.0" <?php if($userInfo['TimeZone'] == '-2.0'){ ?>selected="selected"<?php } ?>>(GMT -2:00) Mid-Atlantic</option>
				<option value="-1.0" <?php if($userInfo['TimeZone'] == '-1.0'){ ?>selected="selected"<?php } ?>>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
				<option value="0.0" <?php if($userInfo['TimeZone'] == '0.0'){ ?>selected="selected"<?php } ?>>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
				<option value="1.0" <?php if($userInfo['TimeZone'] == '1.0'){ ?>selected="selected"<?php } ?>>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
				<option value="2.0" <?php if($userInfo['TimeZone'] == '2.0'){ ?>selected="selected"<?php } ?>>(GMT +2:00) Kaliningrad, South Africa</option>
				<option value="3.0" <?php if($userInfo['TimeZone'] == '3.0'){ ?>selected="selected"<?php } ?>>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
				<option value="3.5" <?php if($userInfo['TimeZone'] == '3.5'){ ?>selected="selected"<?php } ?>>(GMT +3:30) Tehran</option>
				<option value="4.0" <?php if($userInfo['TimeZone'] == '4.0'){ ?>selected="selected"<?php } ?>>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
				<option value="4.5" <?php if($userInfo['TimeZone'] == '4.5'){ ?>selected="selected"<?php } ?>>(GMT +4:30) Kabul</option>
				<option value="5.0" <?php if($userInfo['TimeZone'] == '5.0'){ ?>selected="selected"<?php } ?>>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
				<option value="5.5" <?php if($userInfo['TimeZone'] == '5.5'){ ?>selected="selected"<?php } ?>>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
				<option value="5.75" <?php if($userInfo['TimeZone'] == '5.75'){ ?>selected="selected"<?php } ?>>(GMT +5:45) Kathmandu</option>
				<option value="6.0" <?php if($userInfo['TimeZone'] == '6.0'){ ?>selected="selected"<?php } ?>>(GMT +6:00) Almaty, Dhaka, Colombo</option>
				<option value="7.0" <?php if($userInfo['TimeZone'] == '7.0'){ ?>selected="selected"<?php } ?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
				<option value="8.0" <?php if($userInfo['TimeZone'] == '8.0'){ ?>selected="selected"<?php } ?>>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
				<option value="9.0" <?php if($userInfo['TimeZone'] == '9.0'){ ?>selected="selected"<?php } ?>>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
				<option value="9.5" <?php if($userInfo['TimeZone'] == '9.5'){ ?>selected="selected"<?php } ?>>(GMT +9:30) Adelaide, Darwin</option>
				<option value="10.0" <?php if($userInfo['TimeZone'] == '10.0'){ ?>selected="selected"<?php } ?>>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
				<option value="11.0" <?php if($userInfo['TimeZone'] == '11.0'){ ?>selected="selected"<?php } ?>>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
				<option value="12.0" <?php if($userInfo['TimeZone'] == '12.0'){ ?>selected="selected"<?php } ?>>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
				</select>
		</TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD align="right">User Image:</TD>
			<TD><input type='file' id='userimg' name="userimg" >
			<?php
			$userpic = "files/users/thumbs/{$userInfo['userImage']}";
			if($userInfo['userImage']!='' && file_exists($userpic)){
				echo "<img src='{$userpic}'>";
			}
			?>
			</TD>
		</TR>
		<TR class='PasswordSecondaryRows'>
			<TD style="padding:10px;" align="center" colspan=2>
				<!--<span class="bluebuttonSmall" style='font-size:14px;' onClick="updateUserInfo()">Update</span>-->
				<input type="submit" class="bluebuttonSmall" style='font-size:14px;' name="submit" value="Update">
			</TD>
		</TR>
		
		</tbody>
	</TABLE>
	</form>


<?php
include_once "include_footer.php";
?>
