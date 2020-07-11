<?php
	
	// Create XML Document Object
	$doc = new DOMDocument('1.0');
	// Create "<ajax>" element
	$root = $doc->createElement('ajax');
	// Make <ajax> as root element
	$doc->appendChild($root); 
	
	// Create <js> element
	$child = $doc->createElement('js');
	// Make <js> child of <ajax> element
	$root->appendChild($child);
	// Create Text Node with text "coordination"
	$value = $doc->createTextNode("coordination");
	// Add Text Node to <js> element
	$child->appendChild($value );
	// serialise XML document as string 
	$strXml = $doc->saveXML();
	// Return result to client
	echo $strXml;
?>
