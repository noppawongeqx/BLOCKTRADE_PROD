<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blocktrade";
	
$dividend = array();
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT XDDate,DDividend,DPercentOut,DStock FROM `dividend`";
$result = mysqli_query($conn, $sql);
	
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$dividend[$row['DStock']]['Dividend'] = $row['DDividend'];
		$dividend[$row['DStock']]['XD'] = $row['XDDate'];
		$dividend[$row['DStock']]['PercentOut'] = $row['DPercentOut'];
	}
}
else
	echo "0";
mysqli_close($conn);
var_dump($dividend);
echo "<br>";
echo $dividend['BTS']['Dividend'];

?>