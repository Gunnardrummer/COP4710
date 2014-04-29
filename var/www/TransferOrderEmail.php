<?php
//This email is sent if the purchasers want to transfer among each other.  Can be left in FinalTableButtons.php
session_start()

computer = $_Get["comp"];
orderId = $_Get["oId"];

if (computer = 'yes')
{
	$To = Kenneth.Enloe@ucf.edu;
	$webmaster = sfreund@eecs.ucf.edu;
}
else
{
	$To = sfreund@eecs.ucf.edu;
	$webmaster = Kenneth.Enloe@ucf.edu;
}

if($Urgent == 'true')
{
	$UrgentString = 'This order is urgent.';
}
else
{
	$UrgentString = '';
}

$subject = "A new order was approved by the accountant and transferd to you.";
$message = "A new order that has been approved and transfered to you is ready for purchase.\n
The order Id is: $OrderId.\n 
$UrgentString\n
This order can be viewed on your account.";
$headers = "From: $webmaster";

if( mail($To, $subject, $message, $headers))
{
	echo "Email sent";
}
else
	echo "Email not sent";

?>