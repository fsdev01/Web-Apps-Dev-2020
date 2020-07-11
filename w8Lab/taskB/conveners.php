<?php
	// Create XML DOM object
	$xmlDoc = new DomDocument; 
	// Load the XML file
	$xmlDoc->load("conveners.xml"); 

	// Load the XSL file
	$xslDoc = new DomDocument; 
	$xslDoc->load("conveners.xsl");

	// Create XSLT Processor Object
	$proc = new XSLTProcessor; 
	// Import the stylesheet required for transformation
	$proc->importStyleSheet($xslDoc);

	// Transform the XML using the XSL file
	echo $proc->transformToXML($xmlDoc);
?>