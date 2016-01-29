<?
session_start();

	if($_SESSION["accountcreated"]=="YES"){
		header("Location: /index.html"); 
		exit();
	}


$secret="smartass123"; // put your secret word here 
$seller_id="94333"; // put your store id here 
$order_number = $_POST["order_number"];
$total = $_POST["total"];
$key = $_POST["key"];

$compare2c = strtoupper(md5("$secret"."$seller_id"."$order_number"."$total")); 

if($key != $compare2c){	header("Location: /index.html"); exit(); } 

$card_holder_name = $_POST["card_holder_name"];
$street_address = $_POST["street_address"];
$city = $_POST["city"];
$state = $_POST["state"];
$zip = $_POST["zip"];
$country = $_POST["country"];
$custemail = $_POST["email"];
$phone = $_POST["phone"];
$merchant_order_id = $_POST["merchant_order_id"];
$credit_card_processed = $_POST["credit_card_processed"];

// $ship_name = $_POST["ship_name"];
// $ship_street_address = $_POST["ship_street_address"];
// $ship_city = $_POST["ship_city"];
// $ship_state = $_POST["ship_state"];
// $ship_zip = $_POST["ship_zip"];
// $ship_country = $_POST["ship_country"];
$product_id = $_POST["product_id"];
$quantity = $_POST["quantity"];
$merchant_product_id = $_POST["merchant_product_id"];
$product_description = $_POST["product_description"];


	// Send a Mail to admin with all the details
	// 


$_SESSION["accountcreated"] ="YES";
$_SESSION["customeremailid"] = $custemail ;


include "../mysql.inc";
$link = mysql_connect("$mysqlhost", "$mysqluser", "$mysqlpassword") or die("Could not connect : " . mysql_error());
mysql_select_db("$mysqldb") or die("Could not select database"); 
$result = mysql_query("select username from members") or die("Query failed : " . mysql_error());
$num_rows = mysql_num_rows($result); 
$newusername = 1200 + intval($num_rows) + 1;
$newpassword = substr( md5(uniqid(rand(), true)),0,8); 
$todaydate = date("Y-m-d");    
if($merchant_product_id=="NX12"){$nexion="Y";}else{$nexion="N";}
$result = @mysql_query("INSERT INTO members (username, password, name, emailid, nexion, amount, date, company, address, city, country, phone, url, serverip, lastlogin, 2co_orderid, lastloginip) VALUES ('$newusername', '$newpassword', '$card_holder_name', '$custemail', '$nexion', '$total','$todaydate', NULL, '$street_address', '$city', '$country', '$phone', NULL, NULL, NULL,'$order_number', NULL)") or die("Fatal Error - Contact <A href=\"mailto:sales@cpanelplus.net\">Sales@cpanelplus.net</A>");

$mailtocustomer = "
Dear $card_holder_name,

	Your cPanelPlus.Net Member Account has been created succesfully !

	Username : $newusername
	Password : $newpassword

	Please login into your account and download the products that you have
	purchased at cpanelplus.net

	If you have any problems with your Account please immeditely
	contact us at support@cpanelplus.net

	regards
	cPanelPlus.Net Support Team

    Note : After you login, Please update your profile with 
           your WebSite URL, Server IP and other details !

";

/*
require("../class.phpmailer.php");
$mail = new phpmailer();
$mail->IsSMTP(); $mail->Host = "66.36.30.140";
$mail->SMTPAuth = true;
$mail->Username = "mwebsmtp@mwebsmtp.mihiraweb.com" ;
$mail->Password = "mwebsmtp";
$mail->From = "noone@cpanelplus.net";
$mail->FromName = "cPanelPlus.net";
$mail->AddAddress("$custemail", "$custemail");
$mail->WordWrap = 75;                                 // set word wrap to 50 characters
$mail->IsHTML(true);                                  // set email format to HTML

$mail->Subject = "New Order at cPanelPlus.Net - $order_number";
$mail->Body    = "$mailtocustomer";
$mail->AltBody = "$mailtocustomer";
if(!$mail->Send())
{

  @mail("$custemail", "New Order at cPanelPlus.Net - $order_number", $mailtocustomer, 
    "From: cPanelPlus.net <noone@cpanelplus.net> \r\n"."Reply-To: admin@cpanelplus.net\r\n","-fnoone@cpanelplus.net");
}
*/

 @mail("$custemail", "Your cPanelPlus.Net Account Information", $mailtocustomer, 
    "From: Support - cPanelPlus.net <support@cpanelplus.net> \r\n"."Reply-To: support@cpanelplus.net\r\n","-fsupport@cpanelplus.net");




$mailtoadmin = "
Hey there is a new sale CONFIRMED & the account is created automatically.

here are the details
	Order Number :: $order_number
	Card Holders Name :: $card_holder_name
	Street Address :: $street_address
	City :: $city 
	State :: $state
	Zip :: $zip
	Country :: $country 
	Customer Email id :: $custemail 
	Phone Number :: $phone 
	Merchand Order Id :: $merchant_order_id 
	CreditCard Transaction Status :: $credit_card_processed 
	Total Transaction Amount :: $total 
	Product Id :: $product_id 
	Quantity  :: $quantity 
	Merchant Product Id :: $merchant_product_id
	Product Description :: $product_description 

--- Message that was sent to the user ---
Dear $card_holder_name,

	Your cPanelPlus.Net Member Account has been created succesfully !

	Username : $newusername
	Password : $newpassword

	Please login into your account and download the products that you have
	purchased at cpanelplus.net

	If you have any problems with your Account please immeditely
	contact us at support@cpanelplus.net

	regards
	cPanelPlus.Net Support Team

	Note : After you login, Please update your profile with 
           your WebSite URL, Server IP and other details !

---------------------------------------
";
@mail("admin@cpanelplus.net", "New Confirmed sale at cPanelPlus.Net - $order_number", $mailtoadmin, 
    "From: cPanelPlus.net <noone@cpanelplus.net> \r\n"."Reply-To: admin@cpanelplus.net\r\n","-fnoone@cpanelplus.net"); 



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
			<TR><TD align="left" height=42 bgcolor="#FFFFFF">&nbsp;<img src="/images/logo.gif" border=0></TD></TR>
		</table>
</td></tr>
<TR><TD valign="center" align="center" height=24 bgcolor="#FFFFFF">
		<TABLE width="750" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
		<TR>
			<TD align="center"><A href="index.html">Home</A></TD>
			<TD align="center"><A href="products.html">Products</A></TD>
			<TD align="center"><A href="order.html">Order</A></TD>
			<TD align="center"><A href="demos.html">Demos</A></TD>
			<TD align="center"><A href="login.html">Members - Login</A></TD>
			<TD align="center"><A href="faqs.html">FAQs</A></TD>
			<TD align="center"><A href="/forum/" target="_blank">Forum</A></TD>
			<TD align="center"><A href="support.html">Support</A></TD>
			<TD align="center"><A href="contactus.html">Contact Us</A></TD>
		</TR>
		</TABLE>	
</td></tr>
<TR><TD>
	<TABLE width="750" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: normal; color: #000000;">
	<TR><TD>

			<TABLE width="700" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align="center" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #7A7A7A;">
			<TR><TD>
			<div style='padding:2.0pt 2.0pt 2.0pt 2.0pt'><FONT SIZE="4" COLOR="#8192E0"><B>Purchase Status</B></FONT></div>
			<div align="left" style='padding:2.0pt 2.0pt 2.0pt 22.0pt'>
			<PRE>
 Dear <?=$card_holder_name?>,

 We have received your order at cpanelplus.net,
        Order Number : <?=$order_number?>
 Product Description : <?=$product_description?>
  Transaction Amount : <?=$total?>

Your cPanelPlus.Net Member Account has been created succesfully !
Your Username & Password were sent to <U><?=$custemail?></U>

Please login into your account and download the products that you 
have purchased at cpanelplus.net

If you have any problems with your Account please immeditely
contact us at support@cpanelplus.net

regards
cPanelPlus.Net Support Team
http://cpanelplus.net/

Note : If you are using Yahoo! mail and spam filter is enabled,
	   the mail from us might have been placed in your Bulk-Mail Folder.
	   Please check your Bulk-Mail folder also!
</PRE>
				</DIV>
				<BR><BR><BR><center><BUTTON onClick=location='/index.html'>&nbsp;OK&nbsp;</BUTTON></center>
			</td></tr>
			</table>
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

