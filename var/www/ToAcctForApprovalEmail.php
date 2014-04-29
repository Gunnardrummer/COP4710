<?php
//Needs to be sent from PerformQuery after requester fills out order form and order id is created.
//This email tells accountant to go into system to approve new order.

session_start()

$email = theresa@eecs.ucf.edu;
$Urgent = $_SESSION["Urgent"];
$CompPurchase = $_SESSION["CompPurchase"];
$Vendor = $_SESSION["Vendor"];
$ItemDesc = $_SESSION["ItemDesc"];
$Amount = $_SESSION["Amount"];
$orderId = $_SESSION["OrderId"];

if($Urgent == 'true')
{
	$UrgentString = 'This order is urgent.';
}
else
{
	$UrgentString = '';
}

$subject = "A new order has been placed and needs to be approved.";
$message = "We have received a new order request that needs to be approved. The order ID is $orderId.\n  
The purchase is from $Vendor and should cost $Amount.\n
The description supplied is:\n$ItemDesc.\n
$UrgentString\n
The order can be viewed, approved, and changed on your account.
";
$To = $email;
$webmaster = sfreund@eecs.ucf.edu;
$headers = "From: $webmaster";

if( mail($To, $subject, $message, $headers))
{
	echo "Email sent";
}
else
	echo "Email not sent";

?>