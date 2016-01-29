<?php
require_once "include_header.php";
?>
<TR><TD valign="center" align="center" bgcolor="#FFFFFF">
<div style='padding:2.0pt 2.0pt 2.0pt 22.0pt' align="left">
	<FONT SIZE="4" COLOR="#8192E0"><B>Forgot Password !</B></FONT>
</div>
<?
$membername =$_POST["membername"];
$emailid = $_POST["memberemailid"];

include "mysql.inc";
$link = mysql_connect("$mysqlhost", "$mysqluser", "$mysqlpassword") or die("Could not connect : " . mysql_error());
mysql_select_db("$mysqldb") or die("Could not select database"); 
$result = mysql_query("select password from members where username = '$membername' and emailid like '%$emailid%'") or die("Query failed : " . mysql_error());
$num_rows = mysql_num_rows($result);

if($num_rows <> 1){ echo "Invalid username or emailid. Please try again <BR><BR> <A href=\"/forgotpwd.html\">Ok</A><BR><BR><BR>"; }

if($num_rows == 1){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){$password = $row["password"] ;}
		$resetstring = md5(strrev(md5(strrev($password))));

		$message ="

		Hello $membername,

		We have received a request to reset your member password at cPanelPlus.net.
		If you are the person who has requested to do so, please clik on the below URL.

		http://cpanelplus.net/resetpwd.php?user=$membername&vid=$resetstring




		If are not the person who has requested this password change,
		you can just ignore this message.

		-----------------------------------------------------------------
		Note: This is an automated mail. Please donot reply to this eMail.
		";


		if(@mail("$emailid", "cPanelPlus.net - Request for Password Reset", $message, 
			"From: nobody@cpanelplus.net\r\n" 
		   ."Reply-To: nobody@cpanelplus.net\r\n" 
		   ."X-Mailer: Maximus 007")){
				echo "<BR><BR>A confirmation mail has been sent to <U>$emailid</U> <BR><BR>
						To reset your password, Please check your email and click on the URL mentioned there.";
				echo "<BR><BR><BR><BR><BR><BR>";
		   }else{
				echo "<BR><BR>There was an error while requesting for password reset.<BR><BR>
				Please contact admin &lt;admin@cpanelplus.net&gt; through email";
				echo "<BR><BR><BR><BR><BR><BR>";
		   };

echo "http://cpanelplus.net/resetpwd.php?user=$membername&vid=$resetstring";
}
?>
</td></tr>

<?php
require_once "include_footer.php";
?>