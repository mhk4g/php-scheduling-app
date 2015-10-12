<?php 
session_start();

$winnerID = $_SESSION["best_slot_ID"];
$winnerValue = $_SESSION["best_slot_value"];
$winnerIndex = $_SESSION["best_slot_index"];

$dbuser = "mhk4g";
$dbpass = "password";

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

if(isset($_POST["which"])):
  $IDtoFinalize = $_POST["which"];
else:
  $_SESSION["error"] = "Failed to finalize schedule.";
  header("Location: maker_page.php");
endif;

$finalize = $db->query("UPDATE Schedules SET finalized = '1' WHERE ID = '$IDtoFinalize'");

 # UPDATE DB

 # SEND MAIL

header("Location: maker_page.php");
?>