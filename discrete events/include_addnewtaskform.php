<script>
	<?php
	returnUsersUnderProjectsJSObject($username); //var MYPROJECTS_USERS
	?>

	var update_ProjectUsers = function(){
		var project = _$('nutask_project');
		var users = _$('nutask_userassigned');
		var ac = project.value ;
		My_JsLibrary.selectbox.clear(users);
		//My_JsLibrary.selectbox.append(users,'none','none');
		for( var i=0 ; i < MYPROJECTS_USERS[ac].length ; i++ ){
			var t = MYPROJECTS_USERS[ac][i];
			My_JsLibrary.selectbox.append(users,t,t);
		}
		users.selectedIndex = 0;
	};

	ManageTasksJsFunction.NewTaskForm_hide = function(){
		NEWTASKFORM_VISIBLE = false;
		$('#CreateTask_Form').hideWithBg();
	};

	ManageTasksJsFunction.createNewTask = function(){
		var nutask_project = My_JsLibrary.getFieldValue('nutask_project');
		var nutask_bdesc = My_JsLibrary.getFieldValue('nutask_bdesc');
		var nutask_notes = My_JsLibrary.getFieldValue('nutask_notes');
		var nutask_priority = My_JsLibrary.getFieldValue('nutask_priority');
		var nutask_userassigned = My_JsLibrary.getFieldValue('nutask_userassigned');
		var nutask_deadline = My_JsLibrary.getFieldValue('nutask_deadline');
		var nutask_Private = _$('nutask_Private').checked ? 'Y' : 'N' ;
		var chk_ScheduleTask = My_JsLibrary.getFieldValue('chk_ScheduleTask');

		if( ! My_JsLibrary.checkRequiredFields( ['nutask_project','nutask_bdesc', 'nutask_userassigned', 'nutask_deadline' ] ) ){
			return;
		}

		if( chk_ScheduleTask == 'yes' ){
			var daysb4deadline = My_JsLibrary.getFieldValue('text_daysb4deadline');
			if(!daysb4deadline){daysb4deadline = '0' ;}
		}else{
			var daysb4deadline = '0';
		}
		if(_$('chk_taskontask').checked){
			var onCompletionOf = My_JsLibrary.getFieldValue('text_oncompletionof');
			if(!onCompletionOf){onCompletionOf='0';}
		}else{
			var onCompletionOf = '0';
		}

		$('#span_createTaskSubmit').hide();
		var thisformaction = (EDITEMAILTASKID) ? 'editEmailTasktoNew' : 'createNewTask' ;
		var nutask_notify = (_$('nutask_notify').checked ) ? 'Y' : 'N' ;
		
		DE_USER_action( thisformaction , {
			editemailtaskId : EDITEMAILTASKID ,
			projectName : nutask_project,
			briefDesc : nutask_bdesc,
			notes: nutask_notes,
			priority: nutask_priority,
			userassigned : nutask_userassigned,
			deadline : nutask_deadline,
			isprivate : nutask_Private,
			daysb4deadline : daysb4deadline,
			onCompletionOf : onCompletionOf,
			notifyAssigned : nutask_notify,
			callback:function(a){
				$('#span_createTaskSubmit').show();
				if(a){
					//alert(My_JsLibrary.responsemsg);
					if ( thisformaction == 'editEmailTasktoNew' ){
						My_JsLibrary.windowReload();
						return;
					} 

					var tmp_get_list = My_JsLibrary.parseGETparam('list');
					var defaultpersonalproject = '<?php echo DEFAULTPERSONALPROJECT; ?>' ;
					if( tmp_get_list == 'ptasks' && nutask_project != defaultpersonalproject ){
						My_JsLibrary.showfbmsg(My_JsLibrary.responsemsg, 'green');
						$("#CreateTask_Form").hideWithBg();
					}else if( tmp_get_list != 'ptasks' && nutask_project == defaultpersonalproject ){
						My_JsLibrary.showfbmsg(My_JsLibrary.responsemsg, 'green');
						$("#CreateTask_Form").hideWithBg();
					}else{
						My_JsLibrary.windowReload();
					}
				}else{
					My_JsLibrary.showErrMsg() ;
				}
			}
		});
	};

</script>

<div id="CreateTask_Form" style="display:none; width: 740" class="divAboveBg">
	<TABLE width="740" cellpadding=0 cellspacing=2 border=0  class="divHeadingTable">
	<TR><TD onmousedown="My_JsLibrary.startDrag(event);" class="drag_dialog_title">Create New Task</TD>
		<TD onclick="ManageTasksJsFunction.NewTaskForm_hide()" width="19"><img src="/images/close.gif" border=0></TD>
	</TR>
	</TABLE>
	<TABLE width="738" cellpadding="4" cellspacing=0 border=0>
		<TR><TD align="right"><NOBR>Under Project :</NOBR></TD>
			<TD>
				<select id="nutask_project" onchange='update_ProjectUsers()'>
				<?php
					$tmp_projects_all = $manageProjects->getActiveProjectsListOfUser($username) ;
					html_array2selectboxOptions_selected( $tmp_projects_all, DEFAULTPROJECT );
				?>
				</select>
				<span style='margin-left:20px;'>
					Assign To: <select id='nutask_userassigned'></select>
				</span>
			</TD>
		</TR>
		<TR><TD align="right"><NOBR>Deadline :</NOBR></TD>
			<TD><input type="text" size=12 id="nutask_deadline" class="date_input"></TD>
		</TR>
		<TR><TD align="right"><NOBR>Brief Description :</NOBR></TD>
			<TD><input type="text" size=50 id="nutask_bdesc" class="hilight"></TD>
		</TR>
		<TR><TD align="right" valign="top"><NOBR>Notes :</NOBR></TD>
			<TD><textarea id="nutask_notes" rows=2 cols=40 class="hilight"></textarea></TD>
		</TR>
		<TR><TD align="right">Priority :</TD>
			<TD><select id='nutask_priority'>
					<option value='H'>High</option><option value='N' selected>Normal</option><option value='L'>Low</option>
				</select>
			</TD>
		</TR>
		<TR><TD align="right">Visibility :</TD>
			<TD><label><input type="checkbox" id="nutask_Private"> <span id='nutask_Private_explanation'>Only User can see</span> </label></TD>
		</TR>
		
		<TR>
			<TD align="right">Show in task list :</TD>
			<TD><input type='checkbox' id='chk_ScheduleTask'>&nbsp;
				<input type=text size=2 id='text_daysb4deadline'> days before deadline </TD>
		</TR>
		<TR><TD align="right"> </TD>
			<TD><input type='checkbox' id='chk_taskontask'> on closing of Task ID : <input type=text size=4 id='text_oncompletionof'></TD>
		</TR>
		<TR><TD align="right">Notify :</TD>
			<TD><label><input type="checkbox" id="nutask_notify"> Email assigned person about creation of this task</label></TD>
		</TR>
		<TR>
			<TD></TD>
			<TD style="padding:10px;">
				<span class="bluebuttonSmall" onclick="ManageTasksJsFunction.createNewTask()" id='span_createTaskSubmit'>Create Task</span>
			</TD>
		</TR>
	</TABLE>
</div>


