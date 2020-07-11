<html>
<body>

<H3>List employees who have experience in a programming language.<br/></H3>

<?php 

$DBConnect = @mysqli_connect("localhost", "root","", "test")
		Or die ("<p>Unable to connect to the database server.</p>". "<p>Error code ". mysqli_connect_errno().": ". mysqli_connect_error()). "</p>";

// set up the SQL query string - we will retrieve the whole
// record that matches the name

// get language names from db
$TableName = "languages";

$SQLstring = "select language from languages";
$queryResult = @mysqli_query($DBConnect, $SQLstring)
		Or die ("<p>Unable to query the $TableName table.</p>"."<p>Error code ". mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";


echo "<form>Please select a language: <select name='language'>";
	
$row = mysqli_fetch_row($queryResult);
	
	while ($row) {
		echo "<option value='".$row[0]."'>".$row[0]."</option>";	
		$row = mysqli_fetch_row($queryResult);
	}


echo "</select><br/>Please input the minimum year required: <input type='text' name='year'/><input type='submit' value='Search'/></form>";

mysqli_close($DBConnect);



//if a language is selected, get data from table 
if(isset($_GET['language']))
{

$DBConnect = @mysqli_connect("localhost", "root","", "test")
		Or die ("<p>Unable to connect to the database server.</p>". "<p>Error code ". mysqli_connect_errno().": ". mysqli_connect_error()). "</p>";

	if(isset($_GET['year']) && $_GET['year']>0 )
	{
	
		$SQLstring = "select e.first_name,e.last_name,l.language,x.years FROM employees e, experience x,languages l where e.employee_id=x.employee_id and x.language_id = l.language_id and l.language='".$_GET['language']."' and x.years>=".$_GET['year'];

	}
	else
	{
	$SQLstring = "select e.first_name,e.last_name,l.language,x.years FROM employees e, experience x,languages l where e.employee_id=x.employee_id and x.language_id = l.language_id and language='".$_GET['language']."'";
	}
// perform the query, storing the result
$queryResult = @mysqli_query($DBConnect, $SQLstring)
		Or die ("<p>Unable to query the $TableName table.</p>"."<p>Error code ". mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";

echo "<p>List of Employees who have at least ", $_GET['year'], " years in ", $_GET['language'], ".</p>";

echo "<table width='100%' border='1'>";
echo "<th>First Name</th><th>Last Name</th><th>Language</th><th>Year</th>";
	$row = mysqli_fetch_row($queryResult);
	
	while ($row) {
		echo "<tr><td>{$row[0]}</td>";
		echo "<td>{$row[1]}</td>";
		echo "<td>{$row[2]}</td>";
		echo "<td>{$row[3]}</td></tr>";
		$row = mysqli_fetch_row($queryResult);
	}
	echo "</table>";


	mysqli_close($DBConnect);
}
?>

</body>
</html>