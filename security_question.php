<?php 
session_start();

$dbuser = "mhk4g";
$dbpass = "password";

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

# Standard error message display
if(isset($_SESSION["error"])):
  $error = $_SESSION["error"];
  unset($_SESSION["error"]);
  echo("<p><pre><font color=\"red\"><p align=\"center\">$error</font></pre>");
else:
  echo("<p><pre><font color=\"white\"><p align=\"center\"> </font></pre>");
endif;

# Arrived at page using email link should set this variable properly
if(isset($_GET["username"])) {
  $username = strtr($_GET["username"], "*", "@");
  $GETresult = $db->query("SELECT * from Makers where email='$username'");
  $_SESSION["username"] = $username;

# If submit flag is active, then check the info.
if(isset($_POST["submit"])) {
  unset($_POST["submit"]);
  $answer = hash("sha256",$_POST["answer"]);
  $POSTresult = $db->query("SELECT * from Makers where securityanswer='$answer'");
  
  # If the security question was answered correctly and new password is valid...
  if ((mysqli_num_rows($POSTresult) > 0) && $_POST["newpass"] == $_POST["confirm"]):
    $hashedpw = hash("SHA256", $_POST["newpass"]);
    $user = $_SESSION["username"];
    $resetpw = $db->query("UPDATE Makers SET password = '$hashedpw' WHERE email = '$user'");
    
  # If new password is invalid...
  elseif($_POST["newpass"] != $_POST["confirm"]):
    $_SESSION["error"] = "An account with that email address already exists.";
    header("Location: security_question.php");
    die;
  endif;
  
  }
  
  if (mysqli_num_rows($GETresult) > 0):
    
    # Username exists. Print question!
    $temp = $GETresult->fetch_assoc();
    $question = $temp["securityquestion"];
    $answer = hash("sha256", $temp["securityanswer"]);
    
    echo("
    <html><head><title>Maker password reset</title></head><body>
    <br><p align=\"center\"><img src=\"./img/reset.png\">");
    
    echo("<p><pre><font color=\"black\"><p align=\"center\">$question</font></pre>
    <pre><form action=\"security_question.php\" method=\"post\"><p align=\"center\">
    <p align=\"center\">  Security answer:   <input type=\"text\" name=\"answer\" autocomplete=\"off\" required>  
    <p align=\"center\">Password:          <input type=\"password\" name=\"newpass\" required>
    <p align=\"center\"> Confirm Password:  <input type=\"password\" name=\"confirm\" required> 
    <p align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Submit\"></form></pre>");
    echo("<br><br><br><br><br><br><br><br><br>
    <form action=\"redirect.php\" method=\"post\"><pre><p align=\"center\"><input type=\"submit\" name=\"login\" value=\"Login\">   <input type=\"submit\" name=\"register\" value=\"Register\">
    </pre></form></body>");
  
else:
    
  # Login failed. Return to the first page with failed post message.
  $_SESSION["error"] = "There is no maker account associated with that email address.";
  header("Location: login_page.php");
  
endif;
}


?>