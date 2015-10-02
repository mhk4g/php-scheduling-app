<?php
session_start();
session_unset();

# Reload login page if user or pass were left blank
if(empty($_POST["username"]) || empty($_POST["password"])):
  $_SESSION["error"] = "Please enter an email address and password.";
  header("Location: login_page.php");
  die;
endif;

$username = $_POST["username"];
$password = $_POST["password"];
$dbuser = "mhk4g";
$dbpass = "password";
$hashedpw = hash("sha256", $password);

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

# If login button was clicked...
if(isset($_POST["login"])) {
  $result = $db->query("SELECT * from Makers where email='$username' AND password='$hashedpw'");
  
  if (mysqli_num_rows($result) > 0):
    # Username exists. Login!
    $_SESSION["username"] = $username;
    # Set the maker ID
    # Set the maker name
    header("Location: maker_page.php");
  
  else:
    # Login failed. Return to the first page with failed post message.
    $_SESSION["error"] = "Invalid email address or password.";
    header("Location: login_page.php");
  endif;
  }
?>