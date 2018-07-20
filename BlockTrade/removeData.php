<?php
if(isset($_POST['CTOID'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	$sql = "SELECT CTO.CTOTranDate,CTO.CTOPosition,CTO.CTOUnderlying,CTO.CTOFutureName,CTO.CTOVolumeStart,CTO.CTOSpot,CTO.CTOValue,CTO.SerieID,CTO.CustomerID,CTO.CTOFuturePrice,S.SMultiplier FROM customertransactionopen AS CTO INNER JOIN serie AS S ON CTO.SerieID = S.SerieID WHERE CTOID = '".$_POST['CTOID']."'";
	$result = mysqli_query($con,$sql);
	
	
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_array($result)) {
			$TranDate = $row['CTOTranDate'];
			$CTOPosition = $row['CTOPosition'];
			$CTOUnderlying = $row['CTOUnderlying'];
			$CTOFutureName = $row['CTOFutureName'];
			$CTOVolumeStart = $row['CTOVolumeStart'];
			$CTOVolumeStartFuture = $row['CTOVolumeStart'];
			$CTOSpot = $row['CTOSpot'];
			$CTOValue = $row['CTOValue'];
			$SerieID = $row['SerieID'];
			$CustomerID = $row['CustomerID'];
			$Multiplier = $row['SMultiplier'];
			$FuturePrice = $row['CTOFuturePrice'];
		}
	}
	if($CTOPosition == "Long"){
		$FuturePosition = "Short";
		$CTOVolumeStartFuture = $CTOVolumeStartFuture*-1;
	}
	else{
		$FuturePosition = "Long";
		$CTOVolumeStart = $CTOVolumeStart*-1;
	}
	
	$StockVolume = $Multiplier*$CTOVolumeStart;
	
	$sql = "DELETE FROM `companytransaction` WHERE COTDate = '$TranDate' AND COTUnderlying = '$CTOUnderlying' AND COTPosition = '$CTOPosition' AND COTVolume = '$StockVolume' AND COTCost = '$CTOSpot' AND SerieID = '28' AND CustomerID = '$CustomerID'";
	if (mysqli_query($con, $sql)) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . mysqli_error($con);
	}
		
	$sql = "DELETE FROM `companytransaction` WHERE COTDate = '$TranDate' AND COTUnderlying = '$CTOFutureName' AND COTPosition = '$FuturePosition' AND COTVolume = '$CTOVolumeStartFuture' AND COTCost = '$FuturePrice' AND SerieID = '$SerieID' AND CustomerID = '$CustomerID'";
	if (mysqli_query($con, $sql)) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . mysqli_error($con);
	}
	
	$sql = "DELETE FROM `customertransactionopen` WHERE CTOID = '".$_POST['CTOID']."'";
	if (mysqli_query($con, $sql)) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . mysqli_error($con);
	}
	
	mysqli_close($con);
}
elseif(isset($_POST['CTCID'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	
	$sql = "SELECT CTC.CTCTranDate,CTC.CTCPosition,CTC.CTCUnderlying,CTC.CTCFutureName,CTC.CTCVolume,CTC.CTCSpot,CTC.CTCValue,CTC.SerieID,CTC.CustomerID,CTC.CTCFuturePrice,S.SMultiplier,CTC.CTOID,CTC.CTCDividend,CTC.CTCUnpaid FROM customertransactionclose AS CTC INNER JOIN serie AS S ON CTC.SerieID = S.SerieID WHERE CTCID = '".$_POST['CTCID']."'";
	$result = mysqli_query($con,$sql);
	
	
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_array($result)) {
			$TranDate = $row['CTCTranDate'];
			$CTCPosition = $row['CTCPosition'];
			$CTCUnderlying = $row['CTCUnderlying'];
			$CTCFutureName = $row['CTCFutureName'];
			$CTCVolumeStart = $row['CTCVolume'];
			$CTCVolumeStartFuture = $row['CTCVolume'];
			$Volume = $row['CTCVolume'];
			$CTCSpot = $row['CTCSpot'];
			$CTCValue = $row['CTCValue'];
			$SerieID = $row['SerieID'];
			$CustomerID = $row['CustomerID'];
			$Multiplier = $row['SMultiplier'];
			$FuturePrice = $row['CTCFuturePrice'];
			$CTOID = $row['CTOID'];
			$Dividend = $row['CTCDividend'];
			$Unpaid = $row['CTCUnpaid'];
		}
	}
	
	if($CTCPosition == "Long"){
		$FuturePosition = "Short";
		$CTCVolumeStartFuture = $CTCVolumeStartFuture*-1;
	}
	else{
		$FuturePosition = "Long";
		$CTCVolumeStart = $CTCVolumeStart*-1;
	}
	
	$StockVolume = $Multiplier*$CTCVolumeStart;
	
	$sql = "SELECT CompanyTransactionID FROM `companytransaction` WHERE COTDate = '$TranDate' AND COTUnderlying = '$CTCUnderlying' AND COTPosition = '$CTCPosition' AND COTVolume = '$StockVolume' AND COTCost = '$CTCSpot' AND SerieID = '28' AND CustomerID = '$CustomerID' LIMIT 1";
	$result = mysqli_query($con,$sql);
	
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_array($result)) {
			$TransactionID = $row['CompanyTransactionID'];
		}
	}
	
	$sql = "DELETE FROM `companytransaction` WHERE CompanyTransactionID = '$TransactionID'";
	if (mysqli_query($con, $sql)) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . mysqli_error($con);
	}
	
	$sql = "SELECT CompanyTransactionID FROM `companytransaction` WHERE COTDate = '$TranDate' AND COTUnderlying = '$CTCFutureName' AND COTPosition = '$FuturePosition' AND COTVolume = '$CTCVolumeStartFuture' AND COTCost = '$FuturePrice' AND SerieID = '$SerieID' AND CustomerID = '$CustomerID' LIMIT 1";
	$result = mysqli_query($con,$sql);
	
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_array($result)) {
			$TransactionID = $row['CompanyTransactionID'];
		}
	}
	
	$sql = "DELETE FROM `companytransaction` WHERE CompanyTransactionID = '$TransactionID'";
	if (mysqli_query($con, $sql)) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . mysqli_error($con);
	}
	
	if($Dividend > 0){
		$sql = "UPDATE dividend SET DCurrentVolume = (DCurrentVolume+($Volume*$Multiplier)),CTCID = '0' WHERE CTCID = '".$_POST['CTCID']."'";
		
		if (mysqli_query($con, $sql)) {
			echo "Record updated successfully";
		} else {
			echo "Error updating record: " . mysqli_error($conn);
		}
	}
	if($Unpaid > 0){
		$sql = "UPDATE unpaid SET UNCurrentValue = UNStartValue,CTCID = '0' WHERE CTCID = '".$_POST['CTCID']."'";
		
		if (mysqli_query($con, $sql)) {
			echo "Record updated successfully";
		} else {
			echo "Error updating record: " . mysqli_error($conn);
		}
	}
	
	$sql = "DELETE FROM `customertransactionclose` WHERE CTCID = '".$_POST['CTCID']."'";
	if (mysqli_query($con, $sql)) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . mysqli_error($con);
	}
	
	$sql = "UPDATE customertransactionopen SET CTOVolumeCurrent = (CTOVolumeCurrent+$Volume),CTOValue=((CTOVolumeCurrent)*CTOFuturePrice*$Multiplier) WHERE CTOID = '".$CTOID."'";
	
	if (mysqli_query($con, $sql)) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . mysqli_error($conn);
	}
	
	mysqli_close($con);
}
elseif(isset($_POST['COMID'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";

	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	$sql = "DELETE FROM `companytransaction` WHERE `CompanyTransactionID` = '".$_POST['COMID']."'";
	if (mysqli_query($con, $sql)) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . mysqli_error($con);
	}

	mysqli_close($con);
}
?>