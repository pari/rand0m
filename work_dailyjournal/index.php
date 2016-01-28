<?php
echo " <BR><BR><center><i>Please Use your Employee Project login for entering daily journals </i> </center><BR>" ; exit();

include_once "include_db.php" ;
include_once "include_custom.php" ;

session_start();
$authorized = false;


if( isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
	$ue_uname = $_SERVER['PHP_AUTH_USER'] ;
	$ue_pwd = $_SERVER['PHP_AUTH_PW'] ;

	$result = executesql_returnStrictArray( "select username from users where username='$ue_uname' and user_pwd='$ue_pwd' and role='enterjournals' " );

	if( count($result) ){
		$authorized = true;
		$_SESSION['auth'] = true;
		$_SESSION['loggedinUser'] = $_SERVER['PHP_AUTH_USER'] ;
	}else{
	    $_SESSION['auth'] = null;
	    print('Please restart your browser and visit this page again to login');
	    exit;
	}
}else{
	if (!$authorized){
	    header('WWW-Authenticate: Basic Realm="Login please"');
	    header('HTTP/1.0 401 Unauthorized');
	    $_SESSION['auth'] = null;
	    print('Please restart your browser and visit this page again to login');
	    exit;
	}
}


?>
<HTML>
<HEAD>
	<TITLE>Cigniti Journal</TITLE>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="jquery.date_input.js"></script>
	<script type="text/javascript" src="custom.js"></script>
	<link rel="stylesheet" href="global.css" type="text/css" charset="utf-8">
	<link rel="stylesheet" href="date_input.css" type="text/css" charset="utf-8">
</HEAD>
<BODY topmargin=0 leftmargin=0>
<div id='feedbackmsg'> </div>
<div id='ajaxstatus' style='display:none;'><nobr><img src='images/loading1.gif' border=0> Loading.. </nobr></div>
<script>

$(document).ready(function(){
	$($.date_input.initialize);
	$(document).bind('keypress', function(e) {
		var code = (e.keyCode ? e.keyCode : e.which);
		if(code == 27){
			$('#AddTask_Form').hideWithBg();	
		}
	});
	_$('formonth').selectedIndex = -1;
});


var showTasksForSelectedMonth = function(){
	var fm = _$('formonth').value;
	var fy = _$('foryear').value;
	window.location.href = 'index.php?month=' + fm + '&year=' + fy ;
};


var showaddtaskform = function(){
	My_JsLibrary.showdeadcenterdiv( 'AddTask_Form' );
	$('#AddTask_Form').show();
	_$('nutask_duration').value = '10' ;  // nutask_description , nutask_date
	_$('nutask_description').focus();
};





var do_addnewTask = function(){
	if( !My_JsLibrary.checkRequiredFields( ['nutask_date','nutask_duration', 'nutask_description'] ) ){
		return;
	}

	var newtask_date = _$('nutask_date').value ;
	var newtask_duration = _$('nutask_duration').value ;
	var newtask_description = _$('nutask_description').value ;
	$('#span_createTaskSubmit').hide(); 

	if(Number(newtask_duration) > 720 ){
		My_JsLibrary.showErrMsg('Invalid Duration!'); return;
	}

	DE_USER_action( 'addnewtask' , {
		nutask_date : newtask_date,
		nutask_duration : newtask_duration,
		nutask_desc : newtask_description,
		nutask_project: _$('nutask_project').value,
		callback:function(a){
			if(a){
				window.location.href= 'index.php';
			}else{
				$('#span_createTaskSubmit').show(); 
				My_JsLibrary.showErrMsg() ;
			}
		}
	});
};


var do_deleteTask = function(tid){
	if(!confirm('Are you sure ?')){return;}
	DE_USER_action( 'deleteTask' , {
		taskid : tid,
		callback:function(a){
			if(a){
				window.location.reload();
			}else{
				My_JsLibrary.showErrMsg() ;
			}
		}
	});
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


var show_changePwdForm = function(){
	My_JsLibrary.showdeadcenterdiv( 'ChangePassword_Form' );
	$('#ChangePassword_Form').show();
};


var update_pwd = function(){
	if( !My_JsLibrary.checkRequiredFields([ 'chpwd_current' , 'chpwd_newpwd' ]) ){
		return;
	}
	var currentPassword = _$('chpwd_current').value ;
	var chpwd_newpwd = _$('chpwd_newpwd').value;
	DE_USER_action( 'updatePwd' , {
		currentPwd : currentPassword,
			newPwd : chpwd_newpwd, 
		callback:function(a){
			if(a){
				alert("Password updated !");
				window.location.reload();
			}
		}
	});
};


</script>
<?php
if( @$_GET['month'] ){
	$selectedMonth = $_GET['month'];
	$selectedYear = (@$_GET['year'])? $_GET['year'] : date('Y') ;
	echo "<Center><h2>Journal Entries for '{$ue_uname}' in ".date("F",mktime(0,0,0,$selectedMonth,1,2000)).", $selectedYear </h2></center>";
}else{
	echo "<Center><h2>Recent Journal Entries for '{$ue_uname}' </h2></center>";
}
?>
<div style='clear:both;'></div>
<table align=center cellpadding=0 cellspacing=0 border=0 style='font-size: 95%;' width='95%'>
	<tr>
		<td colspan=4>
			<div>
				<div style='float:left; margin:10px;'>
				<span class='bluebutton' onclick="showaddtaskform();">Add Task</span>
				</div>

				<div style='float:right; margin:10px;'>
				View journal for month :
				<select id='formonth'>
					<option value='01'>January</option>
					<option value='02'>February</option>
					<option value='03'>March</option>
					<option value='04'>April</option>
					<option value='05'>May</option>
					<option value='06'>June</option>
					<option value='07'>July</option>
					<option value='08'>August</option>
					<option value='09'>September</option>
					<option value='10'>October</option>
					<option value='11'>November</option>
					<option value='12'>December</option>
				</select>
				<select id='foryear'>
				<?php
					html_array2selectboxOptions_selected( array('2010','2011','2012','2013','2014') , '<?php echo date(Y);?>' );
				?>
				</select>
				<span class='bluebutton' onclick="showTasksForSelectedMonth();">Go</span>
				</div>

				<div style='clear:both;'></div>
			</div>
		</td>
	</tr>
	<tr class='headingtr'>
		<td width='90'><B>Day</B></td>
		<td width='105' align='right'><B>Duration&nbsp;&nbsp;</B></td>
		<td><B>Task</B></td>
		<td width='70'></td>
		<td><B>Project Name</B></td>
	</tr>
	<?php

	$trclass = 'even';

	if( @$_GET['month'] ){
		$selectedMonth = $_GET['month'];
		$selectedYear = (@$_GET['year'])? $_GET['year'] : date('Y') ;
		$sqlquery= "select jid,task_day, task_mins, task_desc, DATE(task_enteredon) as task_enteredDate , projectName from VIEW_journals_human where task_user='$ue_uname' and MONTH(task_day)='$selectedMonth' and YEAR(task_day)='$selectedYear' order by task_day DESC" ;
	}else{
		$sqlquery= "select jid,task_day, task_mins, task_desc, DATE(task_enteredon) as task_enteredDate , projectName from VIEW_journals_human where task_user='$ue_uname' order by task_day DESC LIMIT 70" ;
	}


	$query = mysql_query($sqlquery) or die("Invalid query: " . mysql_error()) ;
	WHILE ($row = @mysql_fetch_array($query)){
		// extract($row) ; // $pkgId
		$trclass = ($trclass == 'even')? 'odd':'even' ;

	?>
		<TR class='<?php echo $trclass; ?>'>
			<td align='center' valign='top'><?php echo caldate_to_human($row['task_day']); ?></td>
			<td class='mins' align='right' valign='top'>
				<span style='margin-left:10px;'><?php echo $row['task_mins']; ?> mins<span>
			</td>
			<td valign='top'><?php echo $row['task_desc']; ?></td>
			<?php
				if( $row['task_enteredDate'] == date('Y-m-d') ){
					echo "<td align=center valign='top'>
							<span class='blueTextButtonSmall' onclick=\"do_deleteTask('".$row['jid']."')\">Delete</span>
							</td>";
				}else{
					echo "<td></td>";
				}
			?>
			<td><NOBR><?php echo $row['projectName'];?></NOBR></td>
		</TR>
	<?php
	}
	?>
</table>





<div id="AddTask_Form" style="display:none; width: 540" class="divAboveBg">
	<TABLE width="540" cellpadding=0 cellspacing=2 border=0  class="divHeadingTable">
	<TR><TD onmousedown="My_JsLibrary.startDrag(event);" class="drag_dialog_title">Add Task</TD>
		<TD onclick="My_JsLibrary.hideDrag(event);" width="19"><img src="images/close.gif" border=0></TD>
	</TR>
	</TABLE>
	<TABLE width="538" cellpadding="4" cellspacing=0 border=0>
		<TR><TD align="right"><NOBR>Task Date :</NOBR></TD>
			<TD><input type="text" size=12 id="nutask_date" class="date_input" value="<?php echo date("Y-m-d"); ?>"></TD>
		</TR>
		<TR><TD align="right"><NOBR>Project :</NOBR></TD>
			<TD>
				<?php html_query2selectbox("select pid, projectName from VIEW_usersProjects where username = '$ue_uname'", 'nutask_project') ;?>
			</TD>
		</TR>
		<TR><TD align="right"><NOBR>Duration :</NOBR></TD>
			<TD><input type="text" size=2 id="nutask_duration" value='10'> mins</TD>
		</TR>
		<TR><TD align="right" valign=top><NOBR>Description :</NOBR></TD>
			<TD><textarea id="nutask_description" rows=3 cols=40></textarea></TD>
		</TR>
		<TR>
			<TD></TD>
			<TD style="padding:10px;">
				<span class="bluebuttonSmall" onclick="do_addnewTask()" id='span_createTaskSubmit'>Add Task</span>
			</TD>
		</TR>
	</TABLE>
</div>


<div id="ChangePassword_Form" style="display:none; width: 540" class="divAboveBg">
	<TABLE width="540" cellpadding=0 cellspacing=2 border=0  class="divHeadingTable">
	<TR><TD onmousedown="My_JsLibrary.startDrag(event);" class="drag_dialog_title">Change password</TD>
		<TD onclick="My_JsLibrary.hideDrag(event);" width="19"><img src="images/close.gif" border=0></TD>
	</TR>
	</TABLE>
	<TABLE width="538" cellpadding="4" cellspacing=0 border=0>
		<TR><TD align="right"><NOBR>Current password :</NOBR></TD>
			<TD><input type="password" size=16 id="chpwd_current"></TD>
		</TR>
		<TR><TD align="right"><NOBR>New Password :</NOBR></TD>
			<TD><input type="password" size=16 id="chpwd_newpwd"></TD>
		</TR>
		<TR>
			<TD></TD>
			<TD style="padding:10px;">
				<span class="bluebuttonSmall" onclick="update_pwd()" id='span_createTaskSubmit'>Update</span>
			</TD>
		</TR>
	</TABLE>
</div>



<div class='footer'>
	<div style='margin:7px;'>
		<span class='blueTextButtonSmall' style='font-size:105%;' onclick="show_changePwdForm()">Change Password</span>
	</div>
	<div>&copy; 2009 Cigniti Inc<div>
</div>


</BODY>
</HTML>