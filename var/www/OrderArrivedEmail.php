<?php
//Needs to be added to performquery. Email sent to requester and accountant when purchaser updates RecieveDate. 
session_start()

$ReceiveDate = $_SESSION["ReceiveDate"];
$orderId = $_SESSION["OrderId"];
$email = $_SESSION["ReqEmail"];

$To  = '$email' . ', ';
$To .= 'theresa@eecs.ucf.edu';

$webmaster = sfreund@eecs.ucf.edu;

$subject = "Order number $orderId was received.";
$message = "Your order was received on $ReceiveDate.";
$headers = "From: $webmaster";

if( mail($To, $subject, $message, $headers))
{
	echo "Email sent";
}
else
	echo "Email not sent";

?>