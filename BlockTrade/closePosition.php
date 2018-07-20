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
				<div class = "col-md-3">
					  <h3>Account</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" id="account" placeholder="e.g. 6156666" autofocus><ul id="search_suggestion_holder"></ul></h3>
				</div>
				<div class = "col-md-1">
				</div>
				<div class = "col-md-3">
					  <h3>Closing Date</h3>
				</div>
				<div class = "col-md-2">
					  <h3></h3><input type="email" class="form-control" name = 'startdate' id='startdate' placeholder="e.g. 2015-1-31"></h3>
				</div>
				
		</div>
		<div class="col-md-6 col-md-offset-3">
					<h3><button type="button" id="search" class="btn btn-primary btn-lg btn-block" onclick="searchResult()">Search</button></h3>
		</div>
	</div>
	<div id="resulthere"></div>
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

	var closedate = document.getElementById("startdate").value;
	$.ajax({
        type: 'post',
        url: 'getResult.php',
        data: {closedate: closedate},
        success: function( data ) {
        	document.getElementById("resulthere").innerHTML = data;
        }
    });
    
	
});
function searchResult(){
	var account = document.getElementById("account").value;
	var closedate = document.getElementById("startdate").value;
	if(account != ""){
		$.ajax({
	        type: 'post',
	        url: 'getResult.php',
	        data: {account: account, closedate: closedate},
	        success: function( data ) {
	        	document.getElementById("resulthere").innerHTML = data;
	        }
	    });
	}
	else {
		$.ajax({
	        type: 'post',
	        url: 'getResult.php',
	        data: {closedate: closedate},
	        success: function( data ) {
	        	document.getElementById("resulthere").innerHTML = data;
	        }
	    });
	}
}
$(document).keypress(function(e) {
    if(e.which == 13) {
    	var account = document.getElementById("account").value;
    	var closedate = document.getElementById("startdate").value;
    	if(account != ""){
    		$.ajax({
    	        type: 'post',
    	        url: 'getResult.php',
    	        data: {account: account, closedate: closedate},
    	        success: function( data ) {
    	        	document.getElementById("resulthere").innerHTML = data;
    	        }
    	    });
    	}
    	else {
    		$.ajax({
    	        type: 'post',
    	        url: 'getResult.php',
    	        data: {closedate: closedate},
    	        success: function( data ) {
    	        	document.getElementById("resulthere").innerHTML = data;
    	        }
    	    });
    	}
    }
});
</script>
</html>