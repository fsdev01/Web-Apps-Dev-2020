<!--file simplePHP.php -->

<HTML XMLns="http://www.w3.org/1999/xHTML"> 
  <head> 
    <title>A Simple Example</title> 
  </head> 
  <body>
  <H1>Fetching data with PHP</H1>

  <form>
	    <label>User Name:  <input type="text" name="namefield"> </label>
 	    <label><br>Password: <input type="text" name="pwdfield"> <br><br> </label>
		<input type="submit" value="Send to server" />
  </form>
  
  <p> The result data will refresh current page.</p>
  </body> 

<?php 
	// get name and password passed from client
	if(isset($_GET['namefield']) && isset($_GET['pwdfield']))
	{
		$name = $_GET['namefield'];
		$pwd = $_GET['pwdfield'];
		// sleep for 10 seconds to slow server response down
		sleep(10);
		// write back the password concatenated to end of the name
		ECHO ($name." : ".$pwd);
	}
?>

</HTML>