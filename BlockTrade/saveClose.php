<!DOCTYPE html>
<html lang="th">
<head>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <link href="css/styles.css" rel="stylesheet">
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  	<link rel="stylesheet" href="/resources/demos/style.css">
        <script>
		  $(function() {
		    $( "#startdate" ).datepicker({ dateFormat: "yy-mm-dd" });
		  });
		  </script>
<title>Calculate Block Trade Price</title>
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
require_once 'menu.php';?>
<div class="contrainer">
	<div class="row">
		<div class="col-md-12">
<?php

$position = $_GET['p'];
$underlying = $_GET['ui'];
$serie = $_GET['s'];
$volumeStart = $_GET['vol'];
$volumeCurrent = $_GET['volCur'];
$cost = $_GET['cost'];
$transactionDate = $_GET['idate'];
$upfront = $_GET['up'];
$hodingDay = $_GET['holday'];
$interest = $_GET['interest'];
$spot = $_GET['spot'];
$value = $_GET['value'];
$account = "";
$account = $_GET['acc'];
$CTOID = $_GET['id'];
$multiplier = $_GET['multi'];
$discount = $_GET['discount'];
$minDate = $_GET['mindate'];
$minInt = $_GET['minint'];
echo "<table class='table'>";
echo "<caption>Current Information</caption>
		      <thead>
		        <tr>
		          <th>Position</th>
		          <th>Underlying</th>
		          <th>Volume(Start)</th>
    			  <th>Volume(Now)</th>
				  <th>Cost</th>
				  <th>Value</th>
		          <th>Initial Date</th>
		          <th>ดอกมัดจำ</th>
				  <th>Holding Period(days)</th>
		        </tr>
		      </thead>
		      <tbody>";
echo "<tr>";
if ($position == "Long")
	echo "<td><button type='button' class='btn btn-success' disabled='disabled'>Long</button></td>";
elseif ($position == "Short")
echo "<td><button type='button' class='btn btn-danger' disabled='disabled'>Short</button></td>";
echo "<td>".$underlying.$serie."</td>";
echo "<td>".$volumeStart."</td>";
echo "<td>".$volumeCurrent."</td>";
echo "<td>".$cost."</td>";
echo "<td>".$value."</td>";
echo "<td>".$transactionDate."</td>";
echo "<td>".$upfront."</td>";
echo "<td>".$hodingDay."</td>";
//echo "<td><a class='btn btn-primary' href='saveClose.php?p=".$row['CTPosition']."&ui=".$row['CTUnderlying']."&s=".$row['SName']."&vol=".$row['CTVolume']."&cost=".$row['CTCost']."&idate=".$myFormatForView."&up=".$row['CTUpfrontInterest']."&holday=".$hodingday."&hodingInt=".$hodinginterest."' role='button' target='_blank'>Action</a></td>";

echo "</tr>";
echo "</tbody></table>";
?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5 col-md-offset-1">
			<div class = "col-md-4">
				<h3><?php echo $underlying; ?> Price</h3>
			</div>
			<div class = "col-md-6">
				<h3><input type="text" class="form-control" id="spot" placeholder="e.g. 20" autofocus></h3>
			</div>
			<div class = "col-md-2">
			</div>
		</div>
		<div class="col-md-5 col-md-offset-1">
			<div class = "col-md-4">
				<h3>Upfront Interest</h3>
			</div>
			<div class = "col-md-6">
				<h3><input type="text" class="form-control" id="upfrontInterest" style='text-align:right;' disabled></h3>
			</div>
			<div class = "col-md-2">
			</div>
		</div>
	</div>
	<div class='row'>
		<div class="col-md-5 col-md-offset-1">
			<div class = "col-md-4">
				<h3>จำนวนวันขั้นต่ำ</h3>
			</div>
			<div class = "col-md-6">
				<h3><input type="text" class="form-control" id="mindata" value="<?php echo $minDate;?>" disabled></h3>
			</div>
			<div class = "col-md-2">
			</div>
		</div>
		<div class='col-md-5 col-md-offset-1'>
			<div class = "col-md-4">
				<h3>Net Interest</h3>
			</div>
			<div class = "col-md-6">
				<h3><input type="text" class="form-control" id="totalInterest" style='text-align:right;' disabled></h3>
			</div>
			<div class = "col-md-2">
			</div>
		</div>
	</div>
	<div class='row'>
		<div class="col-md-5 col-md-offset-1">
			<div class = "col-md-4">
				<h3>ดอกเบี้ยขั้นต่ำ</h3>
			</div>
			<div class = "col-md-6">
				<h3><input type="text" class="form-control" id="minInterest" value="<?php echo $minInt;?>" disabled></h3>
			</div>
			<div class = "col-md-2">
			</div>
		</div>
		<div class='col-md-5 col-md-offset-1'>
			<div class = "col-md-4">
				<h3>Extra Interest</h3>
			</div>
			<div class = "col-md-6">
				<h3><input type="text" class="form-control" id="netInterest" style='text-align:right;' disabled></h3>
			</div>
			<div class = "col-md-2">
			</div>
		</div>
	</div>
	<div class='row'>
		<div class="col-md-5 col-md-offset-1">
			<div class = "col-md-4">
				<h3>Dividend</h3>
			</div>
			<div class = "col-md-6">
				<h3><input type="text" class="form-control" id="dividend" value='<?php 
				$transactionDate = str_replace('/', '-', $transactionDate);
				$closeDate = date('Y-m-d', strtotime($transactionDate . "+".$hodingDay." days"));
				$transactionDate =  date('Y-m-d', strtotime($transactionDate));
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
								  	$sql = "SELECT DDividend,DPercentOut FROM `dividend` WHERE XDDate > '$transactionDate' AND XDDate <= '$closeDate' AND DStock = '$underlying'";
								  	$result = mysqli_query($conn, $sql);
								  	
								  	if (mysqli_num_rows($result) > 0) {
								  		while($row = mysqli_fetch_assoc($result)) {
											echo $row['DDividend']*$row['DPercentOut'];
								  		}
								  	} 
								  	else 
								  		echo "0";
								  	?>' disabled></h3>
			</div>
			<div class = "col-md-2">
			</div>
		</div>
		<div class='col-md-5 col-md-offset-1'>
			<div class = "col-md-4">
				<h3>Dividend</h3>
			</div>
			<div class = "col-md-6">
				<h3><input type="text" class="form-control" id="dividend1" style='text-align:right;' disabled></h3>
			</div>
			<div class = "col-md-2">
			</div>
		</div>
	</div>
	<div class='row'>
		<div class="col-md-5 col-md-offset-1">
			<br>
		</div>
		<div class='col-md-5 col-md-offset-1'>
			<div class = "col-md-4">
				<h3>Discount</h3>
			</div>
			<div class = "col-md-6">
				<h3><input type="text" class="form-control" id="discount" style='text-align:right;' value='<?php echo $discount;?>' disabled></h3>
			</div>
			<div class = "col-md-2">
			</div>
		</div>
	</div>
	<div class='row'>
		<div class="col-md-5 col-md-offset-1">
			<br>
		</div>
		<div class='col-md-5 col-md-offset-1'>
			<div class = "col-md-4">
				<h3>ส่วนได้ / เสีย ยกมา</h3>
			</div>
			<div class = "col-md-6">
				<h3><input type="text" class="form-control" id="unpaid" style='text-align:right;' value='
				<?php 
				$sql = "SELECT SUM(UNCurrentValue) AS money FROM unpaid AS UN INNER JOIN customer AS C ON UN.CustomerID = C.CustomerID WHERE C.Account = '$account'";
				$result = mysqli_query($conn, $sql);
					
				if (mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_assoc($result)) {
						echo number_format($row['money'] / ($multiplier*$volumeCurrent),5);
					}
				}
				mysqli_close($conn);
				?>' disabled></h3>
			</div>
			<div class = "col-md-2">
			</div>
		</div>
	</div>
	<div class='row'>
		<div class="col-md-5 col-md-offset-1">
			<br>
		</div>
		<div class='col-md-5 col-md-offset-1'>
			<div class = "col-md-4">
				<h3>Closing Price</h3>
			</div>
			<div class = "col-md-6">
				<h3><input type="text" class="form-control" id="closeprice" style='text-align:right;box-shadow:0 0 50px red;'></h3>
			</div>
			<div class = "col-md-2">
			</div>
		</div>
	</div>
	<div class='row'>
		<div class="col-md-12">
			
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
				<h3><button type="button" class="btn btn-lg btn-primary btn-block" id="calculate">Calculate</button></h3>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<br>
			<p class="text-center">----------------------------------------------------------</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
				<div class = "col-md-3">
					  <h3>Closing Date</h3>
				</div>
				<div class = "col-md-3">
					  <h3><input type="email" class="form-control" name = 'startdate' id='startdate' placeholder="e.g. 2015-1-31"></h3>
				</div>
				<div class = "col-md-3">
					  <h3>Closing Volume</h3>
				</div>
				<div class = "col-md-3">
					  <h3><input type="email" class="form-control" name = 'closeVol' id='closeVol' placeholder="Max Volume = <?php echo $volumeCurrent;?>"></h3>
				</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
				<form class="form-inline">
					<div class = "col-md-2">
						  <h3>UI (CA)</h3>
					</div>
					<div class = "col-md-2">
						  <h3><input type="email" class="form-control" id="underlying" style="text-transform: uppercase" placeholder="e.g. PTT"></h3>
					</div>
					<div class="col-sm-2">
					      	<h3><select class="form-control" id="series">
					      		<option>Select</option>
							  <?php $servername = "localhost";
								  	$username = "root";
								  	$password = "";
								  	$dbname = "blocktrade";
								  	
								  	// Create connection
								  	$conn = mysqli_connect($servername, $username, $password, $dbname);
								  	// Check connection
								  	if (!$conn) {
								  		die("Connection failed: " . mysqli_connect_error());
								  	}
								  	$sql = "SELECT SName,SLastTradingDay,Underlying FROM `serie` WHERE SLastTradingDay >= CURDATE() ORDER BY SLastTradingDay";
								  	$result = mysqli_query($conn, $sql);
								  	
								  	if (mysqli_num_rows($result) > 0) {
								  		while($row = mysqli_fetch_assoc($result)) {
											echo "<option value=". $row['SLastTradingDay'].">".$row['SName']."  ".$row['Underlying']."</option>";
								  		}
								  	} 
								  	mysqli_close($conn);
							?>
							</select></h3>
					</div>
				</form>
				<div class = "col-md-1">
					  <h3>Flag</h3>
				</div>
				<div class="col-sm-2">
					<h3><select class="form-control" id="flag">
						<option>Normal</option>
						<option>Flag</option>
					</select></h3>
				</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<h3><button type="button" class="btn btn-lg btn-primary btn-block" id="save">Save</button></h3>
		</div>
	</div>
	
</div>
</body>
<script type="text/javascript">
var multiplier = 0; 
$('#calculate').click(function(){
	if (document.getElementById("spot").value == ""){
		alert("ใส่ราคา <?php echo $underlying;?> ก่อน");
		return;
	}
	var Cprice = 0;
	var TotalInterest = 0;
	var hodingday = 0;
	var discount = parseFloat(document.getElementById("discount").value);
	if(document.getElementById("mindata").value > <?php echo $hodingDay;?>)
		hodingday = document.getElementById("mindata").value;
	else
    	hodingday = <?php echo $hodingDay;?>;
	TotalInterest = <?php echo $spot;?> * <?php echo $interest;?> / 100 * hodingday / 365;
	var upfront = <?php echo $upfront;?>;
	document.getElementById("upfrontInterest").value = upfront;
	var hodingInterest = 0;
	if (TotalInterest > document.getElementById("minInterest").value)
		hodingInterest = TotalInterest;
	else
		hodingInterest = parseFloat(document.getElementById("minInterest").value);
	document.getElementById("totalInterest").value = hodingInterest.toFixed(5);
	document.getElementById("netInterest").value = (upfront-hodingInterest.toFixed(5)).toFixed(5);
	var spot = 0;
	spot = parseFloat(document.getElementById("spot").value);
	var dividend = 0;
	dividend = parseFloat(document.getElementById("dividend").value);
	document.getElementById("dividend1").value= dividend;
	var unpaid = parseFloat(document.getElementById("unpaid").value);
	var position = <?php echo ($position=="Long") ? 1 : 0;?>;
	if(position == 1){
		Cprice = spot + upfront - hodingInterest + dividend - discount - unpaid;
	}
	if(position == 0){
		Cprice = spot - upfront + hodingInterest + dividend + discount + unpaid;
	}
	document.getElementById("closeprice").value = Cprice.toFixed(5);
});
$('#save').click(function(){
	var volume = document.getElementById("closeVol").value;
	if(volume > <?php echo $volumeCurrent;?>){
		alert("ห้ามใส่  Volume เกินจำนวน  <?php echo $volumeCurrent;?>");
		return;
	}
	var tranDate = document.getElementById("startdate").value;
	var position = "<?php echo ($position=="Long") ? "Short" : "Long";?>";
	var underlying;
	if(document.getElementById("underlying").value != "")
		underlying = (document.getElementById("underlying").value).toUpperCase();
	else
		underlying = "<?php echo $underlying;?>";
	var sel = document.getElementById("series");
	var serie = sel.options[sel.selectedIndex].text;
	if(serie == "Select")
		serie = "<?php echo $serie;?>";
	var futureName = "<?php echo $underlying.$serie;?>";
	var spot = document.getElementById("spot").value;
	var totalInterest = document.getElementById("totalInterest").value;
	var netInterest = document.getElementById("netInterest").value;
	var futurePrice = document.getElementById("closeprice").value;
	var dividend = parseFloat(document.getElementById("dividend").value);
	var unpaid = parseFloat(document.getElementById("unpaid").value);
	if(multiplier == 0)
		multiplier = <?php echo $multiplier;?>;
	var value = futurePrice*volume*multiplier;
	sel = document.getElementById("flag");
	var flag = sel.options[sel.selectedIndex].text;
	var account = "<?php echo $account;?>";
	if(flag == "Normal")
		flag = "N";
	else if(flag == "Flag")
		flag = "F";
	if ((position != "") && (tranDate != "") && (underlying != "") && (volume != "") && (spot != "") && (value != "") && (totalInterest != "") && (netInterest != "") && (futurePrice != "") && (flag != "")){
		$.ajax({
	        type: 'post',
	        url: 'saveClosePosition.php',
	        data: {position: position, tranDate: tranDate, underlying: underlying, futureName: futureName, volume: volume, spot: spot, value: value, totalInterest: totalInterest, netInterest: netInterest, futurePrice: futurePrice, flag: flag, serie: serie, multiplier: multiplier,dividend: dividend, account: account, unpaid: unpaid, CTOID: <?php echo $CTOID;?>},
	        success: function( data ) {
	        	alert( data );
	        	//redirect to member.php
	            window.location.href = "index.php";
	        }
	    });
	}
	else{
		alert("กด Calculate ก่อน");
	}
});
$('#series').on('change', function(){
	var sel = document.getElementById("series");
	var multi = sel.options[sel.selectedIndex].text;
	$.ajax({
        type: 'post',
        url: 'transactionSave.php',
        data: {multi: multi},
        success: function( data ) {
        	multiplier = data;
        }
    });
});
$(document).keypress(function(e) {
    if(e.which == 13) {
    	if (document.getElementById("spot").value == ""){
    		alert("ใส่ราคา <?php echo $underlying;?> ก่อน");
    		return;
    	}
    	var Cprice = 0;
    	var TotalInterest = 0;
    	var hodingday = 0;
    	var discount = parseFloat(document.getElementById("discount").value);
    	if(document.getElementById("mindata").value > <?php echo $hodingDay;?>)
    		hodingday = document.getElementById("mindata").value;
    	else
        	hodingday = <?php echo $hodingDay;?>;
    	TotalInterest = <?php echo $spot;?> * <?php echo $interest;?> / 100 * hodingday / 365;
    	var upfront = <?php echo $upfront;?>;
    	document.getElementById("upfrontInterest").value = upfront;
    	var hodingInterest = 0;
    	if (TotalInterest > document.getElementById("minInterest").value)
    		hodingInterest = TotalInterest;
    	else
    		hodingInterest = parseFloat(document.getElementById("minInterest").value);
    	document.getElementById("totalInterest").value = hodingInterest.toFixed(5);
    	document.getElementById("netInterest").value = (upfront-hodingInterest.toFixed(5)).toFixed(5);
    	var spot = 0;
    	spot = parseFloat(document.getElementById("spot").value);
    	var dividend = 0;
    	dividend = parseFloat(document.getElementById("dividend").value);
    	document.getElementById("dividend1").value= dividend;
    	var unpaid = parseFloat(document.getElementById("unpaid").value);
    	var position = <?php echo ($position=="Long") ? 1 : 0;?>;
    	if(position == 1){
    		Cprice = spot + upfront - hodingInterest + dividend - discount - unpaid;
    	}
    	if(position == 0){
    		Cprice = spot - upfront + hodingInterest + dividend + discount + unpaid;
    	}
    	document.getElementById("closeprice").value = Cprice.toFixed(5);

    }
});
</script>
<script>
$(document).ready(function(){
	var defaultdate = new Date();
	var dd = defaultdate.getDate();
	var mm = defaultdate.getMonth()+1; //January is 0!
	var yyyy = defaultdate.getFullYear();

	if(dd<10) {
	    dd='0'+dd
	} 

	if(mm<10) {
	    mm='0'+mm
	} 

	defaultdate = yyyy+'-'+mm+"-"+dd;
	document.getElementById("startdate").value = defaultdate;
});
	$(function() {
		$( "#startdate" ).datepicker({ dateFormat: "yy-mm-dd" });
	});
</script>
</html>