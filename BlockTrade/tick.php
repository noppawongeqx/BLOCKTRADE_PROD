<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Language" content="th"> 
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
<title>Add Tick Size</title>
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
					  <h3>Min</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" id='min' placeholder="e.g. 0 2"></h3>
				</div>
				<div class = "col-md-2">
					  <h3>Max</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="text" class="form-control" id="max" placeholder="e.g. 2 5"></h3>
				</div>
				<div class = "col-md-2">
					  <h3>Tick</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" id="tick" placeholder="e.g. 0.01 0.02"></h3>
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
		          <th>Min</th>
		          <th>Max</th>
		          <th>Tick Size</th>
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
					$sql = "SELECT TickID,Min,Max,Tick FROM `tick` ORDER BY Tick";
					$result = mysqli_query($conn, $sql);
										  	
					if (mysqli_num_rows($result) > 0) {
						while($row = mysqli_fetch_assoc($result)) {
							echo "<tr>";
							echo "<td>".$row['Min']."</td><td>".$row['Max']."</td><td>". $row['Tick']."</td>";
							echo 	"<td>
		  								<button class='btn btn-info' value='".$row['TickID']. "' onClick='finddata(this.value)'>Delete</button>
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
	var min = document.getElementById("min").value;
	var max = document.getElementById("max").value;
	var tick = document.getElementById("tick").value;
	if ((min != "") && (max != "") && (tick != "")){
	    $.ajax({
	        type: 'post',
	        url: 'tickSave.php',
	        data: {min: min, max: max, tick: tick},
	        success: function( data ) {
	        	alert( data );
	        	//refresh page
	        	location.reload();
	        }
	    });
	}
	else {
		alert("กรุณากรอกข้อมูลให้ครบ");
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
        	window.location.href = "tickSave.php";
        }
    });
}
</script>
</html>