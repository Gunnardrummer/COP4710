<?php
	session_start();

	$roleValue = $_SESSION["role"];
	$role = strval($roleValue);
	$role = 2;
	$newPassword = $_POST["password"];
	$verifyPassword = $_POST["vPassword"];
//	$verifyPassword = "12345";
//	$newPassword = "12345";
	//echo $verifyPassword;
	//echo $newPassword;
	$userName = $_SESSION["userName"];
//	$userName = "user";
	$oldAdminPass = null;
	$output = null;
	
	if($verifyPassword == $newPassword)
	{
		//If the admin is changing passwords, store old password in order to re-encrypt database
		if($role == 3){
			$oldAdminPass = "adminPass";
		}

		$CMD = sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar ChangePassword 0 %s string %s string %s 2>&1", $role, $userName, $newPassword);
		exec($CMD, $output);
		//var_dump($output);
		//output[0] should equal 1 if the password was successfully changed
		if($output != null && $output[0] == "1")
			echo "Your password was successfully changed.";
					
		//the password was not reset
		else
			echo "An error occured. Your password was not changed.";
		
		//If admin is changing passwords, the database must be re-encrypted with the new password
		if($oldAdminPass != null && $output != null && $output[0] == "1"){
		
			$CMD = sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar ReEncrypt 2 %s string %s string %s 2>&1", $role, $oldAdminPass, $newPassword);
			exec($CMD, $output);
			//var_dump($output);
			$_SESSION["adminPassword"] = $newPassword;
			//echo $_SESSION["adminPassword"];
		}
		
		header("Location: http://107.170.96.213/SelectQuery.php");
	}
	else{
		echo '<!DOCTYPE html>
		<html>
		<head>Passwords did not match.
		</head>
		<br><br>
		<body>
		<a href="http://107.170.96.213/AccountSettings.html">Please try again.</a>
		</body>
		</html>';
	}
		
?>
