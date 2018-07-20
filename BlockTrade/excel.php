<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if(!isset($_SESSION['loggedin'])){
	header('Location: login.php');
	exit();
}
if(isset($_GET['current'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";

	$filename = "Current Position Summary[".date("d/m/Y")."]";
	$totalValue = 0;
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
		
	
	//if($result = mysqli_query($con,"SELECT CTOUnderlying,CTOPosition,SUM(CTOVolumeCurrent) AS Volume,serie.SMultiplier FROM customertransactionopen INNER JOIN serie ON customertransactionopen.SerieID = serie.SerieID WHERE CTOVolumeCurrent > 0 GROUP BY CTOUnderlying,CTOPosition")){

	if($result = mysqli_query($con,"
		SELECT   CTOUnderlying ,CTOPosition,SUM(Volume) as Vol
		FROM(
		SELECT CTOUnderlying,CTOPosition,CTOVolumeCurrent * serie.SMultiplier AS Volume
		FROM customertransactionopen INNER JOIN serie ON customertransactionopen.SerieID = serie.SerieID WHERE CTOVolumeCurrent > 0 )AS  CTO group by CTOUnderlying,CTOPosition;")){
		//header info for browser
		header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
		header("Content-type: application/x-msexcel; charset=UTF-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		//header("Pragma: no-cache");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		
		//table caption
		echo "<table><tr><th></th><th></th><th></th><th></th><th></th><th>As of : ".date("d/m/Y")."</th></tr><tr><th>Port Position</th></tr></table>";
		
		echo "<table border='1'><tr>";
		echo "<th>Stock</th>
				<th>Side</th>
				<th>Quantity</th>";
		echo "</tr></table>";
	
		while($row = mysqli_fetch_array($result)) {
			echo "<table border='1'><tr>";
			echo "<td>".$row['CTOUnderlying']."</td>";
			echo "<td>".$row['CTOPosition']."</td>";
			echo "<td>".number_format($row['Vol'])."</td>";
			echo "</tr></table>";
		}
		
	}
	

	echo "<br><br>";
	
	//table caption
	echo "<table><tr><th  style='text-align:right;'>Outstanding </th><th  style='text-align:left;'> Client Position</th></tr></table>";
	
	$sql = $_GET['current'];
	$sql .= " ORDER BY CTOFutureName";
	if($result = mysqli_query($con,$sql)){
	
		echo "<table border='1'><tr>";
		echo "<th>Date</th>
				<th>Account</th>
				<th>Position</th>
				<th>Future</th>
				<th>Open Price</th>
				<th>Current Volume</th>
				<th>Value</th>";
		echo "</tr></table>";
	
		while($row = mysqli_fetch_array($result)) {
			echo "<table border='1'><tr>";
			$Newformat = strtotime($row['CTOTranDate']);
			$myFormatForView = date("d/m/Y", $Newformat);
			echo "<td>".$myFormatForView."</td>";
			echo "<td>".$row['Account']."</td>";
			echo "<td>".$row['CTOPosition']."</td>";
			echo "<td>".$row['CTOFutureName']."</td>";
			echo "<td>".$row['CTOFuturePrice']."</td>";
			echo "<td>".$row['CTOVolumeCurrent']."</td>";
			echo "<td>".number_format($row['CTOValue'],2,'.',',')."</td>";
			echo "</tr>";
			$totalValue += $row['CTOValue'];
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><th>Total</th><td>".number_format($totalValue,2,'.',',')."</td></tr></table>";
	}
	
	mysqli_close($con);
}
elseif(isset($_GET['open'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	
	$filename = "Block Trade Open Summary[".date("d/m/Y")."]";
	$totalValue = 0;
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	$sql = $_GET['open'];
	if($result = mysqli_query($con,$sql)){
		
		//header info for browser
		header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
		header("Content-type: application/x-msexcel; charset=UTF-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		//header("Pragma: no-cache");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		
		echo "<table border='1'><tr>";
		echo "<th>Transaction Date</th>
				<th>Account</th>
				<th>Position</th>
				<th>Future</th>
				<th>Spot</th>
				<th>Up-Front Interest</th>
				<th>Open Price</th>
				<th>Start Volume</th>
				<th>Current Volume</th>
				<th>Value</th>";
		echo "</tr></table>";
		
		while($row = mysqli_fetch_array($result)) {
			echo "<table border='1'><tr>";
			$Newformat = strtotime($row['CTOTranDate']);
			$myFormatForView = date("d/m/Y", $Newformat);
			echo "<td>".$myFormatForView."</td>";
			echo "<td>".$row['Account']."</td>";
			echo "<td>".$row['CTOPosition']."</td>";
			echo "<td>".$row['CTOFutureName']."</td>";
			echo "<td>".$row['CTOSpot']."</td>";
			echo "<td>".$row['CTOUpfrontInterest']."</td>";
			echo "<td>".$row['CTOFuturePrice']."</td>";
			echo "<td>".$row['CTOVolumeStart']."</td>";
			echo "<td>".$row['CTOVolumeCurrent']."</td>";
			echo "<td>".number_format($row['CTOValue'],2,'.',',')."</td>";
			echo "</tr>";
			$totalValue += $row['CTOValue'];
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><th>Total</th><td>".number_format($totalValue,2,'.',',')."</td></tr></table>";
	}
	
	mysqli_close($con);
}
elseif(isset($_GET['close'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";

	$filename = "Block Trade Close Summary[".date("d/m/Y")."]";
	$totalValue = 0;
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	$sql = $_GET['close'];
	if($result = mysqli_query($con,$sql)){

		//header info for browser
		header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
		header("Content-type: application/x-msexcel; charset=UTF-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		//header("Pragma: no-cache");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);

		echo "<table border='1'><tr>";
		echo "<th>Close Date</th>
				<th>Account</th>
				<th>Position</th>
				<th>Future</th>
				<th>Spot</th>
				<th>Net Interest</th>
				<th>Close Price</th>
				<th>Volume</th>
				<th>Value</th>";
		echo "</tr></table>";

		while($row = mysqli_fetch_array($result)) {
			echo "<table border='1'><tr>";
			$Newformat = strtotime($row['CTCTranDate']);
			$myFormatForView = date("d/m/Y", $Newformat);
			echo "<td>".$myFormatForView."</td>";
			echo "<td>".$row['Account']."</td>";
			echo "<td>".$row['CTCPosition']."</td>";
			echo "<td>".$row['CTCFutureName']."</td>";
			echo "<td>".$row['CTCSpot']."</td>";
			echo "<td>".$row['CTCNetInterest']."</td>";
			echo "<td>".$row['CTCFuturePrice']."</td>";
			echo "<td>".$row['CTCVolume']."</td>";
			echo "<td>".number_format($row['CTCValue'],2,'.',',')."</td>";
			echo "</tr>";
			$totalValue += $row['CTCValue'];
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Total</td><td>".number_format($totalValue,2,'.',',')."</td></tr></table>";
	}

	mysqli_close($con);
}
elseif(isset($_GET['company'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";

	$filename = "Company Transaction [".date("d/m/Y")."]";
	$totalValue = 0;
	$totalCash = 0;
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	$sql = $_GET['company'];
	if($result = mysqli_query($con,$sql)){

		//header info for browser
		header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
		header("Content-type: application/x-msexcel; charset=UTF-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		//header("Pragma: no-cache");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);

		echo "<table><tr><th></th><th></th><th></th><th>As of : ".date("d/m/Y")."</th></tr><tr><th>Company Transaction</th></tr></table>";
		
		echo "<table border='1'><tr>";
		echo "<th>Transaction Date</th>
				<th>Position</th>
				<th>Name</th>
				<th>Volume</th>
				<th>Price</th>
				<th>Value</th>
				<th>Cash</th>";
		echo "</tr></table>";

		while($row = mysqli_fetch_array($result)) {
			echo "<table border='1'><tr>";
			$Newformat = strtotime($row['COTDate']);
			$myFormatForView = date("d/m/Y", $Newformat);
			echo "<td>".$myFormatForView."</td>";
			echo "<td>".$row['COTPosition']."</td>";
			echo "<td>".$row['COTUnderlying']."</td>";
			echo "<td>".$row['COTVolume']."</td>";
			echo "<td>".$row['COTCost']."</td>";
			echo "<td>".number_format($row['COTValue'],2,'.',',')."</td>";
			echo "<td>".number_format($row['COTCash'],2,'.',',')."</td>";
			echo "</tr>";
			$totalValue += $row['COTValue'];
			$totalCash += $row['COTCash'];
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><th>Total</th><td>".number_format($totalCash,2,'.',',')."</td></tr></table>";
	}

	mysqli_close($con);
}
elseif(isset($_GET['fair'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	
	$filename = "Company Position Summary[".date("d/m/Y")."]";
	$totalValue = 0;
	$totalCash = 0;
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	$dividendpart = "";
	$totaldividend = 0;
	$sql = "SELECT DDividend,DVolume,DStock FROM `dividend` ORDER BY DStock";
	if($result = mysqli_query($con,$sql)){
		while($row = mysqli_fetch_array($result)) {
			$dividendpart .= "<tr><td></td><td></td><td>".$row['DStock']."</td><td>".$row['DVolume']."</td><td>".$row['DDividend']."</td><td>".number_format($row['DDividend']*$row['DVolume'],2,'.',',')."</td><td>".number_format($row['DDividend']*$row['DVolume'],2,'.',',')."</td></tr>";
			$totaldividend += ($row['DDividend']*$row['DVolume']);
		}
	}
	
	$sql = "SELECT COT.CompanyTransactionID,COT.COTDate,COT.COTPosition,COT.COTUnderlying,COT.COTVolume,COT.COTCost,COT.COTValue,COT.COTCash,S.SName,C.Account FROM companytransaction AS COT INNER JOIN customer AS C ON C.CustomerID = COT.CustomerID INNER JOIN serie AS S ON COT.SerieID = S.SerieID";
	if($result = mysqli_query($con,$sql)){
	
		//header info for browser
		header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
		header("Content-type: application/x-msexcel; charset=UTF-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		//header("Pragma: no-cache");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
	
		echo "<table border='1'><tr>";
		echo "<th>Transaction Date</th>
				<th>Position</th>
				<th>Name</th>
				<th>Volume</th>
				<th>Price</th>
				<th>Value</th>
				<th>Cash</th>";
		echo "</tr></table>";
	
		while($row = mysqli_fetch_array($result)) {
			echo "<table border='1'><tr>";
			$Newformat = strtotime($row['COTDate']);
			$myFormatForView = date("d/m/Y", $Newformat);
			echo "<td>".$myFormatForView."</td>";
			echo "<td>".$row['COTPosition']."</td>";
			echo "<td>".$row['COTUnderlying']."</td>";
			echo "<td>".$row['COTVolume']."</td>";
			echo "<td>".$row['COTCost']."</td>";
			echo "<td>".number_format($row['COTValue'],2,'.',',')."</td>";
			echo "<td>".number_format($row['COTCash'],2,'.',',')."</td>";
			echo "</tr>";
			$totalValue += $row['COTValue'];
			$totalCash += $row['COTCash'];
		}
		echo $dividendpart;
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td>Total</td><td>".number_format($totalCash+$totaldividend,2,'.',',')."</td></tr>";
		echo "</table>";
	}

	mysqli_close($con);
}
elseif(isset($_GET['stock'])){
	//Get Current Price From Aspen on Excel
	error_reporting(E_ALL ^ E_NOTICE);
	require_once 'excel/excel_reader2.php';
	$data = new Spreadsheet_Excel_Reader("excel/example.xls");
	
	$spot = [];
	$rowcount = $data->rowcount($sheet_index=0);
	for($j = 3;$j <= $rowcount; $j++){
		if($data->val($j,1) == 1){
				$spot['TRUE'][$data->val(2,2)] = $data->val($j,2);
				$spot['TRUE'][$data->val(2,3)] = $data->val($j,3);
				$spot['TRUE'][$data->val(2,4)] = $data->val($j,4);
		}
		else{
				$spot[$data->val($j,1)][$data->val(2,2)] = $data->val($j,2);
				$spot[$data->val($j,1)][$data->val(2,3)] = $data->val($j,3);
				$spot[$data->val($j,1)][$data->val(2,4)] = $data->val($j,4);
		}
	}
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";

	$filename = "Company Position Summary[".date("d/m/Y")."]";
	$totalValue = 0;
	$currentValue = 0;
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	if($result = mysqli_query($con,"SELECT CTOUnderlying,CTOPosition,SUM(CTOVolumeCurrent) AS Volume,serie.SMultiplier FROM customertransactionopen INNER JOIN serie ON customertransactionopen.SerieID = serie.SerieID WHERE CTOVolumeCurrent > 0 GROUP BY CTOUnderlying,CTOPosition")){
	
		//header info for browser
		header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
		header("Content-type: application/x-msexcel; charset=UTF-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		//header("Pragma: no-cache");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		
		//table caption
		echo "<table><tr><th></th><th></th><th></th><th></th><th></th><th>As of : ".date("d/m/Y")."</th></tr><tr><th>Port Position</th></tr></table>";
		
		echo "<table border='1'><tr>";
		echo "<th>Stock</th>
				<th>Side</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Value</th>";
		echo "</tr></table>";
	
		while($row = mysqli_fetch_array($result)) {
			echo "<table border='1'><tr>";
			echo "<td>".$row['CTOUnderlying']."</td>";
			echo "<td>".$row['CTOPosition']."</td>";
			echo "<td>".number_format($row['Volume']*$row['SMultiplier'])."</td>";
			$currentValue = 0;
			if($row['CTOPosition'] == "Short"){
				echo "<td>".$spot[$row['CTOUnderlying']]['ASK']."</td>";
				$currentValue = $spot[$row['CTOUnderlying']]['ASK']*$row['Volume']*$row['SMultiplier'];
				$currentValue = $currentValue*-1;
			}
			else{
				echo "<td>".$spot[$row['CTOUnderlying']]['BID']."</td>";
				$currentValue = $spot[$row['CTOUnderlying']]['BID']*$row['Volume']*$row['SMultiplier'];
			}
			echo "<td>".number_format($currentValue)."</td>";
			$totalValue += $currentValue;
			echo "</tr>";
		}
		echo "<tr><td></td><td></td><td></td><td>Total</td><td>".number_format($totalValue)."</td></tr></table>";
	}

	mysqli_close($con);
}
elseif(isset($_GET['futures'])){
	//Get Current Price From Aspen on Excel
	error_reporting(E_ALL ^ E_NOTICE);
	require_once 'excel/excel_reader2.php';
	$data = new Spreadsheet_Excel_Reader("excel/example.xls");

	$spot = [];
					$futures = [];
					$rowcount = $data->rowcount($sheet_index=0);
					for($j = 3;$j <= $rowcount; $j++){
						if($data->val($j,1) == 1){
							$spot['TRUE'][$data->val(2,2)] = $data->val($j,2);
							$spot['TRUE'][$data->val(2,3)] = $data->val($j,3);
							$spot['TRUE'][$data->val(2,4)] = $data->val($j,4);
						}
						else{
							$spot[$data->val($j,1)][$data->val(2,2)] = $data->val($j,2);
							$spot[$data->val($j,1)][$data->val(2,3)] = $data->val($j,3);
							$spot[$data->val($j,1)][$data->val(2,4)] = $data->val($j,4);
						}
					}
					
					for($i = 1;$i <= 4;$i++){
						for($j = 3;$j <= $rowcount; $j++){
							$futures[$data->val($j,(5+(2*$i)-1))] = $data->val($j,(5+(2*$i)));
						}
					}

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";

	$filename = "Company Position Summary[".date("d/m/Y")."]";
	$totalValue = 0;
	$totalCurrent = 0;
	$today = new DateTime();
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	$dividend = array();
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	$sql = "SELECT XDDate,DDividend,DPercentOut,DStock FROM `dividend`";
	$result = mysqli_query($con, $sql);
	
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$dividend[$row['DStock']]['Dividend'] = $row['DDividend'];
			$dividend[$row['DStock']]['XD'] = $row['XDDate'];
			$dividend[$row['DStock']]['PercentOut'] = $row['DPercentOut'];
		}
	}
	if($result = mysqli_query($con,"SELECT CTO.CTOID,CTO.CTOTranDate,CTO.CTOPosition,CTO.CTOUnderlying,CTO.CTOFutureName,CTO.CTOVolumeStart,CTO.CTOVolumeCurrent,CTO.CTOSpot,CTO.CTOValue,CTO.CTOUpfrontInterest,CTO.CTOMinimumDay,CTO.CTOMinimumInt,CTO.CTOTotalInterest,CTO.CTODiscount,CTO.CTOPercentInterest,CTO.CTOPercentUpfront,CTO.CTOFuturePrice,S.SName,S.SMultiplier,C.Account FROM customertransactionopen AS CTO INNER JOIN customer AS C ON C.CustomerID = CTO.CustomerID INNER JOIN serie AS S ON CTO.SerieID = S.SerieID WHERE CTOVolumeCurrent > 0 ORDER BY CTO.CTOTranDate")){

		//header info for browser
		header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
		header("Content-type: application/x-msexcel; charset=UTF-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		//header("Pragma: no-cache");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);

		echo "<table border='1'><tr>";
		echo "<th>Date</th>
				<th>Account</th>
				<th>Position</th>
				<th>Future</th>
				<th>Open Price</th>
				<th>Current Volume</th>
				<th>Value</th>
				<th>Futures Price</th>
				<th>Holding Period</th>
				<th>Open Spot</th>
				<th>Spot</th>
				<th>Upfront int</th>
				<th>Discount</th>
				<th>Dividend</th>
				<th>Fair</th>
				<th>Profit/Loss</th>";
		echo "</tr></table>";
	
		while($row = mysqli_fetch_array($result)) {
			$tranDate = new DateTime($row['CTOTranDate']);
			echo "<table border='1'><tr>";
			$Newformat = strtotime($row['CTOTranDate']);
			$myFormatForView = date("d/m/Y", $Newformat);
			echo "<td>".$myFormatForView."</td>";
			echo "<td>".$row['Account']."</td>";
			if($row['CTOPosition'] == "Long")
				echo "<td>Short</td>";
			else 
				echo "<td>Long</td>";
			echo "<td>".$row['CTOFutureName']."</td>";
			echo "<td>".$row['CTOFuturePrice']."</td>";
			echo "<td>".$row['CTOVolumeCurrent']."</td>";
			echo "<td>".number_format($row['CTOValue'],2,'.',',')."</td>";
			echo "<td>".$futures[$row['CTOFutureName']]."</td>";
			$holdingperiod = 0;
			if($tranDate->diff($today)->format("%a") < $row['CTOMinimumDay'])
				$holdingperiod = $row['CTOMinimumDay'];
			else 
				$holdingperiod = $tranDate->diff($today)->format("%a");
			echo "<td>".$holdingperiod."</td>";
			echo "<td>".$row['CTOSpot']."</td>";
			$currentspot = 0;
			if($row['CTOPosition'] == "Long"){
				$currentspot = $spot[$row['CTOUnderlying']]['BID'];
			}
			elseif($row['CTOPosition'] == "Short"){
				$currentspot = $spot[$row['CTOUnderlying']]['ASK'];
			}
			echo "<td>".$currentspot."</td>";
			echo "<td>".$row['CTOUpfrontInterest']."</td>";
			echo "<td>".$row['CTODiscount']."</td>";
			$dividendpayback = 0;
			if (array_key_exists($row['CTOUnderlying'], $dividend)) {
				if($dividend[$row['CTOUnderlying']]['XD']>$row['CTOTranDate']){
					$dividendpayback = $dividend[$row['CTOUnderlying']]['Dividend']*$dividend[$row['CTOUnderlying']]['PercentOut'];
				}
			}
			echo "<td>$dividendpayback</td>";
			$totalint = 0;
			if(($row['CTOSpot']*$row['CTOPercentInterest']/100*$holdingperiod/365) < $row['CTOMinimumInt'])
				$totalint = $row['CTOMinimumInt'];
			else 
				$totalint = ($row['CTOSpot']*$row['CTOPercentInterest']/100*$holdingperiod/365);
			$fairprice = 0;
			$current = 0;
			if($row['CTOPosition'] == "Long"){
				$fairprice = ($currentspot + $row['CTOUpfrontInterest'] - $totalint + $dividendpayback - $row['CTODiscount']);
				$current = ($fairprice*$row['SMultiplier']*$row['CTOVolumeCurrent'])*-1;
			}
			else{ 
				$fairprice = ($currentspot - $row['CTOUpfrontInterest'] + $totalint + $dividendpayback + $row['CTODiscount']);
				$current = ($fairprice*$row['SMultiplier']*$row['CTOVolumeCurrent']);
			}
			echo "<td>$fairprice</td>";
			echo "<td>".number_format($current,2,'.',',')."</td>";
			echo "</tr>";
			$totalValue += $row['CTOValue'];
			$totalCurrent += $current;
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Total</td><td>".number_format($totalCurrent,2,'.',',')."</td></tr></table>";
	}

	mysqli_close($con);
}
elseif(isset($_GET['reward'])){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	
	$filename = "Reward [".date("d/m/Y")."]";
	$totalcompanyprofit = 0;
	// Create connection
	$con = mysqli_connect($servername, $username, $password, $dbname);
	mysqli_set_charset($con,"utf8");
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));
	}
	$sql = $_GET['reward'];
	if($result = mysqli_query($con,$sql)){
	
		//header info for browser
		header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
		header("Content-type: application/x-msexcel; charset=UTF-8");
		header("Content-Disposition: attachment; filename=$filename.xls");
		//header("Pragma: no-cache");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
	
		echo "<table border='1'><tr>";
		echo "	<th>MKT ID</th>
				<th>MKT Name</th>
				<th>Team</th>
				<th>Account</th>
				<th>Open Date</th>
				<th>Close Date</th>
				<th>Underlying</th>
				<th>Position</th>
				<th>Open Price</th>
				<th>Open Volume</th>
				<th>Company Profit</th>
				<th>IC Profit</th>";
		echo "</tr></table>";
	
		while($row = mysqli_fetch_array($result)) {
			echo "<table border='1'><tr>";
			echo "<td>".$row['MKTID']."</td>";
			echo "<td>".$row['MKTName']."</td>";
			echo "<td>".$row['Team']."</td>";
			echo "<td>".$row['Account']."</td>";
			$Newformat = strtotime($row['CTOTranDate']);
			$myFormatForView = date("d/m/Y", $Newformat);
			echo "<td>".$myFormatForView."</td>";
			$Newformat = strtotime($row['CTCTranDate']);
			$myFormatForView = date("d/m/Y", $Newformat);
			echo "<td>".$myFormatForView."</td>";
			$companyprofit = 0;
			if ($row['CTOPosition'] == "Long")
				$companyprofit = (($row['CTCSpot']-$row['CTOSpot'])+($row['CTOFuturePrice']-$row['CTCFuturePrice'])+$row['CTCDividend'])*$row['CTCVolume']*$row['SMultiplier'];
			elseif ($row['CTOPosition'] == "Short")
				$companyprofit = (($row['CTOSpot']-$row['CTCSpot'])+($row['CTCFuturePrice']-$row['CTOFuturePrice']))*$row['CTCVolume']*$row['SMultiplier'];
			echo "<td>".$row['CTOUnderlying']."</td>";
			echo "<td>".$row['CTOPosition']."</td>";
			echo "<td style='text-align:right;'>".$row['CTOSpot']."</td>";
			echo "<td style='text-align:right;'>".$row['CTCVolume']."</td>";
			echo "<td style='text-align:right;'>".number_format($companyprofit,2,'.',',')."</td>";
			echo "<td style='text-align:right;'>".number_format(round($companyprofit/8,3),2,'.',',')."</td>";
			echo "</tr>";
			$totalcompanyprofit += $companyprofit;
		}
		echo "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Total</td><td>".number_format($totalcompanyprofit,2,'.',',')."</td><td style='text-align:right;'>".number_format(($totalcompanyprofit/8),2,'.',',')."</td></tr></table>";
	}
	
	mysqli_close($con);
}
elseif(isset($_GET['account'])){
	
	
	//Get Current Price From Aspen on Excel
	error_reporting(E_ALL ^ E_NOTICE);
	require_once 'excel/excel_reader2.php';
	$data = new Spreadsheet_Excel_Reader("excel/example.xls");
		
	$spot = [];
	$futures = [];
	$rowcount = $data->rowcount($sheet_index=0);
	for($j = 3;$j <= $rowcount; $j++){
		if($data->val($j,1) == 1){
			$spot['TRUE'][$data->val(2,2)] = $data->val($j,2);
			$spot['TRUE'][$data->val(2,3)] = $data->val($j,3);
			$spot['TRUE'][$data->val(2,4)] = $data->val($j,4);
		}
		else{
			$spot[$data->val($j,1)][$data->val(2,2)] = $data->val($j,2);
			$spot[$data->val($j,1)][$data->val(2,3)] = $data->val($j,3);
			$spot[$data->val($j,1)][$data->val(2,4)] = $data->val($j,4);
		}
	}
	
	for($i = 1;$i <= 4;$i++){
		for($j = 3;$j <= $rowcount; $j++){
			$futures[$data->val($j,(5+(2*$i)-1))] = $data->val($j,(5+(2*$i)));
		}
	}
	$filename = "Profit [".date("d/m/Y")."]";
	$accounting = [];
	$underlying = "";
	$sum = 0;
		
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "blocktrade";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	$lastyearprofit = [];
	$sql = "SELECT SUM(LYPAccount) AS Account,SUM(LYPFair) AS Fair,LYPStock FROM `lastyearprofit` GROUP BY LYPStock";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$lastyearprofit[$row['LYPStock']]['Account'] = $row['Account'];
			$lastyearprofit[$row['LYPStock']]['Fair'] = $row['Fair'];
		}
	}
	$sql = "SELECT COT.COTUnderlying,S.SMultiplier,SUM(COTCash) AS Cash,SUM(COTVolume) AS Volume,S.SName FROM companytransaction AS COT INNER JOIN serie AS S ON COT.SerieID = S.SerieID GROUP BY COT.COTUnderlying";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			if($row['SName'] != "UI"){
				$underlying = substr($row['COTUnderlying'],0,(strlen($row['COTUnderlying'])-strlen($row['SName'])));
				$accounting[$underlying] += $row['Cash'];
				$accounting[$underlying] += $futures[$row['COTUnderlying']]*$row['Volume']*$row['SMultiplier'];
			}
			else{
				$accounting[$row['COTUnderlying']] += $row['Cash'];
				if($row['Volume'] > 0){
					$accounting[$row['COTUnderlying']] += $spot[$row['COTUnderlying']]['BID']*$row['Volume'];
				}
				elseif($row['Volume'] < 0){
					$accounting[$row['COTUnderlying']] += $spot[$row['COTUnderlying']]['ASK']*$row['Volume'];
				}
			}
		}
	}
	$sql = "SELECT DDividend,DVolume,DStock FROM `dividend` ORDER BY DStock";
	if($result = mysqli_query($conn,$sql)){
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				//$cashindividend[$row['DStock']] = ($row['DDividend']*$row['DVolume']);
				$cashindividend[$row['DStock']] += ($row['DDividend']*$row['DVolume']);
			}
		}
	}
	// Fair
	$dividend = array();
	$today = new DateTime();
	$sql = "SELECT XDDate,DDividend,DPercentOut,DStock FROM `dividend`";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$dividend[$row['DStock']]['Dividend'] = $row['DDividend'];
			$dividend[$row['DStock']]['XD'] = $row['XDDate'];
			$dividend[$row['DStock']]['PercentOut'] = $row['DPercentOut'];
		}
	}
	if($result = mysqli_query($conn,"SELECT CTO.CTOID,CTO.CTOTranDate,CTO.CTOPosition,CTO.CTOUnderlying,CTO.CTOFutureName,CTO.CTOVolumeStart,CTO.CTOVolumeCurrent,CTO.CTOSpot,CTO.CTOValue,CTO.CTOUpfrontInterest,CTO.CTOMinimumDay,CTO.CTOMinimumInt,CTO.CTOTotalInterest,CTO.CTODiscount,CTO.CTOPercentInterest,CTO.CTOPercentUpfront,CTO.CTOFuturePrice,S.SName,S.SMultiplier,C.Account FROM customertransactionopen AS CTO INNER JOIN customer AS C ON C.CustomerID = CTO.CustomerID INNER JOIN serie AS S ON CTO.SerieID = S.SerieID WHERE CTOVolumeCurrent > 0 ORDER BY CTO.CTOTranDate")){
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				$tranDate = new DateTime($row['CTOTranDate']);
				$Newformat = strtotime($row['CTOTranDate']);
				$myFormatForView = date("d/m/Y", $Newformat);
					
				$holdingperiod = 0;
				if($tranDate->diff($today)->format("%a") < $row['CTOMinimumDay'])
					$holdingperiod = $row['CTOMinimumDay'];
				else
					$holdingperiod = $tranDate->diff($today)->format("%a");
					
				$dividendpayback = 0;
				if (array_key_exists($row['CTOUnderlying'], $dividend)) {
					if($dividend[$row['CTOUnderlying']]['XD']>$row['CTOTranDate']){
						$dividendpayback = $dividend[$row['CTOUnderlying']]['Dividend']*$dividend[$row['CTOUnderlying']]['PercentOut'];
					}
				}
				$totalint = 0;
				if(($row['CTOSpot']*$row['CTOPercentInterest']/100*$holdingperiod/365) < $row['CTOMinimumInt'])
					$totalint = $row['CTOMinimumInt'];
				else
					$totalint = ($row['CTOSpot']*$row['CTOPercentInterest']/100*$holdingperiod/365);
				$fairprice = 0;
				$current = 0;
				if($row['CTOPosition'] == "Long"){
					$fairprice = ($spot[$row['CTOUnderlying']]['BID'] + $row['CTOUpfrontInterest'] - $totalint + $dividendpayback - $row['CTODiscount']);
					$current = ($fairprice*$row['SMultiplier']*$row['CTOVolumeCurrent'])*-1;
				}
				else{
					$fairprice = ($spot[$row['CTOUnderlying']]['ASK'] - $row['CTOUpfrontInterest'] + $totalint + $dividendpayback - $row['CTODiscount']);
					$current = ($fairprice*$row['SMultiplier']*$row['CTOVolumeCurrent']);
				}
				$fair[$row['CTOUnderlying']] += $current;
			}
		}
	}
// 	if($result = mysqli_query($conn,"SELECT CTOUnderlying,CTOPosition,SUM(CTOVolumeCurrent) AS Volume,serie.SMultiplier FROM customertransactionopen INNER JOIN serie ON customertransactionopen.SerieID = serie.SerieID WHERE CTOVolumeCurrent > 0 GROUP BY CTOUnderlying,CTOPosition")){
// 		if (mysqli_num_rows($result) > 0) {
// 			while($row = mysqli_fetch_array($result)) {
// 				$currentValue = 0;
// 				if($row['CTOPosition'] == "Short"){
// 					$currentValue = $spot[$row['CTOUnderlying']]['ASK']*$row['Volume']*$row['SMultiplier'];
// 					$currentValue = $currentValue*-1;
// 				}
// 				else{
// 					$currentValue = $spot[$row['CTOUnderlying']]['BID']*$row['Volume']*$row['SMultiplier'];
// 				}
// 				$fair[$row['CTOUnderlying']] += $currentValue;
// 			}
// 		}
// 	}
	if($result = mysqli_query($conn,"
		SELECT   CTOUnderlying ,CTOPosition,SUM(Volume) as Vol
		FROM(
		SELECT CTOUnderlying,CTOPosition,CTOVolumeCurrent * serie.SMultiplier AS Volume
		FROM customertransactionopen INNER JOIN serie ON customertransactionopen.SerieID = serie.SerieID WHERE CTOVolumeCurrent > 0 )AS  CTO group by CTOUnderlying,CTOPosition;")){
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_array($result)) {
					$currentValue = 0;
					if($row['CTOPosition'] == "Short"){
						$currentValue = $spot[$row['CTOUnderlying']]['ASK']*$row['Vol'];
						$currentValue = $currentValue*-1;
					}
					else{
						$currentValue = $spot[$row['CTOUnderlying']]['BID']*$row['Vol'];
					}
					$fair[$row['CTOUnderlying']] += $currentValue;
				}
			}
	}
	$sql = "SELECT DDividend,DVolume,DStock FROM `dividend` ORDER BY DStock";
	if($result = mysqli_query($conn,$sql)){
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_array($result)) {
				$fair[$row['DStock']] += ($row['DDividend']*$row['DVolume']);
			}
		}
	}
	
	$sql = "SELECT COT.COTUnderlying,S.SMultiplier,SUM(COTCash) AS Cash,SUM(COTVolume) AS Volume,S.SName FROM companytransaction AS COT INNER JOIN serie AS S ON COT.SerieID = S.SerieID GROUP BY COT.COTUnderlying";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			if($row['SName'] != "UI"){
				$underlying = substr($row['COTUnderlying'],0,(strlen($row['COTUnderlying'])-strlen($row['SName'])));
				$fair[$underlying] += $row['Cash'];
			}
			else{
				$fair[$row['COTUnderlying']] += $row['Cash'];
			}
		}
	}
	//header info for browser
	header("Content-Type: application/vnd.ms-excel;charset=UTF-8");
	header("Content-type: application/x-msexcel; charset=UTF-8");
	header("Content-Disposition: attachment; filename=$filename.xls");
	//header("Pragma: no-cache");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	
	echo "<table><tr><th></th><th></th><th></th><th>As of : ".date("d/m/Y")."</th></tr><tr><th>Profit / Loss</th></tr></table>";
	
	echo "<table border='1'><tr>";
	echo "		<th>Underlying</th>
				<th>Accounting</th>
				<th>Fair</th>";
	echo "</tr></table>";
	echo "<table border='1'>";
	///todo
	foreach($accounting as $ul => $profit) {
		if(number_format($profit+$cashindividend[$ul]-$lastyearprofit[$ul]['Account'])!=0 || number_format($fair[$ul]-$lastyearprofit[$ul]['Fair']) != 0 ){
			if($ul !="PS"){
			echo "<tr><td style='text-align:center;'>" . $ul . "</td><td style='text-align:right;'>" . number_format($profit+$cashindividend[$ul]-$lastyearprofit[$ul]['Account']) ."</td><td style='text-align:right;'>".number_format($fair[$ul]-$lastyearprofit[$ul]['Fair'])."</td></tr>";
			$sum += $profit+$cashindividend[$ul]-$lastyearprofit[$ul]['Account'];
			$sumFair += $fair[$ul]-$lastyearprofit[$ul]['Fair'];
			}
				
		}
	}
	echo "<tr><th style='text-align:center;'>Total</th><td style='text-align:right;'>".number_format($sum)."</td><td style='text-align:right;'>".number_format($sumFair)."</td></tr>";
	echo "</table>";
	
	
	$notional = [];
	$totalNotional = 0;
      $LongNotional = 0;
      $ShortNotional = 0;
	$sql = "SELECT CTOUnderlying,CTOPosition,CTOSpot,CTOVolumeCurrent AS Volume,serie.SMultiplier FROM customertransactionopen INNER JOIN serie ON customertransactionopen.SerieID = serie.SerieID WHERE CTOVolumeCurrent > 0";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
		echo "<br><br>";
		echo "<table><tr><th style='text-align:right;'>Outstanding </th><th style='text-align:left;'> Notional</th></tr></table>";
		echo "<table border='1'><tr><th>Underlying</th><th>Notional</th></tr>";
		while($row = mysqli_fetch_assoc($result)) {
			if(array_key_exists($row['CTOUnderlying'],$notional)){
				$notional[$row['CTOUnderlying']] += $row['CTOSpot']*$row['Volume']*$row['SMultiplier']; 
			}
			else {
				$notional[$row['CTOUnderlying']] = $row['CTOSpot']*$row['Volume']*$row['SMultiplier'];
			}
			if($row['CTOPosition'] == "Long"){
				$LongNotional += $row['CTOSpot']*$row['Volume']*$row['SMultiplier'];
			}
			elseif($row['CTOPosition'] == "Short"){
				$ShortNotional += $row['CTOSpot']*$row['Volume']*$row['SMultiplier'];
			}
		}
	}
	foreach($notional as $ul => $value) {
		echo "<tr><td style='text-align:center;'>" . $ul . "</td><td style='text-align:right;'>" . number_format($value) ."</td></tr>";
		$totalNotional += $value;
	}
	echo "<tr><th style='text-align:center;'>Total</th><td>" . number_format($totalNotional) ."</td></tr></table>";
	//Insert Data into Notional Table
	date_default_timezone_set("Asia/Bangkok");
	$today = new DateTime();
	$closetime = date("Y-m-d H:i:s",mktime(17, 0, 0, date("m"), date("d"), date("Y")));
	
	$lastDate;
	$lastNotional;
	$lastShortNotional;
	$lastLongNotional;
	$sql = "SELECT NDate,Notional,LONGNotional,SHORTNotional FROM notional ORDER BY NID DESC LIMIT 1";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$lastDate = new DateTime($row['NDate']);
			$lastNotional = $row['Notional'];
			$lastShortNotional = $row['SHORTNotional'];
			$lastLongNotional = $row['LONGNotional'];
		}
	}

	$loop = $lastDate->diff($today)->format("%a");
	if(($today->format("Y-m-d H:i:s") > $closetime)) {
		if($lastDate->diff($today)->format("%a")>1) {
			for($x = 1; $x <= $loop; $x++){
				if($x == $loop){
					$sql = "INSERT INTO notional (NDate,Notional,LONGNotional,SHORTNotional) VALUES ('$closetime','$totalNotional','$LongNotional','$ShortNotional')";
						
					if (mysqli_query($conn, $sql)) {
						
					} else {
						echo "Error: " . $sql . "<br>" . mysqli_error($conn);
					}
				}
				else{
					date_add($lastDate, date_interval_create_from_date_string('1 days'));
					$lastDate->format("Y-m-d H:i:s");
					$sql = "INSERT INTO notional (NDate,Notional,LONGNotional,SHORTNotional) VALUES ('".$lastDate->format("Y-m-d H:i:s")."','$lastNotional','$lastLongNotional','$lastShortNotional')";
					if (mysqli_query($conn, $sql)) {
						
					} else {
						echo "Error: " . $sql . "<br>" . mysqli_error($conn);
					}
				}
			}
		}
		elseif($lastDate->diff($today)->format("%a")>0) {
			$sql = "INSERT INTO notional (NDate,Notional,LONGNotional,SHORTNotional) VALUES ('$closetime','$totalNotional','$LongNotional','$ShortNotional')";
	
			if (mysqli_query($conn, $sql)) {
				
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}
		}
	}
	
	echo "<br>";
	echo "<table><tr><th style='text-align:center;'>Performance </th></tr></table>";
	$y = date("Y");
	$oldYear = $y-1;
	$sql = "SELECT SUM(notional) AS notional,Count(*) AS Day FROM `notional` WHERE NDate >='$oldYear-12-30' order by NDate DESC" ;
	$result = mysqli_query($conn, $sql);
	$result = mysqli_query($conn, $sql);
	$day = 0;
// 	$leap_year = date('L');
	
// 	$day_duration = 365;
// 	if($leap_year == '1'){
// 		$day_duration = 366;
// 	}
	if (mysqli_num_rows($result) > 0) {
		echo "<table border='1'>";
		while($row = mysqli_fetch_assoc($result)) {
			echo "<tr><th>Average Notional</th><td>".number_format($row['notional']/$row['Day'])."</td></tr>";
			echo "<tr><th>Notional Yield</th><td>".$sumFair/($row['notional']/$row['Day'])*365/$row['Day']*100 ."%</td></tr>";
			$day = $row['Day'];
		}
	}
	
	$sql = "Select  (LONGNotional-SHORTNotional) AS netnotional,NDate as NDate  FROM notional WHERE NDate >= '$oldYear-12-30'" ;
	$result = mysqli_query($conn, $sql);
	$netnotional = 0;
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			if($row['netnotional'] > 0){
				$netnotional+=$row['netnotional'];
			}
		}
	}

	echo "<tr><th>Cash Yield</th><td>".$sumFair/($netnotional/$day)*365/$day*100 ."%</td></tr>";
	
	$LongNotional = 0;
	$ShortNotional = 0;
	$sql = "SELECT CTOUnderlying,CTOPosition,CTOSpot,CTOVolumeCurrent AS Volume,serie.SMultiplier FROM customertransactionopen INNER JOIN serie ON customertransactionopen.SerieID = serie.SerieID WHERE CTOVolumeCurrent > 0";
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			if(array_key_exists($row['CTOUnderlying'],$notional)){
				$notional[$row['CTOUnderlying']] += $row['CTOSpot']*$row['Volume']*$row['SMultiplier'];
			}
			else {
				$notional[$row['CTOUnderlying']] = $row['CTOSpot']*$row['Volume']*$row['SMultiplier'];
			}
			if($row['CTOPosition'] == "Long"){
				$LongNotional += $row['CTOSpot']*$row['Volume']*$row['SMultiplier'];
			}
			elseif($row['CTOPosition'] == "Short"){
				$ShortNotional += $row['CTOSpot']*$row['Volume']*$row['SMultiplier'];
			}
		}
	}
	
	
	echo "<tr><th style='text-align:center;'>Total Cash Used</th><td style='text-align:right;'>".number_format($LongNotional-$ShortNotional)."</td></tr>";
	mysqli_close($conn);
}
?>