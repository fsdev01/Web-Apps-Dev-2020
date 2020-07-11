<!--file data.php -->
<?php
	// get name and password passed from client
	$name = $_GET['namefield'];
	$pwd = $_GET['pwdfield'];
	// write back the password concatenated to end of the name
	$namePwd = $name." : ".$pwd . "<br/>";
	// Get time and date
	// Reference: https://www.php.net/manual/en/function.date.php
	$curTime = "Current Server Time: " . date("D M j G:i:s T Y");
	ECHO $namePwd .$curTime;
?>
