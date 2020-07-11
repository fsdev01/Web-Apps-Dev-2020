<?php
	
	sleep(2); // delay by 2 seconds
	switch($_GET["value"]) {
		// Parameter sent from client determines which code to execute
		// The code sends back the place in the client where the result
		// is to be displayed
		case "Contacts":
			echo "box1|<br/><b>Contacts</b><br/>William Doe 1, Acacia Avenue <br/> Jane Doe, 2 Willow Tree Lane";
			break;
		case "Calendar":
			$dt = gmdate("M d Y H:i:s");
			echo "box2|<br><b>Calendar.</b><br/> $dt";
			break;
		case "Adverts":
			$source = "wrox_logo.gif";
			echo "box3|<br><b>Advert:</b><br/><img src='$source'>";
			break;

	}




?>