<?php

	//Builds a query and executes it by calling a java program. All queries are performed via stored procedures in the mysql database.
	session_start();

	$query = $_POST["query"]; //get the query string
	$output = null; //might need to initialize this to an array. retrieves the output from the executed java program.
	$queryData = array(array()); //query data will be output to a 2d array
	$roleValue = $_SESSION["role"]; //get the user's role
	$role = strval($roleValue); //convert the integer role value to a string for passing to java program
	$adminpass = null;
	$numParameters = getNumParams($query); //the number of parameters this query has
	$reqRole = getReqRole($query); //The role required to perform this particular query
	
	$CMD = null; //stores a string used to execute the java program
	
	if($role == 3)
		$adminpass = $_SESSION["adminPassword"];
	
	if($numParameters == 0)
		$CMD = sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar %s %s %s", $query, $reqRole, $role);
	
	//If a query only has 1 parameter, the form field should be named "param" (This just makes my life easier)
	else if(strcmp($query, "ViewUsers") != 0 && $numParameters == 1)
		$CMD = sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar %s %s %s string %s", $query, $reqRole, $role, $_POST["param"]);
	
	//viewing users requires the admin pass for decryption
	else if(strcmp($query, "ViewUsers") == 0 && $role == 3){
		
		$CMD = sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar %s %s %s string %s", $query, $reqRole, $role, $adminpass);
		}
	else
		$CMD = buildQuery($query, $adminpass, $role, $reqRole); //build the query if there's more than 1 parameter to pass to the java program
	
	$sortOrder = array(); //keeps track of the sorting order of each column. This script initializes all elements to unsorted.
	
	//only execute if the query was succesfully built
	if($CMD != null){
		
		$r = -1;
		
		//Allows for repopulating the table after a removal. r is the row which was removed.
		if($query === "RemoveUser" || $query === "RemoveOrder")
			$r = strval($_POST["row"]);
	
		exec($CMD . ' 2>&1', $output);
		var_dump($output);
		
		if($output != null && !isUpdateQuery($query)){
		
			$i = 0;
			$colCount = 0;
			
			foreach($output as $element){
			
				$colCount++;
				$j=0;
				
				$innerArray = json_Decode($element, true);
				
				foreach($innerArray as $innerElement){
								
						$queryData[$i][$j] = $innerElement;
					
					$j++;
				}
				
				$i++;	
			}
			
			//set sort order for all columns as unsorted
			for($i = 0; $i<$colCount; $i++)
				$sortOrder[$i] = 0;
				
			$_SESSION["sortOrder"] = $sortOrder;
				
			$_SESSION["queryData"] = $queryData;
			
			header("Location: http://107.170.96.213/Query.php?query=$query");
		}
		
		//The query does not return a result set such as an update query.
		else if($output != null){
			
			if($output[0] > 0){
				
				$colCount = 0;
				
				if(count($qd) > 0)
					$colCount = count($qd[0]);
					
				for($i = 0; $i<$colCount; $i++)
					$sortOrder[$i] = 0;
				
				$_SESSION["sortOrder"] = $sortOrder;
				
				$qd = $_SESSION["queryData"];
				$newQueryData = rePopulate($r, $qd);
				$_SESSION["queryData"] = array(array());
				$_SESSION["queryData"] = $newQueryData;
				
				if($query === "RemoveUser" || $query === "RemoveOrder")
					header("Location: http://107.170.96.213/Query.php?query=$query");
				else
					header("Location: http://107.170.96.213/SelectQuery.php");
			}
			else
				echo "The update could not be performed";	
		}
		else
			echo "no output";		
	}
		
	else
		echo "Unknown Query or Permissions not met.";
		
	//This builds a query that passes more than 1 parameter to the java program
	FUNCTION buildQuery($query, $adminpass, $role, $reqRole){
	
		if(strcmp("OrdersByDateRange", $query) == 0 || strcmp("AcctOrdersByDateRange", $query) == 0){
			return sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar %s %s %s date %s date %s", $query, $reqRole, $role, $_POST["beginDate"], $_POST["endDate"]);
		}
			
		else if(strcmp("AddUser", $query) == 0 && $role == 3){
			return sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar %s %s %s string %s string %s string %s string %s string %s string %s string %s", $query, $reqRole, $role, $_POST["userName"], $_POST["password"],
			$_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["role"], $adminpass);
			//return sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar %s %s %s string imauser string 12345 string john string doe string em@em.com string purchaser string adminpass", $query, $reqRole, $role);
		}
		
		else if(strcmp("AddOrder", $query) == 0){
			//generate an order id using date and time down to microseconds. Should be impossible for 2 orders to have the same id.
			date_default_timezone_set('America/New_York');
			$temp1 = date('YmdHis');
			$temp2 = microtime(TRUE);
			$temp2 *= 1000;
			$temp2 = (int)$temp2;
			$temp2 = substr(strval($temp2), 10, 12);
			$orderId = $temp1.$temp2;
			$concatRole = $_POST["requesterFN"]."_".$_POST["requesterLN"];
			$urgentVal = "false";
			$compVal = "false";
			if(strcmp($_POST["urgent"], "true") == 0)
				$urgentVal = "true";
			else
				$urgentVal = "false";
				
			if(strcmp($_POST["compPurchase"], "true") == 0)
				$compVal = "true";
			else
				$compVal = "false";
			return sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar %s %s %s string %s string %s boolean %s boolean %s string %s string %s string linkToFile string %s string %s double %s string %s string %s", $query, $reqRole, $role, $orderId, $_POST["acctNum"],
			$urgentVal, $compVal, $_POST["vendor"], $_POST["description"], $concatRole, $_POST["email"],
			$_POST["amount"], $_POST["acctCode"], $_POST["poNum"]);
			
		}
			
		else if(strcmp("EditOrder", $query) == 0 && $reqRole == 0){
		
			$fName = $_POST["firstName"];
			$lName = $_POST["lastName"];
			$fullName = $fName."_".$lName;
			/*$purdate = $_POST["purchaseDate"]; $appdate = $_POST["approvalDate"]; $recdate = $_POST["recDate"];*/ $acctnum = $_POST["acctNum"];
			$urgent = $_POST["urgent"]; $comppur = $_POST["compPurchase"]; $vendor = $_POST["vendor"]; $descrip = $_POST["description"]; $prenotes = $_POST["preOrderNotes"];
			$attach = $_POST["attachment"]; $email = $_POST["email"]; $amt = $_POST["amount"]; $acode = $_POST["acctCode"]; $ponum = $_POST["poNum"]; $ponotes = $_POST["postOrderNotes"];
			
			$recYear = $_POST["recYear"]; $recMonth = $_POST["recMonth"]; $recDay = $_POST["recDay"];
			$purYear = $_POST["purYear"]; $purMonth = $_POST["purMonth"]; $purDay = $_POST["purDay"];
			$appYear = $_POST["appYear"]; $appMonth = $_POST["appMonth"]; $appDay = $_POST["appDay"];
			
			$fullRecDate = " ";
			$fullPurDate = " ";
			$fullAppDate = " ";
			
			if($recYear != null && $recMonth != null && $recDay != null && $recYear != " " && $recMonth != " " && $recDay != " ")
				$fullRecDate = $recYear."-".$recMonth."-".$recDay;
				
			if($purYear != null && $purMonth != null && $purDay != null && $purYear != " " && $purMonth != " " && $purDay != " ")
				$fullPurDate = $purYear."-".$purMonth."-".$purDay;
			
			if($appYear != null && $appMonth != null && $appDay != null && $appYear != " " && $appMonth != " " && $appDay != " ")
				$fullAppDate = $appYear."-".$appMonth."-".$appDay;
			
			if($fullRecDate == " ") $fullRecDate = "null";
			if($fullPurDate == " ") $fullPurDate = "null";
			if($fullAppDate == " ") $fullAppDate = "null";
			if($acctnum == " ") $acctnum = "null";
			if($urgent == " ") $urgent = "null";     
			if($comppur == " ") $comppur = "null";
			if($vendor == " ") $vendor = "null";     
			if($descrip == " ") $descrip = "null";
			if($prenotes == " ") $prenotes = "null"; 
			if($attach == " ") $attach = "null";
			if($email == " ") $email = "null";       
			if($amt == " ") $amt = "null";
			if($acode == " ") $acode = "null";       
			if($ponum == " ") $ponum = "null";
			if($ponotes == " ") $ponotes = "null";
			
			return sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar %s %s %s string %s date %s date %s date %s date %s string %s boolean %s boolean %s string %s string %s string %s string %s string %s string %s double %s string %s string %s string %s",
			$query, $reqRole, $role, $_POST["orderId"], $_POST["orderReqDate"], $fullPurDate, $fullAppDate, $fullRecDate, $acctnum, $urgent, $comppur,
			$vendor, $descrip, $prenotes, $attach, $fullName, $email, $amt, $acode, $ponum, $ponotes);
		}
		
		else if(strcmp("EditUser", $query) == 0 && $role == 3){
			return sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar %s %s %s string %s string %s string %s string %s string %s string %s", $query, $reqRole, $role, $_POST["userName"], $_POST["firstName"],
			$_POST["lastName"], $_POST["email"], $_POST["role"], $adminpass);
			return sprintf("/usr/bin/java -jar /var/www/PerformQuery.jar %s %s %s string faculty string bill string thomas string iahveanemail@email.com string accountant string %s", $query, $reqRole, $role, $adminpass);
		}		
		else
			return null;
	}
	
	//This returns the number of parameters the query must send to the java program
	FUNCTION getNumParams($query){
	
		if(strcmp("FindOrderByEmail", $query) == 0 || strcmp("FindOrderById", $query) == 0 || strcmp("FindOrderByName", $query) == 0 
		|| strcmp("FindOrderByPartName", $query) == 0 || strcmp("RemoveUser", $query) == 0 || strcmp("RemoveOrder", $query) == 0 || strcmp("ViewUsers", $query) == 0)
			return 1;

		else if(strcmp("OrdersByDateRange", $query) == 0 || strcmp("AcctOrdersByDateRange", $query) == 0)
			return 2;

		else if(strcmp("AddUser", $query) == 0)
			return 7;
		
		else if(strcmp("EditUser", $query) == 0)
			return 6;

		else if(strcmp("AddOrder", $query) == 0)
			return 11;
			
		else if(strcmp("EditOrder", $query) == 0)
			return 18;

		else
			return 0;
	}
	
	//This returns the required role value to perform the given query
	FUNCTION getReqRole($query){
		
		if(strcmp("AddOrder", $query) == 0)
			return -1;
			
		else if(strcmp("FindOrderByEmail", $query) == 0 || strcmp("FindOrderById", $query) == 0 || strcmp("FindOrderByName", $query) == 0 
		|| strcmp("FindOrderByPartName", $query) == 0 || strcmp("RemoveOrder", $query) == 0 || strcmp("OrdersByDateRange", $query) == 0
		|| strcmp("AcctOrdersByDateRange", $query) == 0 || strcmp("AllOrders", $query) == 0 || strcmp("AllApproved", $query) == 0
		|| strcmp("AllPurchased", $query) == 0 || strcmp("AwaitingApproval", $query) == 0 || strcmp("AwaitingPurchase", $query) == 0
		|| strcmp("AwaitingDelivery", $query) == 0 ||  strcmp("EditOrder", $query) == 0 ||  strcmp("GetRoleByEmail", $query) == 0
		||  strcmp("GetEmail", $query) == 0)
			return 0;
			
		else if(strcmp("AcctAllOrders", $query) == 0 || strcmp("AcctAllApproved", $query) == 0 || strcmp("AcctAwaitingApproval", $query) == 0
		|| strcmp("AcctAwaitingApproval", $query) == 0)
			return 1;
			
		else if(strcmp("AddUser", $query) == 0 || strcmp("RemoveUser", $query) == 0 || strcmp("EditUser", $query) == 0 || strcmp("ViewUsers", $query) == 0)
			return 2;
			
		else{
			echo "unknown query";
			return 4; //unknown query
		}
		
		header("Location: http://107.170.96.213/Query.php");
		exit;
	}
	
	//This checks if this query does not return a result set
	FUNCTION isUpdateQuery($query){
	
		if(strcmp("AddOrder", $query) == 0 ||  strcmp("EditOrder", $query) == 0 || strcmp("RemoveOrder", $query) == 0 || strcmp("AddUser", $query) == 0 
		|| strcmp("RemoveUser", $query) == 0 || strcmp("EditUser", $query) == 0){
			return true;
		}
		else
			return false;
	}
	
	//repopulates table after deleting a row. Not currently working for an edit.
	FUNCTION rePopulate($row, $qd){
	
		$newArray = array(array());
		$numRows = count($qd);
		$k = 0;
		
		if($row != -1){
			for($i=0; $i<$numRows; $i++){
					
				$numCols = count($qd[$i]);
				
				if($i == $row1){
					$k++;
					$numRows--;
				}
				
				for($j=0; $j<$numCols; $j++){	
					$newArray[$i][$j] = $qd[$k][$j];
				}
				$k++;
			}
		}
		return $newArray;
	}
?>
