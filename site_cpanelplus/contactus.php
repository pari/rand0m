<?php
require_once "include_header.php";
?>
<TR><TD>
	<TABLE width="750" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
	<TR><TD>

			<TABLE width="700" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #A6A6A6;">
			<TR><TD>
			<div style='padding:2.0pt 2.0pt 2.0pt 2.0pt'><FONT SIZE="4" COLOR="#8192E0"><B>Contact Us</B></FONT></div>
			<div align="justify">
			Please fill in the form below. Please allow 12-24 hrs for a response from us.
			<BR><BR><BR>
<center><b>Our Address : #5-4-5/1, Bhavani Colony, RajendraNagar, Hyd-500030. India</b></center>
<center><b>Phone : +91-9618582582</b></center>
<BR><BR><BR>
<TABLE bgcolor="#C5CAE1" width="550" border="0" cellpadding="0" cellspacing="1" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
<TR><TD>
		<table width="550" border="0" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;" bgcolor="#FFFFFF">
		<form action="contact_submit.php" method="post">
		<tr><td width="18%" align="right">Name:</td><td width="82%"><input name="name" type="text" size=30></td></tr>
		<tr><td align="right">Email:</td><td><input name="email" type="text" size=30></td></tr>
		<tr><td valign="top"  align="right">Message:</td>
		<td><textarea name="message" cols="50" rows="7"></textarea> 
		<br>Note: Please give us information as much as possilble 
		to process your request faster.</td>
		</tr>
		<tr><td  align="center"></td>
			<td>
				<input type="submit" name="Submit" value="Submit">&nbsp;&nbsp;
				<input type="reset" name="Submit2" value="Reset">
			</td>
		</tr>
		</form>
		</table>
</td></tr>
</table>
<BR><BR>





			</div>
			</td></tr>
			</table>
<BR>
	</TD></TR>
	</TABLE>
</td></tr>

<?php
require_once "include_footer.php";
?>