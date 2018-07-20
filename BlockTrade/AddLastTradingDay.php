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
					  <h3>Serie Name </h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" id="Sname" style="text-transform: uppercase" placeholder="e.g. M15,U15"></h3>
				</div>
				<div class = "col-md-1">
				</div>
				<div class = "col-md-3">
					  <h3>Last Trading Day</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" name = 'startdate' id='startdate' placeholder="e.g. 2015-1-31"></h3>
				</div>
		</div>
		<div class = "col-md-12">
				<div class = "col-md-2">
					  <h3>Multiplier</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" id="multiplier"></h3>
				</div>
				<div class = "col-md-1">
				</div>
				<div class = "col-md-3">
					  <h3>Underlying (CA Case)</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" name='underlying' id='underlying' style="text-transform: uppercase" placeholder="e.g. TRUE , BBL"></h3>
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
		          <th>Serie Name</th>
		          <th>Underlying</th>
		          <th>Last Trading Day</th>
		          <th>Multiplier</th>
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
					$sql = "SELECT SerieID,SName,SLastTradingDay,Underlying,SMultiplier FROM `serie` WHERE SLastTradingDay >= CURDATE() ORDER BY SLastTradingDay";
					$result = mysqli_query($conn, $sql);
										  	
					if (mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_assoc($result)) {
							$Newformat = strtotime($row['SLastTradingDay']);
							$myFormatForView = date("d/m/Y", $Newformat);
							echo "<tr>";
							echo "<td>".$row['SName']."</td><td>".$row['Underlying']."</td><td>". $myFormatForView."</td><td>".$row['SMultiplier']."</td>";
							echo 	"<td>
		  								<button class='btn btn-info' value='".$row['SerieID']. "' onClick='finddata(this.value)'>Delete</button>
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
	document.getElementById("multiplier").value = 1000;
});
$('#senddata').click(function(){
	var SName = (document.getElementById("Sname").value).toUpperCase();
	var LTD = document.getElementById("startdate").value;
	var multiplier = document.getElementById("multiplier").value;
	var underlying = (document.getElementById("underlying").value).toUpperCase();
	if ((SName != "") && (LTD != "")){
	    $.ajax({
	        type: 'post',
	        url: 'AddLastTradingDaySave.php',
	        data: {SName: SName, LTD: LTD, multiplier: multiplier, underlying: underlying},
	        success: function( data ) {
	        	alert( data );
	        	//redirect to member.php
	            window.location.href = "AddLastTradingDay.php";
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
        url: 'AddLastTradingDaySave.php',
        data: {deletedata: value},
        success: function( data ) {
        	alert( data );
        	window.location.href = "AddLastTradingDay.php";
        }
    });
}
</script>
</html>