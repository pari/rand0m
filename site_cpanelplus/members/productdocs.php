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
						<TD>&nbsp;&nbsp;&nbsp;<img src="/images/product-docs.png" border="0"></TD>
						<TD valign="middle"><FONT SIZE="4" COLOR="#8192E0"><B>&nbsp;Product Manuals</B></FONT></TD>
					</TR>
					</TABLE>
			</div>
			<BR>
			<div style='padding:2.0pt 2.0pt 2.0pt 58.0pt'>
					<TABLE width="350" border="0" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
					<TR><TD>1.</TD><TD>Nexion - Installation</TD><TD width=40 align="center"></TD><TD width=40 align="center"><A href="./nexion_docs/Nexion-Installation.pdf">PDF</A></TD><TD width=65  align="center"><A href="./nexion_docs/Nexion-Installation.doc">MS-Word</A></TD></TR>
					<TR><TD colspan=5 height=14></TD></TR>
					<TR><TD>2.</TD><TD>Nexion - Howto Customize</TD><TD width=40 align="center"></TD><TD width=40 align="center"><A href="./nexion_docs/Nexion-Customizing.pdf">PDF</A></TD><TD width=65  align="center"><A href="./nexion_docs/Nexion-Customizing.doc">MS-Word</A></TD></TR>
					<TR><TD colspan=5 height=14></TD></TR>
					<TR><TD>3.</TD><TD>Nexion - FAQs</TD><TD width=40 align="center"></TD><TD width=40 align="center">--</TD><TD width=65  align="center">--</TD></TR>
					<TR><TD colspan=5 height=14></TD></TR>
					<TR><TD>4.</TD><TD>Nexion - Product Presentation</TD><TD width=40 align="center"></TD><TD width=40 align="center"><A href="/presentations/nexion.ppt">PPT</A></TD><TD width=65  align="center"></TD></TR>
					</TABLE>
			</DIV>
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
