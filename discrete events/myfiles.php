<?php

include_once "include_db.php";
include_once "include_functions.php";
checkUserSessionandCookie();
include_once "include_header.php";
$username = $_SESSION["uname"];

?>
<SCRIPT>

var localajaxinit = function(){
	My_JsLibrary.selectMainTab('myfiles.php');
	$($.date_input.initialize);

}; // End of localajaxinit



</SCRIPT>

<div style='display:block !important; height: 20px !important; margin: -18px 0 0 !important;padding: 0 !important; position: fixed !important; top: 45% !important; width: 17px; z-index: 100000 ; right:0; background-color: #ECECEC ;' onclick="My_JsLibrary.windowReload();" TITLE="Reload Page">
	<img src='images/icon_refresh.gif' border=0>
	<span id='span_refreshcountdown'></span>
</div>

<div style='clear:both;'></div>

<?php
// get list of files uploaded by me and show in a table
?>

<div style='width:780px; text-align:center; margin-left:auto; margin-right:auto;'>
<table border="0" cellspacing="0" cellpadding="5" align=center class='WorksClosedTable'>
	<tr class='firstRow'>
		<th>FileName</th>
		<th width=120>Size</th>
		<th width=160>Uploaded On</th>
		<th width='80'>Task</th>
	</tr>
	<?php
		$sqlquery= "select Id as fileID, workid, uploadname, uploadedOn, filesize from attachments order by uploadedOn DESC" ;
		$query = mysql_query($sqlquery) or die("Invalid query: " . mysql_error()) ;
		WHILE ($row = @mysql_fetch_array($query)){
			extract($row) ; // fileID, workid , uploadname, uploadedOn, filesize
			?>
			<tr>
				<td align=left>
					<?php echo "<A href='getattachment.php?attachId={$fileID}'>$uploadname</A>"; ?>
				</td>
				<td align=right><?php echo formatBytesToHumanReadable($filesize) ; ?></td>
				<td><span style='margin-left: 25px;'><?php echo caldateTS_to_humanWithOutTS($uploadedOn); ?></span></td>
				<td align=center><A href='#' onclick="ManageTasksJsFunction.detailsWork('<?php echo $workid;?>')"><?php echo $workid; ?></A></td>
			</tr>
			<?php
		}
	?>
</table>
</div>


<?php
	include "include_footer.php";
?>