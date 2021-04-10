<?php 
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: countdown.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Countdown - Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="main">
        <h1>Countdown</h1>
        <h3>Let's celebrate the new year together !</h3>
        <div class="buttons">
                <a href="register.php">Register</a>
                <a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>