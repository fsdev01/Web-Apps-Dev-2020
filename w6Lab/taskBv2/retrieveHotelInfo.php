<?php
$xmlFile = "hotel.xml"; // name of xml file to parse.
$HTML = ""; // HTML string to return to client 
$count = 0;
$dom = DOMDocument::load($xmlFile); // Load XML file into DOM Object
// Alternative:  $dom = new DomDocument( ); $dom -> load($xmlFile);


// Create Associative Array
// (Key,Value) = (HotelName,Price)
$hotelList = array(); // create empty array

// Get list of hotel elements
$hotel = $dom->getElementsByTagName("hotel");

// Extract Properties of the hotel
foreach($hotel as $node)
{
	$citynode = $node->getElementsByTagName("City"); // Get reference to city node
	$cityval = $citynode->item(0)->nodeValue; // Get the text value - firstChild textNode // Hotel City

	$typenode = $node->getElementsByTagName("Type"); 
	$typeval = $typenode->item(0)->nodeValue; // Hotel Type

	$namenode = $node->getElementsByTagName("Name");
	$nameval = $namenode->item(0)->nodeValue; // Hotel Name

	$pricenode = $node->getElementsByTagName("Price");
	$priceval = $pricenode->item(0)->nodeValue; // Hotel price


	// if the extracted hotel type and city match choice, add to the data to be sent back to client
	// Refer to JS file for the paramters
	if( ($typeval==$_GET["type"]) && ($cityval == $_GET["city"])  )
	{
		$hotelList[$nameval] = $priceval; // store city and price
		$count++; // Number of hotels found
	}

}



// CASE 1: if no hotels have been found, set the return message to a string which indicates this
if($count == 0)
{
	$HTML = "<br><span>No hotels available </span>";
} 
else { // CASE 2: There is at least 1 matched hotel where count > 0
	// Sort the hotels according price (e.g. sort by values in associative array)
	asort($hotelList);

	// Loop through all matched hotels with given city and price range category (e.g. budget)
	// Reference: https://www.php.net/manual/en/function.array-multisort.php

	// Form new String
	foreach($hotelList as $name => $price)
	{
		$element  = "<br><span>Hotel: " . $name . "</span><br><span>Price: ". $price . "</span><br>";
		$HTML = $HTML . $element;
	}

}

echo $HTML;
?>

