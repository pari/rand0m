<div style='display:block; z-index: 100; position: absolute; top: 0; width: 15px; height:16; background-color: transparent; right: 100px; color: #FFFFFF; cursor: pointer; font-weight:bold; padding:3px;' onclick="$('#dailytasks_checklist_container').slideDown('normal');" TITLE="Daily Routine Check List">
	&darr;
</div>

<script>

var newRoutineTask = function(){
	var nrt = prompt('Enter new routine task', '');
	if(!nrt)return;

	DE_USER_action( 'newRoutineTask' , {
		nrt : nrt,
		callback:function(a){
			if(a){ window.location.reload(); }else{ My_JsLibrary.showErrMsg() ; }
		}
	});
};


var resetAllRoutineTasks = function(){
	DE_USER_action( 'resetRoutineTasksStatus' , {
		callback:function(a){
			if(a){
				My_JsLibrary.showfbmsg('all tasks were reset !','green');
				var tmp_chkboxes = $('input.dailyroutinetask');
				for( var t=0; t < tmp_chkboxes.length ; t++ ){
					tmp_chkboxes[t].checked = false;
				}
			}else{ My_JsLibrary.showErrMsg() ; }
		}
	});
}

$(document).ready(function(){
	$('input.dailyroutinetask').click(function(){
		var dclid = $(this).attr('dclid') ;
		var task_status = (this.checked) ? 'Y' :'N';
		DE_USER_action( 'updateTasksStatus' , {
			dclid:dclid,
			task_status: task_status,
			callback:function(a){
				if(a){ My_JsLibrary.showfbmsg('Updated Status !','green'); }else{ My_JsLibrary.showErrMsg() ; }
			}
		});
	});
	
	
	$('img.deleteDailyRoutineTask_img').click(function(){
		var dclid = $(this).attr('dclid') ;
		if(!confirm('Sure ?'))return;
		DE_USER_action( 'deleteDailyRoutineTask' , {
			dclid: dclid,
			callback:function(a){
				if(a){ window.location.reload(); }else{ My_JsLibrary.showErrMsg() ; }
			}
		});
	});
	
	
	$('img.deleteDailyRoutineTasks_show_img').click(function(){
		$('img.deleteDailyRoutineTasks_show_img').hide();
		$('img.deleteDailyRoutineTask_img').show();
	});
	
}); 

</script>


<div id='dailytasks_checklist_container' style='display:none; z-index: 103; position:absolute; right: 100px; top:0px; width: 300px; background-color: #FFFFFF; padding: 6px; border: 2px solid #A7A7A7;'>
	
	<div style='margin-bottom:6px;'>
		<div style='float:left; font-size:110%; color: #929292; font-weight:bold; '> Daily Routine Check list </div>
		<div style='float:right;'>
			<img src='/images/close.gif' border=0 onclick="$('#dailytasks_checklist_container').slideUp('fast');">
		</div>
	</div>
	
	<div style='clear:both;'>
		<?php
		function listDailyCheckList(){
			$username = $_SESSION["uname"];
			$query = mysql_query("select dclid, task, status from dailychecklist where username='$username' ") ;
			WHILE( $row = @mysql_fetch_array($query) ){
				extract($row) ; // dclid , username , task, status
				$checked_str = ($status == 'Y')? ' checked': '';
			
				echo "<div style='padding:3px;'>
						<label>
							<input type='checkbox' class='dailyroutinetask' dclid='$dclid' {$checked_str}>&nbsp;".stripslashes($task)."
						</label>
						&nbsp;&nbsp;<img src='images/trash.gif' style='display:none;' class='deleteDailyRoutineTask_img' dclid='$dclid' border=0>
					</div>";
			
			}
		}
		listDailyCheckList();
		?>
	</div>
	
	<HR/>
	<div style='padding:4px;'>
		<span class="bluebuttonSmall" onclick="resetAllRoutineTasks();">reset tasks</span>
		<span class="bluebuttonSmall" onclick="newRoutineTask();" style='margin-left:15px;'>new RoutineTask</span>
		<span style='float:right; margin-right:10px;'>
			<img src='images/trash.gif' class='deleteDailyRoutineTasks_show_img' border=0 title='delete tasks from Daily Routine'>
		</span>
	</div>
	<div style='clear:both;'></div>
</div>
