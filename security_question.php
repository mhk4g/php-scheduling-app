<?php 
session_start();

$dbuser = "mhk4g";
$dbpass = "password";

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

# First visit: username is in GET link, so switch it to session and reload
if(isset($_GET["username"])) {
  
  # First visit: switch to session variables and reload
  $username = strtr($_GET["username"], "*", "@");
  $_SESSION["username"] = $username;
  header("Location: security_question.php");
  die;
}  
  
# Subsequent visits: username is in SESSION
elseif(isset($_SESSION["username"])) {

$username = $_SESSION["username"];
$SESSIONresult = $db->query("SELECT * from Makers where email='$username'");

# If submit flag is active, then check the info.
if(isset($_POST["submit"])) {
  unset($_POST["submit"]);
  $answer = hash("sha256",$_POST["answer"]);
  $POSTresult = $db->query("SELECT * from Makers where securityanswer='$answer'");
  
  # If the security question was answered correctly and new password is valid...
  if ((mysqli_num_rows($POSTresult) > 0) && ($_POST["newpass"] == $_POST["confirm"])):
    $hashedpw = hash("SHA256", $_POST["newpass"]);
    $resetpw = $db->query("UPDATE Makers SET password = '$hashedpw' WHERE email = '$username'");
    $_SESSION["error"] = "Password reset successful.";
    header("Location: login_page.php");
    die;
    
  # If answer was right but new password is invalid...
  elseif((mysqli_num_rows($POSTresult) > 0) && ($_POST["newpass"] != $_POST["confirm"])):
    $_SESSION["error"] = "The new password you entered could not be confirmed. Please try again.";
    header("Location: security_question.php");
    die;
  
  # If the answer was incorrect...
  else:
    $_SESSION["error"] = "Your answer is incorrect. Please try again.";
    header("Location: security_question.php");
    die;
    endif;
  }
  
  if (mysqli_num_rows($SESSIONresult) > 0):
    
    # Username exists. Print question!
    $temp = $SESSIONresult->fetch_assoc();
    $question = $temp["securityquestion"];
    $answer = hash("sha256", $temp["securityanswer"]);
    
    echo("
    <html><head><title>Maker password reset</title></head><body>
    <br><p align=\"center\"><img src=\"./img/reset.png\">");
    
    # Standard error message display
    if(isset($_SESSION["error"])):
      $error = $_SESSION["error"];
      unset($_SESSION["error"]);
      echo("<p><pre><font color=\"red\"><p align=\"center\">$error</font></pre>");
    else:
      echo("<p><pre><font color=\"white\"><p align=\"center\"> </font></pre>");
    endif;
    
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
  $_SESSION["error"] = "Something went terribly wrong. Let's start over, shall we?";
  header("Location: login_page.php");
  die;
endif;
}

?>