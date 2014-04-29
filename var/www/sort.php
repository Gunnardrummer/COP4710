<?php
	session_start();
	
	//$queryData = array(array());
	$queryData = $_SESSION["queryData"]; //The 2d array of query data saved to the session
	$col = $_GET["col"]; //The column being sorted
	//$col = 0;
	//$sortOrder = array();
	$sortOrder = $_SESSION["sortOrder"]; //Is this column already sorted?
	$numRows = count($queryData); //The number of rows for the query data array
	$numCols = count($queryData[0]);
	$newArray = array(array());
	$stringVal = null; //for comparing strings
	$rowsUsed = array(); //Tracks which rows of the query data have been inserted into the new array
	$rowVal = 0; //The current row that stringVal is in
	$k = 0; //Used to index the rowsUsed array
	
	/*for($i = 0; $i<4; $i++){
		for($j=0; $j<3; $j++){
			$queryData[$i][$j] = "crap";
	}}
	
	for($i=0; $i<4; $i++)
		$sortOrder[$i] = 0;*/
	
	//$queryData[5][0] = "morecrap";
	//$queryData[5][1] = "morecrap";
	//$queryData[5][2] = "morecrap";
	//$queryData[5][3] = "morecrap";
	
	//$numRows = count($queryData); //The number of rows for the query data array
	//$numCols = count($queryData[0]);
	
	for($i=0; $i<$numRows; $i++)
		$rowsUsed[$i] = -1;
	
	For($i=0; $i<$numRows; $i++){
		$rowVal = -1;
		$stringVal = null;
		
		//Traverse through the column being sorted and find either the smallest or largest unsorted value depending on
		//the current sorting order
		For($j=0; $j<$numRows; $j++){
			
			while($stringVal == null){
				if($stringVal == null && isUsed($rowsUsed, $numRows, $j) == 0){ //initialize the string
					$stringVal = $queryData[$j][$col];
					$rowVal = $j;	
				}	
				$j++;
			}
			
			if($j >= $numRows)
				break;
			
				
			//find the smallest value
			if($j != $rowVal && ($sortOrder[$col] == 0 || $sortOrder[$col] == 2) && isUsed($rowsUsed, $numRows, $j) == 0 && strcmp($queryData[$j][$col], $stringVal) < 0){
				$stringVal = $queryData[$j][$col];
				$rowVal = $j;
			}
			
			//find the largest value
			else if($j != $rowVal && $sortOrder[$col] == 1 && isUsed($rowsUsed, $numRows, $j) == 0 && strcmp($queryData[$j][$col], $stringVal) > 0){
				$stringVal = $queryData[$j][$col];
				$rowVal = $j;
			}
			/*else if($j != $rowVal){
				$stringVal = $queryData[$j][$col];
				$rowVal = $j;
			}*/
		}
		
		if($k < $numRows){
			//This row is now sorted
			$rowsUsed[$k] = $rowVal;
			
			//$arrayRow = $queryData[$rowVal];
		}
			//Add the entire row of the column to the new array
			for($j=0; $j<$numCols; $j++){
				$newArray[$i][$j] = $queryData[$rowVal][$j];
				//echo $newArray[$i][$j];
				//$queryData[$rowVal][$n];
			}
					
		$k++;
		
	}
	
	//Overwrite the query data array with the newly sorted one
	$_SESSION["queryData"] = $newArray;
	
	//$sortOrder = $_SESSION["sortOrder"];
	
	//Set all other columns to unsorted
	for($i=0; $i<$numCols; $i++){
		if($i != $col)
			$sortOrder[$i] = 0;
	}
	
	if($sortOrder[$col] == 1)
		$sortOrder[$col] = 2;
	else
		$sortOrder[$col] = 1;
	
	$_SESSION["sortOrder"] = $sortOrder;
	
	//include "http://107.170.96.213/FinalTableButtons.php";
	header("Location: http://107.170.96.213/FinalTableButtons.php");
	//exit;
	
	//checks if a row has already been added to the new array
	FUNCTION isUsed($rowsUsed, $numRows, $row){
		
		for($i=0; $i<$numRows; $i++){
			if($rowsUsed[$i] != -1 && $rowsUsed[$i] == $row)
				return 1;
		}
		return 0;
	}
?>