<?php
    // DO PROCESSING ON SERVER SIDE - GENERATE HTML COMPONENTS
    $count = 0;  // Number of books read in
    $HTML = ""; // HTML string to return to client 


    // Reference: Week 6 - Task C
    // Load XML DOM
	$dom = DOMDocument::load("books.xml");

    // Get list of books
    $books = $dom -> getElementsByTagName("book");

    // Traverse each book (node)
    foreach($books as $book){

        // count = 0 represents first item

        // Get the title value - text node's value <date> -> <textNode> -> textValue
        $titleValue= $book->getElementsByTagName("title")->item(0)->nodeValue;

        // Get the Authors value
        $authorsValue = $book->getElementsByTagName("authors")->item(0)->nodeValue;

        // Get the isbn value
        $isbnValue = $book->getElementsByTagName("isbn")->item(0)->nodeValue;

        // Get the price value
        $priceValue = $book->getElementsByTagName("price")->item(0)->nodeValue;

        // get image src
        $imageValue = $book->getElementsByTagName("image")->item(0)->nodeValue;

        // get book id
        $idValue = $book->getElementsByTagName("id")->item(0)->nodeValue;

        // Generate HTML String
        $HTML .= "<div id='{$idValue}'>";
        $HTML .= "<img src='{$imageValue}'/>";
        $HTML .= "<br/> <br/>";
        $HTML .= "<b>Book:</b><span class='book'>{$titleValue}</span><br />";
        $HTML .= "<b>Authors: </b><span class='authors'>{$authorsValue}</span>";
        $HTML .= "<br/> <b>ISBN: </b><span class='ISBN'>{$isbnValue}</span> <br /><b>Price: </b>";
        $HTML .= "<span class='price'>{$priceValue}</span> <br/><br/>";
        $HTML .= "<a href='#' onclick=\"AddRemoveItem('Add',{$idValue});\">Add to Shopping Cart</a>";
        $HTML .= "<br/><br/>";

        $HTML .="</div>";

        $count++;

       
        
    }


    // Case 1: Zero Items
    if($count == 0){
        $HTML = "<p> Zero Items in Catalogue </p>\r\n";
    }

    // Return result to client
    echo $HTML;




    
















?>



