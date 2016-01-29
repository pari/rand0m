<?php

include_once "include_db.php";
include_once "include_functions.php";
checkUserSessionandCookie();
if(IsSadmin()){
	header("Location: users.php");
	exit();
}
include_once "include_header.php";
$username = $_SESSION["uname"];

	$manageProjects = new manageProjects();
	//$manageUsers = new manageUsers();
?>
<SCRIPT>
var EMAILTASKID = '';
var DOIRELOADNOW = false;
var RESCHEDULETASKID = '';
var ISRESCHEDULETASKOWNER = false;
var NEWTASKFORM_VISIBLE = false;


var localajaxinit = function(){

	$(document).bind('keydown', 'ctrl+n', function(event){
		ManageTasksJsFunction.createNewTask_form();
		event.preventDefault();
	});

	$(document).bind('keydown', 'esc', function(){
		if(NEWTASKFORM_VISIBLE==true){
			ManageTasksJsFunction.NewTaskForm_hide();
		}
	});

	<?php if( get_GET_var('list') == 'ptasks' ){ ?>
		My_JsLibrary.selectMainTab('welcome.php?list=ptasks');
	<?php }else{ ?>
		My_JsLibrary.selectMainTab('welcome.php');
	<? } ?>
	$($.date_input.initialize);
	$(document.body).bind('mousemove', function(){
		DOIRELOADNOW = false;
	});
	
	if( My_JsLibrary.cookies.getCookie('NWTBV') == 'false' ){
		$('.NewWorksTable').hide();
	}
	if( My_JsLibrary.cookies.getCookie('WIPT') == 'false' ){
		$('.WorksInProgressTable').hide();
	}
	if( My_JsLibrary.cookies.getCookie('WCPT') == 'false' ){
		$('.WorksCompletedTable').hide();
	}
	if( My_JsLibrary.cookies.getCookie('WCLT') == 'false' ){
		$('.WorksClosedTable').hide();
	}
	
	$('#nutask_Private').click(function(){
		if(this.checked){
			$('#nutask_Private_explanation').html('Uncheck to make this task public');
		}else{
			$('#nutask_Private_explanation').html('Check to make this task private');
		}
	});

	$('#chk_showOnlyMyTasks').click(function(){
		var isThisChecked = this.checked;
		var list_tds = $("TD.AssignedUser") ;
		for( var i=0; i < list_tds.length; i++ ){
			var this_td = list_tds[i] ;
			var this_td_assignedby = $(list_tds[i]).attr('assignedby');
			var this_td_assignedto = $(list_tds[i]).attr('assignedto');
			if( this_td_assignedby == '<?php echo $username; ?>' || this_td_assignedto == '<?php echo $username; ?>'){

			}else{
				if(isThisChecked){
					$(list_tds[i]).parents('tr:eq(0)').hide();
				}else{
					$(list_tds[i]).parents('tr:eq(0)').show();
				}
			}
		}
	});

	ManageTasksJsFunction.showReschedulePopupForTask = function(taskID){
		var imgs = $('img.image_rescheduleTask');
		for(var t=0; t< imgs.length; t++){
			var thisimg = imgs[t];
			if($(thisimg).attr('taskid') == taskID ){
				$(thisimg).click();
				break;
			}
		}
	};


	$('img.addreminder_bell').click(function(){
		var taskid = $(this).attr('taskid');
		DE_USER_action( 'getAllDetailsOfaTask' , {
			workid : taskid,
			callback:function(a){
				if(a){
					var tmp_op = 'var THIS_DETAILS = ' + My_JsLibrary.responsemsg + ' ;' ;
					eval(tmp_op);
					var subj = '[Reminder] ' + Base64.decode( THIS_DETAILS['bdesc'] );
					var body =  Base64.decode( THIS_DETAILS['notes'] ) ;
					show_scheduleEmailForm(subj, body);
				}else{
					My_JsLibrary.showErrMsg() ;
				}
			}
		});


	});
	

	$('img.image_rescheduleTask').click(function(){
		var a = this;
		RESCHEDULETASKID = $(a).attr('taskid');
		var isOwner = $(a).attr('isowner');
		if( isOwner=='YES' ){
			ISRESCHEDULETASKOWNER = true;
			$('#reschedule_date_container').show();
		}else{
			ISRESCHEDULETASKOWNER = false;
			$('#reschedule_date_container').hide();
		}
		My_JsLibrary.alignBbelowA( a , _$('DivRescheduleTask'), -300 , -10);
		$('#DivRescheduleTask').showWithBg();
		_$('reschedule_date').value = $(a).attr('deadline');
	});

	$('img.emailTaskBescImg').click(function(){
		var a = this;
		EMAILTASKID = $(a).attr('taskid');
		My_JsLibrary.alignBbelowA( a , _$('DivEmailTaskDetails'));
		$('#DivEmailTaskDetails').showWithBg();
	});

	$('#chk_ScheduleTask').click(function(){
		_$('text_daysb4deadline').disabled = !_$('chk_ScheduleTask').checked ;
		_$('text_oncompletionof').disabled = !_$('chk_taskontask').checked ;
		if(_$('chk_ScheduleTask').checked){
			_$('chk_taskontask').checked = false ;
		}
	});

	$('#chk_taskontask').click(function(){
		_$('text_daysb4deadline').disabled = !_$('chk_ScheduleTask').checked ;
		_$('text_oncompletionof').disabled = !_$('chk_taskontask').checked ;
		if(_$('chk_taskontask').checked){
			_$('chk_ScheduleTask').checked = false ;
		}
	});

}; // End of localajaxinit


ManageTasksJsFunction.emailTaskDescription = function(){ // ManageTasksJsFunction.emailTaskDescription()
	//EMAILTASKID
	var includeDetails = ( _$('DivEmailTaskDetails_radio_desc').checked ) ? 'no' : 'yes' ;
	var includeAttachments = ( _$('DivEmailTaskDetails_chkbox_AndAttachments').checked ) ? 'yes' : 'no' ;

	if( ! My_JsLibrary.checkRequiredFields( ['DivEmailTaskDetails_email'] ) ){
		return;
	}
			
	DE_USER_action( 'emailTaskDetails' , {
		emailId : _$('DivEmailTaskDetails_email').value ,
		workid : EMAILTASKID,
		includeDetails : includeDetails,
		attachments : includeAttachments,
		callback:function(a){
			if(a){
				My_JsLibrary.showfbmsg( 'Email sent !', 'green');
				$('#DivEmailTaskDetails').hideWithBg();
			}else{
				My_JsLibrary.showErrMsg() ;
			}
		}
	});
};


ManageTasksJsFunction.rescheduleTask = function(){ // ManageTasksJsFunction.rescheduleTask()
	var reschedule_date = _$('reschedule_date').value ;
	var reschedule_daysb4 = (_$('reschedule_chk_daysb4').checked) ? _$('reschedule_daysb4').value : '0' ;
	var reschedule_afterTask = (_$('reschedule_chk_afterTask').checked) ? _$('reschedule_afterTask').value : '0' ;

	if( !ISRESCHEDULETASKOWNER ){
		if( !_$('reschedule_chk_daysb4').checked && !_$('reschedule_chk_afterTask').checked){
			alert('Please choose a Reshedule Option'); return;
		}
	}

	if( _$('reschedule_chk_daysb4').checked ){
	
	}

	if( _$('reschedule_chk_afterTask').checked && _$('reschedule_afterTask').value == '' ){
		alert('Please enter \'Reshedule After\' TaskID'); return;
	}

	DE_USER_action( 'rescheduleTask' , {
		taskId : RESCHEDULETASKID ,
		reschedule_date : reschedule_date,
		reschedule_daysb4 : reschedule_daysb4,
		reschedule_afterTask : reschedule_afterTask,
		callback:function(a){
			if(a){
				window.location.reload();
			}else{
				My_JsLibrary.showErrMsg() ;
			}
		}
	});
};


var pingAndReload = function(){
	DE_USER_action( 'ping' , {
		callback:function(a){ if(a){ My_JsLibrary.windowReload(); }else{ return; } }
	});
};

setInterval(function(){ DOIRELOADNOW = true; }, <?php echo REFRESHTIME-60000 ; ?>);
setInterval(function(){ if(DOIRELOADNOW){ pingAndReload(); }} , <?php echo REFRESHTIME ; ?>);

</SCRIPT>
<div style='display:block !important; height: 20px !important; margin: -18px 0 0 !important;padding: 0 !important; position: fixed !important; top: 45% !important; width: 17px; z-index: 100000 ; right:0; background-color: #ECECEC ;' onclick="My_JsLibrary.windowReload();" TITLE="Reload Page">
	<img src='images/icon_refresh.gif' border=0>
	<span id='span_refreshcountdown'></span>
</div>


<div id='DivEmailTaskDetails' style='position:absolute; z-index: 900; top:0; right:0; background-color: #FFFFFF; width: 400px; display: none; border: 1px solid #515CBC;'>
	<div style='float:left; padding:3px;color: #BD8E6C; font-weight: bold;'>Email Task Details</div>
	<div style='float:right; padding:3px;'><img src='/images/close.gif' border=0 onclick="$('#DivEmailTaskDetails').hideWithBg();"></div>
	<div style='clear:both;'></div>
	<div style='padding:3px; float:left; margin-left:20px;'>
		Email to : <input type="text" size=30 id='DivEmailTaskDetails_email'>
	</div>
	<div style='clear:both;'></div>
	<div style='padding:3px; margin-left:20px;'>
		<label>
		<input type="radio" name="DivEmailTaskDetails_details_radio" id='DivEmailTaskDetails_radio_desc' value="desc"> Only Description
		</label>
	</div>
	<div style='padding:3px; margin-left:20px;'>
		<label>
		<input type="radio" name="DivEmailTaskDetails_details_radio" id='DivEmailTaskDetails_radio_alldetails' value="alldetails" checked> All Details of this task
		</label>
	</div>

	<div style='padding:3px; margin-left:20px;'>
		<label>	<input type="checkbox" id='DivEmailTaskDetails_chkbox_AndAttachments'> Include Attachments </label>
	</div>

	<div style='clear:both;'></div>
	<div style='padding:3px; margin-left:auto; margin-right:auto; margin-bottom: 10px; text-align:center; width: 390px;'>
		<span class='bluebuttonSmall' onclick='ManageTasksJsFunction.emailTaskDescription();'>Send</span>
	</div>
</div>


<div id='DivRescheduleTask' style='text-align:center; position:absolute; z-index: 900; top:0; right:0; background-color: #FFFFFF; width: 400px; display: none; border: 2px solid #449AC4;'>
	<div style='float:left; padding:3px;color: #BD8E6C; font-weight: bold;'>Re-Schedule Task</div>
	<div style='float:right; padding:3px;'><img src='/images/close.gif' border=0 onclick="$('#DivRescheduleTask').hideWithBg();"></div>
	<div style='clear:both;'></div>
	<div style='clear:both; padding:3px; margin-left:auto; margin-right:auto; width: 295px;' id='reschedule_date_container'>
		Reschedule to <input type='text' id='reschedule_date' size=12 class='date_input'>
	</div>
	<div style='padding:3px; float:left; margin-left:20px;'>
		<input type='checkbox' id='reschedule_chk_daysb4' onclick=" if( _$('reschedule_chk_daysb4').checked ){ _$('reschedule_daysb4').disabled = false ; _$('reschedule_chk_afterTask').checked = false; _$('reschedule_afterTask').disabled = true; }" checked> Appear in task list <input type='text' id='reschedule_daysb4' size=2 value='1'> days before
	</div>
	<div style='padding:3px; float:left; margin-left:20px;'>
		<input type='checkbox' id='reschedule_chk_afterTask' onclick=" if( _$('reschedule_chk_afterTask').checked ) { _$('reschedule_daysb4').disabled = true; _$('reschedule_afterTask').disabled = false; _$('reschedule_chk_daysb4').checked = false; }"> Appear in task list  after completion of task <input type='text' id='reschedule_afterTask' size=5 value='' disabled>
	</div>
	<div style='clear:both;'></div>
	<div style='padding:3px; margin-left:auto; margin-right:auto; margin-bottom: 10px;'>
		<span class='bluebuttonSmall' onclick='ManageTasksJsFunction.rescheduleTask();'>Reshcedule</span>
	</div>
</div>


<?php


include "include_addnewtaskform.php";


	$qa_report = new taskReports();
	$qa_report->doNotIncludePersonalCondition = true;
	$qa_report->showOnlyMyTasks = true;
	$qa_report->listQuickEmailTasks();


	$ureport = new taskReports();
	if( get_GET_var('list') == 'ptasks' ){
		$ureport->PersonalTasks = true;
	}else{
		$ureport->PersonalTasks = false;
	}
	$ureport->showOnlyMyTasks = false;

	$sortBy = get_GET_var('sortby');	
	if( $sortBy ){
		$ureport->orderbyfield = $sortBy ;
	}

	$clTasksPeriod = (get_GET_var('ctperiod')) ? get_GET_var('ctperiod') : 'thismonth' ;
	$ureport->closedTasks($clTasksPeriod);

	$ureport->list4sections();
	
include "include_footer.php";

?>