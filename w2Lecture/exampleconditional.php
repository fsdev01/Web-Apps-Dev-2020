<HTML XMLns="http://www.w3.org/1999/xHTML">
<head>
    <meta charset="utf-8"/>
    <meta name="description" content="Switch PHP"/>
    <meta name="keywords" content="PHP,Switch,Conditions"/>
    <meta name="author" content="VH"/>
    <title>An example of using 'Switch'</title>
</head>
<body>
    <h1>An Example of Using "Switch" in PHP</h1>
    <form> 
        <p>
            <label for="day">Please select a weekday</label>
            <select id="day" name="weekday">
                <option value="Sunday">Sunday</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
            </select>
        </p>
        <p>Select Your Role:   &nbsp;
            <label for="teacher">Teacher</label>
            <input type="radio" id="teacher" name="role" value="teacher"/>&nbsp;
            <label for="student">Student</label>
            <input type="radio" id="student" name="role" value="student"/>
        </p>
        <p>
            <input type="submit" value="submit"/>
        </p>
    </form>
    <p>Your Agenda is shown as below.</p>
</body>
<?php
   if(isset($_GET["weekday"]) && isset($_GET["role"])){
       $weekday = $_GET["weekday"];
       $role = $_GET["role"];
       echo "As a $role, on $weekday, your agenda is: &nbsp;";
       if($role == "student"){ // a student's agenda
           switch($weekday){
               case "Monday":
                   echo "HIT3324 Lecture & Lab";
                   break;
               case "Wednesday":
                   echo "HIT3324 Lab";
                   break;
               default:
                   echo "Study Harder";
           } // end of switch   
       } 
       else { // a teacher's agenda
           switch($weekday){
               case "Monday":
                   echo "HIT3324 Lecture & Lab";
                   break;
               case "Wednesday":
                   echo "HIT3324 Lab";
                   break;
               case "Tuesday":
               case "Thursday":
               case "Friday":
                   echo "Research";
                   break;
               default:
                   echo "Have a rest";
           }
       }
                            
    }
    
?>


</HTML>
