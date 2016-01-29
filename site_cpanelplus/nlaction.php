<?php
exit();
////////
$action = $_GET["action"];
$encemailid = $_GET["id"];
$ver = $_GET["ver"];
$name = $_GET["name"];
$ver2 = md5(strrev($encemailid).$action);

if($ver2 != $ver ){header("Location: index.html"); exit();}
?>
<HTML>
<HEAD>
<TITLE>cPanelPlus.net - The ultimate resource for cPanel addons</TITLE>
<STYLE>
			A { color:#0033CC; Text-Decoration: underline;} 
			A:HOVER { color:#FF0099; Text-Decoration: none;}
</style>
</HEAD>

<BODY leftmargin=0 bgcolor="#C5CAE1">
<TABLE width="750" border="0" cellpadding="0" cellspacing="1" bgcolor="#000099" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
<TR><TD>
		<TABLE width="100%" bgcolor="#FFFFFF" border=0 align="center">
			<TR><TD align="left" height=42 bgcolor="#FFFFFF">&nbsp;<img src="./images/logo.gif" border=0></TD></TR>
		</table>
</td></tr>
<TR><TD valign="center" align="center" height=24 bgcolor="#FFFFFF">
		<TABLE width="750" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
		<TR>
			<TD align="center"><A href="/index.html">Home</A></TD>
			<TD align="center"><A href="/products.html">Products</A></TD>
			<TD align="center"><A href="/order.html">Order</A></TD>
			<TD align="center"><A href="/demos.html">Demos</A></TD>
			<TD align="center"><A href="/login.html">Members - Login</A></TD>
			<TD align="center"><A href="/faqs.html">FAQs</A></TD>
			<TD align="center"><A href="/forum/">Forum</A></TD>
			<TD align="center"><A href="/support.html">Support</A></TD>
			<TD align="center"><A href="/contactus.html">Contact Us</A></TD>
		</TR>
		</TABLE>	
</td></tr>
<TR><TD>
	<TABLE width="750" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
	<TR><TD>

			<TABLE width="700" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #A6A6A6;">
			<TR><TD>
			<div style='padding:2.0pt 2.0pt 2.0pt 2.0pt'><FONT SIZE="4" COLOR="#8192E0"><B>NewsLetter - <?echo ucfirst($action);?></B></FONT></div>
			<div align="justify" style='padding:2.0pt 2.0pt 2.0pt 2.0pt'>
			Receive news about our new products, updates, and special offers !!
<BR><BR><BR>
<?
// $action = $_GET["action"];
// $encemailid = $_GET["id"];

// Check whether this email has been registered OR Not
// If already member .. tell the same .. no action
// If not a member send a mail asking confirmation
include "mysql.inc";
$decryptedid = @mcrypt_ecb(MCRYPT_3DES, $key, base64_decode($encemailid), MCRYPT_DECRYPT); 

$link = mysql_connect("$mysqlhost", "$mysqluser", "$mysqlpassword") or die("Could not connect : " . mysql_error());
mysql_select_db("$mysqldb") or die("Could not select database"); 
$result = mysql_query("select emailid from nlregusers where emailid like '%$decryptedid%'") or die("Query failed : " . mysql_error());
$num_rows = mysql_num_rows($result); 

if( $num_rows == 1 && $action=="subscribe"){ 
	echo "<BR><BR><BR><FONT COLOR=\"#FF0033\">You are already registered to the newsletter.</FONT><BR><BR><BR>
			Note : If you want to unsubscribe from our Newsletter, Please visit our home page<BR>
				   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; and select the unsubscribe option
			<BR><BR><BR><BR><BR><BR>";
}elseif( $num_rows == 0 && $action=="unsubscribe" ){
		echo "<BR><BR><BR><FONT COLOR=\"#FF0033\">You are not subscribed to the newsletter.</FONT><BR><BR><BR>
			Note : You can unsubscribe only if you are subscribed to our Newsletter. To subscribe, Please our home page<BR>
				   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; and select the 'subscribe' option
			<BR><BR><BR><BR><BR><BR>";
}elseif( $num_rows ==1 && $action=="unsubscribe" ){
		$result = mysql_query("delete from nlregusers where emailid ='$decryptedid'") or die("<FONT COLOR=\"#FF0033\">UnSubscribe failed : " . mysql_error());
		echo "<BR><BR><FONT SIZE=+2>You are unsbsribed from our NewsLetter !</FONT>";
}elseif( $num_rows == 0 && $action=="subscribe" ){
		$result = mysql_query("INSERT INTO nlregusers (name, emailid) VALUES ('$name', '$decryptedid')") or die("<FONT COLOR=\"#FF0033\">Subscribtion failed : " . mysql_error());
		echo "<BR><BR><FONT SIZE=+2>You are Subscribed to our NewsLetter !</FONT>";
}





?>

<BR><BR><BR><BR>





			</div>
			</td></tr>
			</table>
<BR>
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
