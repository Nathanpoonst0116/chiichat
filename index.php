<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($con);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
  $senderId = $user_data['user_id']; // Current user's ID
  $receiverId = $_POST['receiver_id']; // ID of the user you are chatting with
  $messageText = $_POST['message']; // Message text from the input

  // Check if the message is not empty
  if (!empty($messageText)) {
      // Prepare the insert statement
      $insertQuery = "INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (?, ?, ?)";
      $stmt = $con->prepare($insertQuery);
      $stmt->bind_param("iis", $senderId, $receiverId, $messageText);
      
      if ($stmt->execute()) {
          // Fetch the last inserted message to return
          $lastId = $stmt->insert_id;
          $stmt->close();

          // Fetch the message to return
          $messageQuery = "SELECT * FROM messages WHERE id = ?";
          $stmt = $con->prepare($messageQuery);
          $stmt->bind_param("i", $lastId);
          $stmt->execute();
          $result = $stmt->get_result();
          $messageData = $result->fetch_assoc();

          // Return the message as JSON
          //echo json_encode($messageData);
      } else {
          //echo json_encode(["error" => "Error sending message: " . $stmt->error]);
      }

      // Close the statement
      $stmt->close();
  }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>index web</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">    

  <link rel="stylesheet" href="login-css/login.css">
  <link rel="stylesheet" href="login-css/signup.css">
  <link rel="stylesheet" href="login-css/index.css">
  <link rel="stylesheet" href="login-css/index-add-user.css">
  <link rel="stylesheet" href="login-css/index-side-bar.css">
  <link rel="stylesheet" href="login-css/index-chat.css">




</head>
<body>

  <div class = "login-outer">
    <img class = "bg" src ="login-pic/bg-main.jpg">

    <div id = "index-outer" class = "index-outer">

      
      <div class = "side-bar">
        <div class = logout-outer>
          <a class ="logout" href="logout.php"><i class="bi bi-box-arrow-right"></i></a>

        </div>
        
      </div>

      <div class = "left-contact-list">
        <div class = "contact-header">
          <p class="header-chat-text"> <strong>Chat</strong></p>

          <button id = "add-user" class = "add-user">
            <i class="bi bi-plus-circle"></i>
          </button>

          <div id = "user-list" class = "user-list">
            <p class = "list-text"> Add user into chat </p>
            
             
            <?php
            // Fetch all users except the current user
            $query = "SELECT user_id, user_name FROM users WHERE user_id != '" . $user_data['user_id'] . "'";
            $result = mysqli_query($con, $query);

            while ($row = mysqli_fetch_assoc($result)) {
              $iconUrl = get_other_icon($con, $row['user_id']);
              
              // Use double quotes for the outer echo, single quotes for the inner PHP
              echo '<button id="user-btn-add" class="user-btn-add" data-id="' . $row['user_id'] . '" data-name="' . htmlspecialchars($row['user_name']) . '" onclick="selectUser(\'' . $row['user_id'] . '\', \'' . htmlspecialchars($row['user_name']) . '\', \'' . htmlspecialchars($iconUrl) . '\')">'
                  . $iconUrl .
                  htmlspecialchars($row['user_name']) .
                  '</button>';
              }
            ?>
            

          </div>
        </div>


        <div class = "contact-main">
          <div class = "contact-me">
            <div class = "icon-box">
                <?php echo get_icon($con); ?>
              
            </div>

            <div class = "contact-name">
              <?php echo $user_data['user_name']; ?> (You)
            </div>
          </div>

          <div class = "line"></div>

          <div id="contact-user-box" class="contact-user-box">

          </div>


        

        </div>

      </div>
      <div class = "main-chat-box">
        <div id = "chat-header" class = "chat-header">
          <div class = "header-text"></div>

          <div class = "chat-info">

            <?php 
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"])) {
              $selectedUserId = $_POST["user_id"];
              // Process the received variable here
              echo "Received userId: " . $selectedUserId . '<br>';
            } else {
                echo "No data received <br>";
            }

            echo "selected userId: " . $selectedUserId . '<br>';
            echo "userId: " . $user_data['user_id'];
            ?>

          </div>

        </div>

        <div id = "chat-msg-box" class="chat-msg-box">
          <div id = "msg" class = "msg">

            <button id = 'go-bottom' class = "go-bottom"><strong>Go to bottom </strong></button>

             <!-- Hello, <?php echo htmlspecialchars($user_data['user_name']); ?> <br> -->

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"])) {
              $selectedUserId = $_POST["user_id"];
            } 


            if ($selectedUserId) {
              // Database connection (ensure this is set before this code)
              $con = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
              if ($con->connect_error) {
                  die("Connection failed: " . $con->connect_error);
              }
  
              // Prepare the SQL query
              $sql = "
              SELECT * FROM messages 
              WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
              ORDER BY timestamp ASC";
              $stmt = $con->prepare($sql);
              $stmt->bind_param("iiii", $user_data['user_id'], $selectedUserId, $selectedUserId, $user_data['user_id']);
  
              // Execute the query
              $stmt->execute();
              $result = $stmt->get_result();
  
              // Display messages
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $messageText = htmlspecialchars($row['message_text']);
                    
                    $timestamp = strtotime($row['timestamp']);
                    
                    $formattedTime = date('m-d H:i', $timestamp);
                    
                    $senderId = $row['sender_id'] == $user_data['user_id'] ? "You" : "User {$row['sender_id']}";
            
                    if ($row['sender_id'] == $user_data['user_id']) {
                        echo "<div class='msg-outer'> <p class='msg-name-me'>{$senderId} &nbsp{$formattedTime}</p> <div class='msg-text my-text'>{$messageText}</div></div>";
                    } else {
                        echo "<div class='msg-outer'> <p class='msg-name-other'>{$senderId} &nbsp{$formattedTime}</p> <div class='msg-text other-text'>{$messageText}</div></div>";
                    }
                }
              } else {
                 // echo "No messages found.";
              }
  
              // Close the statement and connection
              $stmt->close();
              $con->close();
            }

            ?>
            
          </div>
          
          
        </div>

        <div class="chat-input-box">

          <form class="message-form" id="messageForm" method="post" action="index.php">
              <input class="message-box" type="text" name="message" autocomplete="off" placeholder="Type a message" required>
              <input type="hidden" id="receiverId" name="receiver_id" value="">
              <div class="send-outer">
                  <input id = "send-btn" class="send-btn" type="submit" value="Send">
              </div>
          </form>

          

        </div>

      </div>
      
     

    </div>
    

  </div>

  <script src="login-js/add-user.js"></script>
  <script src="login-js/chat.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  
</body>
</html>