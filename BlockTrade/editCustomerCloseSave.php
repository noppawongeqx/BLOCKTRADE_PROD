<?php
	$position = $_POST['position'];
	$tranDate = $_POST['tranDate'];
	$account = $_POST['account'];
	$underlying = $_POST['underlying'];
	$spot = $_POST['spot'];
	$futureName = $_POST['futureName'];
	$futurePrice = $_POST['futurePrice'];
	$volume = $_POST['vol'];
	$value = $_POST['value1'];
	$net = $_POST['net'];
	$flag = $_POST['flag'];
	$total = $_POST['total'];
	$serie = $_POST['serie'];
	$CTCID = $_POST['CTCID'];
	$serie = substr($serie,0,4);
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$sql = "UPDATE customertransactionclose SET CTCTranDate = '$tranDate',CTCPosition = '$position',CTCUnderlying = '$underlying',CTCFutureName = '$futureName',CTCVolume = '$volume',CTCSpot = '$spot',CTCValue = '$value',CTCNetInterest = '$net',CTCTotalInterest = '$total',CTCForceCloseFlag = '$flag',CTCFuturePrice = '$futurePrice',SerieID = (SELECT SerieID FROM serie WHERE SName = '$serie'),CustomerID = (SELECT CustomerID FROM customer WHERE Account = '$account') WHERE CTCID = '".$CTCID."'";
	
	if (mysqli_query($conn, $sql)) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . mysqli_error($conn);
	}

	mysqli_close($conn);
?>