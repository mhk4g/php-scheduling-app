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

# Get and store the maker ID in a local variable 
$result = $db->query("SELECT ID FROM Makers where email='$maker'");
$makerID = $result->fetch_assoc()["ID"];
$_SESSION["makerID"] = $makerID;

$scheduleArray = $db->query("SELECT * FROM Schedules WHERE maker = '$makerID'");
$timeslot = $result->fetch_assoc();
print_r($timeslot);

/*
$date = strtotime($tempstrings[0]);
$currentdate = date('l m/d/y', $date);

$times = explode("|",$tempstrings[1]);
foreach($times as $currenttime):
  $unformatted = strtotime($currenttime);
  $formatted = $currentdate . "\n" . date('g:i A',$unformatted);
  $slots[$slotindex] = $formatted;
  $slotindex++;
  $numslots++;
endforeach;

/ * * * * * Table Creation * * * * 
<table border = "1" cellpadding = "4" width="90%" align="center">
<caption><h2>Select your meeting times</h2></caption>
<tr align = "center">
  <th style="width:40px">User</th>
  <th style="width:40px">Action</th>

<?php
# Prints the headers from the $slots array
foreach($slots as $s){
  echo("<th style=\"width:40px\"><b>$s</b></th>");
  }
echo("</tr>");

 * * * * * Data cell population * * * * 
$userindex = 0;
# Prints one row for each existing user
foreach($users as $u){
  echo("<tr align = \"center\">");

  # Displays Edit button if cookie is set
  if (isset($_COOKIE[strtr($u, " ", "_")])) {
    echo("<form action=\"editing.php\" method=\"POST\">");
    echo("<td><b>$u</b></td>");
    echo("<td><input type=\"submit\" name=\"$u\" value=\"Edit\"<td>");
    echo("<input type=\"hidden\" name=\"oldname\" value=\"$u\">");
    echo("<input type=\"hidden\" name=\"userindex\" value=\"$userindex\">");
    echo("</form>");
    }
  else {
    echo("<td><b>$u</b></td>");
    echo("<td> </td>");
  }
  display_populated_row($u, $numslots, $userindex, $userarrays);
  $userindex++;
}



*/


?>

<html>
<head>
  <title>Maker account page</title>
</head>
<body>
  <br><p align="center"><img src="./img/edit.png">
    <?php
  echo("<p><pre><font color=\"black\"><p align=\"center\">Welcome, $name!</font></pre>");
    ?>
<br><br><br><br><br><br><br><br><br><br>
<form action="logout.php" method="post"><pre><p align="center"><input type="submit" name="logout" value="Logout">
</pre></form></body>