<?php 
session_start();

	include("connection.php");
	include("function.php");


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot password</title>
  <link rel="stylesheet" href="login-css/login.css">
  <link rel="stylesheet" href="login-css/signup.css">


</head>
<body>
  <div class = "login-outer">
    <img class = "bg" src ="login-pic/sky-bg.jpg">
    <div id="login-box" class = "login-box">
      <form  class = "login-form" method = "post">
        <div class = "box-title"> Forgot password </div>

        <div class = "input-outer">
          <p> Sorry, you are unable to reset password, please sign up for another account.</p>
        </div>
     


        <div class= "signup-outer">

          <div class ="signup-btn">
            <a class= "back-login" href="signup.php">Go to Signup</a>
          </div>
        </div>
        


      </form>

    </div>
  </div>


  <script src="login-js/login.js"></script>

  

</body>
</html>