<!-- file simplephp.php -->
<!-- Modified Task 2D -->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xHTML">
<head>
    <title> Task 2D</title>
</head>
<body>
    <h1>Fetching data without Ajax</h1>
    <form method="get">
        <p>
            <label for="uname">User Name</label>
            <input type="text" id="uname" name="namefield"/>
        </p>
        <p>
            <label for="pass">Password</label>
            <input type="password" id="pass" name="pwdfield"/>
        </p>
        <p> 
            <label for="gender">Gender: </label>

            <input type="radio" id="male" name="gender" value="male"/>
            <label for="male">Male</label>

            <input type="radio" id="female" name="gender" value="female"/>
            <label for="female">Female</label>

        </p>
        <p>
            <label for="age">Age</label>
            <select id="age" name="age">
                <option value="under18">Under 18</option>
                <option value="18to25">18 to 25 </option>
                <option value="26to35">26 to 35 </option>
            </select>
        </p>
        <p>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email"/>
        </p>

        <p> <input type="submit" value="Send to server"/> </p>
    </form>
    <p> The result data will refresh the current page </p>

    <?php // Embedded PHP Code

        // get name and password from the client
        if(isset($_GET["namefield"]) && isset($_GET["pwdfield"])){
            $name = $_GET["namefield"];
            $pwd = $_GET["pwdfield"];
            $gender = $_GET["gender"];
            $age = $_GET["age"];
            $email = $_GET["email"];
            // Sleep for 5 seconds to slow server response down
            // NOTICEABLE DELAY
            sleep(5);

            //https://www.guru99.com/php-date-functions.html
            //https://www.php.net/manual/en/function.date-default-timezone-get.php
            date_default_timezone_set("Australia/Melbourne");
            $curTime = date("D M y H:i:s T Y");

            // write back the password concatenated to end of the name 
            echo "<p> Registration Success! <br/>"
                 ."$name:$pwd  <br/>"
                 . "Gender: $gender <br/>"
                 . "Age: $age <br/>"
                 . "Email: $email <br/>"
                 . "Current Server Time: $curTime <br/> </p>\r\n"; 
        } 

    ?>


    
</body>


</html>