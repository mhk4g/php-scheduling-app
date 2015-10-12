<?php
session_start();

$dbuser = "mhk4g";
$dbpass = "password";
$numusers = $_POST["numusers"];
$numslots = $_POST["numslots"];
$schedulename = $_POST["schedulename"];
$makerID = $_SESSION["maker_ID"];

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

# If register was clicked...
if(isset($_POST["addtoDB"])) {
  
  # STEP 1: CREATE SCHEDULE
  $schedulequery = "INSERT INTO Schedules (maker, name, numslots, finalized) VALUES ('$makerID', '$schedulename', '$numslots', '0')";
  
  # Attempt to insert into database
  $query = "INSERT INTO Makers (name, email, password, securityquestion, securityanswer) VALUES ('$name', '$username', '$hashedpw', '$question', '$hashedanswer')";
  $result = $db->query($query);
  
  # If the entry is successfully created...
  if($result):
    $_SESSION["maker_email"] = $username;
    $_SESSION["maker_name"] = $name;
    # Set the maker ID
    header("Location: maker_page.php");
      
  # Otherwise, the insert fails because 
  else:
    $_SESSION["error"] = "An account with that email address already exists.";
    header("Location: register_page.php");
    die;
  endif;
}
?>