<?php
	if(isset($_POST['tranDate'])){
		$tranDate = $_POST['tranDate'];
		$position = $_POST['position'];
		$underlying = $_POST['underlying'];
		$volume = $_POST['volume'];
		$spot = $_POST['spot'];
		$value = $_POST['value'];
		$upfront = $_POST['upfront'];
		$totalinterest = $_POST['totalint'];
		$mindate = $_POST['mindate'];
		$minbaht = $_POST['minbaht'];
		$acccount = $_POST['acccount'];
		$serie = $_POST['serie'];
		$percentInterest = $_POST['percentInterest'];
		$multiplier = $_POST['multiplier'];
		$percentUpfront = $_POST['percentUpfront'];
		$futurePrice = $_POST['futurePrice'];
		$discount = $_POST['discount'];
		$serieName = substr($serie,0,4);
		//connect to database
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "blocktrade";
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
		//Check Account
		$sql = "SELECT COUNT(*) AS TOTAL FROM `customer` WHERE Account = '$acccount'";
		$result = mysqli_query($conn,$sql);
		if(mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_array($result)) {
				$check_select = $row['TOTAL'];
			}
		}
		//Insert AccountID to Database if not exist
		if($check_select == 0){
			$sql = "INSERT INTO customer (Account) VALUES ('$acccount')";
			if (mysqli_query($conn, $sql)) {
				echo "Account record stored successfully<br>";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}
		if($multiplier == 1000){
			//Insert Customer's Transaction
			$sql = "INSERT INTO customertransactionopen (CTOTranDate,CTOPosition,CTOUnderlying,CTOFutureName,CTOVolumeStart,CTOVolumeCurrent,CTOSpot,CTOValue,CTOUpfrontInterest,CTODiscount,CTOTotalInterest,CTOMinimumDay,CTOMinimumInt,CTOPercentInterest,CTOPercentUpfront,CTOFuturePrice,SerieID,CustomerID) SELECT '$tranDate','$position','$underlying','".$underlying.$serieName."','$volume','$volume','$spot','$value','$upfront','$discount','$totalinterest','$mindate','$minbaht','$percentInterest','$percentUpfront','$futurePrice',S.SerieID,C.CustomerID FROM serie AS S,customer AS C WHERE S.SName = '$serieName' AND C.Account = '$acccount'";
			
			if (mysqli_query($conn, $sql)) {
				echo "Transaction record stored successfully<br>";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
			
			//Insert Company's Transaction
			//Underlying
			if ($position == "Long"){
				$cash = $value;
				$companyFuture = "Short";
				$spotvalue = $spot * $volume * $multiplier;
				$spotcash = $spotvalue*-1;
				$underlyingVolume = $volume * $multiplier;
				$volume = -1 * $volume;
			}
			elseif ($position == "Short"){
				$cash = -1*$value;
				$companyFuture = "Long";
				$spotvalue = $spot * $volume * $multiplier;
				$spotcash = $spotvalue;
				$underlyingVolume = -1 * $volume * $multiplier;
			}
			$futurename = $underlying.$serieName;
			//underlying
			$sql = "INSERT INTO companytransaction (COTDate,COTUnderlying,COTPosition,COTVolume,COTCost,COTValue,COTCash,SerieID,CustomerID) SELECT '$tranDate','$underlying','$position','$underlyingVolume','$spot','$spotvalue', '$spotcash' , S.SerieID,C.CustomerID FROM serie AS S,customer AS C WHERE S.SName = 'UI' AND C.Account = '$acccount'";
			
			if (mysqli_query($conn, $sql)) {
				echo "Company record stored successfully<br>";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
			//Future
			$sql = "INSERT INTO companytransaction (COTDate,COTUnderlying,COTPosition,COTVolume,COTCost,COTValue,COTCash,SerieID,CustomerID) SELECT '$tranDate','$futurename','$companyFuture','$volume','$futurePrice','$value', '$cash' , S.SerieID,C.CustomerID FROM serie AS S,customer AS C WHERE S.SName = '$serieName' AND C.Account = '$acccount'";
			
			if (mysqli_query($conn, $sql)) {
				echo "Company Hedging record stored successfully";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}
		elseif($multiplier != 1000){
			//Get SeriesID from serie
			$serieID = 0;
			$sql = "SELECT SerieID FROM `serie` WHERE SName = '$serieName' AND Underlying = '$underlying'";
	//		$sql = "SELECT SerieID FROM `serie` WHERE SName = '$serieName' ";
			if (strpos($serieName, '100') !== false) {
				$sql = "SELECT SerieID FROM `serie` WHERE SName = '$serieName' ";
			}
			$result = mysqli_query($conn,$sql);
			if(mysqli_num_rows($result)>0){
				while($row = mysqli_fetch_array($result)) {
					$serieID = $row['SerieID'];
				}
			}
			
			//Insert Customer's Transaction
			$sql = "INSERT INTO customertransactionopen (CTOTranDate,CTOPosition,CTOUnderlying,CTOFutureName,CTOVolumeStart,CTOVolumeCurrent,CTOSpot,CTOValue,CTOUpfrontInterest,CTODiscount,CTOTotalInterest,CTOMinimumDay,CTOMinimumInt,CTOPercentInterest,CTOPercentUpfront,CTOFuturePrice,SerieID,CustomerID) SELECT '$tranDate','$position','$underlying','".$underlying.$serieName."','$volume','$volume','$spot','$value','$upfront','$discount','$totalinterest','$mindate','$minbaht','$percentInterest','$percentUpfront','$futurePrice','$serieID',C.CustomerID FROM customer AS C WHERE C.Account = '$acccount'";
				
			if (mysqli_query($conn, $sql)) {
				echo "Transaction record stored successfully<br>";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
				
			//Insert Company's Transaction
			//Underlying
			if ($position == "Long"){
				$cash = $value;
				$companyFuture = "Short";
				$spotvalue = $spot * $volume * $multiplier;
				$spotcash = $spotvalue*-1;
				$underlyingVolume = $volume * $multiplier;
				$volume = -1 * $volume;
			}
			elseif ($position == "Short"){
				$cash = -1*$value;
				$companyFuture = "Long";
				$spotvalue = $spot * $volume * $multiplier;
				$spotcash = $spotvalue;
				$underlyingVolume = -1 * $volume * $multiplier;
			}
			$futurename = $underlying.$serieName;
			//underlying
			$sql = "INSERT INTO companytransaction (COTDate,COTUnderlying,COTPosition,COTVolume,COTCost,COTValue,COTCash,SerieID,CustomerID) SELECT '$tranDate','$underlying','$position','$underlyingVolume','$spot','$spotvalue', '$spotcash' , S.SerieID,C.CustomerID FROM serie AS S,customer AS C WHERE S.SName = 'UI' AND C.Account = '$acccount'";
				
			if (mysqli_query($conn, $sql)) {
				echo "Company record stored successfully<br>";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
			//Future
			$sql = "INSERT INTO companytransaction (COTDate,COTUnderlying,COTPosition,COTVolume,COTCost,COTValue,COTCash,SerieID,CustomerID) SELECT '$tranDate','$futurename','$companyFuture','$volume','$futurePrice','$value', '$cash' , '$serieID',C.CustomerID FROM customer AS C WHERE C.Account = '$acccount'";
				
			if (mysqli_query($conn, $sql)) {
				echo "Company Hedging record stored successfully";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}
		mysqli_close($conn);
	}
	
	// Chanage Multiplier
	elseif(isset($_POST['multi'])){
		$serie = $_POST['multi'];
		if(strpos($serie, " ") === false )
			$sql = "SELECT SMultiplier FROM `serie` WHERE SName = '$serie' AND Underlying = ''";
		else{
			$serie = substr($_POST['multi'],0,strpos($serie, " ")+1);
			$underlying = substr($_POST['multi'],strpos($serie, " ")+1,strlen($_POST['multi']));
			$sql = "SELECT SMultiplier FROM `serie` WHERE SName = '$serie' AND Underlying = '$underlying'";
		}
		//connect to database
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
		$result = mysqli_query($conn, $sql);
			
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				echo $row['SMultiplier'];
			}
		}
		mysqli_close($conn);
	}
?>