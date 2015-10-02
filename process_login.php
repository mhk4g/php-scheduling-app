<?php
session_start();

$username = $_POST["username"];
$password = $_POST["password"];
$hashedpw = hash("sha256", $password);




$dbuser = "mhk4g";
$dbpass = "password";

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

  # If login button was clicked...
  if(isset($_POST["login"])) {
    echo("<br>LOGIN<br>");
    $result = $db->query("SELECT * from Makers where email='$username' AND password='$hashedpw'");
    
    if (mysqli_num_rows($result) > 0) {
      # Username exists. Login!
      echo("GOOD JOB LOGIN!");
      }
    
    else {
      # Login failed. Return to the first page with failed post message.
      $_SESSION["error"] = "Invalid email address or password.";
      header("Location: assignment2.php");
        }
      }
  
  # If register was clicked...
  elseif(isset($_POST["register"])) {
    echo("<br>REGISTER<br>");
    $name = $_POST["newname"];
    
    # Attempt to inert into database
    $query = "INSERT INTO Makers (name, email, password) VALUES ('$name', '$username', '$hashedpw')"; 
    $result = $db->query($query);
    var_dump($result);
    
    # If the entry is successfully created...
    if($result):
      $_SESSION["name"] = $name;
      $_SESSION["username"] = $username;
      #header("Location: maker_menu.php");
    
    # If it fails...
    else:
      $_SESSION["error"] = "An account with that email address already exists.";
      header("Location: assignment2.php");
    endif;
  }
  
  elseif(isset($_POST["forgot"])) {
    echo("<br>FORGOT<br>");
    header("Location: forgot_password.php");
  }
  
  var_dump($_POST);
?>