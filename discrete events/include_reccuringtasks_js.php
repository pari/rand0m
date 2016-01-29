<script>


var show_ReccuringTaskForm = function(){
	$("#CreateReccuringTask_Form").showWithBg();
};

	<?php
	returnUsersUnderProjectsJSObject($_SESSION["uname"]); //var MYPROJECTS_USERS
	?>

var update_ProjectUsers = function(){
	var project = _$('nrt_project');
	var users = _$('nrt_assignTo');
	var ac = project.value ;
	My_JsLibrary.selectbox.clear(users);
	//My_JsLibrary.selectbox.append(users,'none','none');
	for( var i=0 ; i < MYPROJECTS_USERS[ac].length ; i++ ){
		var t = MYPROJECTS_USERS[ac][i];
		My_JsLibrary.selectbox.append(users,t,t);
	}
	users.selectedIndex = 0;
};


var delete_recurringtask = function(rtid){
	if(!confirm('Sure you want to delete this recurring task ?')){return;}
	
	DE_USER_action( 'deleteRecurringTask' , {
		RTID : rtid ,
		callback: function(a){
			if(a){
				My_JsLibrary.windowReload();
			}else{
				My_JsLibrary.showErrMsg() ;
			}
		}
	});
};

var create_newRecurringTask = function(){
	var nrt_project = _$('nrt_project').value ;
	var nrt_assignTo = _$('nrt_assignTo').value;
	var nrt_startDate = _$('nrt_startDate').value ;
	var nrt_endDate = _$('nrt_endDate').value ;
	var nrt_type = '';
	
	if( _$('nrt_frq_EVERYDAYOFYEAR_radio').checked ){
		nrt_type = 'Y';
		var nrt_frq_EVERYDAYOFYEAR_day = _$('nrt_frq_EVERYDAYOFYEAR_day').value ;
		var nrt_frq_EVERYDAYOFYEAR_month = _$('nrt_frq_EVERYDAYOFYEAR_month').value ;
	}
	if( _$('nrt_frq_weekday_radio').checked ){ nrt_type = 'W'; var nrt_frq_weekday = _$('nrt_frq_weekday').value ; }
	if( _$('nrt_frq_ndays_radio').checked ){ 
		//alert('Recurring Tasks for every N days is not yet implemented !'); return;
		nrt_type = 'N'; 
		var nrt_frq_ndays = _$('nrt_frq_ndays').value ; 
	}
	if( _$('nrt_frq_dayofmonth_radio').checked ){ nrt_type = 'M'; var nrt_frq_dayofmonth = _$('nrt_frq_dayofmonth').value ; }
	
	
	var nrt_isPublic = (_$('nrt_isPublic').checked) ? 'Y' : 'N' ;
	
	DE_USER_action( 'newRecurringTask' , {
		nrt_project : nrt_project ,
		nrt_assignTo : nrt_assignTo ,
		nrt_desc : _$('nrt_desc').value ,
		nrt_startDate : nrt_startDate ,
		nrt_endDate : nrt_endDate ,
		nrt_type : nrt_type ,
		nrt_frq_EVERYDAYOFYEAR_day : nrt_frq_EVERYDAYOFYEAR_day ,
		nrt_frq_EVERYDAYOFYEAR_month : nrt_frq_EVERYDAYOFYEAR_month ,
		nrt_frq_weekday : nrt_frq_weekday ,
		nrt_frq_ndays : nrt_frq_ndays ,
		nrt_frq_dayofmonth : nrt_frq_dayofmonth ,
		nrt_isPublic : nrt_isPublic ,
		nrt_deadlineDays: _$('nrt_deadlineDays').value ,
		callback:function(a){
			if(a){
				My_JsLibrary.windowReload();
			}else{
				My_JsLibrary.showErrMsg() ;
			}
		}
	});

};




</script>

<div id="CreateReccuringTask_Form" style="display:none; width: 740" class="divAboveBg">
	<TABLE width="740" cellpadding=0 cellspacing=2 border=0  class="divHeadingTable">
	<TR><TD onmousedown="My_JsLibrary.startDrag(event);" class="drag_dialog_title" id='CreateReccuringTask_Form_Title'>Create a Recurring Task</TD>
		<TD onclick="My_JsLibrary.hideDrag(event);" width="19"><img src="/images/close.gif" border=0></TD>
	</TR>
	</TABLE>
	<TABLE width="738" cellpadding="4" cellspacing=0 border=0>
		<TR>
			<TD align="right"><NOBR>Under Project :</NOBR></TD>
			<TD>
				
				<select id='nrt_project'  class="hilight"  onchange='update_ProjectUsers()'>
					<?php
					$manageProjects = new manageProjects();
					$tmp_projects_all = $manageProjects->getActiveProjectsListOfUser($_SESSION["uname"]) ;
					html_array2selectboxOptions_selected( $tmp_projects_all, DEFAULTPROJECT );	
					?>
				</select>
			</TD>
			<TD align="right"><NOBR>Assign To :</NOBR></TD>
			<TD>
				<select id='nrt_assignTo'  class="hilight"></select>
			</TD>
		</TR>
		<TR>
			<TD align="right">Description :</TD>
			<TD colspan=3><input type=text size=55 id='nrt_desc'></TD>
		</TR>
		<TR>
			<TD align="right"><NOBR>Start Date :</NOBR></TD>
			<TD><input type="text" size=12 id="nrt_startDate" class="date_input"></TD>
			<TD align="right"><NOBR>End Date :</NOBR></TD>
			<TD><input type="text" size=12 id="nrt_endDate" class="date_input"></TD>
		</TR>
		<TR>
			<TD align="right">Visibility:</TD>
			<TD colspan=3>
				<label><input type='checkbox' id='nrt_isPublic'> Check to make this task public </label></TD>
		</TR>
		
		<TR>
			<TD align="right"> Create Task :</TD>
			<TD colspan=3>
				<input type=text size=2 maxlength=2 value='2' id='nrt_deadlineDays'> days before deadline
			</TD>
		</TR>
		
		<TR>
			<TD align="right">Occurrence : </TD>
			<TD colspan=3>
				<label>
				<input type='radio' id='nrt_frq_EVERYDAYOFYEAR_radio' name='nrt_frq_raadio'>&nbsp;
				On <select id='nrt_frq_EVERYDAYOFYEAR_day'>
				<?php
					for( $d =1; $d< 32 ; $d++){
						echo "<option value='$d'>".dayofmonth_to_daywithsuffix($d)."</option>";
					}
				?>
				</select>
				 of every <select id='nrt_frq_EVERYDAYOFYEAR_month'>
				 	<?php
				 		foreach( array('jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec') as $mon ){
							echo "<option value='$mon'>".month_short_toLong($mon)."</option>";
						}
				 	?>
				 </select> 
				</label>
			</TD>
				
		</TR>
		
		<TR>
			<TD align="right"></TD>
			<TD colspan=3>
				<label>
				<input type='radio' id='nrt_frq_weekday_radio' name='nrt_frq_raadio'>&nbsp;
				Every <select id='nrt_frq_weekday'>
					<?php
				 		foreach( array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun') as $wday ){
							echo "<option value='$wday'>".week_short_toLong($wday)."</option>";
						}
				 	?>
				</select>
				</label>
			</TD>
		</TR>

		<TR>
			<TD align="right"></TD>
			<TD colspan=3>
				<label>
				<input type='radio' id='nrt_frq_ndays_radio' name='nrt_frq_raadio'>&nbsp;
				Every <input size=2 id='nrt_frq_ndays'> days
				</label>
			</TD>
		</TR>
		
		<TR>
			<TD align="right"></TD>
			<TD colspan=3>
				<label>
				<input type='radio' id='nrt_frq_dayofmonth_radio' name='nrt_frq_raadio'>&nbsp;
				On 
				<select id='nrt_frq_dayofmonth'>
					<?php
						for( $d =1; $d< 32 ; $d++){
							echo "<option value='$d'>".dayofmonth_to_daywithsuffix($d)."</option>";
						}
					?>
				</select>
				 of every month 
				</label>
			</TD>
		</TR>
		
		
		<TR>
			<TD></TD>
			<TD style="padding:10px;">
				<span class="bluebuttonSmall" onclick="create_newRecurringTask();" id='span_createReccuringTaskSubmit'>Submit</span>
			</TD>
		</TR>
	</TABLE>
</div>