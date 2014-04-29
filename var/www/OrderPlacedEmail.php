<?php
//Needs to be sent from PerformQuery after requester fills out order form and order id is created.
//This email describes the order that the requester created and is sent to the requester.

session_start()

$ReqEmail = $_SESSION["ReqEmail"];
$Urgent = $_SESSION["Urgent"];
$CompPurchase = $_SESSION["CompPurchase"];
$Vendor = $_SESSION["Vendor"];
$ItemDesc = $_SESSION["ItemDesc"];
$Amount = $_SESSION["Amount"];
$orderId = $_SESSION["OrderId"];

if ($CompPurchase = 'true')
{
	$CompPurchaseString = 'If you have any questions about your computer purchase, please contact sfreund@eecs.ucf.edu';
}
else
{
	$CompPurchaseString = 'If you have questions about your purchase, please contact Kenneth.Enloe@ucf.edu';
}

if($Urgent == 'true')
{
	$UrgentString = 'Your order is urgent and we are getting to it as soon as possible'.
}
else
{
	$UrgentString = '';
}

$subject = "You have placed a new order.";
$message = "We have received your order request. The order ID is $orderId.\n  
$CompPurchaseString.\n Your purchase is from $Vendor and should cost $Amount.\n
The description you supplied is $ItemDesc.
";
$To = $ReqEmail;
$webmaster = sfreund@eecs.ucf.edu;
$headers = "From: $webmaster";

if( mail($To, $subject, $message, $headers))
{
	echo "Email sent";
}
else
	echo "Email not sent";

?>