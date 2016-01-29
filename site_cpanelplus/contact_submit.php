<?php
require_once "include_header.php";
?>
<TR><TD>
	<TABLE width="750" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
	<TR><TD>

			<TABLE width="700" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #7A7A7A;">
			<TR><TD>
			<div style='padding:2.0pt 2.0pt 2.0pt 2.0pt'><FONT SIZE="4" COLOR="#8192E0"><B>Contact Us</B></FONT></div>
			<div align="justify">
			<BR><BR>
				<?
				$messagetobesent =  "IP: ".$_SERVER["REMOTE_ADDR"]."\n BROWSER: ".$_SERVER["HTTP_USER_AGENT"]."\n\n\n\n\n------- Message ------- \n\n ".$_POST["message"];
				if(mail("contactus@cpanelplus.net", "Message from Contactus Page - ".substr(md5(uniqid("")),0,12), $messagetobesent, 
					"From: ".$_POST["name"]."<".$_POST["email"].">\r\n"."Reply-To: ".$_POST["email"]."\r\n" 
				   ."X-Mailer: Maximus 007")){
					   echo "Your message has been submitted to our support team.<BR><BR>
						We will get in contact ASAP. Please allow 12-24 hrs for a trouble ticket response.<BR>
						In most cases it will only be a few hours. 
						<BR><BR><BR><center><BUTTON onClick=location='index.html'>&nbsp;OK&nbsp;</BUTTON></center>
						";
				   }else{			
						echo "There was a problem in submitting your message<BR>please try again !";
				   }; 
				?>
			</DIV>
			</td></tr>
			</table>
			<BR><BR><BR><BR><BR><BR><BR><BR>
	</TD></TR>
	</TABLE>
</td></tr>
<?php
require_once "include_footer.php";
?>