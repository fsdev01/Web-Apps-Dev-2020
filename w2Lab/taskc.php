<?php
    $i = 10; 
    while ($i>0) {
        if ($i == 8) {
            $i--; 
            continue;
        }
        if ($i == 5) {
            break; 
        }
        // return i and then decrement/decrease i
        // output: 10 -> 9 -> 7 -> 6
        //echo $i-- . "\n"; 

        // decrement i and then return i
        // ouput: 9 -> 8 -> 6 -> 5
        echo --$i . "\n";
    }
 ?>