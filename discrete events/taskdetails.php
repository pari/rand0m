<?php

include_once "include_db.php";
include_once "include_functions.php";
checkUserSessionandCookie();
$USERNAME = $_SESSION["uname"];
$TASKID = get_GET_var('taskid');

	$manageWorks = new manageWorks();
	$manageUsers = new manageUsers();
	$manageProjects = new manageProjects();
	$allPeers = $manageUsers->listOfAllPeerUsers($USERNAME);
	$taskDetails = $manageWorks->get_workDetails($TASKID);
			$taskDetails['work_briefDesc'] = ($taskDetails['work_briefDesc']) ? $taskDetails['work_briefDesc'] : 'No Description' ;
	$usersActiveProjects = $manageUsers->get_usersActiveProjects($USERNAME);

	// $allProjects
	//  `workID`,
		//  `work_userAssigned`,
		//  `work_addedBy` ,
		//  `work_dateAdded` timestamp ,
		//  `work_deadLine` date ,
		//  `work_startDate` timestamp,
		//  `work_completeDate` timestamp,
		//  `work_briefDesc` text,
		//  `work_Notes` text,
	//  `work_status` '1',
		//  `work_priority`  'N',
		//  `work_projectName`,
	//  `work_isPrivate` 'N',

	$workStatus = $taskDetails["work_status"] ;
	switch ($workStatus) {
		case $DE_GLOBALS_WORK_NEW:
			$TABLE_CLASS="NewWorksTable";
		break;
		case $DE_GLOBALS_WORK_PROGRESS:
			$TABLE_CLASS="WorksInProgressTable";
		break;
		case $DE_GLOBALS_WORK_COMPLETED:
			$TABLE_CLASS="WorksCompletedTable";
		break;
		case $DE_GLOBALS_WORK_CLOSED:
			$TABLE_CLASS="WorksClosedTable";
		break;
	}



?>
<HTML>
<HEAD>
	<TITLE>Details of Task ID <?php echo $TASKID; ?></TITLE>
	<script type="text/javascript" src="<?php echo JSASSETS_URL;?>jquery.js"></script>
	<script type="text/javascript" src="<?php echo JSASSETS_URL;?>jquery.date_input.js"></script>
	<script type="text/javascript" src="<?php echo JSASSETS_URL;?>custom.js"></script>
	<link rel="stylesheet" href="<?php echo JSASSETS_URL;?>date_input.css" type="text/css" charset="utf-8">
	<link rel="stylesheet" href="css/global.css" type="text/css" charset="utf-8">
	<style type="text/css">
	span.editspan {
		cursor:pointer;
		color: #3F86BA;
	}
	span.editspan:hover {
		text-decoration:underline;
	}
	span.updatespan, span.cancelspan{ margin-left:5px;}

	TABLE.NewWorksTable TR TD{ padding:6px; }
	TABLE.WorksInProgressTable TR TD{ padding:6px; }
	TABLE.WorksCompletedTable TR TD{ padding:6px; }
	TABLE.WorksClosedTable TR TD{ padding:6px; }

	</style>
</HEAD>
<BODY topmargin=0 leftmargin=0>
<SCRIPT>
	<?php 
		returnUsersUnderProjectsJSObject( $USERNAME ); //MYPROJECTS_USERS
	?>

	var update_ProjectUsers = function(){
		var users = _$('editinput_userAssigned');
		var ac = "<?php echo $taskDetails["work_projectName"]; ?>" ;
		My_JsLibrary.selectbox.clear(users);
		for( var i=0 ; i < MYPROJECTS_USERS[ac].length ; i++ ){
			var t = MYPROJECTS_USERS[ac][i];
			My_JsLibrary.selectbox.append(users,t,t);
		}
		My_JsLibrary.selectbox.selectOption(users, '<?php echo $taskDetails["work_userAssigned"]; ?>');
	};


	var DE_USER_action = function(action, argsObject){
		// DE_USER_action( 'logout' , {
		//		variable:value,
		//		callback:function(){
		//
		//		}
		//	});
		argsObject.action = action ;
		if( argsObject.hasOwnProperty('callback') ){
			var cb = argsObject.callback ;
			delete argsObject.callback ;
		}else{
			var cb = function(){};
		}
		$.ajax({
				type: "POST",
				 url: 'actions.php',
				data: argsObject,
			 success: function(resp){ My_JsLibrary.callCB(resp, cb); }
		});
	};

	var FINDDIVTOSHOW = function(EDITSPANFIELD){
		if(!EDITSPANFIELD)return;
		var all_divstohide = $('div.editdiv');
		for(var i=0; i < all_divstohide.length; i++){
			var this_div = all_divstohide[i];
			if( $(this_div).attr('fieldname') == EDITSPANFIELD ){ return this_div; }
		}
		return;
	};

	var FINDSPANTOSHOW = function(EDITSPANFIELD){
		if(!EDITSPANFIELD)return;
		var all_spantohide = $('span.editspan');
		for(var i=0; i < all_spantohide.length; i++){
			var this_span = all_spantohide[i];
			if( $(this_span).attr('fieldname') == EDITSPANFIELD ){ return this_span; }
		}
		return;
	};

	var localajaxinit = function(){
		$($.date_input.initialize);

		$('span.editspan').click(function(){
			var EDITSPANFIELD = $(this).attr('fieldname');
			$(this).hide();
			if( EDITSPANFIELD == 'userAssigned'){
				update_ProjectUsers();
			}
			var dts = FINDDIVTOSHOW(EDITSPANFIELD); // find div to show
			$(dts).show();
		});

		$('span.cancelspan').click(function(){
			var EDITSPANFIELD = $(this).attr('fieldname');
			var dts = FINDDIVTOSHOW(EDITSPANFIELD);
			$(dts).hide();
			var sts = FINDSPANTOSHOW(EDITSPANFIELD);
			$(sts).show();
		});

		$('span.updatespan').click(function(){
			var EDITSPANFIELD = $(this).attr('fieldname');
			var newValue = My_JsLibrary.getFieldValue('editinput_'+ EDITSPANFIELD);
			DE_USER_action( 'editTaskField',
			{
				workid : '<?php echo $TASKID; ?>',
				fieldName: EDITSPANFIELD,
				fieldValue: newValue,
				callback:function(a){
					if(a){
						window.location.reload();
					}else{
						alert(My_JsLibrary.responsemsg);
					}
				}
			});
		});

		$('span.filename').click(function(){
			var attachId = $(this).attr("attachId") ;
			if(!attachId){ return; }
			window.location.href = "getattachment.php?attachId=" + attachId ;
		});

	}; // End of localajaxinit


	var EditTaskJsFunction = {
		addComment:function(){ // EditTaskJsFunction.addComment();
			var newComment = My_JsLibrary.getFieldValue('newcomment_ta');
			
			if( ! My_JsLibrary.checkRequiredFields( ['newcomment_ta'] ) ){
				return;
			}
			
			var notifyAssigned = (_$('newcomment_notify').checked) ? 'Y' : "N" ;
			
			DE_USER_action( 'AddComment',
			{
				newComment : newComment,
					workid : '<?php echo $TASKID; ?>',
					notifyAssigned : notifyAssigned,
				callback:function(a){
					if(a){
						window.location.reload();
					}else{
						alert(My_JsLibrary.responsemsg);
					}
				}
			});
		}
		
	};


	var DELETETHISTASK = function(){
		if(!confirm('Are you sure? \n\n Task will be deleted permanently.\n There is no Undo for this action. ')){return;}
		DE_USER_action( 'deleteTask',
		{
			workid : '<?php echo $TASKID; ?>',
			callback:function(a){
				if(a){
					window.opener.My_JsLibrary.windowReload();
					window.close();
				}else{
					alert(My_JsLibrary.responsemsg);
				}
			}
		});
	};
</SCRIPT>

<TABLE align=center border=0 class="<?php echo $TABLE_CLASS; ?>" cellpadding=0 cellspacing=0>
	<TR>
		<TD colspan=4 align='center' class="firstRow">
			<span>Details of Task <?php echo $TASKID; ?></span>
			<?php
			if( $taskDetails["work_addedBy"] == $USERNAME ){
				echo "<span class='bluebuttonSmall' style='margin-left:100px;' onclick='DELETETHISTASK();'>Delete</span>";
			}
			?>
		</TD>
	</TR>
	<TR><TD class='evenrow'><nobr>Assigned to :</nobr></TD>
		<TD class='evenrow'>
			<?php
			if( $taskDetails["work_addedBy"] == $USERNAME || $taskDetails["work_userAssigned"] == $USERNAME ){
			?>
			<span class="editspan" fieldname="userAssigned"><?php echo $taskDetails["work_userAssigned"]; ?></span>
			<?php }else{ ?>
				<?php echo $taskDetails["work_userAssigned"]; ?>
			<?php } ?>
			
			
			<div style="display:none;" class="editdiv" fieldname="userAssigned">
				<select id='editinput_userAssigned'>
				<?php
					html_array2selectboxOptions_selected( $allPeers , $taskDetails["work_userAssigned"] );
				?>
				</select>
				<span class='bluebuttonSmall updatespan' fieldname="userAssigned">update</span>
				<span class='bluebuttonSmall cancelspan' fieldname="userAssigned">cancel</span>
			</div>

		</TD>
		<TD class='evenrow'>Deadline :</TD>
		<TD class='evenrow'>
			<?php
			if( $taskDetails["work_addedBy"] == $USERNAME ){
			?>
			<span class="editspan" fieldname="deadLine"><?php echo caldate_to_human($taskDetails["work_deadLine"]); ?></span>
			<?php }else{ ?>
				<?php echo caldate_to_human($taskDetails["work_deadLine"]); ?>
			<?php } ?>
			
			<div style="display:none;" class="editdiv" fieldname="deadLine">
				<input size=10 class='date_input' id='editinput_deadLine' value="<?php echo $taskDetails["work_deadLine"]; ?>">
				<span class='bluebuttonSmall updatespan' fieldname="deadLine">update</span>
				<span class='bluebuttonSmall cancelspan' fieldname="deadLine">cancel</span>
			</div>

		</TD>
	</TR>
	<TR><TD class='oddrow'>Project :</TD>
		<TD class='oddrow'>
			<?php
			if( $taskDetails["work_addedBy"] == $USERNAME ){
			?>
			<span class='editspan' fieldname="projectName"><?php echo $taskDetails["work_projectName"]; ?></span>
			<?php }else{ ?>
				<?php echo $taskDetails["work_projectName"]; ?>
			<?php } ?>

			<div style="display:none;" class="editdiv" fieldname="projectName">
				<select id='editinput_projectName'>
				<?php
					html_array2selectboxOptions_selected( $usersActiveProjects , $taskDetails["work_projectName"] );
				?>
				</select>
				<span class='bluebuttonSmall updatespan' fieldname="projectName">update</span>
				<span class='bluebuttonSmall cancelspan' fieldname="projectName">cancel</span>
			</div>
			
		</TD>
		<TD class='oddrow'>Priority :</TD>
		<TD class='oddrow'>
			
			<?php
				if( $taskDetails["work_priority"] == 'H' ){
					$priorityStr = "<span style='color:#F42C20; font-weight:bold;'>High</span>";
				}elseif( $taskDetails["work_priority"] == 'N' ){
					$priorityStr = 'Normal';
				}elseif( $taskDetails["work_priority"] == 'L' ){
					$priorityStr = 'Low';
				}

			if( $taskDetails["work_addedBy"] == $USERNAME ){
			?>
			<span class="editspan" fieldname="priority"><?php echo $priorityStr; ?></span>
			<?php }else{ ?>
				<?php echo $priorityStr ; ?>
			<?php } ?>

			<div style="display:none;" class="editdiv" fieldname="priority">
				<select id='editinput_priority'><option value='N'>Normal</option><option value='H'>High</option><option value='L'>Low</option></select>
				<span class='bluebuttonSmall updatespan' fieldname="priority">update</span>
				<span class='bluebuttonSmall cancelspan' fieldname="priority">cancel</span>
			</div>

		</TD>
	</TR>
	<TR><TD class='evenrow'>Created By:</TD>
		<TD class='evenrow'>
			<span id=''><?php echo $taskDetails["work_addedBy"]; ?></span>
		</TD>
		<TD class='evenrow'>Created On :</TD>
		<TD class='evenrow'>
			<span id=''><?php echo caldateTS_to_humanWithTS($taskDetails["work_dateAdded"]); ?></span>
		</TD>
	</TR>
	<?php

	if($workStatus <> $DE_GLOBALS_WORK_NEW){
	?>
	<TR>
		<?php
		if( $workStatus == $DE_GLOBALS_WORK_PROGRESS || $workStatus == $DE_GLOBALS_WORK_COMPLETED || $workStatus == $DE_GLOBALS_WORK_CLOSED ){
		?>	
		<TD class='oddrow'>Start Date:</TD>
		<TD class='oddrow'>
			<span id=''><?php echo caldateTS_to_humanWithTS($taskDetails["work_startDate"]); ?></span>
		</TD>
		<?php
		}else{
			echo "<td class='oddrow' colspan=2>&nbsp;</td>";
		}

		if( $workStatus == $DE_GLOBALS_WORK_COMPLETED || $workStatus == $DE_GLOBALS_WORK_CLOSED ){
		?>
			<TD class='oddrow'>Completed On :</TD>
			<TD class='oddrow'>
				<span id=''><?php echo caldateTS_to_humanWithTS($taskDetails["work_completeDate"]); ?></span>
			</TD>
		<?php
		}else{
			echo "<td class='oddrow' colspan=2>&nbsp;</td>";
		}
		?>
	</TR>
	<?php } ?>
	<TR><TD class='evenrow' valign='top'>Description:</TD>
		<TD class='evenrow' colspan=3>
			<?php
			if( $taskDetails["work_addedBy"] == $USERNAME ){
			?>
			<span class='editspan' fieldname='briefDesc'><?php echo stripslashes($taskDetails["work_briefDesc"]); ?></span>
			<?php
			}else{ 
				 echo stripslashes($taskDetails["work_briefDesc"]);
			}
			?>
			<div style="display:none;" class="editdiv" fieldname="briefDesc">
				<textarea id='editinput_briefDesc' rows=2 cols=40><?php echo stripslashes($taskDetails["work_briefDesc"]); ?></textarea>
				<BR>
				<span class='bluebuttonSmall updatespan' fieldname="briefDesc">update</span>
				<span class='bluebuttonSmall cancelspan' fieldname="briefDesc">cancel</span>
			</div>
		</TD>
	</TR>
	<TR><TD class='oddrow' valign='top'>Notes:</TD>
		<TD class='oddrow' colspan=3>
			<?php
			if( $taskDetails["work_addedBy"] == $USERNAME ){
			?>
			<span class='editspan' fieldname='Notes'><PRE>&nbsp;<?php echo stripslashes($taskDetails["work_Notes"]); ?></PRE></span>
			<?php
			}else{
				echo '&nbsp;'.stripslashes(mynl2br($taskDetails["work_Notes"])).'</PRE>';
			}
			?>
			<div style="display:none;" class="editdiv" fieldname="Notes">
				<textarea id='editinput_Notes' rows=2 cols=40><?php echo stripslashes($taskDetails["work_Notes"]); ?></textarea>
				<BR>
				<span class='bluebuttonSmall updatespan' fieldname="Notes">update</span>
				<span class='bluebuttonSmall cancelspan' fieldname="Notes">cancel</span>
			</div>

		</TD>
	</TR>
	
	<TR><TD class='evenrow'>Visibility :</TD>
		<TD class='evenrow'>
			<?php
			if( $taskDetails["work_addedBy"] == $USERNAME ){
			?>
			<span class='editspan' fieldname='isPrivate'><?php if ($taskDetails["work_isPrivate"] == 'Y'){ echo 'Private'; }else{ echo 'Public'; } ?></span>
			<?php
			}else{ 
				 if ($taskDetails["work_isPrivate"] == 'Y'){ echo 'Private'; }else{ echo 'Public'; }
			}
			?>
			<div style="display:none;" class="editdiv" fieldname="isPrivate">
				<input type='checkbox' id='editinput_isPrivate' <?php if ($taskDetails["work_isPrivate"] == 'Y'){ echo 'checked'; } ?>> Private Task
				<BR>
				<span class='bluebuttonSmall updatespan' fieldname="isPrivate">update</span>
				<span class='bluebuttonSmall cancelspan' fieldname="isPrivate">cancel</span>
			</div>
		</TD>
		<TD colspan=2>
			<?php if( $taskDetails["work_addedBy"] == $_SESSION["uname"] || $taskDetails["work_userAssigned"] == $_SESSION["uname"] || IsSadmin() ){ ?>
			<span class='bluebuttonSmall' onclick="window.opener.ManageTasksJsFunction.showReschedulePopupForTask('<?php echo $TASKID; ?>'); window.close();">Reschedule this Task</span>
			<?php } ?>
		</TD>
	</TR>
	
	
</TABLE>


<TABLE align=center cellpadding=2 cellspacing=2 border=0 style='font-size: 90%;'>
	<?php
		echo $manageWorks->get_attachments($TASKID , false);
	?>
	<TR>
		<TD valign=top align=right>
			<span style="cursor: pointer; color: #3D4399; padding:1px; margin-right: 5px;font-weight:bold;" id="attachfile_divheading" onclick="$('#attachfile_divbox').toggle();"> Attach a file:</span>
		</TD>
		<TD>
			<div style="clear:both; display:none; margin-left:auto; margin-right:auto; padding:10px;" id="attachfile_divbox">
			<form enctype="multipart/form-data" action="uploader.php" method="POST">
				<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo 5000*1024 ; ?>" />
				<input type="hidden" name="fileupload_workid" value="<?php echo $TASKID; ?>">
				<input type="hidden" name="fileupload_requestURI" value="<?php echo $_SERVER["REQUEST_URI"] ; ?>">
				Select file to attach : <input name="uploadedfile" type="file">&nbsp;
				<input type="image" src="/images/btGo.gif" value="Go" border="0"/>
				<BR>Each file should be under 5 Mb
			</form>
			</div>
		</TD>
	</TR>
	
	
<TR>
	<TD valign=top align=right><div style='margin-right:5px; margin-top:2px;'><b>Add Comment:</b></div></TD>
	<TD align='center'>
		<textarea id='newcomment_ta' rows=2 cols=50></textarea>
		<div style="margin-top:5px;">
			<label><input type='checkbox' id='newcomment_notify'> Notify about this Comment</label>
		</div>
		<div style="margin-top:10px;">
			<span class='bluebuttonSmall' onclick="_$('newcomment_ta').value=''; _$('newcomment_ta').focus();">reset</span>
			<span class='bluebuttonSmall' onclick="EditTaskJsFunction.addComment();"><b>Add Comment</b></span>
		</div>
	</TD>
</TR>
</TABLE>

<?php
	$manageWorks->get_workComments( $TASKID );
?>
</div>


</BODY>
</HTML>