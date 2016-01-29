<?php

	include_once "include_db.php";
	include_once "include_functions.php";
	checkUserSessionandCookie();
	include_once "include_header.php";

	$username = $_SESSION["uname"];



?>
<SCRIPT>
	var newIcalURL = function(){
		if(!confirm('Sure you want to generate a new iCal URL ?')){return;}
		DE_USER_action( 'newIcalURL', {
			callback:function(a){
				if(a){
					window.location.reload();
				}else{
					My_JsLibrary.showErrMsg() ;
				}
			}
		});
	};

	var updatePassword_do = function(){
		var upwd_cpass = _$('upwd_cpass').value ;
		var upwd_nupass = _$('upwd_nupass').value;
		var upwd_rnupass = _$('upwd_rnupass').value ;

		if( !My_JsLibrary.checkRequiredFields(['upwd_cpass', 'upwd_nupass', 'upwd_rnupass']) ){
			return ;
		}
		if( upwd_nupass !== upwd_rnupass ){
			alert('Passwords do not match');
			return;
		}
		if( upwd_nupass.length < 5){
			alert('Password should be atleast 5 characters');
			return;
		}

		DE_USER_action( 'updateUserPassword', {
			upwd_cpass : upwd_cpass,
			upwd_nupass : upwd_nupass,
			callback:function(a){
				if(a){
					alert("Password Updated !! \n Please login using your new password.");
					window.location.href='index.php';
				}else{
					My_JsLibrary.showErrMsg() ;
				}
			}
		});
	};
	
	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('options.php');

		$("td.bgcolorselect_td").click(function(){
			var selcolor = $(this).attr('bgcolor');
			DE_USER_action( 'setBgColor',
			{
				newColor: selcolor,
				callback:function(a){
					if(a){
						window.location.href='options.php';
					}else{
						My_JsLibrary.showErrMsg() ;
					}
				}
			});
		});

	}; // End of localajaxinit


</SCRIPT>


<div style='clear:both; margin-left:auto; margin-right:auto; margin-top:15px;'>
	<center><div><nobr><b>Pick a background color for your 'Discrete Events' account</b></nobr></div></center>
	<table cellpadding=0 cellspacing=2 border=0 width='400' align=center>
		<tr>
			<td colspan=4>
			
			</td>
		</tr>
		<tr>
			<td class='bgcolorselect_td' bgcolor='#427BC1' width='25%'>#427BC1</td>
			<td class='bgcolorselect_td' bgcolor='#CFA376' width='25%'>#CFA376</td>
			<td class='bgcolorselect_td' bgcolor='#5EA876' width='25%'>#5EA876</td>
			<td class='bgcolorselect_td' bgcolor='#A0826B' width='25%'>#8C8773</td>
		</tr>
		<tr>
			<td class='bgcolorselect_td' bgcolor='#DE7B5F' width='25%'>#DE7B5F</td>
			<td class='bgcolorselect_td' bgcolor='#74929A' width='25%'>#74929A</td>
			<td class='bgcolorselect_td' bgcolor='#AA240D' width='25%'>#AA240D</td>
			<td class='bgcolorselect_td' bgcolor='#238377' width='25%'>#238377</td>
		</tr>
	</table>
</div>


<div style='clear:both; margin-left:auto; margin-right:auto; margin-top:15px;'>
	<table cellpadding=0 cellspacing=2 border=0 width='400' align=center>
		<tr>
			<td colspan=4>
			<b>Change Password</b>
			</td>
		</tr>
		<tr>
			<td width='50%'>Current Password : </td>
			<td width='50%'><input type='password' id='upwd_cpass'></td>
		</tr>
		<tr>
			<td width='50%'>New Password : </td>
			<td width='50%'><input type='password' id='upwd_nupass'></td>
		</tr>
		<tr>
			<td width='50%'>Retype New Password : </td>
			<td width='50%'><input type='password' id='upwd_rnupass'></td>
		</tr>
		<tr>
			<td width='100%' align='center' colspan=2>
				<span class="bluebutton" onclick="updatePassword_do()">&nbsp;Update&nbsp;</span>	
			</td>
		</tr>
	</table>
</div>

<div style='clear:both; margin-left:auto; margin-right:auto; margin-top:25px;'>
	<table cellpadding=0 cellspacing=2 border=0 width='90%' align=center>
		<tr>
			<Td align=center>
			<b><nobr>iCal Reminders URL</nobr></b>
			</td>
		</tr>
		<tr>
			<td>
				<?php 
				$tmp_manageUsers = new manageUsers() ;
				$this_key = $tmp_manageUsers->get_userSingleDetail( $username, 'remindersicalkey' );
				echo 'http://'.$_SESSION["subdomain"].'.discreteevents.com/ical_reminders.php?key='.$this_key ; ?> 
			</td>
		</tr>
		<tr>
			<td align='center'>
				<span class="bluebutton" onclick="newIcalURL()">&nbsp;Generate new iCal URL&nbsp;</span>	
			</td>
		</tr>
	</table>
</div>

<?php
include "include_footer.php";
?>