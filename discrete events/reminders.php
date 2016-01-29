<?php

include_once "include_db.php";
include_once "include_functions.php";
checkUserSessionandCookie();
include_once "include_header.php";
$username = $_SESSION["uname"];

?>
<SCRIPT>

var localajaxinit = function(){
	My_JsLibrary.selectMainTab('reminders.php');
	$($.date_input.initialize);

	$("TD.editReminder").click(function(){
		var emid = $(this).attr('emailid');
		EDIT_SCHEDULEDEMAILID = emid;
		edit_scheduledEmail(emid);
	});

}; // End of localajaxinit


var edit_scheduledEmail = function(emid){
	DE_USER_action( 'get_scheduledEmailDetails' , {
		emailId : emid,
		callback:function(a){
			if(a){
				eval( My_JsLibrary.responsemsg );
				tmp_thisemail['email_subject'] = Base64.decode(tmp_thisemail['email_subject']);
				tmp_thisemail['email_body'] = Base64.decode(tmp_thisemail['email_body']);
				_$('nu_scEmail_to').value = tmp_thisemail['email_to'] ;
				_$('nu_scEmail_subject').value = tmp_thisemail['email_subject'] ;
				_$('nu_scEmail_body').value = tmp_thisemail['email_body'] ;
				_$('nu_scEmail_when').value = tmp_thisemail['email_date'] ;
				My_JsLibrary.selectbox.selectOption(_$('nu_scEmail_hr') , tmp_thisemail['email_hour'].addZero() );
				$("#ScheduleEmail_Form_Title").html('Edit Reminder');
				$('#ScheduleEmail_Form').showWithBg();
			}else{

			}
		}
	});
};


var delete_scheduledEmail = function(schid){
	if( !confirm('Sure ?') ){return;}

	DE_USER_action( 'deleteScheduledEmail' , {
		schid : schid,
		callback:function(a){
			if(a){
				window.location.reload();
			}else{
				My_JsLibrary.showErrMsg() ;
			}
		}
	});
};

</SCRIPT>

<div style='display:block !important; height: 20px !important; margin: -18px 0 0 !important;padding: 0 !important; position: fixed !important; top: 45% !important; width: 17px; z-index: 100000 ; right:0; background-color: #ECECEC ;' onclick="My_JsLibrary.windowReload();" TITLE="Reload Page">
	<img src='images/icon_refresh.gif' border=0>
	<span id='span_refreshcountdown'></span>
</div>

<?php
// List Scheduled Emails
	$scheduledEmails = new scheduledEmails();
	$scheduledEmails->listScheduledEmails();
?>
<?php
	include "include_footer.php";
?>