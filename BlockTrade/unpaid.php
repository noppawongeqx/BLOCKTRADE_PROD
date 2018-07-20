<!DOCTYPE html>
<html>
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
require 'menu.php';?>
<div class = "container">
	<div class = "row">
		<div class = "col-md-12">
				<div class = "col-md-2">
					  <h3>เลชที่บัญชี </h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" id="account" placeholder="e.g. 9901070"></h3>
				</div>
				<div class = "col-md-1">
				</div>
				<div class = "col-md-3">
					  <h3>วันที่เกิด</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" name = 'startdate' id='startdate'></h3>
				</div>
		</div>
		<div class = "col-md-12">
				<div class = "col-md-2">
					  <h3>จำนวน</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" id="amount"></h3>
				</div>
				<div class = "col-md-1"><h3>บาท</h3>
				</div>
				<div class = "col-md-3">
					  <h3>สาเหตุ</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" name='reason' id='reason' placeholder="มีต้นทุนค่า Short"></h3>
				</div>
		</div>
		<div class="col-md-6 col-md-offset-3">
				<h3><button type="button" id="senddata" class="btn btn-primary btn-lg btn-block">Save</button></h3>
		</div>
	</div>
	<div class = "row">
		<div class="col-md-12">
			<table class="table">
		      <caption><h3>Current Data</h3></caption>
		      <thead>
		        <tr>
		          <th>เลขบัญชี</th>
		          <th>วันที่เกิด</th>
		          <th>วันที่รับ / จ่าย</th>
		          <th>มูลค่าเริ่มต้น</th>
		          <th>มูลค่าปัจจุบัน</th>
		          <th>สาเหตุ</th>
		          <th>ลบ</th>
		        </tr>
		      </thead>
		      <tbody>
		      <?php $servername = "localhost";
					$username = "root";
					$password = "";
					$dbname = "blocktrade";
										  	
					// Create connection
					$conn = mysqli_connect($servername, $username, $password, $dbname);
					mysqli_set_charset($conn,"utf8");
					// Check connection
					if (!$conn) {
						die("Connection failed: " . mysqli_connect_error());
					}
					$sql = "SELECT UN.UNID,C.Account,UN.UNTranDate,UN.UNPaidDate,UN.UNStartValue,UN.UNCurrentValue,UN.UNEvent from unpaid as UN INNER JOIN customer AS C ON UN.CustomerID = C.CustomerID";
					$result = mysqli_query($conn, $sql);
										  	
					if (mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_assoc($result)) {
							$Newformat = strtotime($row['UNTranDate']);
							$myFormatForView = date("d/m/Y", $Newformat);
							$Newformat1 = strtotime($row['UNPaidDate']);
							$year=date("Y",$Newformat1);
							if($year == 1970)
								$myFormatForView1 = "-";
							else
								$myFormatForView1 = date("d/m/Y", $Newformat1);
							echo "<tr>";
							echo "<td>".$row['Account']."</td><td>".$myFormatForView."</td><td>". $myFormatForView1."</td><td>".$row['UNStartValue']."</td><td>".$row['UNCurrentValue']."</td><td>".$row['UNEvent']."</td>";
							echo 	"<td>
		  								<button class='btn btn-info' value='".$row['UNID']. "' onClick='finddata(this.value)'>Delete</button>
		  							</td>";
		    				echo "</tr>";
						}
					} 
					mysqli_close($conn);
				?>
		      </tbody>
		    </table>
		</div>
	</div>
</div>
</body>
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
$('#senddata').click(function(){
	var account = (document.getElementById("account").value);
	var trandate = document.getElementById("startdate").value;
	var amount = document.getElementById("amount").value;
	var reason = (document.getElementById("reason").value);
	if ((account != "") && (trandate != "")){
	    $.ajax({
	        type: 'post',
	        url: 'unpaidsave.php',
	        data: {account: account, trandate: trandate, amount: amount, reason: reason},
	        success: function( data ) {
	        	alert( data );
	        	//redirect to member.php
	            window.location.href = "unpaid.php";
	            document.getElementById("account").value = "";
	            document.getElementById("startdate").value = "";
	        }
	    });
	}
	else {
		alert("ใส่เลขที่บัญชีด้วยครับ");
	}
    
});

</script>
<script>
function finddata(value){
	$.ajax({
        type: 'post',
        url: 'unpaidsave.php',
        data: {deletedata: value},
        success: function( data ) {
        	alert( data );
        	window.location.href = "unpaid.php";
        }
    });
}
</script>
</html>