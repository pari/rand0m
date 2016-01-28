<?php

include_once "include_db.php" ;
include_once "include_custom.php" ;

session_start();
$authorized = false;

if( isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
	$ue_uname = $_SERVER['PHP_AUTH_USER'] ;
	$ue_pwd = $_SERVER['PHP_AUTH_PW'] ;

	$query = mysql_query("select username as USERNAMEFROMDB from users where username='$ue_uname' and user_pwd='$ue_pwd' and role='viewjournals'") or die("Invalid query: " . mysql_error()) ;
	WHILE ($row = @mysql_fetch_array($query)){
		extract($row) ; // $USERNAMEFROMDB
	}

	if( $USERNAMEFROMDB ){
		$authorized = true;
		$_SESSION['auth'] = true;
	}else{
	    $_SESSION['auth'] = null;
	    echo 'Please restart your browser and visit this page again to login' ;
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

var localajaxinit = function(){
	$($.date_input.initialize);
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



</script>
<?php

$SELECTEDUSER = @$_POST['view_user'] ;

if( @$_POST['startDate'] ){
	$startDate = @$_POST['startDate'] ;
	$endDate = @$_POST['endDate'] ;
	
	echo "<Center><h2>Journal Entries for '{$SELECTEDUSER}' on " . date("jS \of F Y" , mktime(0,0,0,$selectedMonth,$selectedDay,$selectedYear)) . " </h2></center>";

}else{
	$SELECTEDUSER = 'Every One';
	$startDate = date("Y-m-01") ;
	$endDate = date("Y-m-t") ;
}


	if( $SELECTEDUSER == 'Every One' ){
		$reportQuery = "select jid,task_day, task_mins, task_desc, task_user, FullName, projectName, Designation from VIEW_journals_human where task_day>='$startDate' and task_day <= '$endDate' order by task_user, jid DESC" ;
	}else{
		$reportQuery = "select jid,task_day, task_mins, task_desc, task_user, FullName, projectName, Designation from VIEW_journals_human where task_user='$SELECTEDUSER' and  task_day>='$startDate' and task_day <= '$endDate'  order by task_user, jid DESC" ;
	}
?>
<div style='clear:both;'></div>
<table align=center cellpadding=0 cellspacing=0 border=0 style='font-size: 95%;' width='95%'>
	<tr>
	<td colspan=6>
		<FORM action='admin.php' method='POST'>
		<div style='margin:10px;'>
		View journal for 
		<?php
			$listOfUsers = array_merge(array('Every One'), executesql_returnStrictArray("select username from users where role='enterjournals' "));
			html_array2selectbox( $listOfUsers , 'view_user' );
		?>
		between <input type="text" size=12 id="startDate" name='startDate' class="date_input" value="">
		and <input type="text" size=12 id="endDate" name='endDate' class="date_input" value="">&nbsp;&nbsp;
		<INPUT TYPE='SUBMIT' value='Go'>
		</div>
		</FORM>
	</td>
	</tr>

	<tr>
	<td colspan=6>
		<div style='margin:10px; width: 100%; text-align:center; margin-left:auto; margin-right:auto;'>
			<B> Daily Journal for <?php echo $SELECTEDUSER; ?> between <?php echo $startDate; ?> and <?php echo $endDate; ?> </B>
		</div>
	</td>
	</tr>

	<tr class='headingtr'>
		<td><B>Day</B></td>
		<td><B>Duration</B></td>
		<td><B>Task</B></td>
		<td><B>User</B></td>
		<td><B>Project</B></td>
		<td><B>Designation</B></td>
	</tr>
	<?php
	$trclass = 'even';
	$query = mysql_query($reportQuery);
	$total_mins = 0;
	WHILE ($row = @mysql_fetch_array($query)){ // extract($row); 
		$trclass = ($trclass == 'even')? 'odd':'even' ;
		$total_mins += $row['task_mins'] ; 
		?>
			<TR class='<?php echo $trclass; ?>'>
				<td><NOBR><?php echo caldate_to_human($row['task_day']); ?></NOBR></td>
				<td class='mins'><NOBR><?php echo $row['task_mins']; ?> mins</NOBR></td>
				<td><?php echo $row['task_desc']; ?></td>
				<td><NOBR><?php echo $row['FullName']; ?></NOBR></td>
				<td><NOBR><?php echo $row['projectName']; ?></NOBR></td>
				<td><NOBR><?php echo $row['Designation']; ?></NOBR></td>
			</TR>
		<?php
	}
	?>
	<tr class='headingtr'>
		<td align=right><B>Total:</B></td>
		<td><B><?php echo $total_mins; ?> mins</B></td>
		<td><B></B></td>
		<td><B></B></td>
		<td><B></B></td>
		<td><B></B></td>
	</tr>
</table>

<div class='footer'>&copy; 2009 Cigniti Inc</div>

</BODY>
</HTML>