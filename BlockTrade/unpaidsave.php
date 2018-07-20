<?php
if(isset($_POST['account']) AND isset($_POST['trandate']) AND isset($_POST['amount']) AND isset($_POST['reason'])){
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
	
	$sql = "INSERT INTO unpaid (UNTranDate,UNStartValue,UNCurrentValue,UNEvent,CustomerID) SELECT '".$_POST['trandate']."','".$_POST['amount']."','".$_POST['amount']."','".$_POST['reason']."',C.CustomerID FROM customer AS C WHERE C.Account = '".$_POST['account']."'";
	
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
	$sql = "DELETE FROM unpaid WHERE UNID ='".$_POST['deletedata']."'";
	
	if (mysqli_query($conn, $sql)) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . mysqli_error($conn);
	}
	
	mysqli_close($conn);
}
if(isset($_POST['account'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	$sql = "SELECT SUM(UNCurrentValue) AS money FROM unpaid AS UN INNER JOIN customer AS C ON UN.CustomerID = C.CustomerID WHERE C.Account = '".$_POST['account']."'";
	$result = mysqli_query($conn, $sql);
		
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			echo $row['money'];
		}
	}
	mysqli_close($conn);
}
?>