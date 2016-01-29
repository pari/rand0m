<?php

include_once "include_db.php";
include_once "include_functions.php";
if( !$_SESSION["uname"] ){ header('Location: logout.php'); exit(); }

$CUSTOM_STYLES ="";

include_once "include_functions.php";
include_once "include_header.php";
include_once "include_header_links.php";

if( !$AM_I_ADMIN ){ 
	echo 'You are not authorised to access this page.';
	include_once "include_footer.php";
	exit();
}

$existingName = getAppVariable('comp_Name');
$existingLogo = getAppVariable('comp_Logo');
?>
<script>

	$(document).ready(function() {

	});


	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('<?php echo getCurrentScriptFileName();?>');
	};

</script>


	<FORM action="upload.php" method="post" enctype="multipart/form-data"  >
	<TABLE align=center class="manageLRS" cellpadding=5 cellspacing=5 width="500" style="margin-top:30px;">
		<tbody>
		<TR class="firstRow"><Td colspan=2 id="pwdTitleRow" align="center" style="cursor:pointer;">Add Company Information</Td></TR>
		<TR class="firstRow">
			<Td colspan=2 id="errmsg" align="center" style="cursor:pointer;">
				<?php
				if(isset($_GET['res'])){
					if($_GET['res']){
						echo "Company Information Updated Successfully.";
					}else{
						echo "Company Information Update Failed!";
					}
				}
				?>
			</Td>
		</TR>
		<TR class="PasswordSecondaryRows">
			<TD align="right">Company Name:</TD>
			<TD><input type="text" id="c_name" name="c_name" value="<?=$existingName?>" size=36></TD>
		</TR>
		<TR class="PasswordSecondaryRows">
			<TD align="right">Company Logo:</TD>
			<TD><input type="file" id="c_logo" name="c_logo" >&nbsp;
			<?php
			$logoPath = "files/c_logos/".$existingLogo;
			if($existingLogo!='' && file_exists($logoPath))
			{
				echo "<img src='{$logoPath}' >" ;
			}
			?>
			</TD>
		</TR>
	
		<TR class="PasswordSecondaryRows">
			<TD style="padding:10px;" align="center" colspan=2>
				<input type="submit" class="bluebuttonSmall" style="font-size:14px;" value="Update Company Info" ></TD>
		</TR>
		</tbody>
	</TABLE>
	</FORM>


<?php
include_once "include_footer.php";
?>




