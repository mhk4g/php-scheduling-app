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

?> 

<html>
<head>
  <title>Maker account page</title>
</head>
<body>
  <br><p align="center"><img src="./img/edit.png">
    <?php
  echo("<p><pre><font color=\"black\"><p align=\"center\">Welcome, $name!</font><br><br>");
    ?>

<?php
# Test method to print session 

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

# Get and store the maker ID in a local variable 
$result = $db->query("SELECT ID FROM Makers where email='$maker'");
$makerID = $result->fetch_assoc()["ID"];
$_SESSION["makerID"] = $makerID;

$scheduleArray = $db->query("SELECT * FROM Schedules WHERE maker = '$makerID'");
$num_schedules = $scheduleArray->num_rows;

// * * * * FOR EACH SCHEDULE* * * * //
for($i = 0; $i < $num_schedules; $i++) {
  $currentSchedule = $scheduleArray->fetch_assoc();
  $currentScheduleID = $currentSchedule["ID"];
  $currentScheduleName = $currentSchedule["name"];
  $currentScheduleNumslots = $currentSchedule["numslots"];

  echo("<table border = \"1\" cellpadding = \"4\" width=\"90%\" align=\"center\">");
  echo("<caption><h2>$currentScheduleName</h2></caption>");
  echo("<tr align = \"center\">");
  echo("<th style=\"width:40px\">Name</th>");
  echo("<th style=\"width:40px\">Email</th>");
  echo("<th style=\"width:40px\">ID</th>");

  # Fetch timeslots from DB
  $timeSlotArray = $db->query("SELECT * FROM Timeslots WHERE schedule = '$currentScheduleID'");
  $checksPerSlot = [];
  // * * * * FOR EACH TIMESLOT * * * * //
  for($j = 0; $j < $currentScheduleNumslots; $j++){
    $currentColumn = $timeSlotArray->fetch_assoc();
    $currentColumnString = $currentColumn["datestring"];
    $checksPerSlot[] = 0;
    echo("<th style=\"width:40px\"><b>$currentColumnString</b></th>");
  }
  echo("</tr>");
  
  //* * * * * Data cell population * * * *//
 $userArray = $db->query("SELECT * FROM Users WHERE schedule = '$currentScheduleID'");
 
 for($k = 0; $k < $userArray->num_rows; $k++) {
      $currentUser = $userArray->fetch_assoc();
      $currentUserID = $currentUser["ID"];
      $currentUserEmail = $currentUser["email"];
      $currentUserName = $currentUser["name"];
      echo("<tr align = \"center\">");
      echo("<td>$currentUserName</td>");
      echo("<td>$currentUserEmail</td>");
      echo("<td>$currentUserID</td>");
      $currentUserCheckboxes = explode("^", $currentUser["checkboxes"]);
      for($l = 0; $l < $currentScheduleNumslots; $l++) {
        if($currentUserCheckboxes[$l]):
        echo("<td>&#10003</td>");
        $checksPerSlot[$l]++;
      else:
        echo("<td> </td>");
      endif;
        }
      }
  
  // * * * * PRINT TOTALS PER SLOT * * * * //    
  echo("<tr align = \"center\">");
  echo("<td colspan=\"3\"><b>Total</b></td>");
 for($m = 0; $m < $currentScheduleNumslots; $m++) {
   echo("<td><b>$checksPerSlot[$m]</b></td>"); 
 }
 // * * * * PRINT EDIT BUTTONS * * * * //
  echo("</table><form action=\"edit_table.php\">");
  echo("<p align=\"center\"><input type=\"submit\" name=\"edit\" value=\"Edit this table\">");
  echo("<input type=\"submit\" name=\"finalize\" value=\"Finalize this table\"></form>");
  echo("<br><br>");

} // SCHEDULE

// * * * * PRINT LOGOUT BUTTON * * * * //
echo("<br><br><br><br><br><br><br><br><br><br>");
echo("<form action=\"logout.php\" method=\"post\"><pre><p align=\"center\"><input type=\"submit\" name=\"logout\" value=\"Logout\">");
echo("</pre></form></body>");
?>