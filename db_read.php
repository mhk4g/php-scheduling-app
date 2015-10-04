<?php 

$user = "mhk4g";
$pass = "password";

$db = new mysqli('localhost', $user, $pass, "ScheduleDB");
if ($db->connect_error):
    die ("Could not connect to db: " . $db->connect_error);
  endif;
  
$maker = "1";
$scheduleID = "1";
$i = 0;

// Loading all the schedules from one maker into an array
$result = $db->query("SELECT * FROM SCHEDULES WHERE MAKER = '$maker'");
$timeslot = $result->fetch_assoc();
print_r($timeslot);

  //echo("<t>$timeslot</tc>");


// Loading all timeslots for a single schedule
$result = $db->query("SELECT * FROM TIMESLOTS WHERE SCHEDULE = '$scheduleID';");
//print_r($result . "<br>");

// Printing each row to the screen
for($i = 0; $i < $result->num_rows; $i++):
  $timeslot = $result[$i];
  $slotname = $timeslot["name"];  
  echo("<tc>$timeslot</tc>");
endfor;

#   *IDEA*: Add each user's checkmark list as a serialized array column in the user table

// Printing each user's names and checkmarks to the screen 
// Serialize the array? Give each checkmark its own boolean-sized entry?
$result = $db->query("SELECT * FROM Users WHERE Schedule = '$scheduleID'");
for($i = 0; $i < $result->num_rows; $i++):
  $row = $result[$i];
  $name = $row["name"];
  echo("<tr>$name<td></td></tr>");
endfor;
?>