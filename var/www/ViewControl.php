<?php
	$type = $_POST['typeQ'];
//	echo($type);
//	if(strcmp($query, "ViewUsers") == 0){
//	Header(Location: http://107.170.96.213/AwaitingApproval
//
	//exec("/var/www/testtest.php");
	if(strcmp($type, "ViewUsers") == 0){
	echo("ViewUsers");
	header("Location: http://107.170.96.213/ViewUsersRD.html");
	}
	if(strcmp($type, "AwaitingApproval") == 0){
	echo("AwaitingA");
	header("Location: http://107.170.96.213/AwaitingApprovalRD.html");
	}
	if(strcmp($type, "AllOrders") == 0){
	echo("AllOrders");
	header("Location: http://107.170.96.213/AllOrdersRD.html");
	}
	if(strcmp($type, "AcctAllApproved")==0){
	echo("Accountant All Approved");
	header("Location: http://107.170.96.213/AcctAllApprovedRD.html");
	}
	if(strcmp($type, "AwaitingDelivery")==0){
	echo("Awaiting Delivery");
	header("Location: http://107.170.96.213/AwaitingDeliveryRD.html");
	}
	if(strcmp($type, "AcctAllOrders")==0){
	echo("Accountant All Orders");
	header("Location: http://107.170.96.213/AcctAllOrdersRD.html");
	}
	if(strcmp($type, "AllApproved")==0){
        echo("All Approved");
        header("Location: http://107.170.96.213/AllApprovedRD.html");
        }
	if(strcmp($type, "AwaitingPurchase")==0){
        echo("Awaiting Purchase");
        header("Location: http://107.170.96.213/AwaitingPurchaseRD.html");
        }
	if(strcmp($type, "AcctAwaitingApproval")==0){
        echo("Accountant Awaiting Approval");
        header("Location: http://107.170.96.213/AcctAwaitingApprovalRD.html");
        }
	if(strcmp($type, "AllPurchased")==0){
        echo("All Purchased");
        header("Location: http://107.170.96.213/AllPurchasedRD.html");
        }
	if(strcmp($type, "FindOrderByEmail")==0){
        echo("Find Order By Email");
        header("Location: http://107.170.96.213/FindOrderByEmailRD.html");
        }
	if(strcmp($type, "OrdersByDateRange")==0){
        echo("Find Order By Date Range");
        header("Location: http://107.170.96.213/OrdersByDateRangeRD.html");
        }
	if(strcmp($type, "FindOrderById")==0){
        echo("Find Order By ID");
        header("Location: http://107.170.96.213/FindOrderByIdRD.html");
        }
	if(strcmp($type, "FindOrderByName")==0){
        echo("Find Order By ID");
        header("Location: http://107.170.96.213/FindOrderByNameRD.html");
        }
	if(strcmp($type, "RemoveOrder")==0){
        echo("Remove Order");
        header("Location: http://107.170.96.213/RemoveOrderRD.html");
        }
	if(strcmp($type, "EditOrder")==0){
        echo("Edit Order");
        header("Location: http://107.170.96.213/EditOrderRD.html");
        }
	if(strcmp($type, "FindOrderByPartName")==0){
        echo("Find Order by Part Name");
        header("Location: http://107.170.96.213/FindOrderByPartNameRD.html");
        }
	if(strcmp($type, "AcctOrdersByDateRange")==0){
        echo("Accountant Orders by Date Range");
        header("Location: http://107.170.96.213/AcctOrdersByDateRangeRD.html");
        }

	//header("Location: http://107.170.96.213/SelectQuery.php");
?>
