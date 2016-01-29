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
				<TD>&nbsp;&nbsp;&nbsp;</TD>
				<TD valign="middle"><FONT SIZE="4" COLOR="#8192E0"><B>&nbsp;Welcome to our Members area</B></FONT></TD>
			</TR>
			</TABLE>
			</div>
			<BR>
				<TABLE width="93%" border="0" cellpadding="3" cellspacing="0" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #747474;">
				<TR bgcolor="#EFEFEF">
					<TD valign="top"><NOBR>&nbsp;<A href="downloads.php">Member Downloads</A>&nbsp;&nbsp;</NOBR></TD>
					<TD>The download area is a members-only section, where you can Download the products you have purchased.
					In this area you can also download any updates released for your products.</TD>
				</TR>
				<TR><TD colspan=2 height=25></TD></TR>
				<TR>
					<TD valign="top"><NOBR>&nbsp;<A href="ticket.php">Trouble Ticket</A></NOBR></TD>
					<TD>Here you can submit your support request. We will be responding to your tickets through Email. While submitting a Trouble ticket, please provide us with as much information regarding the problem, so that we can solve your problem at the earliest. We will get in contact ASAP. Please allow 12-24 hrs for a trouble ticket response. In most cases it will only be a few hours. 
					</TD>
				</TR>
				<TR><TD colspan=2 height=25></TD></TR>
				<TR  bgcolor="#EFEFEF">
					<TD valign="top"><NOBR>&nbsp;<A href="productdocs.php">Product Manuals</A></NOBR></TD>
					<TD>This is a download area where you can download 
						<div align="left" style='padding:2.0pt 2.0pt 2.0pt 8.0pt'>
						<LI>Product manuals
						<LI>Installation documents
						<LI>FAQs
						<LI>Presentations
						<LI>Archive of our NewsLetters till date
						</div>
					</TD>
				</TR>
				<TR><TD colspan=2 height=25></TD></TR>
				<TR>
					<TD valign="top"><NOBR>&nbsp;<A href="tips.php">Tips & Tricks</A></NOBR></TD>
					<TD>A collection of Tips & Tricks suggested by our tech experts to customize your cPanel</TD>
				</TR>
				<TR><TD colspan=2 height=25></TD></TR>
				<TR bgcolor="#EFEFEF">
					<TD valign="top"><NOBR>&nbsp;<A href="profile.php">My Profile</A></NOBR></TD>
					<TD>Change your password and contact details. It is very important that you keep your profile<BR>
						up to date to receive updates.</TD>
				</TR>
				</TABLE>
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
