<!DOCTYPE html>
<html>
<head>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<link href="../css/styles.css" rel="stylesheet">
</head>
<body>
<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if(!isset($_SESSION['loggedin'])){
	header('Location: login.php');
	exit();
}
//Result of All transaction of given account
if(isset($_POST['account']) AND isset($_POST['closedate'])){
	$account = $_POST['account'];
	$closedate = date_create($_POST['closedate']);
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	
	$queryresult = 0;
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
	    die('Could not connect: ' . mysqli_error($con));
	}
	
	$sql = "SELECT CTO.CTOID,CTO.CTOTranDate,CTO.CTOPosition,CTO.CTOUnderlying,CTO.CTOFutureName,CTO.CTOVolumeStart,CTO.CTOVolumeCurrent,CTO.CTOSpot,CTO.CTOValue,CTO.CTOUpfrontInterest,CTO.CTOMinimumDay,CTO.CTOMinimumInt,CTO.CTODiscount,CTO.CTOTotalInterest,CTO.CTOPercentInterest,CTO.CTOPercentUpfront,CTO.CTOFuturePrice,S.SName,S.SLastTradingDay,S.SMultiplier FROM customertransactionopen AS CTO INNER JOIN customer AS C ON C.CustomerID = CTO.CustomerID INNER JOIN serie AS S ON CTO.SerieID = S.SerieID WHERE C.Account = '$account' AND CTO.CTOVolumeCurrent > 0";
		
	$result = mysqli_query($con,$sql);
	echo "<div class='col-md-12'>";
	echo "<table class='table'>";
	echo "<caption>Current Position of ".$account."</caption>
		      <thead>
		        <tr>
		          <th>Position</th>
		          <th>Futures</th>
				  <th>Cost</th>
		          <th>Volume(Start)</th>
				  <th>Volume(Now)</th>
				  <th>Value</th>
		          <th>Initial Date</th>
		          <th>ดอกมัดจำ</th>
				  <th>ดอกทั้งหมด</th>
				  <th>Action</th>
		        </tr>
		      </thead>
		      <tbody>";
	$number = 1;
	if(mysqli_num_rows($result)>0){
		$queryresult = mysqli_num_rows($result);
		while($row = mysqli_fetch_array($result)) {
				echo "<tr>";
				if ($row['CTOPosition'] == "Long")
					echo "<td><button type='button' class='btn btn-success' disabled='disabled' id='position".$number."' value='".$row['CTOPosition']."'>Long</button></td>";
				elseif ($row['CTOPosition'] == "Short")
				echo "<td><button type='button' class='btn btn-danger' disabled='disabled' id='position".$number."' value='".$row['CTOPosition']."'>Short</button></td>";
				echo "<td>".$row['CTOFutureName']."</td>";
				echo "<td>".$row['CTOFuturePrice']."</td>";
				echo "<td>".$row['CTOVolumeStart']."</td>";
				echo "<td>".$row['CTOVolumeCurrent']."</td>";
				echo "<td>".number_format($row['CTOValue'],2,'.',',')."</td>";
				$Newformat = strtotime($row['CTOTranDate']);
				$myFormatForView = date("d/m/Y", $Newformat);
				echo "<td>".$myFormatForView."</td>";
				echo "<td>".$row['CTOUpfrontInterest']."</td>";
				echo "<td>".$row['CTOTotalInterest']."</td>";
				$hodingday = (date_diff($closedate,date_create($row['CTOTranDate']))->format("%a"));
				$hodinginterest = number_format($row['CTOSpot'] * $row['CTOPercentInterest'] / 100 * $hodingday / 365,5,'.',',');
				echo "<td><a class='btn btn-primary' href='saveClose.php?acc=".$account."&id=".$row['CTOID']."&p=".$row['CTOPosition']."&ui=".$row['CTOUnderlying']."&mindate=".$row['CTOMinimumDay']."&minint=".$row['CTOMinimumInt']."&s=".$row['SName']."&vol=".$row['CTOVolumeStart']."&volCur=".$row['CTOVolumeCurrent']."&cost=".$row['CTOFuturePrice']."&idate=".$myFormatForView."&up=".$row['CTOUpfrontInterest']."&discount=".$row['CTODiscount']."&holday=".$hodingday."&spot=".$row['CTOSpot']."&interest=".$row['CTOPercentInterest']."&multi=".$row['SMultiplier']."&value=".number_format($row['CTOValue'],2,'.',',')."' role='button' target='_blank'>Action</a></td>";
				echo "</tr>";
				$number++;
			}
		}
	echo "</tbody></table></div>";
	mysqli_close($con);
	if($queryresult == 0) {
		echo "No result";
	}	
	
}
elseif(isset($_POST['closedate'])){
	$closedate = date_create($_POST['closedate']);
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	
	$queryresult = 0;
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
	    die('Could not connect: ' . mysqli_error($con));
	}
	
	$sql = "SELECT CTO.CTOID,CTO.CTOTranDate,CTO.CTOPosition,CTO.CTOUnderlying,CTO.CTOFutureName,CTO.CTOVolumeStart,CTO.CTOVolumeCurrent,CTO.CTOSpot,CTO.CTOValue,CTO.CTOUpfrontInterest,CTO.CTOMinimumDay,CTO.CTOMinimumInt,CTO.CTODiscount,CTO.CTOTotalInterest,CTO.CTOPercentInterest,CTO.CTOPercentUpfront,CTO.CTOFuturePrice,S.SName,S.SLastTradingDay,S.SMultiplier,C.Account FROM customertransactionopen AS CTO INNER JOIN customer AS C ON C.CustomerID = CTO.CustomerID INNER JOIN serie AS S ON CTO.SerieID = S.SerieID WHERE CTO.CTOVolumeCurrent > 0 ORDER BY CTOFutureName ASC,Account ASC,CTOTranDate DESC ";
		
	$result = mysqli_query($con,$sql);
	echo "<div class='col-md-12'>";
	echo "<table class='table'>";
	echo "<caption>Current Position</caption>
		      <thead>
		        <tr>
				  <th>Account</th>
		          <th>Position</th>
		          <th>Futures</th>
				  <th>Cost</th>
		          <th>Volume(Start)</th>
				  <th>Volume(Now)</th>
				  <th>Value</th>
		          <th>Initial Date</th>
		          <th>ดอกมัดจำ</th>
				  <th>ดอกทั้งหมด</th>
				  <th>Action</th>
		        </tr>
		      </thead>
		      <tbody>";
	$number = 1;
	if(mysqli_num_rows($result)>0){
		$queryresult = mysqli_num_rows($result);
		while($row = mysqli_fetch_array($result)) {
				echo "<tr>";
				echo "<td>".$row['Account']."</td>";
				if ($row['CTOPosition'] == "Long")
					echo "<td><button type='button' class='btn btn-success' disabled='disabled' id='position".$number."' value='".$row['CTOPosition']."'>Long</button></td>";
				elseif ($row['CTOPosition'] == "Short")
				echo "<td><button type='button' class='btn btn-danger' disabled='disabled' id='position".$number."' value='".$row['CTOPosition']."'>Short</button></td>";
				echo "<td>".$row['CTOFutureName']."</td>";
				echo "<td>".$row['CTOFuturePrice']."</td>";
				echo "<td>".$row['CTOVolumeStart']."</td>";
				echo "<td>".$row['CTOVolumeCurrent']."</td>";
				echo "<td>".number_format($row['CTOValue'],2,'.',',')."</td>";
				$Newformat = strtotime($row['CTOTranDate']);
				$myFormatForView = date("d/m/Y", $Newformat);
				echo "<td>".$myFormatForView."</td>";
				echo "<td>".$row['CTOUpfrontInterest']."</td>";
				echo "<td>".$row['CTOTotalInterest']."</td>";
				$hodingday = (date_diff($closedate,date_create($row['CTOTranDate']))->format("%a"));
				$hodinginterest = number_format($row['CTOSpot'] * $row['CTOPercentInterest'] / 100 * $hodingday / 365,5,'.',',');
				echo "<td><a class='btn btn-primary' href='saveClose.php?acc=".$row['Account']."&id=".$row['CTOID']."&p=".$row['CTOPosition']."&ui=".$row['CTOUnderlying']."&mindate=".$row['CTOMinimumDay']."&minint=".$row['CTOMinimumInt']."&s=".$row['SName']."&vol=".$row['CTOVolumeStart']."&volCur=".$row['CTOVolumeCurrent']."&cost=".$row['CTOFuturePrice']."&idate=".$myFormatForView."&up=".$row['CTOUpfrontInterest']."&discount=".$row['CTODiscount']."&holday=".$hodingday."&spot=".$row['CTOSpot']."&interest=".$row['CTOPercentInterest']."&multi=".$row['SMultiplier']."&value=".number_format($row['CTOValue'],2,'.',',')."' role='button' target='_blank'>Action</a></td>";
				echo "</tr>";
				$number++;
			}
		}
	echo "</tbody></table></div>";
	mysqli_close($con);
	if($queryresult == 0) {
		echo "No result";
	}	
	
}
//Result of Edit Open Position
elseif(isset($_POST['openPos']) AND $_POST['openPos'] == 1){
	$account = $_POST['account'];
	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];
	$underlying = $_POST['underlying'];
	
	$sql = "SELECT CTO.CTOID,CTO.CTOTranDate,CTO.CTOPosition,CTO.CTOUnderlying,CTO.CTOFutureName,CTO.CTOVolumeStart,CTO.CTOVolumeCurrent,CTO.CTOSpot,CTO.CTOValue,CTO.CTOUpfrontInterest,CTO.CTOTotalInterest,CTO.CTOPercentInterest,CTO.CTOPercentUpfront,CTO.CTOFuturePrice,S.SName,C.Account FROM customertransactionopen AS CTO INNER JOIN customer AS C ON C.CustomerID = CTO.CustomerID INNER JOIN serie AS S ON CTO.SerieID = S.SerieID WHERE CTO.CTOTranDate BETWEEN '$startdate' AND '$enddate'";
	
	echo "<div class='col-md-12'>";
	echo "<h3><p class='text-center'>All Open Transaction Between ".date("d/m/Y", strtotime($startdate))." To  ".date("d/m/Y", strtotime($enddate));
	if($account != ""){
		$sql = $sql." AND C.Account = '$account'";
		echo " of Account $account";
	}
	if($underlying != ""){
		$sql = $sql." AND CTO.CTOUnderlying = '$underlying'";
		echo " AND  Underlying : $underlying";
	}
	$sql .= " ORDER BY CTOFutureName";
	echo "</p></h3>";
	echo "</div>";
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	
	$queryresult = 0;
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
	    die('Could not connect: ' . mysqli_error($con));
	}
	
	$result = mysqli_query($con,$sql);
	echo "<div class='col-md-12'>";
	echo "<table class='table'>";
	echo "<thead>
		        <tr>
		          <th>Initial Date</th>
				  <th>Account</th>
		          <th>Position</th>
		          <th>Underlying</th>
		          <th>Volume(Start)</th>
				  <th>Volume(Now)</th>
				  <th>Spot</th>
		          <th>ดอกมัดจำ</th>
				  <th>ราคา ฟิวเจอร์</th>
				  <th>Value</th>
				  <th>Delete</th>
		        </tr>
		      </thead>
		      <tbody>";
	if(mysqli_num_rows($result)>0){
		$queryresult = mysqli_num_rows($result);
		while($row = mysqli_fetch_array($result)) {
				echo "<tr>";
				$Newformat = strtotime($row['CTOTranDate']);
				$myFormatForView = date("d/m/Y", $Newformat);
				echo "<td>".$myFormatForView."</td>";
				echo "<td>".$row['Account']."</td>";
				if ($row['CTOPosition'] == "Long")
					echo "<td><button type='button' class='btn btn-success' disabled='disabled' value='".$row['CTOPosition']."'>Long</button></td>";
				elseif ($row['CTOPosition'] == "Short")
				echo "<td><button type='button' class='btn btn-danger' disabled='disabled' value='".$row['CTOPosition']."'>Short</button></td>";
				echo "<td>".$row['CTOFutureName']."</td>";
				echo "<td>".$row['CTOVolumeStart']."</td>";
				echo "<td>".$row['CTOVolumeCurrent']."</td>";
				echo "<td>".$row['CTOSpot']."</td>";
				echo "<td>".$row['CTOUpfrontInterest']."</td>";
				echo "<td>".$row['CTOFuturePrice']."</td>";
				echo "<td>".number_format($row['CTOValue'],2,'.',',')."</td>";
				echo "<td><button type='button' value='".$row['CTOID']."' onclick='del(this.value)' class='btn btn-primary'>delete</button></td>";
				echo "</tr>";
			}
		}
	echo "</tbody></table></div>";
	mysqli_close($con);
	if($queryresult == 0) {
		echo "No result";
	}	
	else
		echo "<br><br><a class='btn btn-success' href='excel.php?open=".urlencode($sql)."' role='button'>Download to Excel</a>";
}

//Result of Edit Close Position
elseif(isset($_POST['closePos']) AND $_POST['closePos'] == 2){
	$account = $_POST['account'];
	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];
	$underlying = $_POST['underlying'];
	
$sql = "SELECT CTC.CTCID,CTC.CTCTranDate,CTC.CTCPosition,CTC.CTCUnderlying,CTC.CTCFutureName,CTC.CTCVolume,CTC.CTCSpot,CTC.CTCTotalInterest,CTC.CTCNetInterest,CTC.CTCFuturePrice,CTC.CTCValue,CTC.CTCForceCloseFlag,S.SName,C.Account FROM customertransactionclose AS CTC INNER JOIN customer AS C ON C.CustomerID = CTC.CustomerID INNER JOIN serie AS S ON CTC.SerieID = S.SerieID WHERE CTC.CTCTranDate BETWEEN '$startdate' AND '$enddate'";

echo "<div class='col-md-12'>";
echo "<h3><p class='text-center'>All Close Transaction Between ".date("d/m/Y", strtotime($startdate))." To  ".date("d/m/Y", strtotime($enddate));
if($account != ""){
	$sql = $sql." AND C.Account = '$account'";
	echo " of Account $account";
}
if($underlying != ""){
$sql = $sql." AND CTC.CTCUnderlying = '$underlying'";
echo " AND  Underlying : $underlying";
}
$sql .= " ORDER BY CTCFutureName";
echo "</p></h3>";
	echo "</div>";

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";

	$queryresult = 0;
	// Create connection
$con = mysqli_connect($servername, $username, $password, $dbname);
if (!$con) {
die('Could not connect: ' . mysqli_error($con));
}

$result = mysqli_query($con,$sql);
echo "<div class='col-md-12'>";
	echo "<table class='table'>";
	echo "<thead>
				<tr>
				  <th>วันที่ปิด</th>
				  <th>เลขที่ปัญชี</th>
		          <th>สถานะ</th>
		          <th>Futures</th>
				  <th>ราคาปิดสัญญา</th>
		          <th>Volume</th>
				  <th>Value</th>
		          <th>หุ้นอ้างอิง</th>
				  <th>ราคาหุ้นอ้างอิง</th>
			 	  <th>Delete</th>
		        </tr>
		      </thead>
		      <tbody>";
	if(mysqli_num_rows($result)>0){
		$queryresult = mysqli_num_rows($result);
		while($row = mysqli_fetch_array($result)) {
		echo "<tr>";
		$Newformat = strtotime($row['CTCTranDate']);
		$myFormatForView = date("d/m/Y", $Newformat);
		echo "<td>".$myFormatForView."</td>";
		echo "<td>".$row['Account']."</td>";
		if ($row['CTCPosition'] == "Long")
			echo "<td><button type='button' class='btn btn-success' disabled='disabled' value='".$row['CTCPosition']."'>Long</button></td>";
		elseif ($row['CTCPosition'] == "Short")
			echo "<td><button type='button' class='btn btn-danger' disabled='disabled' value='".$row['CTCPosition']."'>Short</button></td>";
		echo "<td>".$row['CTCFutureName']."</td>";
		echo "<td>".$row['CTCFuturePrice']."</td>";
		echo "<td>".$row['CTCVolume']."</td>";
		echo "<td>".number_format($row['CTCValue'],2,'.',',')."</td>";
		echo "<td>".$row['CTCUnderlying']."</td>";
		echo "<td>".$row['CTCSpot']."</td>";
		echo "<td><button type='button' value='".$row['CTCID']."' onclick='del(this.value)' class='btn btn-primary'>delete</button></td>";
		echo "</tr>";
		}
		}
		echo "</tbody></table></div>";
	mysqli_close($con);
		if($queryresult == 0) {
			echo "No result";
		}
		else
			echo "<br><br><a class='btn btn-success' href='excel.php?close=".urlencode($sql)."' role='button'>Download to Excel</a>";
		
}
//edit Company Transaction
if(isset($_POST['company']) AND $_POST['company'] == 1){
	$account = $_POST['account'];
	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];
	$underlying = $_POST['underlying'];
	
	$sql = "SELECT COT.CompanyTransactionID,COT.COTDate,COT.COTPosition,COT.COTUnderlying,COT.COTVolume,COT.COTCost,COT.COTValue,COT.COTCash,S.SName,C.Account FROM companytransaction AS COT INNER JOIN customer AS C ON C.CustomerID = COT.CustomerID INNER JOIN serie AS S ON COT.SerieID = S.SerieID WHERE COT.COTDate BETWEEN '$startdate' AND '$enddate'";
	
	echo "<div class='col-md-12'>";
	echo "<h3><p class='text-center'>All Close Transaction Between ".date("d/m/Y", strtotime($startdate))." To  ".date("d/m/Y", strtotime($enddate));
	if($account != ""){
		$sql = $sql." AND C.Account = '$account'";
		echo " of Account $account";
	}
	if($underlying != ""){
		$sql = $sql." AND COT.COTUnderlying = '$underlying'";
		echo " AND  Underlying : $underlying";
	}
	$sql .= " ORDER BY COTUnderlying";
	echo "</p></h3>";
	echo "</div>";
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	
	$queryresult = 0;
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	
	$result = mysqli_query($con,$sql);
	echo "<div class='col-md-12'>";
	echo "<table class='table'>";
	echo "<thead>
	<tr>
	<th>Initial Date</th>
			          <th>Position</th>
			          <th>Underlying</th>
			          <th>Volume</th>
					  <th>ราคา</th>
					  <th>Value</th>
			          <th>กระแสเงินสด</th>
			        </tr>
			      </thead>
			      <tbody>";
	if(mysqli_num_rows($result)>0){
		$queryresult = mysqli_num_rows($result);
		while($row = mysqli_fetch_array($result)) {
			echo "<tr>";
			$Newformat = strtotime($row['COTDate']);
			$myFormatForView = date("d/m/Y", $Newformat);
			echo "<td>".$myFormatForView."</td>";
			if ($row['COTPosition'] == "Long")
				echo "<td><button type='button' class='btn btn-success' disabled='disabled' value='".$row['COTPosition']."'>Long</button></td>";
			elseif ($row['COTPosition'] == "Short")
				echo "<td><button type='button' class='btn btn-danger' disabled='disabled' value='".$row['COTPosition']."'>Short</button></td>";
			echo "<td>".$row['COTUnderlying']."</td>";
			echo "<td>".$row['COTVolume']."</td>";
			echo "<td>".$row['COTCost']."</td>";
			echo "<td>".$row['COTValue']."</td>";
			echo "<td>".$row['COTCash']."</td>";
			echo "</tr>";
		}
	}
	echo "</tbody></table></div>";
	mysqli_close($con);
	if($queryresult == 0) {
		echo "No result";
	}
	else
		echo "<br><br><a class='btn btn-success' href='excel.php?company=".urlencode($sql)."' role='button'>Download to Excel</a>";
	
}
?>
</body>
</html>