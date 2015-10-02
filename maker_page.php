<?php  
session_start();

if(isset($_SESSION["username"])):
  $username = $_SESSION["username"];
  #$name = $_SESSION["name"];
  $first = $username;
else:
  $_SESSION["error"] = "Please log in to access your maker account.";
  header("Location: login_page.php");
  die;
endif;

$dbuser = "mhk4g";
$dbpass = "password";

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }
  
$result = $db->query("SELECT ID FROM Makers where email='$username'");
?>
<html>
<head>
  <title>Maker account page</title>
</head>
<body>
  <br><p align="center"><img src="./img/edit.png">
    <?php
    if(isset($_SESSION["error"])):
      $error = $_SESSION["error"];
      session_unset();
      echo("<p><pre><font color=\"red\"><p align=\"center\">$error</font></pre>");
  else:
  echo("<p><pre><font color=\"black\"><p align=\"center\">Welcome, $first!</font></pre>");
endif;
    ?>
<br><br><br><br><br><br><br><br><br><br>
<form action="logout.php" method="post"><pre><p align="center"><input type="submit" name="logout" value="Logout">
</pre></form></body>