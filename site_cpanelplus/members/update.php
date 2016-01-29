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
				<TD>&nbsp;&nbsp;&nbsp;<img src="/images/pad-pen.gif" border="0"></TD>
				<TD valign="middle"><FONT SIZE="4" COLOR="#8192E0"><B>&nbsp;My Profile</B></FONT></TD>
			</TR>
			</TABLE>
			</div>
			<BR>
<?
if($_POST["action"]=='cdet'){
$newcompany =  trim($_POST["company"]);
$newaddress =  trim($_POST["address"]);
$newcity =  trim($_POST["city"]);
$newcountry =  trim($_POST["country"]);
$newphone =  trim($_POST["phone"]);
$newemail =  trim($_POST["email"]);
$newurl =  trim($_POST["url"]);
$newsip =  trim($_POST["sip"]);

$membername = $_SESSION["lmembername"] ;

	include "../mysql.inc";
	$link = mysql_connect("$mysqlhost", "$mysqluser", "$mysqlpassword") or die("Could not connect : " . mysql_error());
	mysql_select_db("$mysqldb") or die("Could not select database"); 
	$result = mysql_query("UPDATE members SET emailid='$newemail',company= '$newcompany', address= '$newaddress',city='$newcity',country='$newcountry',phone='$newphone',url='$newurl',serverip='$newsip' WHERE username='$membername'") or die("Query failed : " . mysql_error());

	echo "<CENTER>Contact Details updated successfully</CENTER>";
	$_SESSION["memberemail"] = $newemail;

}




if($_POST["action"]=='cpwd'){
			$membername = $_SESSION["lmembername"] ;
			$newpwd = $_POST["npwd"];

			include "../mysql.inc";
			$link = mysql_connect("$mysqlhost", "$mysqluser", "$mysqlpassword") or die("Could not connect : " . mysql_error());
			mysql_select_db("$mysqldb") or die("Could not select database"); 

			$result = mysql_query("select password from members where username ='$membername'") or die("Query failed : " . mysql_error());
			$num_rows = mysql_num_rows($result); 

			if($num_rows <> 1){ echo "<CENTER><FONT SIZE=\"2\" face=\"arial\">Password not updated, Please try again</FONT></CENTER>";}

			if($num_rows == 1){
						while($row = mysql_fetch_array($result, MYSQL_ASSOC)){ $password = $row["password"] ; }

						if($_POST["epwd"]!=$password){
							echo "<CENTER><FONT SIZE=\"2\" face=\"arial\">Password not updated, Please try again</FONT></CENTER>";
						}


						if($_POST["epwd"]==$password){
							$result2 = mysql_query("UPDATE members SET password= '$newpwd' WHERE username='$membername'");
							echo "<CENTER><FONT SIZE=2 face=\"arial\"><B>Password updated !</B></FONT></CENTER><BR>";
						}
			}

}
?>
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