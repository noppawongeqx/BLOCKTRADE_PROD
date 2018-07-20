<?php
	$position = $_POST['position'];
	$tranDate = $_POST['tranDate'];
	$underlying = $_POST['underlying'];
	$futureName = $_POST['futureName'];
	$volume = $_POST['volume'];
	$spot = $_POST['spot'];
	$value = $_POST['value'];
	$totalInterest = $_POST['totalInterest'];
	$netInt = $_POST['netInterest'];
	$futurePrice = $_POST['futurePrice'];
	$flag = $_POST['flag'];
	$account ="";
	$account = $_POST['account'];
	$CTOID = $_POST['CTOID'];
	$serie = $_POST['serie'];
	$multiplier = $_POST['multiplier'];
	$serieName = substr($serie,0,4);
	$dividend = $_POST['dividend'];
	$unpaid = $_POST['unpaid'];
	
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
	$sql = "UPDATE customertransactionopen SET CTOVolumeCurrent = CTOVolumeCurrent-".$volume.",CTOValue=(CTOVolumeCurrent*CTOFuturePrice*$multiplier) WHERE CTOID = '".$CTOID."'";
	
	if (mysqli_query($conn, $sql)) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . mysqli_error($conn);
	}
	
	if($multiplier == 1000){	
	
		$sql = "INSERT INTO customertransactionclose (CTCTranDate,CTCPosition,CTCUnderlying,CTCFutureName,CTCVolume,CTCSpot,CTCTotalInterest,CTCNetInterest,CTCFuturePrice,CTCValue,CTCForceCloseFlag,CTCDividend,CTCUnpaid,CTOID,SerieID,CustomerID) SELECT '$tranDate','$position','$underlying','$futureName','$volume','$spot','$totalInterest','$netInt','$futurePrice','$value','$flag','$dividend','$unpaid','$CTOID',S.SerieID,C.CustomerID FROM serie AS S,customer AS C WHERE S.SName = '$serie' AND C.Account = '$account'";
		
		if (mysqli_query($conn, $sql)) {
			echo "Transaction record stored successfully<br>";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		$CTCID = mysqli_insert_id($conn);
		echo $position;
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
		$sql = "INSERT INTO companytransaction (COTDate,COTUnderlying,COTPosition,COTVolume,COTCost,COTValue,COTCash,SerieID,CustomerID) SELECT '$tranDate','$underlying','$position','$underlyingVolume','$spot','$spotvalue', '$spotcash' , S.SerieID,C.CustomerID FROM serie AS S,customer AS C WHERE S.SName = 'UI' AND C.Account = '$account'";
		
		if (mysqli_query($conn, $sql)) {
			echo "Company record stored successfully<br>";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		//Future
		$sql = "INSERT INTO companytransaction (COTDate,COTUnderlying,COTPosition,COTVolume,COTCost,COTValue,COTCash,SerieID,CustomerID) SELECT '$tranDate','$futurename','$companyFuture','$volume','$futurePrice','$value', '$cash' , S.SerieID,C.CustomerID FROM serie AS S,customer AS C WHERE S.SName = '$serieName' AND C.Account = '$account'";
		
		if (mysqli_query($conn, $sql)) {
			echo "Company Hedging record stored successfully";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
	elseif($multiplier != 1000){
		//Get serieID
		$serieID = 0;
		
		$sql = "SELECT SerieID FROM `serie` WHERE SName = '$serieName' AND Underlying = '$underlying'";
		if($serieName == "A100")
		{
			$sql = "SELECT SerieID FROM `serie` WHERE SName = '$serieName' ";
		}
//		$sql = "SELECT SerieID FROM `serie` WHERE SName = '$serieName' ";
		$result = mysqli_query($conn,$sql);
		if(mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_array($result)) {
				$serieID = $row['SerieID'];
			}
		}
		
		$sql = "INSERT INTO customertransactionclose (CTCTranDate,CTCPosition,CTCUnderlying,CTCFutureName,CTCVolume,CTCSpot,CTCTotalInterest,CTCNetInterest,CTCFuturePrice,CTCValue,CTCForceCloseFlag,CTCDividend,CTCUnpaid,CTOID,SerieID,CustomerID) SELECT '$tranDate','$position','$underlying','$futureName','$volume','$spot','$totalInterest','$netInt','$futurePrice','$value','$flag','$dividend','$unpaid','$CTOID','$serieID',C.CustomerID FROM customer AS C WHERE C.Account = '$account'";
		
		if (mysqli_query($conn, $sql)) {
			echo "Transaction record stored successfully<br>";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		$CTCID = mysqli_insert_id($conn);
		echo $position;
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
		$sql = "INSERT INTO companytransaction (COTDate,COTUnderlying,COTPosition,COTVolume,COTCost,COTValue,COTCash,SerieID,CustomerID) SELECT '$tranDate','$underlying','$position','$underlyingVolume','$spot','$spotvalue', '$spotcash' , S.SerieID,C.CustomerID FROM serie AS S,customer AS C WHERE S.SName = 'UI' AND C.Account = '$account'";
		
		if (mysqli_query($conn, $sql)) {
			echo "Company record stored successfully<br>";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
		//Future
		$sql = "INSERT INTO companytransaction (COTDate,COTUnderlying,COTPosition,COTVolume,COTCost,COTValue,COTCash,SerieID,CustomerID) SELECT '$tranDate','$futurename','$companyFuture','$volume','$futurePrice','$value', '$cash' , '$serieID',C.CustomerID FROM customer AS C WHERE C.Account = '$account'";
		
		if (mysqli_query($conn, $sql)) {
			echo "Company Hedging record stored successfully";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
	if($dividend != 0){
		$sql = "UPDATE dividend SET DCurrentVolume = DCurrentVolume - ($volume*$multiplier),CTCID = $CTCID WHERE DStock='$underlying'
		and XDDate <= '$tranDate' and   XDDate > (select CTOTranDate from customertransactionopen  WHERE CTOID = '".$CTOID."')";
			
		if (mysqli_query($conn, $sql)) {
			echo "Company record stored successfully<br>";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}

	if($unpaid > 0){
		$sql = "UPDATE unpaid AS UN INNER JOIN customer AS C ON UN.CustomerID = C.CustomerID SET UN.UNCurrentValue = '0',CTCID = $CTCID,UN.UNPaidDate = '$tranDate' WHERE C.Account = '$account'";
		if (mysqli_query($conn, $sql)) {
			echo "Record updated successfully";
		} else {
			echo "Error updating record: " . mysqli_error($conn);
		}
	}
	mysqli_close($conn);
?>