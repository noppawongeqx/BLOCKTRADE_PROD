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
require 'menu.php';

	$min = array();
	$max = array();
	$tick = array();
	
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
  	$sql = "SELECT Min,Max,Tick FROM `tick`";
  	$result = mysqli_query($conn, $sql);
  	
  	if (mysqli_num_rows($result) > 0) {
  		while($row = mysqli_fetch_assoc($result)) {
			$min[] = $row['Min'];
			$max[] = $row['Max'];
			$tick[] = $row['Tick'];
  		}
  	} 
  	mysqli_close($conn);
?>
<div class = "container">
	<div class = "row">
		<div class = "col-md-6">
				<div class = "col-md-4">
					  <label><h1><input type="radio" name="inlineRadioOptions" id="long" value="option1" checked="checked"> <span class="label label-success">Long</span></h1> </label>
				</div>
				<div class = "col-md-4">
					  <label><h1><input type="radio" name="inlineRadioOptions" id="short" value="option2"> <span class="label label-danger">Short</span></h1></label>
				</div>
		</div>
		<div class="col-md-6">
			<div class = "col-md-6">
					  <h3>จำนวนวันคงเหลือ </h3>
				</div>
				<div class = "col-md-6">
					  <h3></h3><input type="text" class="form-control" id="dateleft" style='text-align:right;' disabled></h3>
				</div>
		
		</div>
	</div>
	<div class = "row">
		<div class = "col-md-6">
				<div class = "col-md-3">
						  <h3>UI</h3>
					</div>
					<div class = "col-md-5">
						  <h3></h3><input type="email" class="form-control" id="underlying" style="text-transform: uppercase" autofocus></h3>
					</div>
					<div class="col-md-4">
					      	<h3><select class="form-control" id="series">
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
		</div>
		<div class="col-md-6">
			<div class = "col-md-6">
					  <h3>ราคา </h3>
				</div>
				<div class = "col-md-6">
					  <h3></h3><input type="text" class="form-control" id="spotprice" style='text-align:right;' disabled></h3>
				</div>
		</div>
	</div>
	<div class = "row">
		<div class = "col-md-6">
				<div class = "col-md-3">
					  <h3>Price</h3>
				</div>
				<div class = "col-md-6">
					  <h3></h3><input type="text" class="form-control" id="spot"></h3>
				</div>
				<div class = "col-md-3">
					  <h3>บาท</h3>
				</div>
		</div>
		<div class="col-md-6">
			<div class = "col-md-6">
					  <h3>ดอกเบี้ยมัดจำ </h3>
				</div>
				<div class = "col-md-6">
					  <h3></h3><input type="text" class="form-control" id="interest1" style='text-align:right;' disabled></h3>
				</div>
		
		</div>
	</div>
	<div class = "row">
		<div class = "col-md-6">
				<div class = "col-md-4">
					  <h3>ดอกเบี้ยมัดจำ</h3>
				</div>
				<div class = "col-md-6">
					  <label class="radio-inline"><div class="col-md-6"><h3><input type="radio" name="inlineRadio" id="percent" value="op1" checked="checked"> Percent</h3></div><div class="col-md-6"> <h3><input type="email" class="form-control" id="percentvalue"></h3></div></label>
				</div>
				<div class = "col-md-2">
					  <h3>%</h3>
				</div>
		</div>
		<div class="col-md-6">
			<div class = "col-md-6">
					  <h3>Discount </h3>
				</div>
				<div class = "col-md-6">
					  <h3></h3><input type="text" class="form-control" id="discountdividend" style='text-align:right;' disabled></h3>
				</div>
		
		</div>
	</div>
	<div class = "row">
		<div class = "col-md-6">
				<div class = "col-md-6 col-md-offset-4">
					  <label class="radio-inline"><div class="col-md-6"><h3><input type="radio" name="inlineRadio" id="tick" value="op2"> Tick</h3></div><div class="col-md-6"> <h3> <input type="email" class="form-control" id="tickvalue"> </h3></div></label>
				</div>
				<div class = "col-md-2">
					  <h3>บาท</h3>
				</div>
		</div>
		<div class="col-md-6">
			<div class = "col-md-6">
					  <h3>ราคาเปิดสัญญา </h3>
				</div>
				<div class = "col-md-6">
					  <h3></h3><input type="text" class="form-control" id="openprice" style='text-align:right;box-shadow:0 0 50px red;'></h3>
				</div>
		
		</div>
	</div>
	<div class='row'>
		<div class = "col-md-6">
				<div class = "col-md-4">
					  <h3>ดอกเบี้ยทั้งหมด </h3>
				</div>
				<div class = "col-md-5">
					  <h3></h3><input type="email" class="form-control" id="interest3"></h3>
				</div>
				<div class = "col-md-3"><h3>% </h3>
				</div>
		</div>
		<div class="col-md-6">
			<div class = "col-md-6">
					  <h3>ดอกเบี้ยทั้งหมด </h3>
				</div>
				<div class = "col-md-6">
					  <h3></h3><input type="text" class="form-control" id="totalinterest" style='text-align:right;' disabled></h3>
				</div>
		</div>
	</div>
	<div class='row'>
		<div class = "col-md-6">
				<div class = "col-md-4">
					  <h3>จำนวนวันขั้นต่ำ </h3>
				</div>
				<div class = "col-md-5">
					  <h3></h3><input type="email" class="form-control" id="mindate"></h3>
				</div>
				<div class = "col-md-3"><h3>วัน </h3>
				</div>
		</div>
		<div class="col-md-6">
			<br>
		</div>
	</div>
	<div class='row'>
		<div class = "col-md-6">
				<div class = "col-md-4">
					  <h3>จำนวนดอกขั้นต่ำ </h3>
				</div>
				<div class = "col-md-5">
					  <h3></h3><input type="email" class="form-control" id="minbaht"></h3>
				</div>
				<div class = "col-md-3"><h3>บาท </h3>
				</div>
		</div>
		<div class="col-md-6">
			<br>
		</div>
	</div>
	<div class='row'>
		<div class = "col-md-6">
				<div class = "col-md-4">
					  <h3>Discount</h3>
				</div>
				<div class = "col-md-5">
					  <h3></h3><input type="text" class="form-control" name = 'discount' id='discount'></h3>
				</div>
				<div class = "col-md-3"> <h3>บาท</h3>
				</div>
		</div>
		<div class="col-md-6">
			<br>
		</div>
	</div>
	<div class='row'>
		<div class = "col-md-6">
				<div class = "col-md-4">
					  <h3>Date</h3>
				</div>
				<div class = "col-md-5">
					  <h3></h3><input type="email" class="form-control" name = 'startdate' id='startdate'></h3>
				</div>
				<div class = "col-md-3">
				</div>
		</div>
		<div class="col-md-6">
			<br>
		</div>
	</div>
	<div class = "row">
		<div class="col-md-6 col-md-offset-3">
			<h3><button type="button" id="calculate" class="btn btn-info btn-lg btn-block">Calculate</button></h3>
		</div>
	</div>
	<div class="row">
		<div class = "col-md-12">
				<div class = "col-md-2">
					  <h3>เลขที่บัญชี</h3>
				</div>
				<div class = "col-md-2">
					  <h3><input type="email" class="form-control" id="account"></h3>
				</div>
				<div class = "col-md-1">
					  <h3>Vol </h3>
				</div>
				<div class = "col-md-1">
					  <h3></h3><input type="text" class="form-control" id="contract" ></h3>
				</div>
				<div class = "col-md-1"><h3>สัญญา </h3>
				</div>
				<div class = "col-md-1">
				</div>
				<div class = "col-md-1">
					  <h3>ตัวคูณ </h3>
				</div>
				<div class = "col-md-2">
					  <h3><input type="email" class="form-control" id="multiplier" disabled></h3>
				</div>
				
		</div>
		<div class="col-md-6 col-md-offset-3">
			<h3><button type="button" id="senddata" class="btn btn-primary btn-lg btn-block">Save</button></h3>
		</div>
	</div>
</div>
</body>
<script>
var min = <?php echo json_encode($min) ?>;
var max = <?php echo json_encode($max) ?>;
var tick = <?php echo json_encode($tick) ?>;
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
	document.getElementById("multiplier").value = 1000;
	document.getElementById("mindate").value = 3;
	document.getElementById("minbaht").value = 0.003;
	document.getElementById("discount").value = 0;
	document.getElementById("interest3").value = 4.5;
	$('.navbar').next('.container').css('background-color','rgb(130, 241, 130)');
		
	
});
$('#long').click(function(){
	$('.navbar').next('.container').css('background-color','rgb(130, 241, 130)');
	document.getElementById("interest3").value = 4.5;
});
$('#short').click(function(){
	$('.navbar').next('.container').css('background-color','rgb(220, 126, 123)');
	document.getElementById("interest3").value = 5.5;
});
$('#calculate').click(function(){
	var min = <?php echo json_encode($min) ?>;
	var max = <?php echo json_encode($max) ?>;
	var tick = <?php echo json_encode($tick) ?>;
	var sel = document.getElementById("series");
	var serie = sel.options[sel.selectedIndex].text;
	var LTD = new Date($('#series').val());
	var discount = document.getElementById("discount").value;
	var transactionDay = new Date(document.getElementById("startdate").value);
	var diff = new Date(LTD-transactionDay);
	var days = diff/1000/60/60/24;
	
	if (days <= document.getElementById("mindate").value){
		days = document.getElementById("mindate").value;
	}
	document.getElementById("dateleft").value = days;
	document.getElementById("spotprice").value = document.getElementById("spot").value;
	document.getElementById("discountdividend").value = discount;
	//var ticksize = parseFloat(document.getElementById("tick"));
	if(document.getElementById("percent").checked){
		document.getElementById("interest1").value = (document.getElementById("spot").value * (document.getElementById("percentvalue").value/100) * days / 365).toFixed(5);
	}
	else if(document.getElementById("tick").checked){
		document.getElementById("interest1").value = parseFloat(document.getElementById("tickvalue").value);
	}
	var positionlong = false;
	var positionshort = false;
	if(document.getElementById("long").checked){
		document.getElementById("openprice").value = (parseFloat(document.getElementById("spot").value) + parseFloat(document.getElementById("interest1").value) - parseFloat(document.getElementById("discount").value)).toFixed(5);
		positionlong = true;
	}
	else if(document.getElementById("short").checked){
		document.getElementById("openprice").value = (parseFloat(document.getElementById("spot").value) - parseFloat(document.getElementById("interest1").value) + parseFloat(document.getElementById("discount").value)).toFixed(5);
		positionshort = true;
	}
	document.getElementById("totalinterest").value = (document.getElementById("spot").value * (document.getElementById("interest3").value/100) * days / 365).toFixed(5);
	if(positionlong){
		alert("Customer Open Long "+document.getElementById("underlying").value+serie+" and Spot = "+document.getElementById("spot").value) ;
	}else{
		alert("Customer Open Short "+document.getElementById("underlying").value+serie+" and Spot = "+document.getElementById("spot").value);
	}
});
$('#senddata').click(function(){
	//document.getElementById("senddata").disabled = true;
	var position;
	if(document.getElementById("long").checked){
		position = "Long";
	}
	else if(document.getElementById("short").checked){
		position = "Short";
	}
	var tranDate = document.getElementById("startdate").value;
	var underlying = (document.getElementById("underlying").value).toUpperCase();
	var volume = document.getElementById("contract").value;
	var cost = document.getElementById("spot").value;
	var value = document.getElementById("openprice").value * document.getElementById("contract").value * document.getElementById("multiplier").value;
	var upfront = document.getElementById("interest1").value;
	var totalint = document.getElementById("totalinterest").value;
	var mindate = document.getElementById("mindate").value;
	var minbaht = document.getElementById("minbaht").value;
	var acccount = document.getElementById("account").value;
	var percentInterest = document.getElementById("interest3").value;
	var percentUpfront = document.getElementById("percentvalue").value;
	var multiplier = document.getElementById("multiplier").value;
	var futurePrice = document.getElementById("openprice").value;
	var discount = document.getElementById("discountdividend").value;
	var sel = document.getElementById("series");
	var serie = sel.options[sel.selectedIndex].text;
	if ((position != "") && (tranDate != "") && (underlying != "") && (volume != "") && (cost != "") && (value != "") && (upfront != "") && (totalint != "") && (mindate != "") && (acccount != "")){
	    $.ajax({
	        type: 'post',
	        url: 'transactionSave.php',
	        data: {position: position, tranDate: tranDate, underlying: underlying, volume: volume, spot: cost, value: value, upfront: upfront, totalint: totalint, mindate: mindate, minbaht: minbaht, acccount: acccount, serie: serie, percentInterest: percentInterest, multiplier: multiplier, percentUpfront: percentUpfront, futurePrice: futurePrice, discount: discount},
	        success: function( data ) {
	        	document.getElementById("senddata").disabled = false;
	        	alert( data );
	        	//redirect to member.php
	            window.location.href = "index.php";
	        }
	    });
	}
	else{
		alert("กรุณากรอกข้อมูลให้ครบ หรือ กด Calculate ก่อน");
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
        	document.getElementById("multiplier").value = data;
        }
    });
});
$(document).keypress(function(e) {
    if(e.which == 13) {
    	var min = <?php echo json_encode($min) ?>;
    	var max = <?php echo json_encode($max) ?>;
    	var tick = <?php echo json_encode($tick) ?>;
    	var sel = document.getElementById("series");
    	var serie = sel.options[sel.selectedIndex].text;
    	var LTD = new Date($('#series').val());
    	var discount = document.getElementById("discount").value;
    	var transactionDay = new Date(document.getElementById("startdate").value);
    	var diff = new Date(LTD-transactionDay);
    	var days = diff/1000/60/60/24;
    	if (days <= document.getElementById("mindate").value){
    		days = document.getElementById("mindate").value;
    	}
    	document.getElementById("dateleft").value = days;
    	document.getElementById("spotprice").value = document.getElementById("spot").value;
    	document.getElementById("discountdividend").value = discount;
    	//var ticksize = parseFloat(document.getElementById("tick"));
    	if(document.getElementById("percent").checked){
    		document.getElementById("interest1").value = (document.getElementById("spot").value * (document.getElementById("percentvalue").value/100) * days / 365).toFixed(5);
    	}
    	else if(document.getElementById("tick").checked){
    		document.getElementById("interest1").value = parseFloat(document.getElementById("tickvalue").value);
    	}
    	if(document.getElementById("long").checked){
    		document.getElementById("openprice").value = (parseFloat(document.getElementById("spot").value) + parseFloat(document.getElementById("interest1").value) - parseFloat(document.getElementById("discount").value)).toFixed(5);
    	}
    	else if(document.getElementById("short").checked){
    		document.getElementById("openprice").value = (parseFloat(document.getElementById("spot").value) - parseFloat(document.getElementById("interest1").value) + parseFloat(document.getElementById("discount").value)).toFixed(5);
    	}
    	document.getElementById("totalinterest").value = (document.getElementById("spot").value * (document.getElementById("interest3").value/100) * days / 365).toFixed(5);
    }
});
</script>
</html>