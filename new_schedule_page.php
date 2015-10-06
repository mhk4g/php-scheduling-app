<?php 
session_start();

$dbuser = "mhk4g";
$dbpass = "password";

# Set $maker and $name based on db entries
if(isset($_SESSION["maker_email"])):
  $maker = $_SESSION["maker_email"];
  $name = explode(" ", $_SESSION["maker_name"])[0];
else:
  $_SESSION["error"] = "Please log in to access your maker account.";
  header("Location: login_page.php");
  die;
endif;

# Test method to print session 
print_r($_SESSION); 

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

?>

<html>
<head>
  <title>Maker account page</title>
</head>
<body>
  <br><p align="center"><img src="./img/new.png">
<br>
<form action="new_schedule_page.php" method="get"><pre>
<p align ="center"> How many users would you like to add? <input type="text" name="newusers" size=5 autocomplete="off"> 
</form>
<br><br><br><br><br><br><br><br><br>
<form action="logout.php" method="post"><pre><p align="center"><input type="submit" name="logout" value="Logout">
</pre></form></body>