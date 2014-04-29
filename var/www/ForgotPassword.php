<?php
	//must be included on the top of each page
	session_start();
	$output = null;

	//get email
	$email = $_POST["email"];
	
	//check if email is entered
	if ($email)
	{
		$CMD = sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar GetRoleByEmail 0 1 string %s 2>&1", $email);
		exec($CMD, $output);
		var_dump($output);
		if($output != null && strcmp($output[0], "admin") != 0){
			
			$output = null;
			
			//generate a new random password
			$newPW = rand();
			
			//md5 encripts the random number password which converts it into a string
			$newPW = md5($newPW);
			
			//Shorten the given string
			$newPW = substr($newPW, 0, 10);
							
			//update the database with the new password
			$CMD = sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar ForgotPassword 0 1 string %s string %s", $email, $newPW);
			exec($CMD, $output);
			
			//output[0] should equal 1 if the password was successfully changed
			if($output != null && $output[0] == 1)
			{
				//email user new password
				//Who the email is coming from
				$webmaster = "EECS_OrderForm@knights.ucf.edu";
				
				//sets the headers and the body of the email
				$headers = "From: $webmaster";
				$subject = "New admin password for EECS order forms";
				//WE NEED A CONTACT NAME AND NUMBER
				$message = "Your new password is: $newPW. \nIf you didnt request this change, please contact ___________";
				
				//sends the email
				if( mail($email, $subject, $message, $headers))
				{
					echo "Your password was reset.  Please check your email to find your new password";
				}
				else
					echo "An error occured and the email was not sent. Please try again";
			}
			else{
				//the password was not reset
				echo "An error occured. Your password was not reset";
			}
		}
	}
	//alert user if email not entered
	else
		echo "Please enter your email";
		
?>
