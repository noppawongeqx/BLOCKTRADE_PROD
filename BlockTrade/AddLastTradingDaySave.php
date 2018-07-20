<?php
if(isset($_POST['SName']) AND isset($_POST['LTD']) AND isset($_POST['multiplier'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$sql = "INSERT INTO serie (SName,Underlying,SLastTradingDay,SMultiplier) VALUES ('".$_POST['SName']."','".$_POST['underlying']."','".$_POST['LTD']."','".$_POST['multiplier']."')";
	
	if (mysqli_query($conn, $sql)) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	//$_SESSION['branch_id']=mysqli_insert_id($con);
	mysqli_close($conn);
}
if(isset($_POST['deletedata'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	$sql = "DELETE FROM serie WHERE SerieID ='".$_POST['deletedata']."'";
	
	if (mysqli_query($conn, $sql)) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . mysqli_error($conn);
	}
	
	mysqli_close($conn);
}
?>