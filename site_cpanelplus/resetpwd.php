<?php
require_once "include_header.php";
?>
<TR><TD valign="center" align="center" bgcolor="#FFFFFF">
<div style='padding:2.0pt 2.0pt 2.0pt 22.0pt' align="left">
	<FONT SIZE="4" COLOR="#8192E0"><B>Forgot Password !</B></FONT>
</div>
<?
$user = $_GET["user"];
$vid = $_GET["vid"];

include "mysql.inc";
$link = mysql_connect("$mysqlhost", "$mysqluser", "$mysqlpassword") or die("Could not connect : " . mysql_error());
mysql_select_db("$mysqldb") or die("Could not select database"); 
$result = mysql_query("select password, emailid from members where username = '$user'") or die("Query failed : " . mysql_error());
$num_rows = mysql_num_rows($result);

if($num_rows <> 1){ echo "Invalid request<BR><BR><BR>"; }

if($num_rows == 1){
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)){$password = $row["password"] ; $emailid = $row["emailid"];}
		$resetstring = md5(strrev(md5(strrev($password))));
		if($resetstring == $vid){
					// Reset the password and send an email
					$newpwd = substr( md5(uniqid(rand(), true)), 0, 8); 
					$result = mysql_query("update members set password='$newpwd' where username='$user'") or die("Password Reset failed : " . mysql_error());

					$message ="\nHello $user,\n\nYour password as been reset to $newpwd \n\n\n
					-----------------------------------------------------------------
					Note: This is an automated mail. Please donot reply to this eMail. \n\n ";

					if(@mail("$emailid", "cPanelPlus.net - Request for Password Reset", $message, 
						"From: nobody@cpanelplus.net\r\n" 
					   ."Reply-To: nobody@cpanelplus.net\r\n" 
					   ."X-Mailer: Maximus 007")){
							echo "<BR><BR>You password has been reset, the new password has been sent to <U>$emailid</U> <BR><BR>
										  Please check your email and login with the password mentioned there.";
							echo "<BR><BR><BR><BR><BR><BR>";
					   }else{
							echo "<BR><BR><FONT COLOR=#FF0033>Your Password has been reset to $newpwd</FONT> ";
							echo "<BR><BR><BR><BR><BR><BR>";
					   };
		}
}
?>
</td></tr>

<?php
require_once "include_footer.php";
?>