<?php
exit();

// $CURRENT_USERID

include_once "include_db.php";
if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CUSTOM_STYLES ="";

include_once "include_functions.php";
include_once "include_header.php";
include_once "include_header_links.php";

$MU = new ManageUsers();
$MU->userId = $CURRENT_USERID ;

?>
<script>

// To add a Calendar PopUp
	// in localajaxinit add
	// $($.date_input.initialize);

	// In HTML add a text field of class 'date_input'
	// <input type='text' class='date_input' size=12 id='XXXX'>


	$(document).ready(function() {

	});


	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('<?php echo getCurrentScriptFileName();?>');
	};

</script>


	<TABLE cellpadding=2 cellspacing=1 width="600" style='margin-top:30px;' align='center' border=0>
		<tbody>
		<TR>
			<TD>
			<?php
			
			?>
			</TD>
		</TR>
		</tbody>
	</TABLE>


<?php
include_once "include_footer.php";
?>