<?php

	include ("../include_variables.php");
	include ("include_header.php");

// For the time being disable the userlog
	include ("include_footer.php");
	exit();
?>
<script>

var localajaxinit = function(){
	My_JsLibrary.selectMainTab('index.php');
};
</script>





<table id='Table_actionsList' align=center class="ssa_clistTable" cellpadding=8 cellspacing=1>
	<tr>
		<td class="firstRow">Date</td>
		<td class="firstRow">Username </td>
		<td class="firstRow">subdomain</td>
		<td class="firstRow">Action</td>
		<td class="firstRow">IP</td>
		<td class="firstRow">Browser</td>
	</tr>
<?php

	$sqlquery= "select username, DATE_FORMAT(datetime, '%b %D %Y %r') as datetime,DATE_FORMAT(datetime, '%b %D') as shortdate, subdomain, action, IP, browser from ".$MASTERDB.".userlog order by Id DESC LIMIT 100";

	$query = mysql_query($sqlquery) or die("Invalid query: " . mysql_error()); 
	IF( @mysql_num_rows($query) == 0 ){ // No log !
	?>
		<TR><TD colspan=6 align=center>No Entries Found !</TD></TR>
	<?php
	}

	$tdclass = "evenrow";
	WHILE ($row = @mysql_fetch_array($query)){
		extract($row); // $username, $datetime, $subdomain, $action
		$tdclass = ( $tdclass == "evenrow" ) ? "oddrow" : "evenrow" ;
	?>
	<tr>
		<td class="<?php echo $tdclass;?>" TITLE="<?php echo $datetime ?>"><?php echo $shortdate ?></td>
		<td class="<?php echo $tdclass;?>"><?php echo $username ?></td>
		<td class="<?php echo $tdclass;?>"><?php echo $subdomain ?></td>
		<td class="<?php echo $tdclass;?>"><?php echo $action ?></td>
		<td class="<?php echo $tdclass;?>"><?php echo $IP ?></td>
		<?php
			$short_browserstr = "UnKnown ?";
			if( strpos(strtolower($browser), 'firefox') !== false ){
				$short_browserstr = "Firefox";
			}

			echo "<td class='".$tdclass."' TITLE=\" $browser \"> $short_browserstr </td>";
		?>
	</tr>
	<?php
	}
?>

</table>

<?php

	include ("include_footer.php");

?>