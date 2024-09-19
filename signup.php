<?php 
session_start();

	include("connection.php");
	include("function.php");

  $icon = [
    "login-pic/icon1.webp",
    "login-pic/icon2.webp",
    "login-pic/icon3.webp",
    "login-pic/icon4.webp",
    "login-pic/icon5.webp"
   
  ];


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted

    if($_POST['password'] == $_POST['re-password']) {

      $user_name = $_POST['user_name'];
      $password = $_POST['password'];

      if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
      {

        $user_icon = $icon[array_rand($icon)];


        //save to database
        $user_id = random_num(20);
        $query = "insert into users (user_id,user_name,password,user_icon) values ('$user_id','$user_name','$password', '$user_icon')";

        mysqli_query($con, $query);

        echo "<script>
                alert('Signup Successful! You have successfully signed up.');
                window.location.href = 'login.php';
              </script>";

        die;
      }else
      {
        $error_msg = "Please enter valid information!";
		  }
    }

    else {
      $error_msg = "Both passwords are not equal!";
    }
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="login-css/login.css">
  <link rel="stylesheet" href="login-css/signup.css">


</head>
<body>
  <div class = "login-outer">
    <img class = "bg" src ="login-pic/sky-bg.jpg">
    <div id="login-box" class = "login-box">
      <form  class = "login-form" method = "post">
        <div class = "box-title"> Signup </div>

        <div class = "input-outer">
          <input class= "input-box" type = "text" name ="user_name" placeholder="Username"> 
          <input class= "input-box" type = "password" name = "password" placeholder="Password">
          <input class= "re-pw-box" type = "password" name = "re-password" placeholder="Re-enter password">
          <?php if(!empty($error_msg)): ?>
            <div class="error-message2"><?php echo $error_msg; ?></div>
          <?php endif; ?>
        </div>
     

        <div class = "submit-outer">
          <input class= "submit-btn" type = "submit" value = "Sign Up"> 

        </div>

        <div class= "signup-outer">

          <div class ="signup-btn">
            <a class= "back-login" href="login.php">Go back to Login</a>
          </div>
        </div>
        


      </form>

    </div>
  </div>


  <script src="login-js/login.js"></script>

  

</body>
</html>