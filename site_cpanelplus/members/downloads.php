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
				<TD>&nbsp;&nbsp;&nbsp;<img src="/images/group-details-logo.png" border="0"></TD>
				<TD valign="middle"><FONT SIZE="4" COLOR="#8192E0"><B>&nbsp;Member Downloads</B></FONT></TD>
			</TR>
			</TABLE>
			</div>
			<BR>
<?
if($_SESSION["nexionstatus"]=='Y'){
?>
<!-- Nexion Starts Here -->
<center>
<table border="0" cellpadding=0 cellspacing="1" bgcolor="#C0C0C0" width="600">
<TR><TD bgcolor="#FFFFFF">
				<table border="0" cellspacing="1" cellspacing=2 bordercolor="#FFFFFF" width="600" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;" bgcolor="#FFFFFF">
				
				<tr><td width="600" bgcolor="#C5CAE1" height=24>
					&nbsp;<B><FONT SIZE="2">Nexion</FONT></B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- NEW! </td>
				</tr>
					
					
				<TR><td bgcolor="#FFFFFF" valign="top">
						<div align="justify" style='padding:2.0pt 2.0pt 2.0pt 2.0pt'>
						'Nexion' is a cPanel skin with <U>Windows Explorer style user friendly interface</U>, having well categorized Menus and SubMenus for easy navigation. The colors of the skin can be changed from a single configuration file. Nexion also has a well integrated 'Context Based Help' which will be very useful for the customers who are not aware of the advanced features. 
						Following are some of the noticeable features of 'Nexion'.
						</div>

						<div align="left" style='padding:2.0pt 2.0pt 10.0pt 20.0pt'>
						<LI> Windows Explorer style User Friendly Interface
						<LI> Context Based Help where ever needed
						<LI> Highly customizable
						<LI> Change your color scheme whenever you want
						<LI> Java Script Validations
						<LI> Highly recommended for low bandwidth / DialUp users
						<LI> Tested with popular versions of Interenet Explorer and Netscape Navigator
						<LI> Free upgrades
						</div>
						</td>
				</tr>

				<tr><td bgcolor="#FFFFFF" valign="top" align="center"><img border="0" src="/images/nexion_small.gif" width="549" height="170"></td>
				</TR>

				<tr><td bgcolor="#FFFFFF" valign="middle" align="center" height=30>
					<A href="dlnexion.php?nx=1"><img src="/images/dloadnow.jpg" border=0></A>
					</td>
				</TR>
				</table>

</TD></TR></TABLE></center>
<BR>
		<div align="justify" style='padding:2.0pt 2.0pt 2.0pt 62.0pt' bgcolor="#FFFFFF">
		You can also Download Nexion in any of the following readymade color schemes !<BR>
		Note : Please refer to the <A href="/members/productdocs.php">Product Manuals</A> Page for Installation documents
		</DIV>
<BR><BR>

<TABLE border=0 cellpadding=0 cellspacing=1 bgcolor="#C5CAE1" width=600 align="center">
<TR><TD>
	<TABLE border=0 cellpadding=0 cellspacing=1 bgcolor="#FFFFFF" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;" align="center" width=600>
	<TR><TD align="left" colspan=2 height=22 bgcolor="#C5CAE1">&nbsp;<B><FONT SIZE="2">Nexion - Default</FONT></B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- NEW! </TD></TR>
	<TR><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<TD align="center" width="70%" height=200><img src="/images/screenshots/nexion_vsmall_default.gif" border=0></TD>
		<TD valign="middle" align="center"><A href="dlnexion.php?nx=1"><img src="/images/dloadnow.jpg" border=0></A><BR><BR>
		</TD></TR>
		</form>
		</table>
</td></tr>
</table>

<BR><BR>

<TABLE border=0 cellpadding=0 cellspacing=1 bgcolor="#C5CAE1" width=600 align="center">
<TR><TD>
	<TABLE border=0 cellpadding=0 cellspacing=1 bgcolor="#FFFFFF" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;" align="center" width=600>
	<TR><TD align="left" colspan=2 height=22 bgcolor="#C5CAE1">&nbsp;<B><FONT SIZE="2">Nexion - Red</FONT></B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- NEW! </TD></TR>
	<TR><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<TD align="center" width="70%" height=180><img src="/images/screenshots/nexion_vsmall_red.gif" border=0></TD>
		<TD valign="middle" align="center"><A href="dlnexion.php?nx=2"><img src="/images/dloadnow.jpg" border=0></A><BR><BR>
		</TD></TR>
		</form>
		</table>
</td></tr>
</table>

<BR><BR>

<TABLE border=0 cellpadding=0 cellspacing=1 bgcolor="#C5CAE1" width=600 align="center">
<TR><TD>
	<TABLE border=0 cellpadding=0 cellspacing=1 bgcolor="#FFFFFF" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;" align="center" width=600>
	<TR><TD align="left" colspan=2 height=22 bgcolor="#C5CAE1">&nbsp;<B><FONT SIZE="2">Nexion - Green</FONT></B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- NEW! </TD></TR>
	<TR><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<TD align="center" width="70%" height=180><img src="/images/screenshots/nexion_vsmall_green.gif" border=0></TD>
		<TD valign="middle" align="center"><A href="dlnexion.php?nx=3"><img src="/images/dloadnow.jpg" border=0></A><BR><BR>
		</TD></TR>
		</form>
		</table>
</td></tr>
</table>

<BR><BR>

<TABLE border=0 cellpadding=0 cellspacing=1 bgcolor="#C5CAE1" width=600 align="center">
<TR><TD>
	<TABLE border=0 cellpadding=0 cellspacing=1 bgcolor="#FFFFFF" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;" align="center" width=600>
	<TR><TD align="left" colspan=2 height=22 bgcolor="#C5CAE1">&nbsp;<B><FONT SIZE="2">Nexion - Silver</FONT></B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- NEW! </TD></TR>
	<TR><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<TD align="center" width="70%" height=180><img src="/images/screenshots/nexion_vsmall_silver.gif" border=0></TD>
		<TD valign="middle" align="center"><A href="dlnexion.php?nx=4"><img src="/images/dloadnow.jpg" border=0></A><BR><BR>
		</TD></TR>
		</form>
		</table>
</td></tr>
</table>
<!-- Nexion Ends Here -->
<?}?>

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
