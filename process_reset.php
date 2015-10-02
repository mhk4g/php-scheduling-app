<?php
session_start();
session_unset();

# Reload login page if user or pass were left blank
if(empty($_POST["username"])):
  $_SESSION["error"] = "Please enter a valid email address.";
  header("Location: reset_page.php");
  die;
endif;

$username = $_POST["username"];
$dbuser = "mhk4g";
$dbpass = "password";

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

# If login button was clicked...
if(isset($_POST["reset"])) {
  $result = $db->query("SELECT * from Makers where email='$username'");
  
  if (mysqli_num_rows($result) > 0):
    # Username exists. Login!
    echo("A password reset link has been sent to your email address.");
  
  else:
    # Login failed. Return to the first page with failed post message.
    $_SESSION["error"] = "There is no maker account associated with the email address you provided.";
    header("Location: reset_page.php");
  endif;
  }
?>