<?php
session_start();

$query = $_SESSION["queryData"];
$OrderToEdit = $_GET["row"];
$OrderId = $_GET["orderId"];
$ReqDate = $_GET["reqDate"];
$role = $_GET["role"];
//navigation bar
echo'<!DOCTYPE html>
<html>
<head><style type="text/css">
ul
 {
 list-style-type:none;
 margin:0;
 padding:0;
 } 

#menu {
    width: 100%;
    height: 35px;
    font-size: 16px;
    font-family: Tahoma, Geneva, sans-serif;
    font-weight: bold;
    text-align: center;
    text-shadow: 3px 2px 3px #6B6B6B;
    background-color: Black;
        border-radius: 8px;
}
  #menu li { 
display: inline; 
padding: 20px; 
}

#menu a {
    text-decoration: none;
    color: White;
    padding: 8px 8px 8px 8px;
}

#menu a:hover {
    color: Black;
    background-color: #FFFFFF;
}
</style>


<title></title>
</head>
<body bgcolor="#DBA901">
<div id="menu">
<ul>
 <li><a href="AccountSettings.html">Account Settings</a></li>
 <li><a href="SelectQuery.php">Select New Query</a></li>
 <li><a href="OrderPage.html">Add Order</a></li>';
 if($role == 3)
 {
	    echo'<li><a href="AddUser.html">Add User</a></li>';
 }
 echo'
 <li><a href="logout.php">Logout</a></li>
 </ul> 
</div>';
if($ReqDate == null)
	$ReqDate = " ";
$RecDate = $_GET["recDate"];
if($RecDate == null)
	$RecDate = " ";
$PurDate = $_GET["purDate"];
if($PurDate == null)
	$PurDate = " ";
$AppDate = $_GET["appDate"];
if($AppDate == null)
	$AppDate = " ";
$AcctNum = $_GET["acctNum"];
if($AcctNum == null)
	$AcctNum = " ";
$Urgent = $_GET["urgent"];
if($Urgent == null)
	$Urgent = " ";
$ComPur = $_GET["comPur"];
if($ComPur == null)
	$ComPur = " ";
$Vendor = $_GET["vendor"];
if($Vendor == null)
	$Vendor = " ";
$Descrip = $_GET["descrip"];
if($Descrip == null)
	$Descrip = " ";
$PreNotes = $_GET["preNotes"];
if($PreNotes == null)
	$PreNotes = " ";
$Attachment = $_GET["attach"];
if($Attachment == null)
	$Attachment = " ";
$Requester = $_GET["requester"];
if($Requester == null)
	$Requester = " ";
$ReqEmail = $_GET["reqEmail"];
if($ReqEmail == null)
	$ReqEmail = " ";
$Amt = $_GET["amt"];
if($Amt == null)
	$Amt = " ";
$AcctCode = $_GET["acctCode"];
if($AcctCode == null)
	$AcctCode = " ";
$PONum = $_GET["poNum"];
if($PONum == null)
	$PONum = " ";
$PONotes = $_GET["poNotes"];
if($PONotes == null)
	$PONotes = " ";
	
$firstName = stristr($Requester, "_", true);
$lastName = trim(stristr($Requester, "_", false), "_");

//$role = $_GET["role"]
//$OrderToEdit = 0;

echo '<style>';
	echo 'table, th, td{border:2px solid black;}';
	echo 'table{background-color:black; align="center";}';
	echo 'th, td{padding:20px;}';	
	echo 'th{background-color:#F5DA81;}';
	echo 'td{background-color:#DBA901;}';
	echo 'body {background-color:#D8D8D8;}';
echo '</style>';
echo '<body bgcolor="#DBA901">';
echo '<form method="post" name="EditOrder" action="PerformQuery.php">';
echo'</br></br>';
echo '<table name="EditOrder">';
echo '<tr>';


	//table headings
	echo '<tr>';
	echo '<th>OrderId</th>';
	if($role > 1){
	echo '<th>OrderReqDate</th>';
	echo '<th>Recieved Date</th>';
	echo '<th>PurchaseDate</th>';
	echo '<th>ApprovalDate</th>';
	echo '<th>AcctNumber</th>';
	}
	echo '<th>Urgent</th>';
	if($role > 1){
	echo '<th>CompPurchase</th>';
	}
	echo '<th>Vendor</th>';
	echo '<th>ItemDesc</th>';
	if($role > 1){
	echo '<th>PreOrderNotes</th>';
	}
	echo '<th>Attachment</th>';
	echo '<th>FirstName</th>';
	echo '<th>LastName</th>';
	echo '<th>ReqEmail</th>';
	echo '<th>Amount</th>';
	if($role > 1){
	echo '<th>AcctCode</th>';
	echo '<th>PONumber</th>';
	echo '<th>PostOrderNotes</th>';
	}
	echo '</tr>';
	
	$recYear = null;
	$recMonth = null;
	$recDay = null;
	if($RecDate != null && $RecDate != " "){
		$recYear = stristr($RecDate, "-", true);
		$temp = trim(stristr($RecDate, "-", false), "-");
		$recMonth = stristr($temp, "-", true);
		$recDay = trim(stristr($temp, "-", false), "-");
	}
	
	$purYear = null;
	$purMonth = null;
	$purDay = null;
	if($PurDate != null && $PurDate != " "){
		$purYear = stristr($PurDate, "-", true);
		$temp = trim(stristr($PurDate, "-", false), "-");
		$purMonth = stristr($temp, "-", true);
		$purDay = trim(stristr($temp, "-", false), "-");
	}
	
	$appYear = null;
	$appMonth = null;
	$appDay = null;
	if($AppDate != null && $AppDate != " "){
		$appYear = stristr($AppDate, "-", true);
		$temp = trim(stristr($AppDate, "-", false), "-");
		$appMonth = stristr($temp, "-", true);
		$appDay = trim(stristr($temp, "-", false), "-");
	}

echo '<tr>';
	
	echo '<td>'.$OrderId.'</td>
			<input type = "hidden" name = "orderId"
			value ="'.$OrderId.'"/>';
			if($role>1){
			echo '<input type = "hidden" name = "orderReqDate"
			value ="'.$ReqDate.'"/>
		  <td>'.$ReqDate.'</td>';
		
				echo'<td>';	  
				echo '<input type ="text" name="recYear" value="';
				if($recYear != null)
					echo $recYear.'">Year</input>';
				else
					echo ' ">Year</input>';	
				echo '<input type ="text" name="recMonth" value="';
				if($recMonth != null)
					echo $recMonth.'">Month</input>';
				else
					echo ' ">Month</input>';	
				echo '<input type ="text" name="recDay" value="';
				if($recDay != null)
					echo $recDay.'">Day</input>';
				else
					echo ' ">Day</input>';
				 echo '</td>  
				  <td>';  
				  echo '<input type ="text" name="purYear" value="';
				if($purYear != null)
					echo $purYear.'">Year</input>';
				else
					echo ' ">Year</input>';	
				echo '<input type ="text" name="purMonth" value="';
				if($purMonth != null)
					echo $purMonth.'">Month</input>';
				else
					echo ' ">Month</input>';	
				echo '<input type ="text" name="purDay" value="';
				if($purDay != null)
					echo $purDay.'">Day</input>';
				else
					echo ' ">Day</input>';
				  echo '</td>  
				  <td>';
				  echo '<input type ="text" name="appYear" value="';
				if($appYear != null)
					echo $appYear.'">Year</input>';
				else
					echo ' ">Year</input>';	
				echo '<input type ="text" name="appMonth" value="';
				if($appMonth != null)
					echo $appMonth.'">Month</input>';
				else
					echo ' ">Month</input>';	
				echo '<input type ="text" name="appDay" value="';
				if($appDay != null)
					echo $appDay.'">Day</input>';
				else
					echo ' ">Day</input>';
				  echo '</td>  
				  <td>
				  <input type = "text" name= "acctNum" 
			  Value="'.$AcctNum.'">
				  </input></td>';
		  }
		  
		  echo'<td>
		  <input type = "hidden" name = "urgent" value = "false"/>
		  <input type = "checkbox" id = "urgent" name= "urgent" Value="true"';

		if($Urgent === "true")
			echo ' checked';
		
		echo '></input></td>';
		if($role > 1)
		{
			echo'
			  <td>
			  <input type = "hidden" name = "compPurchase" value = "false"/>
			  <input type = "checkbox" id = "compPurchase" name= "compPurchase" Value="true"';
			  
			  if($ComPur === "true")
					echo ' checked';
					
			echo '></input></td>';
		}
		echo'
		  <td>
		  <input type = "hidden" name = "query" value="EditOrder"/>
		  <input type = "text" name= "vendor" 
      Value="'.$Vendor.'">
		  </input></td>
		  <td>
		  <input type = "text" name= "description" 
      Value="'.$Descrip.'">
		  </input></td>';
		
		if($role>1)
		{
		echo'
			  <td>
			  <input type = "text" name= "preOrderNotes" 
		  Value="'.$PreNotes.'">
			  </input></td>';
		}
		echo'
		  <td>
		  <input type = "text" name= "attachment" 
      Value="'.$Attachment.'">
		  </input></td>
		  <td>
		  <input type = "text" name= "firstName"
		  Value= "'.$firstName.'">
		  </input></td>
		  <td>
		  <input type = "text" name= "lastName" 
      Value="'.$lastName.'">
		  </input></td>
		  <td>
		  <input type = "text" name= "email"
      Value="'.$ReqEmail.'">
		  </input></td>
		  <td>
		  <input type = "text" name= "amount" 
      Value="'.$Amt.'">
		  </input></td>';
		if($role>1)
		{
			  echo'
			  <td>
			  <input type = "text" name= "acctCode" 
		  Value="'.$AcctCode.'">
			  </input></td>
			  <td>
			  <input type = "text" name= "poNum" 
		  Value="'.$PONum.'">
			  </input></td>
			  <td>
			  <input type = "text" name= "postOrderNotes" 
		  Value="'.$PONotes.'">
			  </input></td></tr></table>';
		}

//The hidden field contains the row that must be changed
echo' <input type="hidden" name="row" value="';
echo $OrderToEdit;
echo '"/>';
echo ' 	<input type="submit" name="submit" value="Submit changes"/>';
echo '	</form>';

echo'</body>';

echo '</html>';

?>