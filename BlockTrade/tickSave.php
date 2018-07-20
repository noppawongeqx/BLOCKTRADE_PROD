<?php
if(isset($_POST['min']) AND isset($_POST['max']) AND isset($_POST['tick'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$sql = "INSERT INTO tick (Min,Max,Tick) VALUES ('".$_POST['min']."','".$_POST['max']."','".$_POST['tick']."')";
	
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
	$sql = "DELETE FROM tick WHERE TickID='".$_POST['deletedata']."'";
	
	if (mysqli_query($conn, $sql)) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . mysqli_error($conn);
	}
	
	mysqli_close($conn);
}
?>