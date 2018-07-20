<?php
if(isset($_POST['MKTID']) AND isset($_POST['MKTName'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	mysqli_set_charset($conn,"utf8");
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	$sql = "UPDATE customer SET MKTID = '".$_POST['MKTID']."',MKTName = '".$_POST['MKTName']."',TEAM = '".$_POST['TEAM']."' WHERE CustomerID = '".$_POST['CustomerID']."'";
		
	if (mysqli_query($conn, $sql)) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . mysqli_error($conn);
	}
	mysqli_close($conn);
}
?>