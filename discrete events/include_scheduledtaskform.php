<script>

var show_scheduleEmailForm = function(subj , body ){
	//My_JsLibrary.showdeadcenterdiv( 'ScheduleEmail_Form' );
	if(!subj){ var subj = '';}
	if(!body){ var body = '';}
	EDIT_SCHEDULEDEMAILID = 0 ;
	<?php
		$manageUsers = new manageUsers();
		$username = $_SESSION["uname"];
		echo "var myemail='". $manageUsers->get_userSingleDetail( $username, 'user_primaryEmail' ). "';";
		echo "var TomorrowCaldate='". getTomorrowCaldate(1). "';";
	?>
	$("#ScheduleEmail_Form_Title").html('Schedule a Reminder');
	_$('nu_scEmail_to').value = myemail ;
	_$('nu_scEmail_when').value = TomorrowCaldate ;
	_$('nu_scEmail_subject').value = subj ;
	_$('nu_scEmail_body').value = body ;

	$('#ScheduleEmail_Form').showWithBg();
	_$('nu_scEmail_subject').focus();
};


var schedule_newTask = function(){
	var emailTo = My_JsLibrary.getFieldValue('nu_scEmail_to');
	var emailSubject = My_JsLibrary.getFieldValue('nu_scEmail_subject');
	var emailBody = My_JsLibrary.getFieldValue('nu_scEmail_body');
	var emailWhen = My_JsLibrary.getFieldValue('nu_scEmail_when');
	var emailHour = My_JsLibrary.getFieldValue('nu_scEmail_hr');
	if( ! My_JsLibrary.checkRequiredFields( ['nu_scEmail_to', 'nu_scEmail_subject', 'nu_scEmail_when'] ) ){
		return;
	}
	var thisForm_action = (EDIT_SCHEDULEDEMAILID) ? 'updateScheduledEmail' : 'scheduleAnEmail' ;
	DE_USER_action( thisForm_action , {
		reminderId : EDIT_SCHEDULEDEMAILID,
		emailTo : emailTo,
		emailSubject : emailSubject,
		emailBody : emailBody,
		emailWhen : emailWhen,
		emailHour : emailHour,
		callback:function(a){
			if(a){
				window.location.reload();
			}else{
				My_JsLibrary.showErrMsg() ;
			}
		}
	});	
};

</script>
<div id="ScheduleEmail_Form" style="display:none; width: 740" class="divAboveBg">
	<TABLE width="740" cellpadding=0 cellspacing=2 border=0  class="divHeadingTable">
	<TR><TD onmousedown="My_JsLibrary.startDrag(event);" class="drag_dialog_title" id='ScheduleEmail_Form_Title'>Schedule a Reminder</TD>
		<TD onclick="My_JsLibrary.hideDrag(event);" width="19"><img src="/images/close.gif" border=0></TD>
	</TR>
	</TABLE>
	<TABLE width="738" cellpadding="4" cellspacing=0 border=0>
		<TR><TD align="right"><NOBR>Email To :</NOBR></TD>
			<TD><input type="text" size=30 id="nu_scEmail_to" class="hilight"></TD>
		</TR>

		<TR><TD align="right"><NOBR>Email Subject :</NOBR></TD>
			<TD><input type="text" size=50 id="nu_scEmail_subject" class="hilight"></TD>
		</TR>

		<TR><TD align="right" valign='top'><NOBR>Email Body :</NOBR></TD>
			<TD><textarea id="nu_scEmail_body" class="hilight" rows=4 cols=40></textarea></TD>
		</TR>

		<TR><TD align="right"><NOBR>When :</NOBR></TD>
			<TD>
				<input type="text" size=12 id="nu_scEmail_when" class="date_input">
				&nbsp;
				<select id='nu_scEmail_hr'>
					<option value='00'>12 AM</option>
					<option value='01'>01 AM</option>
					<option value='02'>02 AM</option>
					<option value='03'>03 AM</option>
					<option value='04'>04 AM</option>
					<option value='05'>05 AM</option>
					<option value='06'>06 AM</option>
					<option value='07'>07 AM</option>
					<option value='08'>08 AM</option>
					<option value='09' selected>09 AM</option>
					<option value='10'>10 AM</option>
					<option value='11'>11 AM</option>
					<option value='12'>12 PM</option>
					<option value='13'>01 PM</option>
					<option value='14'>02 PM</option>
					<option value='15'>03 PM</option>
					<option value='16'>04 PM</option>
					<option value='17'>05 PM</option>
					<option value='18'>06 PM</option>
					<option value='19'>07 PM</option>
					<option value='20'>08 PM</option>
					<option value='21'>09 PM</option>
					<option value='22'>10 PM</option>
					<option value='23'>11 PM</option>
				</select>
			</TD>
		</TR>
		<TR>
			<TD></TD>
			<TD style="padding:10px;">
				<span class="bluebuttonSmall" onclick="schedule_newTask();" id='span_createTaskSubmit'>Submit</span>
			</TD>
		</TR>
	</TABLE>
</div>