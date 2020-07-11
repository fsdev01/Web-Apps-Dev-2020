<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="description" content="PHP Task D"/>
    <meta name="keywords" content="TaskD"/>
    <meta name="author" content="VH"/>
    <title>Week 2 Lab - Task D Quiz</title>
    
</head>
<body>
    <h1> Lab 3 - Task D Quiz</h1>
    <form method="get">
        <p>Answer all of the questions on the quiz, then select the Score button to grade the quiz</p>
    
        

    </form>
<?php
    // Function Defintion Block
    function sanitise_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    //Maps Index Number to Multiple Choice Letter
    // Example: Index 1 maps to "a"
    // Example: Index 2 maps to "b" and so on. 
    function mapIndexToLetter($indexNumber){
        $letters =  array('a','b','c','d');

        return $letters[$indexNumber-1];

    }
    
    $i = 1; // Keeps Track of Question
    $questions = array("None"); // Index 0 mapped to None

    // Create File Connection/Handle
    $theFile = fopen("quizdata.txt","r");

    while(! feof($theFile)){

        // Extracts the first line
        $curLine = fgets($theFile);


        // sanitise the input and remove spaces at the start and end of the string
        $curLine = sanitise_input($curLine);


        // Get the array of the current line
        $temp = explode(",",$curLine);
        
        // Get the answer for the question
        $answer = $temp[count($temp)-1]; 

        // Get the index of the answer
        $answerIndex = array_search($answer,$temp);

        // Get the multiple choice letter of the answer
        $answerLetter = mapIndexToLetter($answerIndex);

        // [Question,A,B,C,D,Answer]
        //echo "Answer: $answer         index:$answerIndex     answer: $answerLetter  i:$i" ;
        
        // Update Array - containing question and answer
        // Question 1 Mapped to Answer1  $questions[1] = answer1
        $questions[$i] = $answerLetter;


        // Check whether there is response
        if( isset($_GET["q{$i}"])){
            $selectedAns = $_GET["q{$i}"];
        }

        // FIX: Create a separate function to create html elemnts
        // Create Questions
        echo "<form method=\"get\">\r\n";
        echo "<p>\r\n"
            ."<strong> Q{$i}. $temp[0] </strong><br/>"
            ."<label> <input type='radio' name='q{$i}' value='a'/> $temp[1] </label><br/>\r\n" 
            ."<label> <input type='radio' name='q{$i}' value='b'/> $temp[2] </label><br/>\r\n"
            ."<label> <input type='radio' name='q{$i}' value='c'/> $temp[3] </label><br/>\r\n"
            ."<label> <input type='radio' name='q{$i}' value='d'/> $temp[4] </label><br/>\r\n"
            ."</p>\r\n";

        //print_r($temp);
        echo "<br/>\r\n";
        $i++;
    }

    // Close File Connection/Handle
    fclose($theFile);
    echo "<p><input type=\"submit\" value=\"Score\"/></p>\r\n";
    echo "</form>\r\n";
    
    // NOTICE: AT THIS STAGE, if FOUR Questions then $i is equal to 5 (because $i++ is located at end of while loop)
    // Check if all menu options selected
    $countQuestions = count($questions);
    $selectedAll = true;
    $userResponses = array("None"); // Question 0 set to None
    for($i = 1 ; $i < $countQuestions ; $i++){

        // Check if MC is selected
        if(!isset($_GET["q{$i}"])){
            // Detected at least 1 MC/Question9 not selected
            $selectedAll = false;
        } else
        {  // store answeer
            array_push($userResponses,$_GET["q{$i}"]);
        }
    }
    //print_r($userResponses);
    /**
    if($selectedAll){
        echo "<p>Status: Answered All Questions.</p>\r\n";
    } else {
        echo "<p>Status: Incomplete Questions</p>\r\n";
    }
    **/


    // Check for valid answers 
    // Precondition: User has responded to each question
    if($selectedAll){

        // Go Through Each Question and check response
        // Skip $i=0 as it is empty
        for($i = 1; $i < $countQuestions; $i++){
            $userAnswer = $userResponses[$i];
            $correctAnswer = $questions[$i];

            //Check whether the answer is correct
            $result = "";
            if($userAnswer == $correctAnswer){
                $result = "Correct!";
            } else{
                $result ="Incorrect";
            }

            echo "<p>Question $i: $userAnswer ($result) </p>\r\n";

        }



    }   


?>
    
</body>
</html>


