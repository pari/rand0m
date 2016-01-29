<?php
require_once "include_header.php";
?>
<TR><TD>
		<TABLE width="750" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
			<TR><TD><IMG src="./images/cpplusbanner.jpg" border=0></TD>
			</TR>
		</TABLE>
</td></tr>

<TR><TD>
		<TABLE width="750" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center">
			<TR>
			<TD valign="top" align="center" width=200>
					<script language="JavaScript">
					function validate() {
						var f = document.nlform.name.value;
						var g = document.nlform.emailid.value;

							if (f == "") {	
								alert('Please enter Your Name');
								document.nlform.name.focus();
								return false;
							}

							if (g == "") {	
								alert('Please enter your e-Mail Id');
								document.nlform.emailid.focus();
								return false;
							}						
						return true;				
					}
					</SCRIPT>
					<TABLE width=200 cellpadding=0 cellspacing=0>
						<TR><TD colspan=2  height=28>
							<B><FONT size=2 COLOR="#0033CC">&nbsp;&nbsp;Newsletter</FONT></B></TD>
						</TR>
						<TR>
							<TD align="right">Name :&nbsp;</TD><TD><input type="text" name="name" size=12></TD>
						</TR>
						<TR>
							<TD align="right">Email Id :&nbsp;</TD><TD><input type="text" name="emailid" size=12></TD>
						</TR>
						<TR>
							<TD align="right"><input type="radio" name="action" value="subscribe" checked>&nbsp;</TD><TD>Subscribe</TD>
						</TR>
						<TR>
							<TD align="right"><input type="radio" name="action" value="unsubscribe">&nbsp;</TD><TD>Un Subscribe</TD>
						</TR>
						<TR>
							<TD colspan=2 align="center"><input type="submit" value="Next"></TD>
						</TR>
					</TABLE>		
					<BR><BR>


			</TD>
			<TD valign="top" align="center" width=550>
					<TABLE width=550 cellpadding=0 cellspacing=0 style="font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #000000;">
					<TR><TD colspan=2 height=28><B><FONT size=2 COLOR="#0033CC">Latest Updates</FONT></B></TD></TR>
					<TR><TD height=18>
						New Version '<A href="products.html">CPanel Xpress</A>' v1.2<BR>
						Fixed two major bugs in the previous version
					</TD>
					<TD align="center" width=100 valign="top">
						<FONT COLOR="#0000CC">- 02.06.2003</FONT>
					</TD>
					</TR>
					</TABLE>
					<BR>
					<BR>

					<TABLE width=550 cellpadding=0 cellspacing=0 style="font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #000000;">
					<TR><TD colspan=2 height=01 bgcolor="#646464"></TD></TR>
					<TR><TD colspan=2 height=04 bgcolor="#FFFFFF">&nbsp;</TD></TR>
					<TR><TD height=18>
						<div align="justify">
						New product '<A href="products.html">CPanel Xpress</A>' released.<BR><BR>
						'CPanel XPress' is Free software<BR>
						<A href="/regcpx.php">Click here</A> to Download your copy of 'CPanel Xpress'
						<UL>
						<LI> Manage any number of Cpanel accounts
						<LI> Manage Cpanel accounts on multiple servers
						<LI> All the Data is stored in a secure encrypted file format
						<LI> Password Protected Application
						<LI> <U>Free for Both Commercial and Personal Use</U>
						</UL>
						</div>
					</TD>
					<TD align="center" width=100 valign="top">
						<FONT COLOR="#0000CC">- 11.27.2003</FONT>
					</TD>
					</TR>
					</TABLE>



						




			</TR>
<TR>
			<TD valign="top" align="center" width=200>
			&nbsp;
			</TD>
			<TD valign="top" align="center" width=550>
					<TABLE width=550 cellpadding=0 cellspacing=0 style="font-family: Arial, Verdana, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #000000;">
					<TR><TD colspan=2 height=01 bgcolor="#646464"></TD></TR>
					<TR><TD colspan=2 height=04 bgcolor="#FFFFFF">&nbsp;</TD></TR>
					<TR><TD height=18>
						<div align="justify">
						New cPanel skin '<A href="products.html">Nexion</A>' released. 'Nexion' is a cPanel skin with Windows Explorer style User Friendly Interface, having well categorized Menus and SubMenus for easy navigation. Other features includes
						<UL>
						<LI> Windows Explorer style User Friendly Interface
						<LI> Context Based Help where ever needed
						<LI> Highly customizable
						<LI> Change your color scheme whenever you want
						<LI> Java Script Validations
						<LI> Download in four exciting color schemes
						<LI> Highly recommended for low bandwidth / DialUp users
						<LI> Tested with popular versions of Interenet Explorer and Netscape Navigator
						<LI> Free upgrades
						</UL>
						</div>
					</TD>
					<TD align="center" width=100 valign="top">
						<FONT COLOR="#0000CC">- 10.20.2003</FONT>
					</TD>
					</TR>
					</TABLE>
			</TR>
		</TABLE>
</td></tr>
<?php
require_once "include_footer.php";
?>