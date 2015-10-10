<?php 
session_start();

$dbuser = "mhk4g";
$dbpass = "password";


$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

$IDtoFinalize = $_POST["which"] + 1;

if(isset($_POST["which"])):
  $IDtoFinalize = $_POST["which"];
else:
  $_SESSION["error"] = "Failed to finalize schedule.";
  header("Location: maker_page.php");
endif;

$finalize = $db->query("UPDATE Schedules SET finalized = '1' WHERE ID = '$IDtoFinalize'");

header("Location: maker_page.php");
?>