<?php
//Email from accountant to purchaser after accountant approves purchase. Should be able to leave it in FinalTableButtons.php
session_start()

$Urgent = $_Get["Urgent"];
$computer = $_Get["comp"];
$orderId = $_Get["oId"];

if (computer = 'yes')
{
	$To = sfreund@eecs.ucf.edu;
}
else
{
	$To = Kenneth.Enloe@ucf.edu;
}

if($Urgent == 'true')
{
	$UrgentString = 'This order is urgent.';
}
else
{
	$UrgentString = '';
}

$subject = "A new order was approved by the accountant.";
$message = "A new order has been approved and is ready for purchase.\n
The order Id is: $OrderId.\n 
$UrgentString\n
This change can be viewed on your account.";
$webmaster = theresa@eecs.ucf.edu;
$headers = "From: $webmaster";

if( mail($To, $subject, $message, $headers))
{
	echo "Email sent";
}
else
	echo "Email not sent";

?>