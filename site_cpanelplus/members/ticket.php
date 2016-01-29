<?
session_start();

	if(!$_SESSION["lmembername"]){
		session_unset(); 
		session_destroy(); 
		header("Location: index.php"); 
		exit();
	}

	$membername = $_SESSION["lmembername"] ;
?>
<HTML>
<HEAD>
<TITLE>cPanelPlus.net - Member login for <?=$membername?></TITLE>
<STYLE>
			A { color:#0033CC; Text-Decoration: underline;} 
			A:HOVER { color:#FF0099; Text-Decoration: none;}
</style>
</HEAD>

<BODY leftmargin=0 bgcolor="#C5CAE1">
<TABLE width="750" border="0" cellpadding="0" cellspacing="1" bgcolor="#000099" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
<TR><TD>
		<TABLE width="100%" bgcolor="#FFFFFF" border=0 align="center">
			<TR><TD align="left" height=42 bgcolor="#FFFFFF">&nbsp;<img src="/images/logo.gif" border=0></TD>
			    <TD align="center" height=42 bgcolor="#FFFFFF" valign="middle"><FONT SIZE="2" COLOR="#0066CC">Member login for <?=$membername?></FONT></TD>
			</TR>
		</table>
</td></tr>
<TR><TD valign="center" align="center" height=24 bgcolor="#FFFFFF">
		<TABLE width="750" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
		<TR>
			<TD align="center" width="80"><A href="member.php">Home</A></TD>
			<TD align="center"><A href="downloads.php">Member Downloads</A></TD>
			<TD align="center"><A href="ticket.php">Trouble Ticket</A></TD>
			<TD align="center"><A href="productdocs.php">Product Manuals</A></TD>
			<TD align="center"><A href="tips.php">Tips & Tricks</A></TD>
			<TD align="center"><A href="profile.php">My Profile</A></TD>
			<TD align="center"><A href="logout.php">Logout</A></TD>
		</TR>
		</TABLE>	
</td></tr>

<TR><TD>
	<TABLE width="750" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
	<TR><TD>
			<div style='padding:2.0pt 2.0pt 2.0pt 2.0pt'>
			<TABLE cellpadding=0 cellspacing=0>
			<TR>
				<TD>&nbsp;&nbsp;&nbsp;<img src="/images/story.gif" border="0"></TD>
				<TD valign="middle"><FONT SIZE="4" COLOR="#8192E0"><B>&nbsp;Trouble Ticket</B></FONT></TD>
			</TR>
			</TABLE>
			</div>
			<BR>
<!-- Form Starts Here -->
<script language="JavaScript">
function validate() {
var f = document.submitticket.subject.value;
var g = document.submitticket.problem.value;

	if (f == "") {	
		alert('Please enter Your Query Subject');
		document.submitticket.subject.focus();
		return false;
	}

	if (g == "") {	
		alert('Please Explain your problem in detail !');
		document.submitticket.problem.focus();
		return false;
	}						
return true;				
}
</SCRIPT>
<FORM action="submit_ticket.php" name="submitticket" method="post"  onSubmit="return validate();">
<TABLE width=550 align="center" cellpadding=0 cellspacing=2 border=0 style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #000000;">
<TR>
	<TD valign="top" align="right"><nobr>Related to :</NOBR></TD>
	<TD><select size="1" name="relatedto">
		<option value="Installation" selected>Installation</option>
		<option value="Customization">Customizing</option>
		<option value="FeatureRequest">Feature Request&nbsp;&nbsp;</option>
		<option value="BugReport">Bug Report</option>
		<option value="Other">Other</option>
		</select></TD>
</TR>
<TR><TD colspan=2 height=5></TD></TR>
<TR>
	<TD valign="top" align="right">Subject :</TD>
	<TD><input type="text" name="subject" size=66></TD>
</TR>
<TR><TD colspan=2 height=5></TD></TR>
<TR>
	<TD valign="top" align="right">Problem :</TD>
	<TD><textarea cols="50" rows="7" name="problem"></textarea></TD>
</TR>
<TR><TD colspan=2 height=3></TD></TR>
<TR>
	<TD valign="top" align="right"></TD>
	<TD><INPUT TYPE="submit" value="Submit"></TD>
</TR>
<TR><TD colspan=2 height=3></TD></TR>
<TR>
	<TD valign="top" align="right"><B>Note :&nbsp;</B></TD>
	<TD><div align="justify">The above issue will be replied through email. Our current records show that your e-Mail 
		id is <U><?=$_SESSION["memberemail"]?></U>, you can update your e-Mail id in the '<A HREF="profile.php">My Profile</A>' Section.</div> </TD>
</TR>
</TABLE>
</FORM>
<!-- Form Ends here -->
			<BR><BR><BR><BR>
	</TD></TR>
	</TABLE>
</td></tr>



<TR><TD>
		<TABLE width="750" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
			<TR><TD height=30 valign="center" align="center">Copyrights &copy; 2003 cPanelPlus.net</TD></TR>
		</TABLE>
</td></tr>


</TABLE>

</BODY>
</HTML>
