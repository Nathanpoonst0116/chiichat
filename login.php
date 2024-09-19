<?php 

session_start();

include("connection.php");
include("function.php");

$error_msg = ""; 

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if(!empty($user_name) && !empty($password) && !is_numeric($user_name))
    {
        $query = "SELECT * FROM users WHERE user_name = '$user_name' LIMIT 1";
        $result = mysqli_query($con, $query);

        if($result && mysqli_num_rows($result) > 0)
        {
            $user_data = mysqli_fetch_assoc($result);
            
            if($user_data['password'] === $password)
            {
                $_SESSION['user_id'] = $user_data['user_id'];
                $_SESSION['user_name'] = $user_data['user_name'];
                header("Location: index.php");
                die;
            } else {
                $error_msg = "Wrong username or password!";
            }
        } else {
            $error_msg = "Wrong username or password!";
        }
    } else {
        $error_msg = "Please enter valid information!";
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
        <div class = "box-title"> Login </div>

        <div class = "input-outer">
          <input class= "input-box" type = "text" name ="user_name" placeholder="Username"> 
          <input class= "input-box" type = "password" name = "password" placeholder="password">

          <?php if(!empty($error_msg)): ?>
            <div class="error-message"><?php echo $error_msg; ?></div>
          <?php endif; ?>

          <a class= "forgot-pw" href="forgot-pw.php">Forgot password?</a>
        </div>
     

        <div class = "submit-outer">
          <input class= "submit-btn" type = "submit" value = "Log In"> 

        </div>

        <div class= "signup-outer">
          <p class="signup-ques">Don't have an account?<p>

          <div class ="signup-btn">
            <a class= "signup-text" href="signup.php">Signup</a>
          </div>
        </div>
        


      </form>

    </div>
  </div>
  


  <script src="login-js/login.js"></script>

</body>
</html>