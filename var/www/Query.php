<?php
//Must be at the top of all html pages
session_start();

//role value indicates who is who: 1- Purcahser 2-Accountants 3-Admin
	$roleValue = $_SESSION["role"];
	$role = strval($roleValue);
	

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

$query = $_GET["query"];

//if query is verify users
if(strcmp($query, "ViewUsers")==0 || $query === "RemoveUser" || $query === "EditUser")
{
	//retrieves array from database that holds all the data
	$queryData = $_SESSION["queryData"];
	//number of rows in the table
	$numrows=count($queryData);
	
	//style page for table
	echo '<head>';
	echo '<style>';
	echo 'table, th, td{border:2px solid black;}';
	echo 'table{background-color:black; align="center";}';
	echo 'th, td{padding:20px;}';	
	echo 'th{background-color:#F5DA81;}';
	echo 'td{background-color:#DBA901;}';
	echo 'body {background-color:#D8D8D8;}';
	echo '</style>';
	echo '</head>';
	echo '<html>';
	echo '<body>';
	echo '<br/><br/>';
	echo '<table>';
	echo '<tr>';
	//table headings
	echo '<th>UserID</th>';
	echo '<th>UserName</th>';
	echo '<th>FirstName</th>';
	echo '<th>LastName</th>';
	echo '<th>Email</th>';
	echo '<th>Role</th>';
	echo '<th>Edit or Remove User</th>';
	echo '</tr>';
	$numCols = count($queryData[0]);
	for($i=0; $i<$numrows; $i++)
	{
		echo '<tr>';
		for($j=0; $j<$numCols; $j++)
		{
			//places data in appropriate table locations
			echo '<td>';
			echo $queryData[$i][$j];
			echo '</td>';
			if($j==5)
			{
				//Adds buttons to end of each row
				//Edit button- copies user information into textboxes (except for userid and username which can be displayed but cannot be changed)
								//After information is changed, a submit button must send the the new information to PerformQuery.php
				//Remove Button- Must have pop up that verifies user can be deleted. If so, call PerformQuery.php and call the RemoveUser procedure
				echo '<td>';
				//changed from <buttton to <input to better manage the information
				//This could be wrong, but im trying to return the row in the array
				//that we want to edit, then we can open the new page with the information
				//in the array]
				echo '<a href=EditUser.php?row='.$i;
				echo '>Edit User</a>';
				echo '<br/><br/>';
				echo '<form name="removeUser'.$i.'" method="post" action="PerformQuery.php">';
				echo '<input type="hidden" name="query" value="RemoveUser"/>';
				echo '<input type="hidden" name="param" value="';
				echo $queryData[$i][1];
				echo '"/>';
				echo '<input type="hidden" name="row" value="';
				echo $i;
				echo '"/>';
				if($queryData[$i][5]!="admin")
				{
					echo "<input type='button' value='Remove' onclick=confirmDelete('$i')></button>";
				}
				echo '</form>';
				echo '</td>';
			}
		}
		
		echo '</tr>';
	}
	echo '
	<script type="text/javascript">
	function confirmDelete(row){
	var answer = confirm("Are you sure you want to remove this User?");
	if(answer){
		document.forms["removeUser"+row].submit();
	}
	else{}
	}
	</script></table></html>';
}
//If the query is NOT view users
else
{
	//array for query data and number of rows in the table
	$queryData = $_SESSION["queryData"];
	$numrows=count($queryData);
	
	//Sets up styling for table
	echo '<head>';
	echo '<style>';
	echo 'table, th, td{border:2px solid black;}';
	echo 'table{background-color:black; align="center";}';
	echo 'th, td{padding:20px;}';	
	echo 'th{background-color:#F5DA81;}';
	echo 'td{background-color:#DBA901;}';
	echo 'body {background-color:#D8D8D8;}';
	echo '</style>';
	echo '</head>';
	
	echo '<html>';
	echo'</br></br>';
	echo '<a href="CSVExport.php">Export to Excel</a></br></br>';
	echo '<table>';
	echo '<tr>';
	
	//Purchaser “order list” will contain the requestor, requestor’s email address, 
	//whether the purchase is urgent, vendor, item description, link to any 
	//attachments, and purchase amount (price).
	if($role == 1)
	{
		//Displays table headings
		echo '<th><a href="sort.php?col=0">Order ID</a></th>';
		echo '<th><a href="sort.php?col=1">Requester</a></th>';
		echo '<th><a href="sort.php?col=2">Requesters email</a></th>';
		echo '<th><a href="sort.php?col=3">Item Desc.</a></th>';
		echo '<th><a href="sort.php?col=4">Vendor</a></th>';
		echo '<th><a href="sort.php?col=5">Amount</a></th>';
		echo '<th><a href="sort.php?col=6">Urgent</a></th>';
		echo '<th><a href="sort.php?col=7">Attach</a></th>';
		echo '<th>Cancel Order</th>';
		echo '</tr>';
		echo '</form>';
		//Displays the data
		for($i=0; $i<$numrows; $i++)
		{
				$OrderId = $queryData[$i][0];
				$Urgent = $queryData[$i][6];
				$Vendor = $queryData[$i][4];
				$Descrip = $queryData[$i][3];
				$Attachment = $queryData[$i][7];
				$Requester = $queryData[$i][1];
				$ReqEmail = $queryData[$i][2];
				$Amt = $queryData[$i][5];
				
			echo '<tr>';
			for($j=0; $j<8; $j++)
			{
				echo '<td>';
				echo $queryData[$i][$j];
				echo '</td>';
				if($j==7)
				{
					echo '<td>';
					echo '<a href="EditOrder.php?row='.$i.' & role = '.$role.'&orderId='.$OrderId.'&urgent='.$Urgent.'&vendor='.$Vendor.'&descrip='.$Descrip.'&attach='.$Attachment.'&requester='.$Requester.'&reqEmail='.$ReqEmail.'&amt='.$Amt.'">Edit</a>';
					echo '<br/><br/>';
					echo '<form name="removeOrder'.$i.'" method="post" action="PerformQuery.php">';
					echo '<input type="hidden" name="query" value="RemoveOrder"/>';
					echo '<input type="hidden" name="param" value="';
					echo $queryData[$i][0];
					echo '"/>';
					echo '<input type="hidden" name="row" value="';
					echo $i;
					echo '"/>';
					echo "<input type='button' value='Remove' onclick=confirmDeleteOrder('$i')></button>";
					echo '</form>';
					echo '</td>';
				}
			}
		}
		echo '
		<script type="text/javascript">
		function confirmDeleteOrder(row){
			var answer = confirm("Are you sure you want to remove this order?");
			if(answer){
				document.forms["removeOrder"+row].submit();
			}
			else{}
			}
		</script>';
	}
	
	//Accountant and Admin order list
	if($role == 2 || $role == 3)
	{
		echo'<br/><br/>';
		//Displays table headings
		echo '<th><a href="sort.php?col=0">OrderId</a></th>';
		echo '<th><a href="sort.php?col=1">OrderReqDate</a></th>';
		echo '<th><a href="sort.php?col=2">PurchaseDate</a></th>';
		echo '<th><a href="sort.php?col=3">ApprovalDate</a></th>';
		echo '<th><a href="sort.php?col=4">ReceiveDate</a></th>';
		echo '<th><a href="sort.php?col=5">AcctNumber</a></th>';
		echo '<th><a href="sort.php?col=6">Urgent</a></th>';
		echo '<th><a href="sort.php?col=7">CompPurchase</a></th>';
		echo '<th><a href="sort.php?col=8">Vendor</a></th>';
		echo '<th><a href="sort.php?col=9">ItemDesc</a></th>';
		echo '<th><a href="sort.php?col=10">PreOrderNotes</a></th>';
		echo '<th><a href="sort.php?col=11">Attachment</a></th>';
		echo '<th><a href="sort.php?col=12">Requestor</a></th>';
		echo '<th><a href="sort.php?col=13">ReqEmail</a></th>';
		echo '<th><a href="sort.php?col=14">Amount</a></th>';
		echo '<th><a href="sort.php?col=15">AcctCode</a></th>';
		echo '<th><a href="sort.php?col=16">PONumber</a></th>';
		echo '<th><a href=sort.php?col=17">PostOrderNotes</a></th>';
		echo '<th>Edit</th>';
		echo '</tr>';
		echo '</form>';

		//Displays the data
		for($i=0; $i<$numrows; $i++)
		{
				if(count($queryData[$i]) == 18){
					$OrderId = $queryData[$i][0];
					$Urgent = $queryData[$i][6];
					$Vendor = $queryData[$i][8];
					$Descrip = $queryData[$i][9];
					$Attachment = $queryData[$i][11];
					$Requester = $queryData[$i][12];
					$ReqEmail = $queryData[$i][13];
					$Amt = $queryData[$i][14];
					$OrdDate = $queryData[$i][1];
					$PurDate = $queryData[$i][2];
					$AppDate = $queryData[$i][3];
					$RecDate = $queryData[$i][4];
					$AcctNum = $queryData[$i][5];
					$PreNotes = $queryData[$i][10];
					$AcctCode = $queryData[$i][15];
					$PONum = $queryData[$i][16];
					$PostNotes = $queryData[$i][17];
					$CompPur = $queryData[$i][7];
				}
				else{
					$OrderId = $queryData[$i][0];
					$Requester = $queryData[$i][1];
					$ReqEmail = $queryData[$i][2];
					$Descrip = $queryData[$i][3];
					$Vendor = $queryData[$i][4];
					$Amt = $queryData[$i][5];
					$Urgent = $queryData[$i][6];
					$Attachment = $queryData[$i][7];
					$OrdDate = " ";
					$PurDate = " ";
					$AppDate = " ";
					$RecDate = " ";
					$AcctNum = " ";
					$PreNotes = " ";
					$AcctCode = " ";
					$PONum = " ";
					$PostNotes = " ";
					$CompPur = " ";
				}
				
			echo '<tr>';
			for($j=0; $j<18; $j++)
			{
				echo '<td>';
				echo $queryData[$i][$j];
				echo '</td>';
				if($j==17)
				{
					echo '<td>';
					echo '<a href="EditOrder.php?row='.$i.' & role='.$role.'&orderId='.$OrderId.'&urgent='.$Urgent.'&vendor='.$Vendor.'&descrip='.$Descrip.'&attach='.$Attachment.'&requester='.$Requester.'&reqEmail='.$ReqEmail.'&amt='.$Amt.'&reqDate='.$OrdDate.'&purDate='.$PurDate.'&appDate='.$AppDate.'&recDate='.$RecDate.'&acctNum='.$AcctNum.'&preNotes='.$PreNotes.'&acctCode='.$AcctCode.'&poNum='.$PONum.'&poNotes='.$PostNotes.'&comPur='.$CompPur.'">Edit</a>';
					echo '<br/><br/>';
					echo '<form name="removeOrder'.$i.'" method="post" action="PerformQuery.php">';
					echo '<input type="hidden" name="query" value="RemoveOrder"/>';
					echo '<input type="hidden" name="param" value="';
					echo $queryData[$i][0];
					echo '"/>';
					echo '<input type="hidden" name="row" value="';
					echo $i;
					echo '"/>';
					echo "<input type='button' value='Remove' onclick=confirmDeleteOrder('$i')></button>";
					echo '</form>';

					//echo '<button onclick="confirmDelete('.$queryData[$i][0].')">Remove</button>';
					/*if($role==3)
					{
						echo '<br/><br/>';
						echo '<a href="PurchaseMain.html">Add New Order</a>';
					}*/
					if($role==2)
					{
						echo '<br/><br/>';
						echo '<a href="AcctApproveOrderEmail.php?Urgent='.$Urgent.' & comp='.$isComputer.' & oId='.$orderId.'">Approve Order</a>';
					}
					if($role==1)
					{
						echo '<br/><br/>';
						echo '<a href="TransferOrderEmail.php?Urgent='.$Urgent.' & comp='.$isComputer.' & oId='.$orderId.'">Approve Order</a>';
					}
						echo '</td>';
				}
			}
		}
		echo '
		<script type="text/javascript">
		function confirmDeleteOrder(row){
			var answer = confirm("Are you sure you want to remove this order?");
			if(answer){
				document.forms["removeOrder"+row].submit();
			}
			else{}
		}
		</script>';
	}
	echo '</tr>';

	echo '</table>';
	echo '</body>';
	echo '</html>';
}
?>
