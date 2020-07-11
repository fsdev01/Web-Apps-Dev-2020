<html>
<body>


<?php 

$DBConnect = @mysqli_connect("localhost", "root","", "test")
		Or die ("<p>Unable to connect to the database server.</p>". "<p>Error code ". mysqli_connect_errno().": ". mysqli_connect_error()). "</p>";

// set up the SQL query string - we will retrieve the whole
// record that matches the name


$SQLstring = "select * from inventory";
$queryResult = @mysqli_query($DBConnect, $SQLstring)
		Or die ("<p>Unable to query the inventory table.</p>"."<p>Error code ". mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";




echo "<table width='100%' border='1'>";
echo "<th>Make</th><th>Model</th><th>Price</th><th>Quantity</th>";
	$row = mysqli_fetch_row($queryResult);
	
	while ($row) {
		echo "<tr><td>{$row[1]}</td>";
		echo "<td>{$row[2]}</td>";
		echo "<td>{$row[3]}</td>";
		echo "<td>{$row[4]}</td></tr>";
		$row = mysqli_fetch_row($queryResult);
	}
	echo "</table>";


	mysqli_close($DBConnect);

?>


</body>
</html>