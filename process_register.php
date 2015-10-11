<?php
session_start();
session_unset();

# Reload login page if user or pass were left blank
if(empty($_POST["username"]) || empty($_POST["password"])):
  $_SESSION["error"] = "Please enter an email address and password.";
  header("Location: register_page.php");
  die;
endif;

$dbuser = "mhk4g";
$dbpass = "password";
$username = $_POST["username"];
$password = $_POST["password"];
$confirm = $_POST["password2"];
$question = $_POST["question"];
$name = $_POST["newname"];
$hashedanswer = hash("sha256", $_POST["answer"]);
$hashedpw = hash("sha256", $password);

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

# If the passwords don't match, go back.  
if($password != $confirm) {
    $_SESSION["error"] = "The passwords you provided do not match. Please try again.";
    header("Location: register_page.php");
    die;
}

# If register was clicked...
if(isset($_POST["register"])) {
  
  # Attempt to insert into database
  $query = "INSERT INTO Makers (name, email, password, securityquestion, securityanswer) VALUES ('$name', '$username', '$hashedpw', '$question', '$hashedanswer')";
  $result = $db->query($query);
  
  # If the entry is successfully created...
  if($result):
    $_SESSION["maker_email"] = $username;
    $_SESSION["maker_name"] = $name;
    # Set the maker ID
    header("Location: maker_page.php");
      
  # Otherwise, the insert fails because 
  else:
    $_SESSION["error"] = "An account with that email address already exists.";
    header("Location: register_page.php");
    die;
  endif;
}
?>