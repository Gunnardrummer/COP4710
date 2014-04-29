<?php
session_start();
$filename=AllOrders.csv;

$query = $_SESSION["queryData"];
$numrows=count($query);
$numCols = count($query[0]);

$newArray = array(array());

$newArray[0][0] = "OrderId";
$newArray[0][1] = "OrderReqDate";
$newArray[0][2] = "PurchaseDate";
$newArray[0][3] = "ApprovalDate";
$newArray[0][4] = "ReceiveDate";
$newArray[0][5] = "AcctNumber";
$newArray[0][6] = "Urgent";
$newArray[0][7] = "CompPurchase";
$newArray[0][8] = "Vendor";
$newArray[0][9] = "ItemDesc";
$newArray[0][10] = "PreOrderNotes";
$newArray[0][11] = "Attachment";
$newArray[0][12] = "Requestor";
$newArray[0][13] = "ReqEmail";
$newArray[0][14] = "Amount";
$newArray[0][15] = "AcctCode";
$newArray[0][16] = "PONumber";
$newArray[0][17] = "PostOrderNotes";

for($i=0;$i<$numrows;$i++)
{
	for($j=0;$j<$numCols;$j++)
	{
		$newArray[$i+1][$j] = $query[$i][$j];
	}
}

$delimiter = "\t";
foreach ($newArray as $fields) 
{
	$dataRow = implode($delimiter,$fields);
	print $dataRow."\r\n";
}

header('HTTP/1.1 200 OK');
header('Cache-Control: no-cache, must-revalidate');
header("Pragma: no-cache");
header("Expires: 0");
header('Content-Type: application/csv');
header('Content-Disposition: attachement; filename="'.$filename.'";');
?>

