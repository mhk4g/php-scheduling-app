<?php 
session_start();

$dbuser = "mhk4g";
$dbpass = "password";
$numslots = $_SESSION["numslots"];
$userID = $_SESSION["userID"];
$sessionID = $_SESSION["scheduleID"];

print_r($_POST);
print_r($_SESSION);

$writestring = "";
$writestring = $writestring . (isset($_POST["box0"]) ? "1"  : (string)"0");

for($i = 1; $i < $numslots; $i++):
  if(isset($_POST["box" . $i])):
    $writestring .= "^1";
  else:
    $writestring .= "^0";
  endif; 
endfor;

echo($writestring);

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }
  
$result = $db->query("UPDATE Users SET checkboxes = '$writestring' WHERE ID = '$userID'");

header("Location: user_page.php");
 ?>