<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="description" content="PHP Task G"/>
    <meta name="keywords" content="TaskD"/>
    <meta name="author" content="VH"/>
    <title>Week 4 Lab - Task G Quiz</title>
    
</head>
<body>
    <h1> Week 4 Lab - Task G Quiz</h1>

<?php


    // load local DB setting - variables
    require_once("localsetting.php"); 

    // Sanitise Input Function
    function sanitise_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    // Establish SQL Connection
    $DBConnect = @mysqli_connect($host, $user,$pwd, $sql_db)
            Or die ("<p>Unable to connect to the database server.</p>". "<p>Error code ". mysqli_connect_errno().": ". mysqli_connect_error()). "</p>";



    $TableName ="quiz";
    $SQLstring= "Select * FROM $TableName";

    // Execute query statement
    $queryResult = @mysqli_query($DBConnect, $SQLstring)
            OR die ("<p>Unable to query the $TableName table.</p>"."<p>Error code ". mysqli_errno($DBConnect). ": ".mysqli_error($DBConnect)). "</p>";


    // SECTION 1: DISPLAY THE QUIZ QUESTIONS
    echo "<form method='get' action=''>\r\n"
        ." <p>Answer all of the questions on the quiz, then select the Score button to grade the quiz</p>\r\n";
    echo "<p>\r\n";

    $row = mysqli_fetch_assoc($queryResult); // fetch first row data from queryResult
    $i= 0; // keep track # of questions - local instance
    while ($row) {
       $i++; // increment question #
       echo "<p>\r\n"
            ."<strong> Q{$i}.  {$row['question']}</strong><br/>"
            ."<label> <input type='radio' name='q{$i}' value='A'/> {$row['A']} </label><br/>\r\n" 
            ."<label> <input type='radio' name='q{$i}' value='B'/> {$row['B']} </label><br/>\r\n"
            ."<label> <input type='radio' name='q{$i}' value='C'/> {$row['C']}</label><br/>\r\n"
            ."<label> <input type='radio' name='q{$i}' value='D'/> {$row['D']} </label><br/>\r\n"
            ."</p>\r\n";

        // Store the Answer in PHP session variable
        $letterAnswer = $row['answer']; // answer "A" or "B" or "C" or "D"
        $questionNo = "q" . $i; // Q1 or Q2 or Q3 or ...
        $_SESSION[$questionNo] = strtoupper($letterAnswer); // convert answer to uppercase
         
        $row = mysqli_fetch_assoc($queryResult); // fetch next row
        
    }
    echo "<p><input type=\"submit\" value=\"Score\"/></p>\r\n";
    echo "</form>\r\n";



    mysqli_free_result($queryResult);
    mysqli_close($DBConnect);


    // Section 2: Analyse Input
    // Check 2: Inputs are provided
    // EQUIVALENT TO if(!isset($_GET["Q1"]) && !isset($_GET["Q2"]) && !isset($_GET["Q3"]) && .... )
    $selectedAll = true;
    $totalQuestions = $i; // store total number of questions extracted from DB
    // Store user's responses where format of Data -> [None,UserResponse1,UserResponse2, ...]
    $userResponses = array("None"); //  (Question 0 not utilised) 
    for($i = 1 ; $i <= $totalQuestions ; $i++){
        // Check if MC is selected
        if(!isset($_GET["q{$i}"])){
            // Detected at least 1 MC/Question  not selected
            $selectedAll = false;
        } else
        {  // store user's response into array
            array_push($userResponses,$_GET["q{$i}"]);
        }
    }


    // Compare Actual Answers with Correct Answers 
    // Precondition: User has responded to all questions
    $countCorrect = 0;
    //print_r($userResponses);

    if($selectedAll){

        // Go Through Each Question and check response
        // Skip $i=0 as it is empty
        for($i = 1; $i <= $totalQuestions; $i++){
            $userAnswer = $userResponses[$i]; // user's response
            $correctAnswer = $_SESSION['q' . $i] ; // correct answer

            //echo "<p>Q{$i} User Selected: $userAnswer <br/>   Correct Answer $correctAnswer</p>\r\n";

            //Check whether the answer is correct
            $result = "";
            if($userAnswer == $correctAnswer){
                $result = "Correct!";
                $countCorrect++;
            } else{
                $result ="Incorrect";
            }

            echo "<p>Question $i: $userAnswer ($result) </p>\r\n";

        }
        echo "<p> You have answered $countCorrect out of $totalQuestions correctly. </p>\r\n";

        session_unset();
        session_destroy();
    } else {
        // Display Error Message: Incomplete form
        // As $userResponse is initalise with 1 element [None], but will fill user's responses
        if(count($userResponses) > 1){
            echo "<p>ERROR: incomplete quiz </p>";
        }
    }   

    








?>

    
</body>
</html>


