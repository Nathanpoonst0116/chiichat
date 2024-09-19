<?php

function check_login($con)
{

	if(isset($_SESSION['user_id']))
	{

		$id = $_SESSION['user_id'];
		$query = "select * from users where user_id = '$id' limit 1";

		$result = mysqli_query($con,$query);
		if($result && mysqli_num_rows($result) > 0)
		{

			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	}

	//redirect to login
	header("Location: login.php");
	die;

}

function get_icon($con) {
  $id = $_SESSION['user_id'];

  $query = "SELECT user_name, user_icon FROM users WHERE user_id = '$id'";
  $result = mysqli_query($con, $query);
  $user = mysqli_fetch_assoc($result);

  if($result && mysqli_num_rows($result) > 0) {
    return '<img class= "user-icon" src="' . htmlspecialchars($user['user_icon']) . '" alt="User Icon">' ;
  } else {
    return '<img class= "user-icon" src="login-pic/default-icon.jpeg" alt="Default Icon">';
  }

}

function get_other_icon($con, $id) {

  $query = "SELECT user_name, user_icon FROM users WHERE user_id = '$id'";
  $result = mysqli_query($con, $query);
  $user = mysqli_fetch_assoc($result);

  if($result && mysqli_num_rows($result) > 0 && isset($result)) {
    return '<img class= "user-icon-add" src="' . htmlspecialchars($user['user_icon']) . '" alt="User Icon">' ;
  } else {
    return '<img class= "user-icon-add" src="login-pic/default-icon.jpeg" alt="Default Icon">';
  }

}


function random_num($length) {
    $text = "";
    if ($length < 5) {
        $length = 5;
    }

    $len = rand(4, $length);

    for ($i = 0; $i < $len; $i++) { 
        $text .= rand(0, 9);
    }

    return $text;
}

function get_user_name($con, $userId) {
  $query = "SELECT user_name FROM users WHERE user_id = ?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->fetch_assoc()['user_name'] ?? 'Unknown User';
}

function get_selected_user_id() {


}