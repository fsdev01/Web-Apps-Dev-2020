<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="description" descripton="phone"/>
    <meta name="keywords" descrition="phone,lookup,bowlers,search"/>
    <meta name="author" description="Vinh Huynh - 102125413"/>
    <title>Phone Number Search</title>
</head>
<body>
    
    <p>Input a name in the below text box and click 'search' button to see the result</p>
    
    <form method="get">
        <p>
            <label for="pName">Name: </label>
            <input type="text" id="pName" name="pName" /> 
            <input type="submit" value="Search" />
        </p>
            
    </form>
    
<?php
// Caution: Input is not sanitised -- to be implemented    
    
    // Extract Get Parameter value (key-value pair)
    if(isset($_GET["pName"])) {
        
        $targetName = $_GET["pName"];
        
        //Condition: Check whether the input is empty
        if(empty($targetName)){
            echo "<p>Error: Empty Input String </p>\r\n";
        } else {
            // Step 1: Check the file exists and load the file into array
            // Case A: File Does not Exist
            if( !file_exists("data/bowlers.txt") ){
                echo "<p>Error: bowlers.txt file does not exist in </p>\r\n";
            } else {
                // Create Handle - File Connection
                $postFile = fopen("data/bowlers.txt","r");
                
                // Extract the first line of the file
                $curLine = fgets($postFile); 
                
                // Create an associative array
                $phoneBook = array();
                
                // Loop through every line in the text file
                while( !feof($postFile) ){ // While not end of file
                    
                    // Fixed Bug: New Line character
                    // SOLUTION 1: Remove the new line and carriage return characters
                    // Reference; https://thisinterestsme.com/php-remove-newlines/
                    // (Alternative) SOLUTION 2: use trim($data) function to remove empty spaces
                    $curLine = str_replace(array("\n","\r"),"",$curLine);
                    
                    
                    //Split the string to an array
                    $curLineArray = explode(",",$curLine);
                    
                    
                    // Put the "Key: Person Name" and "value: phone number" in array
                    // Note in text file person's contact is stored as : "Name, Phone Number" 
                    // Example: "John, 0430903910" - 
                    $name = strtoupper($curLineArray[0]); // extract name from current line & convert to uppercase
                    $phoneNumber = $curLineArray[1]; // extract phone number from current line of file
                    $phoneBook[$name] = $phoneNumber; 
                    
                    // Get the next line of the text file
                    $curLine = fgets($postFile);
                    
                    
                } 
                
                
                // Testing Purposes - Print out results 
                /*
                foreach($phoneBook as $key=>$value){
                    echo "$key: $value <br/>";
                }
                print_r($phoneBook);
                */
                
                
                // Close File handle 
                fclose($postFile);
                
            }
            
            // Step 2: Find the phoneNumber corresponding to Person's Name
            
            // Convert the input string into upper case
            $upperCaseTargetName = strtoupper($targetName);
            

            // Check whether the key exists (e.g. Person's Number)
            // Case A: The Key exists
            if(array_key_exists($upperCaseTargetName,$phoneBook)){
                // Get the the phoneNumber
                $matchedPhoneNumber = $phoneBook[$upperCaseTargetName];
                echo "<p>The phone number of $targetName is: $matchedPhoneNumber </p>\r\n";
                
            } else { // Case B: The key doesn't exist
                echo "<p>Unable to find the phone number for \"$targetName\"</p>\r\n";
            }
        }

    }
    

?>
</body>




</html>