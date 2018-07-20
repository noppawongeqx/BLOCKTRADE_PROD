<?php
if(isset($_POST['Stock']) AND isset($_POST['XDDate']) AND isset($_POST['dividend']) AND isset($_POST['volume']) AND isset($_POST['position']) AND isset($_POST['perout'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$sql = "INSERT INTO dividend (XDDate,DStock,DDividend,DPercentOut,DVolume,DCurrentVolume,DCompanyPosition) VALUES ('".$_POST['XDDate']."','".$_POST['Stock']."','".$_POST['dividend']."','".$_POST['perout']."','".$_POST['volume']."','".$_POST['volume']."','".$_POST['position']."')";
	
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
	$sql = "DELETE FROM dividend WHERE DID='".$_POST['deletedata']."'";
	
	if (mysqli_query($conn, $sql)) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . mysqli_error($conn);
	}
	
	mysqli_close($conn);
}
?>