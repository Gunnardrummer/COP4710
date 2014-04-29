<?php
//Needs to be added to perform query.  Email needs to be sent to requester when a purchaser modifies the order date.
session_start()

$orderDate = $_SESSION["PurchaseDate"];
$orderId = $_SESSION["OrderId"];
$email = $_SESSION["ReqEmail"];

$To = $email;
$webmaster = sfreund@eecs.ucf.edu;

$subject = "Order number $orderId was purchased.";
$message = "Your order was purchased.\n The order date is $orderDate.";
$headers = "From: $webmaster";

if( mail($To, $subject, $message, $headers))
{
	echo "Email sent";
}
else
	echo "Email not sent";

?>