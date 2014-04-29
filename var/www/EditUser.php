<?php
session_start();

$UserToEdit = $_GET["row"];
$query = $_SESSION["queryData"];
$userName = $query[$userToEdit][1];
$FN = $query[$userToEdit][2];
$LN = $query[$userToEdit][3];
$email = $query[$userToEdit][4];
$role = $query[$userToEdit][5];

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

//style page for table
echo '<style>';
	echo 'table, th, td{border:2px solid black;}';
	echo 'table{background-color:black; align="center";}';
	echo 'th, td{padding:20px;}';	
	echo 'th{background-color:#F5DA81;}';
	echo 'td{background-color:#DBA901;}';
	echo 'body {background-color:#D8D8D8;}';
echo '</style>';
echo '<body bgcolor="#DBA901">';
echo '<form method="post" name="EditUser" action="PerformQuery.php">';
echo '<input type="hidden" name="query" value="EditUser"/>';
/*echo '<input type ="hidden" name="userName" value="$userName"/>';
echo '<input type ="hidden" name="firstName" value="$FN"/>';
echo '<input type ="hidden" name="lastName" value="$LN"/>';
echo '<input type ="hidden" name="email" value="$email"/>';
echo '<input type ="hidden" name="role" value="$role"/>';*/
echo'</br></br>';
echo '<table name="EditTable">';
echo '<tr>';

//table headings
echo '<th>UserID</th>';
echo '<th>UserName</th>';
echo '<th>FirstName</th>';
echo '<th>LastName</th>';
echo '<th>Email</th>';
echo '<th>Role</th>';
echo '</tr>';

echo '	<tr>';
//Editing UserID is not allowed, display only
echo ' 	<td>';
echo $query[$UserToEdit][0];
echo '</td>';

//Editing UserName is not allowed, display only
echo ' 	<td>';
echo $query[$UserToEdit][1];
echo '</td>';

echo '   <td>';
echo ' <input type="text" name="firstName" value="';
echo $query[$UserToEdit][2];
echo '">';
echo ' </input>';
echo '</td>';

echo ' 	<td>';
echo ' <input type="text" name="lastName" value="';
echo $query[$UserToEdit][3];
echo '">';
echo ' </input>';
echo '</td>';

echo '   <td>';
echo ' <input type="text" name="email" value="';
echo $query[$UserToEdit][4];
echo '">';
echo ' </input>';
echo '</td>';

echo '   <td>';

//Admins cannot change role
$role = $query[$UserToEdit][5];
if($role === "admin")
	echo $role;
else{
	echo '<input type="radio" name="role" value="purchaser" ';
	if($role === "purchaser")
		echo 'checked';
	echo '>Purchaser</input></br>';
	echo '<input type="radio" name="role" value="accountant" ';
	if($role === "accountant")
		echo 'checked';
	echo '>Accountant</input>';
}
echo '	</td></tr>';
echo '	</table>';

/*echo' <input type="hidden" name="userId" value';
echo $query[$UserToEdit][0];
echo '"/>';*/
echo' <input type="hidden" name="userName" value="';
echo $query[$UserToEdit][1];
echo '"/>';

//hidden variable contains the row that needs to be edited with the new information
/*echo' <input type="hidden" name="row" value"';
echo $UserToEdit;
echo' "/>';*/
echo ' 	<input type="submit" name="submit" value="Submit changes"/>';
echo '	</form>';

echo'</body>';

echo '</html>';

?>
