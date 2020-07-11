<?php
    // Create new PHP session or resume existing one
    session_start();
    // Set content to XML Document
    header('Content-Type: text/xml');
?>
<?php

    // Cart Session not set - set to empty
    if(!isset($_SESSION["Cart"])){
        $_SESSION["Cart"] = "";
    }
    
    // Parameter 1: title of the book
    $newitem = $_GET["book"];
    // Parameter 2: action - "Add" or "Remove" item from cart
    $action = $_GET["action"];
    
    // The Current Cart is not empty 
    if ($_SESSION["Cart"] != "") {
        
        // Extract Cart Associative array into $MDA
        $MDA = $_SESSION["Cart"];
        
        // Case 1: Cart already exists with an item in it
        if ($action == "Add"){
            // Case: the cart already has this book in it
            if ($MDA[$newitem] != ""){  
                $MDA[$newitem]++; // add 1 to no of copies
            }
            // Case:this is the first copy of this book to be added (there may be other books)
            else {
                $MDA[$newitem] = "1";
            }
        // Case: // we are processing a “Remove” request
        } else{
                $MDA= ""; // // nb – we are clearing the whole cart; this is what the spec requires
            }
    // Case 2: User adds new item to the cart
    } else{
        $MDA[$newitem] = "1";
    }

    // Update the Session Cart with new CART
    $_SESSION["Cart"] = $MDA; 
    // send result to client
    ECHO (toXml($MDA));         								
 
    
function toXml($MDA){
    // Create XML DOM object
    $doc = new DomDocument('1.0');
    // Create <cart> element
    $cart = $doc->createElement('cart');
    // Make <cart> as root element
    $cart = $doc->appendChild($cart);

    //Condition: Argument - Cart is not empty
    if($MDA!=""){
        // foreach ($aCart as $titleValue => $qtyValue)
        foreach ($MDA as $a => $b)
        {
            // Create <book> element and add to Cart
            $book = $doc->createElement('book');
            $book = $cart->appendChild($book);

            // Create <title> and add to to <book>
            $title = $doc->createElement('title'); 
            $title = $book->appendChild($title);  
            // Initalise <book> with text value 
            $value = $doc->createTextNode($a);
            $value = $title->appendChild($value);

            // Create <quantity> and add to <book>
            $quantity = $doc->createElement('quantity');
            $quantity = $book->appendChild($quantity);
            // Initalise <quantity> with qty value
            $value2 = $doc->createTextNode($b);
            $value2 = $quantity->appendChild($value2);
        }
        
    }
    // Serialise XML DOM Object to String
    $strXml = $doc->saveXML(); 
    return $strXml;
}
?>
