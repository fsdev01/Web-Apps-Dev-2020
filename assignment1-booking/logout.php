<?php
	// STUDENT ID: 102125413 VINH HUYNH
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="description" content="Logout Page"/>
    <meta name="keywords" content="taxi,booking,customer"/>
    <meta name="author" content="Vinh Huynh - 102125413"/>
    <title> Logout page - CabsOnline </title>
    <!-- Style Sheets -->
    <link href="styles/common.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <header>
        <span id="brand">
            <!--Reference: https://www.w3schools.com/icons/fontawesome_icons_intro.asp -->
            <i class="fa fa-car fa-2x" style="color:orange"></i> 
            <span id="title">CabsOnline </span>
        </span>
        <nav>
            <a href='login.php'>Login</a>
            <a href='register.php'>Register</a>
            <a href='booking.php'>Booking</a>
            <a href='admin.php'>Admin</a>
        </nav>
    </header>
    <section id='logoutBox'>
        <h1> You Have Succesfully Logged Out </h1>
    </section>
<footer>
    <p>CabsOnline Pty Ltd &copy; 2020. All rights reserved. </p>
</footer>
    
</body>


<?php
	// Unset Session Variable and Destroy Session
	// Reference: https://www.w3schools.com/php/php_sessions.asp
	session_unset();
	session_destroy();

?>

    
</html>