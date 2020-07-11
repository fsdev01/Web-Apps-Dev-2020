<?php
    // Create new PHP session or resume existing one
    session_start();
    // Set content type to XML
    header('Content-Type: text/xml');
?>
<?php
        // Create Cart if it doesn't exist
		if(!isset($_SESSION["Cart"])){
			$_SESSION["Cart"] = "";
		}

        // Extract the parameters from global variables
        $newitem = $_POST["book"]; //title of the book
        $action = $_POST["action"]; //action name - Add or Remove


        // CASE 1: Cart already exists
        if ($_SESSION["Cart"] != "")
        {
            // Load existing cart from SESSION variable
            $MDA = $_SESSION["Cart"];

            // ACTION 1: Processing "Add" Request
            if ($action == "Add")
            {
                // The Cart already has this book in it
                if ($MDA[$newitem] != "")
                {  
                    // Increment the quantity of the book
                    $value = $MDA[$newitem] + 1;
                    $MDA[$newitem] = $value;
                }
                // This is the first copy of this book to be added (there may be other books in the cart)
                else
                {
                    $MDA[$newitem] = "1";
                }
            } 
            // ACTION 2: Processing "Remove" request
            else
            {
                // Clear the whole cart (assume bookstore only stores 1 book)
                $MDA= ""; 
				
            }
        }
        // CASE 2: Cart does not exist. (i.e. we have no books ordered)
        else
        {
            $MDA[$newitem] = "1";
        }


        // Update Session Variable latest value
        $_SESSION["Cart"] = $MDA; 
        // Send response to CLIENT
        ECHO (toXml($MDA));  
        								
    
   // Converts "Cart" Object (ARRAY) into XML object
   function toXml($MDA)
    {
        // Create DOM object
        $doc = new DomDocument('1.0');
        // Add "cart" element
        $cart = $doc->appendChild($doc->createElement('cart'));
        // total dollar value
        $total = 0;
        
        // Loop Through - extract value of items
        if($MDA!=""){
            foreach ($MDA as $Item => $ItemName)
            {
                $book = $cart->appendChild($doc->createElement('book'));
    
                $title = $book->appendChild($doc->createElement('Title'));
                $title->appendChild($doc->createTextNode($Item));
                
                $authors = $book->appendChild($doc->createElement('Authors'));
                $authors->appendChild($doc->createTextNode($_POST['authors']));
             
                $isbn = $book->appendChild($doc->createElement('ISBN'));
                $isbn->appendChild($doc->createTextNode($_POST['ISBN']));
            
                $price = str_replace("$","",$_POST['price']);
                $priceNode = $book->appendChild($doc->createElement('Price'));
                $priceNode->appendChild($doc->createTextNode($price));
    
                $quantity = $book->appendChild($doc->createElement('Quantity'));
                $quantity->appendChild($doc->createTextNode($ItemName));
            
                $total = $total + $price * $ItemName;
            }
        }      
        $totalNode =  $cart->appendChild($doc->createElement('Total'));
        $totalNode->appendChild($doc->createTextNode($total));

        $strXml = $doc->saveXML(); 
        return $strXml;
    }

?>
