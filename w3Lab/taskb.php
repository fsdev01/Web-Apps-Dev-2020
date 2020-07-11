<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="description" descripton="palindrome task a"/>
    <meta name="keywords" descrition="palindrome,pattern"/>
    <meta name="author" description="Vinh Huynh - 102125413"/>
    <title>Perfect Palindrome</title>
</head>
<body>
    <h1>Check for perfect palindrome</h1>
    
    <form method="get">
        <p>
            <label for="str">String: </label>
            <input type="text" id="str" name="userstr" /> 
        </p>
        <p>
            <input type="submit" value="Check for perfect palindrome" />
        </p>
    </form>
    
<?php
// Caution: Input is not sanitized
// HINT IN LAB: use the String functions: strrev & strcmp 

//Check whether the string was set
if(isset($_GET["userstr"])){
    $input = $_GET["userstr"];
    
    // Check whether the input is empty
    // Case A: Input is not empty string
    if(!empty($input)){
        
        $result = true; // assume result is palindrome
        
        // Step 1: Count # of characters in the string
        $strLength = strlen($input);
        
        // Step 2: Two Cases - Even characters or Odd Characters
        // Example: "civic" - 5 characters: index 0 to 4   A:[0,1] B: [3,4]
        //          "deed" - 4 characters: index 0 to 3. Part A:[0,1] B:[2,3]
        
        // Convert String to Array of characters
        $charArray = str_split($input); // Split each character into an array element


        // Note: indiv() not supported in PHP5
        //$midIndex = intdiv($strLength,2); // index of last element in first half of an array
        // WorkAround Solution: use round()
        $midIndex = round($strLength/2);


        for($i = 0; $i < $midIndex ; $i++){
            $charA = $charArray[$i];
            $charB = $charArray[$strLength - 1 - $i]; 
            // Perform case insensitive comparison
            if(strcasecmp($charA,$charB) != 0){
                $result = false;
            }
            
        }
        
        // Print out results
        if($result == false){
            echo "<p>The string '{$input}' is not palindrome!</p>\r\n";
        } else {
            echo "<p>The string '{$input}' is a perfect palindrome! </p>\r\n";
        }
        
        
        
    }else { // Case B: Input is an empty string
        echo "<p>Error: Input is empty. Provide a string.</p>\r\n";
    }
    
    
    
    
    
}
    
    
    
?>
</body>




</html>