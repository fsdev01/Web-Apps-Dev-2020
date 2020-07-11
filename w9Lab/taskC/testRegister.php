<?php
/*
	Author: Wei Lai
	Date: 9/10/2018
*/

header('Content-Type: text/xml');

if(isset($_GET["name"]) && isset($_GET["email"]) && isset($_GET["password"]) && isset($_GET["phone"]) ){

	$name = $_GET["name"];
	$email = $_GET["email"];
	$password = $_GET["password"];
	$phone = $_GET["phone"];
	
	$errMsg = "";
	if (empty($name)) {
			$errMsg .= "You must enter a name. <br />";
	}
	
	if (empty($email)) {
			$errMsg .= "You must enter an email id. <br />";
	}

	if (empty($password)) {
			$errMsg .= "You must enter a password. <br />";
	}

	if (empty($phone)) {
			$errMsg .= "You must enter a phone. <br />";
	}
	
	if ($errMsg != "") {
			echo $errMsg;
	}
	else {
	
	$xmlfile = 'data/testData.xml';
	
	$doc = new DomDocument();
	
	if (!file_exists($xmlfile)){ // if the xml file does not exist, create a root node $customers
		$customers = $doc->createElement('customers');
		$doc->appendChild($customers);
	}
	else { // load the xml file
		$doc->preserveWhiteSpace = FALSE; 
		$doc->load($xmlfile);  
	}
	
	//create a customer node under customers node
	$customers = $doc->getElementsByTagName('customers')->item(0);
	$customer = $doc->createElement('customer');
	$customers->appendChild($customer);
	
	// create a Name node ....
	$Name = $doc->createElement('name');
	$customer->appendChild($Name);
	$nameValue = $doc->createTextNode($name);
	$Name->appendChild($nameValue);
	
	//create a Email node ....
	$Email = $doc->createElement('email');
	$customer->appendChild($Email);
	$emailValue = $doc->createTextNode($email);
	$Email->appendChild($emailValue);
	
	//create a pwd node ....
	$pwd = $doc->createElement('password');
	$customer->appendChild($pwd);
	$pwdValue = $doc->createTextNode($password);
	$pwd->appendChild($pwdValue);

	//MODIFIED: create phone node 
	$Phone = $doc->createElement('phone');
	$customer->appendChild($Phone);
	$PhoneValue = $doc->createTextNode($phone);
	$Phone->appendChild($PhoneValue);

	
	//save the xml file
	$doc->formatOutput = true;
	$doc->save($xmlfile);  

	$outputString = "Dear $name, you have succesfully registered, a confirm email sent to $email";
	echo $outputString;
	} 
}
?>