<?php 
session_start();
print_r($_SESSION);
print_r($_POST);

if(!isset($_POST["step"])):
  $_SESSION["step"] = 1;
else:
  $_SESSION["step"] = $_POST["step"];
endif;

$dbuser = "mhk4g";
$dbpass = "password";
$step = $_SESSION["step"];

# Set $maker and $name based on db entries
if(isset($_SESSION["maker_email"])):
  $maker = $_SESSION["maker_email"];
else:
  $_SESSION["error"] = "Please log in to access your maker account.";
  header("Location: login_page.php");
  die;
endif;

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
<?php
  echo("<pre><p align =\"center\"><b>Step $step</b></pre>");
if($step == 1) {
  echo("<form action=\"new_schedule_page.php\" method=\"POST\"><pre>
  <p align =\"center\">  Name of schedule:   <input type=\"text\" name=\"schedulename\" autocomplete=\"off\" autofocus=\"autofocus\" size=55 required> 
 
 How many users would you like to add?      <input type=\"number\" name=\"numusers\" autocomplete=\"off\" min=\"2.0\" max=\"30.0\" step=\"1.0\" required>
  
 How many time slots would you like to add? <input type=\"number\" name=\"numslots\" autocomplete=\"off\" min=\"2.0\" max=\"10.0\" step=\"1.0\" required>
 <p align=\"center\"><input type=\"hidden\" name=\"step\" value=2><input type=\"submit\" name=\"submit\" value=\"Submit\"></form>"); 
}

elseif($step == 2) {
  $numslots = $_POST["numslots"];
  $numusers = $_POST["numusers"];
  $schedulename = strtr($_POST["schedulename"], " ", "*");
  echo("<form action=\"process_new_schedule.php\" method=\"POST\"><pre>
  <p align =\"center\"> Enter user names and email addresses below. You may use only first names or first <i>and</i> last.
  
  
  <b>User name                Email address</b>         ");
  
  for($i = 1; $i < $_POST["numusers"] + 1; $i++) {
    echo("<p align=\"center\">$i. <input type=\"text\" name=\"n$i\" size=25 autocomplete=\"off\" required>     <input type=\"text\" name=\"e$i\" size=25 autocomplete=\"off\" required>   ");
  } 
  $step++;
 echo("
 
 <p align =\"center\"><b>Step $step</b>
 <p align =\"center\"> Enter time slots below in chronological order. Example of recommended format: 5/25/2015 16:00
 
 
 <b>Time slots</b>");
 
 for($i = 1; $i < $numslots + 1; $i++) {
   echo("<p align=\"center\">$i. <input type=\"text\" name=\"s$i\" size=25 autocomplete=\"off\" required>");
 }
 $step++;
echo("<p align=\"center\"><input type=\"hidden\" name=\"numslots\" value=$numslots><input type=\"hidden\" name=\"numusers\" value=$numusers><input type=\"hidden\" name=\"schedulename\" value=$schedulename>
<p align =\"center\"><b>Step $step</b>


Press this button to create your schedule. This cannot be undone.


<input type=\"submit\" name=\"addtoDB\" value=\"Create this schedule\" onclick=\"return confirm('Are you sure you want to create this schedule?')\"></form>");
 
}


?>
<br><br><br><br><br><br><br>
<form action="logout.php" method="post"><pre><p align="center"><input type="submit" name="logout" value="Logout">
</pre></form></body>