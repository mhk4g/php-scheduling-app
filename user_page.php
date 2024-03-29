<?php  
session_start();

if(isset($_GET["userID"])):
  $scheduleID = $_GET["scheduleID"];
  $userID = (int)$_GET["userID"];
  $_SESSION["userID"] = $userID;
  $_SESSION["scheduleID"] = $scheduleID;
else:
  $scheduleID = $_SESSION["scheduleID"];
  $userID = (int)$_SESSION["userID"];
endif;

$dbuser = "mhk4g";
$dbpass = "password";

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }
  
$result = $db->query("SELECT name FROM Users WHERE ID='$userID'");
$resultArray = $result->fetch_assoc();
$userFirstName = explode(" ",$resultArray["name"])[0];

# Print image and welcome message
echo("<html><head><title>User page</title></head><body>");
echo("<br><p align=\"center\"><img src=\"./img/welcome.png\">");
echo("<p><pre><font color=\"black\"><p align=\"center\">Welcome, $userFirstName!</font><br><br>");

# Get and store the maker ID in a local variable 

$scheduleArray = $db->query("SELECT * FROM Schedules WHERE ID = '$scheduleID'");
$num_schedules = $scheduleArray->num_rows;

# For each schedule belonging to the current maker...
for($i = 0; $i < $num_schedules; $i++) {
  $currentSchedule = $scheduleArray->fetch_assoc();
  $currentScheduleID = $currentSchedule["ID"];
  $currentScheduleName = $currentSchedule["name"];
  $currentScheduleNumslots = $currentSchedule["numslots"];
  $currentScheduleFinalized = (bool)$currentSchedule["finalized"];
  $currentScheduleWinner = $currentSchedule["winningslotID"];
  if($currentScheduleFinalized):
    $winnerTemp = $db->query("SELECT datestring FROM Timeslots WHERE ID = '$currentScheduleWinner'");
    $winnerString = $winnerTemp->fetch_row()[0];
  endif;
  
  # Create the table to display the schedule
  echo("<table border = \"1\" cellpadding = \"4\" width=\"50%\" align=\"center\">");
  echo("<caption><h2>$currentScheduleName</h2>");
  if($currentScheduleFinalized):
    echo("<p align=\"center\">(Final: $winnerString)");
  endif;
  echo("</caption>");
  echo("<tr align = \"center\">");
  echo("<th style=\"width:40px\">Name</th>");
  echo("<th style=\"width:40px\">Email</th>");
  echo("<th style=\"width:40px\">Edit</th>");

  # Fetch timeslots from DB
  $timeSlotArray = $db->query("SELECT * FROM Timeslots WHERE schedule = '$currentScheduleID'");
  $checksPerSlot = [];
  
  # Write each timeslot to its own column header
  for($j = 0; $j < $currentScheduleNumslots; $j++){
    $currentColumn = $timeSlotArray->fetch_assoc();
    $currentColumnString = $currentColumn["datestring"];
    $checksPerSlot[] = 0;
    echo("<th style=\"width:40px\"><b>$currentColumnString</b></th>");
  }
  echo("</tr>");
  
  # Get all users that are linked to the current schedule
 $userArray = $db->query("SELECT * FROM Users WHERE schedule = '$currentScheduleID'");
 
 # Write each user to their own row
  for($k = 0; $k < $userArray->num_rows; $k++) {
    $currentUser = $userArray->fetch_assoc();
    $currentUserID = (int)$currentUser["ID"];
    $currentUserEmail = $currentUser["email"];
    $currentUserName = $currentUser["name"];
    echo("<tr align = \"center\" valign=\"middle\">");
    echo("<td>$currentUserName</td>");
    echo("<td>$currentUserEmail</td>");
    if($currentUserID == $userID && !$currentScheduleFinalized):
      echo("<td valign=\"center\"><form action=\"user_edit_page.php\" method=\"post\"><p><input type=\"submit\" name=\"submit\" value=\"Edit\"></form>");
    else:
      echo("<td><form action=\"user_edit.php\" method=\"post\"><p><input type=\"submit\" name=\"nullbutton\" value=\" \" hidden><font color=\"white\">N\A</font></form>");    
    endif;
    echo("</td>");
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

  echo("<tr align = \"center\">");
  echo("<td colspan=\"3\"><p><p><b>Total</b><p></td>");
  for($m = 0; $m < $currentScheduleNumslots; $m++) {
    echo("<td><b>$checksPerSlot[$m]</b></td>"); 
 }

}

?>
