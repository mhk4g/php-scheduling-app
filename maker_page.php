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

# Print image and welcome message
echo("<html><head><title>Maker account page</title></head><body>");
echo("<br><p align=\"center\"><img src=\"./img/edit.png\">");
echo("<p><pre><font color=\"black\"><p align=\"center\">Welcome, $name!</font><br><br>");

if(isset($_SESSION["error"])) {
  $error = $_SESSION["error"];
  unset($_SESSION["error"]);
  echo("<p><pre><font color=\"red\"><p align=\"center\">$error</font></pre>");
}

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

# For each schedule belonging to the current maker...
for($i = 0; $i < $num_schedules; $i++) {
  $currentSchedule = $scheduleArray->fetch_assoc();
  $currentScheduleID = $currentSchedule["ID"];
  $currentScheduleName = $currentSchedule["name"];
  $currentScheduleNumslots = $currentSchedule["numslots"];
  $currentScheduleIsFinalized = $currentSchedule["finalized"];

  # Create the table to display the schedule
  echo("<table border = \"1\" cellpadding = \"4\" width=\"50%\" align=\"center\">");
  echo("<caption><h2>$currentScheduleName</h2>");
  if(!$currentScheduleIsFinalized):
    echo("<form action=\"finalize.php\" method=\"POST\">");
    echo("<input type=\"submit\" name=\"finalize\" value=\"Finalize this schedule\" onclick=\"return confirm('Are you sure you want to finalize this table? This cannot be undone.')\">");
    echo("<input type=\"hidden\" name=\"which\" value=\"$i\"><p></form>");
  else:
    echo("(Final)<p>");
  endif;
  echo("</caption>");
  echo("<tr align = \"center\">");
  echo("<th style=\"width:40px\">Name</th>");
  echo("<th style=\"width:40px\">Email</th>");
  echo("<th style=\"width:40px\">ID</th>");

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

  #     
  echo("<tr align = \"center\">");
  echo("<td colspan=\"3\"><b>Total</b></td>");
  for($m = 0; $m < $currentScheduleNumslots; $m++) {
    echo("<td><b>$checksPerSlot[$m]</b></td>"); 
 }
 // * * * * PRINT EDIT BUTTONS * * * * //
  echo("</table><br><br>");

} // SCHEDULE

// * * * * PRINT LOGOUT BUTTON * * * * //
echo("<br><br><br><br><br><br><br><br><br><br>");
echo("<form action=\"logout.php\" method=\"post\"><pre><p align=\"center\"><input type=\"submit\" name=\"logout\" value=\"Logout\">");
echo("</pre></form></body>");
?>