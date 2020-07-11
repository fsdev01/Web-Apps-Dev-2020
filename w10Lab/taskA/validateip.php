<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta name="description" content="Valiate IPv4"/>
	<meta name="keywords" content="validate,ipv4,address"/>
	<meta name="author" content="VH"/>
	<title> Validate IPv4 Address - Lab10</title>
</head>
<body>
	<h1>Please input an IPv4 Address in below text box and click the button</h1>
	<form method="get">
		<input type="text" name="ipaddress"/>
		<input type="submit" value="submit"/>
	</form>

<?php

	// Check whether variable is set
	if(isset($_GET["ipaddress"])) {
		$inputStr = $_GET["ipaddress"]; // extract ip address provided the user

		// Case A: Input is empty
		if(empty($inputStr)){
			echo "<p>Error: Please enter a string </p>\r\n";
		} else { 

		// Case B: Input is not empty

			// Task A:IPv4 Valid range 0.0.0.0 - 255.255.255.255
			// References: https://www.regular-expressions.info/numericranges.html
			// 			   https://stackoverflow.com/questions/5284147/validating-ipv4-addresses-with-regexp

			// Cover Ranges: (1) 250 to 255 and (2) 200 to 249 and (3) 0 to 199
			$pattern = "/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/";
			$valid = preg_match($pattern,$inputStr);
			if($valid == true){
				echo "<p>The input $inputStr is a valid IPv4 Address</p>\r\n";
			} else {
				echo "<p>The input $inputStr is NOT a valid IPv4 Address</p>\r\n";
			}

		}
		

	}

?>

</body>

</html>