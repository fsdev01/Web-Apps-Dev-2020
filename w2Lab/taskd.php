<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta title="description" content="Week 2 Lab Task D"/>
    <meta title="keywords" content="Task,Lab"/>
    <meta title="author" content="VH - 102125413"/>
    <title> Week 2 - Lab Task D</title>
</head>
<body>
    <h1> Lab 2 - Task D </h1>
    <form method="get">
        <p>
            <label for="number">Please input a number: </label>
            <input type="text" id="number" name="number"/>
            <button type="submit">Submit</button>
        </p>
    </form>


<?php

    // check if input is set
    if(isset($_GET["number"])) {
        getSeq($_GET["number"]) ;
    }



    function getSeq($x){
        // Case 1: Input is <=0 (invalid input)
        if(!is_numeric($x) || !ctype_digit($x) || $x <=0)
        {
            echo "<p> Invalid Input: Number must be > 0</p>";
            return;
        }

        // Case 2: Valid Input ( > 0)
        // Print out sequence of numbers from x to 1 subject to conditions:
        if($x != 1 ){
            echo $x . "<br/>"; // Print input number as required
        }

        // Loop through the sequence - go backwards
        // Decrement first and then return i
        for($i = $x ; $i > 1 ; --$i){
            // check if $input can be divided evently by $i
            if($x % $i == 0){
                continue;
            }
            else{  // Print out odd numbers
                echo $i . "<br/>";
            }
        }

        echo "1"; // Print "1" as required



    }

?>

</body>

</html>