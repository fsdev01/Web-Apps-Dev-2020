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
    $title = $_GET["book"];
    // Parameter 2: action - "Add" or "Remove" item from cart
    $action = $_GET["action"];
    if(isset($_GET["isbn"]) && isset($_GET["price"])){
        // Parameter 3: isbn of the book
        $ISBN = $_GET["isbn"];
        // Paramter 4: price of the book
        $price = $_GET["price"];
        $price = (float) substr($price,1);
    }

    
    // The Current Cart is not empty 
    if ($_SESSION["Cart"] != "") {
        
        // Extract Cart Associative array into $cart
        $cart = $_SESSION["Cart"];

        // Case 1: Cart already exists with an item in it
        if ($action == "Add"){
            // Case: the cart already has this book in it 
            // Add one more for the book
            if ($cart[$title] != ""){  

                // Extract existing item from cart
                $value = $cart[$title];
                // Update quantity
                $value["qty"] = (int) $value["qty"] + 1;
                // Update total for that book title
                // Remove '$' from the string
                $value["total"] =  $price * (int) $value['qty'];
                // Update book in cart
                $cart[$title] = $value;

            }
            // Case 2 :this is the first copy of this book to be added (there may be other books)
            else {
                // Create new array as per hints
                $value = array();
                $value['qty'] = 1;
                $value['isbn'] = $ISBN;
                $value['total'] = $price;

                // 2D Associative Array
                // Key: $bookTitle
                // Value: Associative Array of Properties
                $cart[$title] = $value;
            }
        // Case: We are processing a “Remove” request
        } else{
            // Deletes ONE COPY of the Book

            // Extract existing book from cart
            $value = $cart[$title];
            // Update quantity - Decrease by 1
            $value["qty"] = (int) $value["qty"] - 1;
            // Update total for that book title
            // Remove '$' from the string
            $value["total"] =  $price * (int) $value['qty'];
            
            // CASE1: If NEW QTY is 0:
            $newQty = (int) $value["qty"];
            if($newQty == 0){
                // Delete item from the cart
                unset($cart[$title]);
                // Case 1A: Cart is empty (no more other books)
                if(count($cart) == 0){
                    $cart = "";
                }
                

            } else { // CASE 2: If new Qty > 0
                // Update book in cart with decreased qty
                $cart[$title] = $value;
            }

            


        }
    // Case 2: the “cart” is NOT present – ie, we have no books ordered; order one!
    } else{
        // Create new array as per hints
        $value = array();
        $value['qty'] = 1;
        $value['isbn'] = $ISBN;
        $value['total'] = $price;

        // 2D Associative Array
        // Key: $bookTitle
        // Value: Associative Array of Properties
        $cart[$title] = $value;
    }

    // Update the Session Cart with new CART
    $_SESSION["Cart"] = $cart; 
    // send result to client
    ECHO (toXml($cart));         								
 
    
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
        foreach ($MDA as $Item => $ItemDetails)
        {
            // Create <book> element and add to Cart
            $book = $doc->createElement('book');
            $book = $cart->appendChild($book);

            // Create <title> and add to to <book>
            $title = $doc->createElement('title'); 
            $title = $book->appendChild($title);  
            // Initalise <book> with text value 
            $value = $doc->createTextNode($Item);
            $value = $title->appendChild($value);

            // Create <quantity> and add to <book>
            $quantity = $doc->createElement('quantity');
            $quantity = $book->appendChild($quantity);
            // Initalise <quantity> with qty value
            $value2 = $doc->createTextNode($ItemDetails['qty']);
            $value2 = $quantity->appendChild($value2);

            // Create <isbn> element and add to <book>
            $isbnElement = $doc->createElement('isbn');
            $ibsnElement = $book->appendChild($isbnElement);
            // Initalise <isbn> with value
            $value3 = $doc->createTextNode($ItemDetails['isbn']);
            $value3 = $isbnElement->appendChild($value3);

            // Create total element
            $total = $doc->createElement("total");
            $total = $book->appendChild($total);
            $value4 = $doc->createTextNode($ItemDetails['total']);
            $value4 = $total->appendChild($value4);
            //echo "<p>Special: " . $ItemDetails['total'] . " </p>\r\n";
            


            
        }
        
    }
    // Serialise XML DOM Object to String
    $strXml = $doc->saveXML(); 
    return $strXml;
}
?>
