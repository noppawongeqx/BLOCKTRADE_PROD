<?php
if(isset($_POST['insertdata'])){
	$RDATE = $_POST['RDATE'];
	$Acctotal = $_POST['Acctotal'];
	$AccDTD = $_POST['AccDTD'];
	$AccMTD = $_POST['AccMTD'];
	$AccYTD = $_POST['AccYTD'];
	$Fairtotal = $_POST['Fairtotal'];
	$FairDTD = $_POST['FairDTD'];
	$FairMTD = $_POST['FairMTD'];
	$FairYTD = $_POST['FairYTD'];
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$sql = "INSERT INTO revenue (RDate,TotalAccount,DTDAccount,MTDAccount,YTDAccount,TotalFair,DTDFair,MTDFair,YTDFair) VALUES ('$RDATE','$Acctotal','$AccDTD','$AccMTD','$AccYTD','$Fairtotal','$FairDTD','$FairMTD','$FairYTD')";
	
	if (mysqli_query($conn, $sql)) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	//$_SESSION['branch_id']=mysqli_insert_id($con);
	mysqli_close($conn);
}
elseif(isset($_POST['updatedata'])){
	$Acctotal = $_POST['Acctotal'];
	$AccDTD = $_POST['AccDTD'];
	$AccMTD = $_POST['AccMTD'];
	$AccYTD = $_POST['AccYTD'];
	$Fairtotal = $_POST['Fairtotal'];
	$FairDTD = $_POST['FairDTD'];
	$FairMTD = $_POST['FairMTD'];
	$FairYTD = $_POST['FairYTD'];
	$RID = $_POST['RID'];
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
	
	$sql = "UPDATE revenue SET TotalAccount = '$Acctotal',DTDAccount = '$AccDTD',MTDAccount = '$AccMTD',YTDAccount = '$AccYTD',TotalFair = '$Fairtotal',DTDFair = '$FairDTD',MTDFair = '$FairMTD',YTDFair = '$FairYTD' WHERE RID = '$RID'";
	
	if (mysqli_query($conn, $sql)) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . mysqli_error($conn);
	}
	mysqli_close($conn);
}
?>