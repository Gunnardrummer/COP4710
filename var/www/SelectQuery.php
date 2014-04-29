<?php
//must be included on the top of every page
session_start();

//gets the role from the database. 1- Purcahser 2-Accountants 3-Admin
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

//Displays drop down list for associated roles. Different roles get different querry options.  AccountSettings.html will have the change password form.

//ADD USERS BUTTON MUST BE ADDED FOR ADMIN, NOT YET IN HTML CODE.  THIS CAN BRING UP THE SAME PAGE AS THE EDIT BUTTON BUT MUST ALLOW FOR THE EDITTING OF THE USERNAME AND PASSWORD
if($role == 3){
echo '<!DOCTYPE html>
<html>
<head>
<title></title>
</head>
<body>
<br/><br/><br/>
  Please select a query to perfom:
  <br/><br/>
  <form method="post" action="ViewControl.php">
<select name="typeQ">
   <option value="AcctAllOrders">All Orders</option>
   <option value="ViewUsers">View Users</option>
   <option value="AllPurchased">All Purchased</option>
   <option value="AcctAllApproved">View All Approved</option>
   <option value="AcctOrdersByDateRange">Orders By Date Range</option>
   <option value="AcctAwaitingApproval">Awaiting Approval</option>
   <option value="FindOrderByEmail">Find Order By Email</option>
   <option value="AwaitingDelivery">Awaiting Delivery</option>
   <option value="FindOrderById">Find Order By Id</option>
   <option value="AwaitingPurchase">Awaiting Purchase</option>
   <option value="FindOrderByName">Find Order By Partial Name</option>
   <option value="FindOrderByPartName">Find Order By Part Name</option>
 </select> 
    <br/><br/>
    <input type="submit" name="submit"/>
 </form>
</body>
</html>';
}

else if($role == 2){
echo '<!DOCTYPE html>
<html>
<head>
<title></title>
</head>
<body>
<br/><br/><br/>
  Please select a query to perfom:
  <br/><br/>
  <form method="post" name="typeQ" action="ViewControl.php">
<select name="typeQ">
   <option value="AcctAllOrders">All Orders</option>
   <option value="AllPurchased">All Purchased</option>
   <option value="AcctAllApproved">All Approved</option>
   <option value="AcctAwaitingApproval">Awaiting Approval</option>
   <option value="AcctOrdersByDateRange">Orders By Date Range</option>
   <option value="FindOrderByEmail">Find Order By Email</option>
   <option value="AwaitingDelivery">Awaiting Delivery</option>
   <option value="FindOrderById">Find Order By Id</option>
   <option value="AwaitingPurchase">Awaiting Purchase</option>
   <option value="FindOrderByName">Find Order By Partial Name</option>
   <option value="FindOrderByPartName">Find Order By Part Name</option>
 </select> 
    <br/><br/>
    <input type="submit" name="submit"/>
 </form>
</body>
</html>';
}
if($role == 1)
{
echo '<!DOCTYPE html>
<html>
<head>
<title></title>
</head>
<body>
<br/><br/><br/>
  Please select a query to perfom:
  <br/><br/>
  <form method="post" name="typeQ" action="ViewControl.php">
<select name="typeQ">
   <option value="AllOrders">All Orders</option>
   <option value="AllPurchased">All Purchased</option>
   <option value="AwaitingApproval">Awaiting Approval</option>
   <option value="FindOrderByEmail">Find Order By Email</option>
   <option value="OrdersByDateRange">Orders By Date Range</option>
   <option value="AwaitingDelivery">Awaiting Delivery</option>
   <option value="FindOrderById">Find Order By Id</option>
   <option value="AllApproved">All Approved</option>
   <option value="AwaitingPurchase">Awaiting Purchase</option>
   <option value="FindOrderByName">Find Order By Partial Name</option>
   <option value="FindOrderByPartName">Find Order By Part Name</option>
 </select> 
    <br/><br/>
    <input type="submit" name="submit"/>
 </form>
</body>
</html>';
}
?>
