<?
session_start();

	if(!$_SESSION["lmembername"]){
		session_unset(); 
		session_destroy(); 
		header("Location: index.php"); 
		exit();
	}

	$membername = $_SESSION["lmembername"] ;

	include "../mysql.inc";
	$link = mysql_connect("$mysqlhost", "$mysqluser", "$mysqlpassword") or die("Could not connect : " . mysql_error());
	mysql_select_db("$mysqldb") or die("Could not select database"); 
	$result = mysql_query("select date,company,address,city,country,phone,url,serverip from members where username ='$membername'") or die("Query failed : " . mysql_error());
	$num_rows = mysql_num_rows($result); 

if($num_rows <> 1){ session_unset(); session_destroy(); header("Location: index.php");  exit();}

	function condate($mdate){
		$year = intval(substr($mdate,0,4));
		$month = intval(substr($mdate,5,2));
		$day = intval(substr($mdate,8,2));
		return date("F j, Y", mktime(0,0,0,$month,$day,$year));
	}

while($row = mysql_fetch_array($result, MYSQL_ASSOC)){ 
	$date = condate($row["date"]);
	$company = $row["company"];
	$address = $row["address"];
	$city = $row["city"];
	$country = $row["country"];
	$phone = $row["phone"];
	$url = $row["url"];
	$serverip = $row["serverip"];
}


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
				<TD>&nbsp;&nbsp;&nbsp;<img src="/images/pad-pen.gif" border="0"></TD>
				<TD valign="middle"><FONT SIZE="4" COLOR="#8192E0"><B>&nbsp;My Profile</B></FONT></TD>
			</TR>
			</TABLE>
			</div>
			<div style='padding:2.0pt 2.0pt 2.0pt 58.0pt'>
					<TABLE cellpadding=3 cellspacing=0 border=0 style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #000000;">
					<TR><TD>User-id</TD>			<TD>&nbsp;:&nbsp;</TD>	<TD><?=$membername?></TD>				</TR>
					<TR><TD>Registered date</TD>	<TD>&nbsp;:&nbsp;</TD>	<TD><?=$date?></TD>						</TR>
					<TR><TD>Full Name</TD>			<TD>&nbsp;:&nbsp;</TD>	<TD><?=$_SESSION["lmembername"]?></TD>	</TR>
					<TR><TD colspan=3 height=20></TD>																</TR>
					<TR><TD>Company</TD>			<TD>&nbsp;:&nbsp;</TD>	<TD><?=$company?></TD>					</TR>
					<TR><TD valign=top>Address</TD>	<TD>&nbsp;:&nbsp;</TD>	<TD><?=$address?></TD>					</TR>
					<TR><TD>City</TD>				<TD>&nbsp;:&nbsp;</TD>	<TD><?=$city?></TD>						</TR>
					<TR><TD>Country</TD>			<TD>&nbsp;:&nbsp;</TD>	<TD><?=$country?></TD>					</TR>
					<TR><TD colspan=3 height=20></TD>																</TR>
					<TR><TD>Phone</TD>				<TD>&nbsp;:&nbsp;</TD>	<TD><?=$phone?></TD>					</TR>
					<TR><TD>Email</TD>				<TD>&nbsp;:&nbsp;</TD>	<TD><?=$_SESSION["memberemail"]?></TD>	</TR>
					<TR><TD>URL</TD>				<TD>&nbsp;:&nbsp;</TD>	<TD><?=$url?></TD>						</TR>
					<TR><TD>Server-IP</TD>			<TD>&nbsp;:&nbsp;</TD>	<TD><?=$serverip?></TD>					</TR>
					</TABLE>
			</DIV>
			<BR><BR>
			<script language="javascript">	
				function showhidemenu(divid2,divid3)
				{
					if (document.getElementById(divid2).style.display == 'none')
					{	document.getElementById(divid2).style.display = 'block';
						document.getElementById(divid3).style.display = 'none';
					}else{
						document.getElementById(divid2).style.display = 'none';
					}
				}

				function validate() {
				var f = document.changepwd.epwd.value;
				var g = document.changepwd.npwd.value;
				var h = document.changepwd.rnpwd.value;

					if (f == "") {	
						alert('Please enter Your current Password');
						document.changepwd.epwd.focus();
						return false;
					}

					if (g == "") {	
						alert('Please enter your New Password');
						document.changepwd.npwd.focus();
						return false;
					}

					if (h != g) {	
						alert('Passwords donot match \n\n please Enter again');
						document.changepwd.npwd.focus();
						return false;
					}
				
				return true;				
		}
			</script>
			<TABLE align="center" width=500 style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #000000;">
			<TR>
				<TD><a style='cursor:hand' onClick="showhidemenu('2','4')">Update Contact details</A></TD>
				<TD><a style='cursor:hand' onClick="showhidemenu('4','2')">Change Password</A></TD>
			</TR>
			</TABLE>

<div id='4' style='display:none;padding:5.0pt 3.0pt 3.0pt 12.0pt'>
		<BR>
		<TABLE border=0 width="600" align="center" cellpadding=0 cellspacing=1 bgcolor="#000000">
		<TR><TD>
			<table width="100%" border=0 cellpadding=2 cellspacing=2 style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;" bgcolor="#FFFFFF">
			<form action="update.php" method="post" name="changepwd" onSubmit="return validate();">
			<TR><TD colspan=2 align="center" bgcolor="#E9DFC0" height=20><B>&nbsp;Change Password</B></TD></TR>
			<TR><TD colspan=2 height=4></TD></TR>
			<TR><TD align="right">Current Password :&nbsp;</TD>
				<TD><input type="password" name="epwd" size=20><input type="hidden" name="action" value="cpwd"></TD>
			</TR>
			<TR><TD align="right">New Password :&nbsp;</TD>
				<TD><input type="password" name="npwd" size=20></TD>
			</TR>
			<TR><TD align="right">ReType New Password :&nbsp;</TD>
				<TD><input type="password" name="rnpwd" size=20></TD>
			</TR>
			<TR><TD colspan=2 align="center"><input type="submit" value="Change" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; font-weight: bold; color: #993300;"></TD>
			</TR>
			</form>
			</TABLE>
		</TD></TR>
		</TABLE>
		<BR>
</div>

<div id='2' style='display:none;padding:5.0pt 3.0pt 3.0pt 12.0pt'>
		<BR>
		<TABLE border=0 width="600" align="center" cellpadding=0 cellspacing=1 bgcolor="#000000">
		<TR><TD>
			<table width="100%" border=0 cellpadding=2 cellspacing=2 style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;" bgcolor="#FFFFFF">
			<form action="update.php" method="post" name="contactdetails" onSubmit="return checkcdetails();">
			
			<TR><TD colspan=2 align="center" bgcolor="#E9DFC0" height=20><B>&nbsp;Update contact details</B></TD></TR>
			<TR><TD colspan=2 height=4></TD></TR>
			<TR><TD align="right" width="40%">Company :&nbsp;</TD>
				<TD><input type="text" name="company" value="<?=$company?>" size=40><input type="hidden" name="action" value="cdet"></TD>
			</TR>
			<TR><TD align="right">Address :&nbsp;</TD>
				<TD><input type="text" name="address" value="<?=$address?>" size=40></TD>
			</TR>
			<TR><TD align="right">City :&nbsp;</TD>
				<TD><input type="text" name="city" value="<?=$city?>" size=40></TD>
			</TR>
			<TR><TD align="right">Country :&nbsp;</TD>
				<TD><input type="text" name="country" value="<?=$country?>" size=40></TD>
			</TR>
			<TR><TD align="right">Ph:&nbsp;</TD>
				<TD><input type="text" name="phone" value="<?=$phone?>" size=40></TD>
			</TR>
			<TR><TD align="right">Email:&nbsp;</TD>
				<TD><input type="text" name="email" value="<?=$_SESSION["memberemail"]?>" size=40></TD>
			</TR>
			<TR><TD align="right">URL:&nbsp;</TD>
				<TD><input type="text" name="url" value="<?=$url?>" size=40></TD>
			</TR>
			<TR><TD align="right">Server Ip:&nbsp;</TD>
				<TD><input type="text" name="sip" value="<?=$serverip?>" size=40></TD>
			</TR>
			<TR><TD colspan=2 align="center">
				<input type="submit" value="Change" style="font-family:Verdana,Arial;font-size:10px;font-weight:bold;color:#993300;">
				&nbsp;&nbsp;&nbsp;
				<input type="reset" value="Reset" style="font-family:Verdana,Arial;font-size:10px;font-weight:bold;color:#993300;">
			</TD>
			</TR>
			</form>
			</TABLE>
		</TD></TR>
		</TABLE>
		<BR>
</div>

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
<?mysql_close($link); ?>