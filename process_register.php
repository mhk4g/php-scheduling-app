<?php
session_start();
session_unset();

# Reload login page if user or pass were left blank
if(empty($_POST["username"]) || empty($_POST["password"])):
  $_SESSION["error"] = "Please enter an email address and password.";
  header("Location: register_page.php");
  die;
endif;

$username = $_POST["username"];
$password = $_POST["password"];
$name = $_POST["newname"];
$dbuser = "mhk4g";
$dbpass = "password";
$hashedpw = hash("sha256", $password);

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

# If register was clicked...
if(isset($_POST["register"])) {
  
  # Attempt to insert into database
  $query = "INSERT INTO Makers (name, email, password) VALUES ('$name', '$username', '$hashedpw')"; 
  $result = $db->query($query);
  var_dump($result);
  
  # If the entry is successfully created...
  if($result):
    $_SESSION["name"] = $name;
    $_SESSION["username"] = $username;
    header("Location: maker_menu.php");
    
  # If it fails...
  else:
    $_SESSION["error"] = "An account with that email address already exists.";
    header("Location: register_page.php");
  endif;
}
?>