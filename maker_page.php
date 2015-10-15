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
$maker_ID = $result->fetch_assoc()["ID"];
$_SESSION["maker_ID"] = $maker_ID;

echo("<form action=\"new_schedule_page.php\" method=\"POST\"><p align=\"center\"><input type=\"submit\" name=\"newschedule\" value=\"Make a new schedule\"></form><br>");

$scheduleArray = $db->query("SELECT * FROM Schedules WHERE maker = '$maker_ID'");
$num_schedules = $scheduleArray->num_rows;
$currentWinnerValue = 0;
$currentWinnerIndex = 0;
$currentWinnerID = 0;

# For each schedule belonging to the current maker...
for($i = 1; $i < $num_schedules + 1; $i++) {
  $currentSchedule = $scheduleArray->fetch_assoc();
  $currentScheduleID = $currentSchedule["ID"];
  $currentScheduleName = $currentSchedule["name"];
  $currentScheduleNumslots = $currentSchedule["numslots"];
  $currentScheduleFinalized = (bool)$currentSchedule["finalized"];
  $currentScheduleWinner = $currentSchedule["winningslotindex"];
  if($currentScheduleFinalized):
    $winnerTemp = $db->query("SELECT datestring FROM Timeslots WHERE ID = '$currentScheduleWinner'");
    $winnerString = $winnerTemp->fetch_row()[0];
  endif;
;

  # Create the table to display the schedule
  echo("<table border = \"1\" cellpadding = \"4\" width=\"50%\" align=\"center\">");
  echo("<caption><h2>$currentScheduleName</h2>");
  if(!$currentScheduleFinalized):
    echo("<form action=\"process_finalize.php\" method=\"POST\">");
    echo("<input type=\"submit\" name=\"finalize\" value=\"Finalize this schedule\" onclick=\"return confirm('Are you sure you want to finalize this table? This cannot be undone.')\">");
    echo("<input type=\"hidden\" name=\"which\" value=\"$currentScheduleID\"><p></form>");
  else:
    echo("<p align=\"center\">(Final: $winnerString)");
  endif;
  echo("</caption>");
  echo("<tr align = \"center\">");
  echo("<th style=\"width:40px\">Name</th>");
  echo("<th style=\"width:40px\">Email</th>");
  echo("<th style=\"width:40px\">ID</th>");

  # Fetch timeslots from DB
  $timeSlotArray = $db->query("SELECT * FROM Timeslots WHERE schedule = '$currentScheduleID'");
  $checksPerSlot = [];
  $indexToID = [];
  
  # Write each timeslot to its own column header
  for($j = 0; $j < $currentScheduleNumslots; $j++){
    $currentColumn = $timeSlotArray->fetch_assoc();
    $currentColumnString = $currentColumn["datestring"];
    $checksPerSlot[] = 0;
    $indexToID[] = $currentColumn["ID"];
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
      if($checksPerSlot[$l] > $currentWinnerValue):
        $currentWinnerValue = $checksPerSlot[$l];
        $currentWinnerIndex = $l;
        $currentWinnerID = $indexToID[$l];
      endif;
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

# Store the current winning slots for each schedule  
$_SESSION["best_slot_ID$currentScheduleID"] = $currentWinnerID;
$_SESSION["best_slot_value$currentScheduleID"] = $currentWinnerValue;
$_SESSION["best_slot_index$currentScheduleID"] = $currentWinnerIndex;
  
  $checksPerSlot = [];
  $indexToID = [];
  $currentWinnerValue = 0;
  $currentWinnerID = 0;
  $currentWinnerIndex = 0;
 }
 
  echo("</table><br><br>");


// * * * * PRINT LOGOUT BUTTON * * * * //
echo("<br><br><br><br><br><br>");
echo("<form action=\"logout.php\" method=\"post\"><pre><p align=\"center\"><input type=\"submit\" name=\"logout\" value=\"Logout\">");
echo("</pre></form></body>");
?>