<?php
//create cookie
session_start();
//grab the user info and store it into a cookie
$user = $_POST["username"];
$pass = $_POST["password"];
//initlize variables
$role = null;
$roleVal = -1;
$output = null;

//create connection to the database
//this will need to be changed if hosted on a new server
$con = mysql_connect('localhost','root','newsqlpassword');

//error messages
if(!$con){
die('Could not connect');
}
//'mydb' is specific to our database will need to be changed if on a new server
if(!mysql_select_db('mydb')){
die('Could not select database');
}
//create the SQL function call and call it storing into $result
$CMD = sprintf("SELECT VerifyPassword('%s', '%s')", $user, $pass);
$result = mysql_query($CMD);

if(!$result){
die('Could not query');
}
//get the result value into $role
$role = mysql_result($result, 0);

if($role == null) 
echo "No result returned";

//compare the returned value to assign the role number in the cookie
if($role != null && strcmp($role, "-1") != 0){
if(strcmp($role, "admin") == 0)
$roleVal = 3;

else if(strcmp($role, "accountant") == 0)
$roleVal = 2;

else if(strcmp($role, "purchaser") == 0)
$roleVal = 1;

if($roleVal >=1 && $roleVal <=3){
//set up the cookie
$_SESSION["role"] = $roleVal;
$_SESSION["userName"] = $user;

//if account is an admin account
if($roleVal == 3) 
$_SESSION["adminPassword"] = $pass;
else
$_SESSION["adminPassword"] = null;

//Log in was success take them into the site
header("Location: http://107.170.96.213/SelectQuery.php");	
}
}
else{
//The user failed to login redirect to the main page.
session_destroy();
header("Location: http://107.170.96.213/index.html");
}	
?>
