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
<title>Add Dividend</title>
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
					  <h3>XD Date</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" name = 'startdate' id='startdate' placeholder="e.g. 2015-1-31"></h3>
				</div>
				<div class = "col-md-2">
					  <h3>Stock</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="text" class="form-control" id="stock" style="text-transform: uppercase" placeholder="e.g. PTT"></h3>
				</div>
				<div class = "col-md-2">
					  <h3>Dividend/share</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" id="dividend"></h3>
				</div>
		</div>
		<div class = "col-md-12">
				
				<div class = "col-md-2">
					  <h3>NO. of stock</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" name='volume' id='volume' placeholder="e.g. 10000"></h3>
				</div>
				<div class = "col-md-2">
					  <h3>% Cash Out</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="text" class="form-control" name='perout' id='perout' placeholder="e.g. 0.9"></h3>
				</div>
				<div class = "col-md-2">
					  <h3>Position</h3>
				</div>
				<div class = "col-md-2">
					  <h3><h3><select class="form-control" id="position"><option>BUY</option><option>SELL</option></select></h3></h3>
				</div>
		</div>
		<div class="col-md-6 col-md-offset-3">
				<h3><button type="button" id="senddata" class="btn btn-primary btn-lg btn-block">Save</button></h3>
		</div>
	</div>
	<div class = "row">
		<div class="col-md-8">
			<table class="table">
		      <caption>Current Data</caption>
		      <thead>
		        <tr>
		          <th>XD Date</th>
		          <th>Stock</th>
		          <th>Dividend</th>
		          <th>% Cash out</th>
		          <th>Start Volume</th>
		          <th>Current Volume</th>
		          <th>Company Position</th>
		          <th>Delete</th>
		        </tr>
		      </thead>
		      <tbody>
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
					$sql = "SELECT DID,XDDate,DStock,DDividend,DPercentOut,DVolume,DCurrentVolume,DCompanyPosition FROM `dividend` ORDER BY DStock";
					$result = mysqli_query($conn, $sql);
										  	
					if (mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_assoc($result)) {
							$Newformat = strtotime($row['XDDate']);
							$myFormatForView = date("d/m/Y", $Newformat);
							echo "<tr>";
							echo "<td>".$myFormatForView."</td><td>".$row['DStock']."</td><td>". $row['DDividend']."</td><td>". $row['DPercentOut']."</td><td>".number_format($row['DVolume'])."</td><td>".number_format($row['DCurrentVolume'])."</td><td>".$row['DCompanyPosition']."</td>";
							echo 	"<td>
		  								<button class='btn btn-info' value='".$row['DID']. "' onClick='finddata(this.value)'>Delete</button>
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
$('#senddata').click(function(){
	var Stock = (document.getElementById("stock").value).toUpperCase();
	var XDDate = document.getElementById("startdate").value;
	var dividend = document.getElementById("dividend").value;
	var volume = document.getElementById("volume").value;
	var perout = document.getElementById("perout").value;
	var sel = document.getElementById("position");
	var position = sel.options[sel.selectedIndex].text;
	if ((Stock != "") && (XDDate != "") && (dividend != "") && (volume != "")){
	    $.ajax({
	        type: 'post',
	        url: 'addDividendSave.php',
	        data: {Stock: Stock, XDDate: XDDate,perout: perout, dividend: dividend, volume: volume, position: position},
	        success: function( data ) {
	        	alert( data );
	        	//redirect to member.php
	            window.location.href = "addDividend.php";
	            document.getElementById("Sname").value = "";
	            document.getElementById("startdate").value = "";
	        }
	    });
	}
	else {
		alert("��سҡ�͡���������ú");
	}
    
});

</script>
<script>
function finddata(value){
	$.ajax({
        type: 'post',
        url: 'addDividendSave.php',
        data: {deletedata: value},
        success: function( data ) {
        	alert( data );
        	window.location.href = "addDividend.php";
        }
    });
}
</script>
</html>