<html>
<body>

<H3>List employees who have experience in a programming language.<br/></H3>

<?php 
	// LAB 4 - ECERCISE F - MODIFIED SearchSkill.php 

	// Connection Details
	require_once("localsetting.php");

	// Establish SQL Connection
	$DBConnect = @mysqli_connect($host, $user,$pwd, $sql_db)
			Or die ("<p>Unable to connect to the database server.</p>". "<p>Error code ". mysqli_connect_errno().": ". mysqli_connect_error()). "</p>";



	//  INPUT FIELD 1: CITY NAME
	echo "<form>"
		."<label for='city'>Please input city name </label>\r\n"
		."<input type='text' id='city' name='city'/> <br />\r\n";



	// INPUT FIELD 2: LANGUAGE
	// Extract data from database
	$TableName = "languages";
	$SQLstring = "select language from languages";
	$queryResult = @mysqli_query($DBConnect, $SQLstring)
			Or die ("<p>Unable to query the $TableName table.</p>"."<p>Error code ". mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";
	echo "<label for='lang'>Please select a language: </label>\r\n"
		."<select id='lang' name='language'>\r\n";

	
	$row = mysqli_fetch_row($queryResult); // fetch first row data from queryResult
	while ($row) {
		echo "<option value='".$row[0]."'>".$row[0]."</option>\r\n";	
		$row = mysqli_fetch_row($queryResult);
	}
	mysqli_close($DBConnect);
	echo "</select> <br />\r\n";


	// INPUT FIELD 3: YEARS OF EXPERIENCE 
	echo "<label for='years'>Please input the minimum year required: </label>\r\n";
	echo "<input type='text' name='year'/><input type='submit' value='Search'/>\r\n";
	echo "</form>\r\n";




	//At minimum if a language is selected, get data from table 
	if(isset($_GET['language']))
	{

		// Extract language into a variable
		$language = $_GET['language']; 


		// Establish Data Connection 
		$DBConnect =  @mysqli_connect($host, $user,$pwd, $sql_db)
				Or die ("<p>Unable to connect to the database server.</p>". "<p>Error code ". mysqli_connect_errno().": ". mysqli_connect_error()). "</p>";


		// Option 1: ALL THREE INPTUS - Language, City and Year -  Provided
		if(isset($_GET['year']) && $_GET['year']>0 && isset($_GET['city']) && $_GET['city']!=""){
			$city = $_GET['city'];
			$year = $_GET['year'];

			$SQLstring = "select e.first_name,e.last_name,l.language,x.years,e.city "
						."FROM employees e, experience x,languages l "
						."WHERE e.employee_id=x.employee_id "
						."AND x.language_id = l.language_id "
						."AND l.language='{$language}' "
						."AND x.years>= $year "
						."AND e.city='{$city}'";


		} // Option 2: TWO INPUTS PROVIDED - Language and Year is Selected (input data)
		else if(isset($_GET['year']) && $_GET['year']>0 )
		{
			$year = $_GET['year'];

			$SQLstring = "select e.first_name,e.last_name,l.language,x.years,e.city "
						." FROM employees e, experience x,languages l "
						." WHERE e.employee_id=x.employee_id "
						." AND x.language_id = l.language_id "
						." AND l.language='{$language}' "
						." AND x.years>= $year";

		}
		// Option 3: TWO INPUTS PROVIDED: Language and City Name only provided
		else if(isset($_GET['city']) && $_GET['city']!=""){
			$city = $_GET['city'];
			$SQLstring = "select e.first_name,e.last_name,l.language,x.years,e.city "
						." FROM employees e, experience x,languages l "
						." WHERE e.employee_id=x.employee_id "
						." AND x.language_id = l.language_id "
						." AND l.language='{$language}' "
						." AND e.city='{$city}'";

		}
		// Option 4: ONE INPUT PROVIDED: Language is only selected
		else
		{
			$SQLstring = "select e.first_name,e.last_name,l.language,x.years, e.city "
						 ."FROM employees e, experience x,languages l "
						 ."WHERE e.employee_id=x.employee_id and x.language_id = l.language_id and language='".$_GET['language']."'";
		}


		// perform the query, storing the result
		$queryResult = @mysqli_query($DBConnect, $SQLstring)
				Or die ("<p>Unable to query the $TableName table.</p>"."<p>Error code ". mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";

		echo "<p><strong>List of Employees who have at least ", $_GET['year'], " years in ", $_GET['language'], " living in {$_GET['city']}",". </strong></p>";

		echo "<table width='100%' border='1'>";
		echo "<th>First Name</th><th>Last Name</th><th>Language</th><th>Year</th><th>City</th>";
		$row = mysqli_fetch_row($queryResult);
		
		while ($row) {
			echo "<tr><td>{$row[0]}</td>";
			echo "<td>{$row[1]}</td>";
			echo "<td>{$row[2]}</td>";
			echo "<td>{$row[3]}</td>";
			echo "<td>{$row[4]}</td></tr>";
			$row = mysqli_fetch_row($queryResult);
		}
		echo "</table>";

		mysqli_free_result($queryResult);
		mysqli_close($DBConnect);
}
?>

</body>
</html>