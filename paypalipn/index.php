<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Test form Paypal</title>
</head>

<body>
<h2>Processing Transaction...</h2>
<p><strong>Please wait... please don't close this window.</strong></p>
<?php
// https://www.sandbox.paypal.com/cgi-bin/webscr?
// https://www.paypal.com/cgi-bin/webscr
?>
<form method="post" name="paypal_form" action="https://www.sandbox.paypal.com/cgi-bin/webscr?">

    <input type="hidden" name="business" value="sales@centerlimit.com" />
    <input type="hidden" name="cmd" value="_xclick" />
    <!-- the next three need to be created -->
    <input type="hidden" name="image_url" value="http://centerlimit.com/logo.jpg" />
    <input type="hidden" name="return" value="http://www.centerlimit.com/paypal_ipn/success.php" />
    <input type="hidden" name="cancel_return" value="http://www.centerlimit.com/paypal_ipn/cancelled.php" />
    <input type="hidden" name="notify_url" value="http://www.centerlimit.com/paypal_ipn/ipn.php" />
    <input type="hidden" name="rm" value="2" />
    <input type="hidden" name="currency_code" value="USD" />
    <input type="hidden" name="lc" value="US" />
    <input type="hidden" name="bn" value="toolkit-php" />
    <input type="hidden" name="cbt" value="Continue" />
    
    <!-- Payment Page Information -->
    <input type="hidden" name="no_shipping" value="" />
    <input type="hidden" name="no_note" value="1" />
    <input type="hidden" name="cn" value="Comments" />
    <input type="hidden" name="cs" value="" />
    
    <!-- Product Information -->
    <input type="hidden" name="item_name" value="MY ITEM NAME" />
    <input type="hidden" name="amount" value="3" /> <!--  Actual Payment Amount -->
    <input type="hidden" name="quantity" value="1" />
    <input type="hidden" name="item_number" value="777" />
    <input type="hidden" name="undefined_quantity" value="" />
    <input type="hidden" name="on0" value="Order ID" />
    <input type="hidden" name="os0" value="12345-345" />
    <input type="hidden" name="on1" value="" />
    <input type="hidden" name="os1" value="" />
    
    <!-- Shipping and Misc Information -->
    <input type="hidden" name="shipping" value="" /> 
    <input type="hidden" name="shipping2" value="" />
    <input type="hidden" name="handling" value="" />
    <input type="hidden" name="tax" value="0" />  <!-- Tax Percent in Amount -->
    <input type="hidden" name="custom" value="" />
    <input type="hidden" name="invoice" value="" />
    
    <!-- Customer Information -->
    <input type="hidden" name="first_name" value="Mr. X" />
    <input type="hidden" name="last_name" value="" />
    <input type="hidden" name="address1" value="Street no. 1" />
    <input type="hidden" name="address2" value="" />
    <input type="hidden" name="city" value="MyTown" />
    <input type="hidden" name="state" value="" />
    <input type="hidden" name="zip" value="10004" />
    <input type="hidden" name="email" value="ship@to-me.com" />
    <input type="hidden" name="night_phone_a" value="" />
    <input type="hidden" name="night_phone_b" value="" />
    <input type="hidden" name="night_phone_c" value="" />


<input type="submit" name="Submit" value="Process Payment" />
</form>
</body>
</html>
