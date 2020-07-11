<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="description" content="PHP Task D"/>
    <meta name="keywords" content="TaskD"/>
    <meta name="author" content="VH"/>
    <title>Week 2 Lab - Task E Quiz</title>
    
</head>
<body>
    <h1> Lab 2 - Task E Quiz</h1>
    <form method="get">
        <p>Answer all of the questions on the quiz, then select the Score button to grade the quiz</p>
    
        <p>
            <strong>1. Which is a Transport Layer Protocol? </strong><br/>
            <label><input type="radio" name="q1" value="a"/>&nbsp;TCP</label><br/>
            <label><input type="radio" name="q1" value="b"/>&nbsp;HTTP</label><br/>
            <label><input type="radio" name="q1" value="c"/>&nbsp;POP3</label><br/>
            <label><input type="radio" name="q1" value="d">&nbsp;CDMA</label><br/>
        </p>

        <p>
            <strong>2. window.onload is an</strong> <br/>
            <label><input type="radio" name="q2" value="a"/>&nbsp;A Property</label><br/>
            <label><input type="radio" name="q2" value="b"/>&nbsp;An object</label><br/>
            <label><input type="radio" name="q2" value="c"/>&nbsp;An event</label><br/>
            <label><input type="radio" name="q2" value="d">&nbsp;A Method</label><br/>
        </p>        
        
        
        <p>
            <strong>3. The Internet acronym DNS stands for</strong> <br/>
            <label><input type="radio" name="q3" value="a"/>&nbsp;Dynamic Network Server</label><br/>
            <label><input type="radio" name="q3" value="b"/>&nbsp;Domain Network Server</label><br/>
            <label><input type="radio" name="q3" value="c"/>&nbsp;Dynamic Network System</label><br/>
            <label><input type="radio" name="q3" value="d">&nbsp;Domain Name System</label><br/>
        </p> 
        
        
        <p>
            <strong>4. The expression 17 % 5 evalutes to </strong> <br/>
            <label><input type="radio" name="q4" value="a"/>&nbsp;1</label><br/>
            <label><input type="radio" name="q4" value="b"/>&nbsp;2</label><br/>
            <label><input type="radio" name="q4" value="c"/>&nbsp;It does not evaluate. It produces an error</label><br/>
            <label><input type="radio" name="q4" value="d">&nbsp;3</label><br/>
        </p> 
        
        <p>
            <strong>5. In PHP all variables start with</strong> <br/>
            <label><input type="radio" name="q5" value="a"/>&nbsp;!</label><br/>
            <label><input type="radio" name="q5" value="b"/>&nbsp;%</label><br/>
            <label><input type="radio" name="q5" value="c"/>&nbsp;&amp;</label><br/>
            <label><input type="radio" name="q5" value="d">&nbsp;$</label><br/>
        </p>
        
        <p>
            <input type="submit" value="Score"/>
        </p>
    </form>
<?php

    if(   isset($_GET["q1"])  &&  isset($_GET["q2"]) && isset($_GET["q3"]) && isset($_GET["q4"]) && isset($_GET["q5"])  ){
        $q1 = $_GET["q1"];
        $q2 = $_GET["q2"];
        $q3 = $_GET["q3"];
        $q4 = $_GET["q4"];
        $q5 = $_GET["q5"];
        
        echo "<h2>Quiz Results </h2>\r\n";
        
        $countCorrect = 0; // count # of correct response;
        
        // Question 1: 
        $q1Result = "";
        if($q1 == "a"){
            $q1Result = "Correct!";
            $countCorrect++;
        } else{
            $q1Result = "Incorrect";
        }
        
        echo "<p>Question 1: $q1 ($q1Result) </p>\r\n";

        
        // Question 2: 
        $q2Result = "";
        if($q2 == "c"){
            $q2Result = "Correct!";
            $countCorrect++;
        } else{
            $q2Result = "Incorrect";
        }
        
        echo "<p>Question 2: $q2 ($q2Result) </p>\r\n";

        // Question 3: 
        $q3Result = "";
        if($q3 == "d"){
            $q3Result = "Correct!";
            $countCorrect++;
        } else{
            $q3Result = "Incorrect";
        }
        
        echo "<p>Question 3: $q3 ($q3Result) </p>\r\n";
        
        // Question 4: 
        $q4Result = "";
        if($q4 == "b"){
            $q4Result = "Correct!";
            $countCorrect++;
        } else{
            $q4Result = "Incorrect";
        }
        
        echo "<p>Question 4: $q4 ($q4Result) </p>\r\n";
        
        
        // Question 5: 
        $q5Result = "";
        if($q5 == "d"){
            $q5Result = "Correct!";
            $countCorrect++;
        } else{
            $q5Result = "Incorrect";
        }
        
        echo "<p>Question 5: $q5 ($q5Result) </p>\r\n";
        
        echo "<p><strong> You scored $countCorrect out of 5 answers correctly !</strong></p>\r\n";
        
        
        
    } else {
        echo "<p>Please complete the quiz </p>\r\n";
    }
    
    

?>
    
</body>
</html>


