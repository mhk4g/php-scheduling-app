<?php 

$dbuser = "mhk4g";
$dbpass = "password";
$success = TRUE;

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

$nms = ["Matt Kauper", "Friendly Maker", "Mister Thirdguy"];
$ems = ["mhk4g@virginia.edu", "mhkaup@gmail.com", "mhk2448@email.vccs.edu"];
$qs = ["In what city were you born?", "What is your favorite food?", "What was the name of your first pet?"];
$pws = [hash("SHA256", "secret"), hash("SHA256", "hello"), hash("SHA256", "password")];
$ans = [hash("SHA256", "Kailua"), hash("SHA256", "pizza"), hash("SHA256", "Luna")];


# SETUP
$SQLcommands = [];
$SQLcommands[] = "SET FOREIGN_KEY_CHECKS = 0";

# CLEAR TABLES
$SQLcommands[] = "TRUNCATE TABLE Makers";
$SQLcommands[] = "TRUNCATE TABLE Schedules";
$SQLcommands[] = "TRUNCATE TABLE Timeslots";
$SQLcommands[] = "TRUNCATE TABLE Users";

# INSERT MAKERS
$SQLcommands[] = "INSERT INTO Makers (name, email, password, securityquestion, securityanswer) VALUES ('$nms[0]', '$ems[0]', '$pws[0])', '$qs[0]', '$ans[0]')";
$SQLcommands[] = "INSERT INTO Makers (name, email, password, securityquestion, securityanswer) VALUES ('$nms[1]', '$ems[1]', '$pws[1])', '$qs[1]', '$ans[1]')";
$SQLcommands[] = "INSERT INTO Makers (name, email, password, securityquestion, securityanswer) VALUES ('$nms[2]', '$ems[2]', '$pws[2])', '$qs[2]', '$ans[2]')";

# INSERT SCHEDULES
$SQLcommands[] = "INSERT INTO Schedules (maker, name, numslots, finalized) VALUES ('1', 'Soccer practice schedule', '6', '0')";
$SQLcommands[] = "INSERT INTO Schedules (maker, name, numslots, finalized) VALUES ('1', 'Family vacation', '4', '0')";
$SQLcommands[] = "INSERT INTO Schedules (maker, name, numslots, finalized) VALUES ('2', 'Presentation deadline', '5', '0')";

# INSERT TIMESLOTS
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('1', '1', '5/10/2015 10:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('1', '1', '5/10/2015 12:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('1', '1', '5/12/2015 14:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('1', '1', '5/12/2015 17:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('1', '1', '5/17/2015 8:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('1', '1', '5/17/2015 20:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('2', '1', '12/25/2015 8:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('2', '1', '12/26/2015 22:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('2', '1', '12/31/2015 4:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('2', '1', '1/1/2016 12:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('3', '2', '10/10/2015 10:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('3', '2', '10/10/2015 12:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('3', '2', '10/12/2015 14:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('3', '2', '10/12/2015 17:00')";
$SQLcommands[] = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('3', '2', '10/17/2015 8:00')";

# INSERT USERS
$SQLcommands[] = "INSERT INTO Users (email, name, schedule, maker, checkboxes) VALUES ('mhk4g@gmail.com', 'Matt Kauper', '1', '1', '0^1^0^0^1^1')";
$SQLcommands[] = "INSERT INTO Users (email, name, schedule, maker, checkboxes) VALUES ('jean@example.ex', 'Jean Baudrillard', '1', '1', '1^1^0^0^0^0')";
$SQLcommands[] = "INSERT INTO Users (email, name, schedule, maker, checkboxes) VALUES ('susan@example.ex', 'Susan Sontag', '1', '1', '0^1^0^1^0^1')";
$SQLcommands[] = "INSERT INTO Users (email, name, schedule, maker, checkboxes) VALUES ('soren@example.ex', 'Soren Kierkegaard', '1', '1', '1^1^0^1^1^0')";
$SQLcommands[] = "INSERT INTO Users (email, name, schedule, maker, checkboxes) VALUES ('michel@example.ex', 'Michel Foucault', '1', '1', '1^1^0^1^1^0')";
$SQLcommands[] = "INSERT INTO Users (email, name, schedule, maker, checkboxes) VALUES ('mhk4g@gmail.com', 'Matt Kauper', '2', '1', '0^0^0^0')";
$SQLcommands[] = "INSERT INTO Users (email, name, schedule, maker, checkboxes) VALUES ('albert@example.ex', 'Albert Camus', '2', '1', '1^0^1^1')";
$SQLcommands[] = "INSERT INTO Users (email, name, schedule, maker, checkboxes) VALUES ('mhkaup@gmail.com', 'Franklin Powers', '2', '1', '0^0^0^0')";
$SQLcommands[] = "INSERT INTO Users (email, name, schedule, maker, checkboxes) VALUES ('mhkaup@gmail.com', 'Matt Kauper', '3', '2', '0^0^0^0^0')";
$SQLcommands[] = "INSERT INTO Users (email, name, schedule, maker, checkboxes) VALUES ('prince@example.ex', 'Morris Day', '3', '2', '1^0^1^1^0')";
$SQLcommands[] = "INSERT INTO Users (email, name, schedule, maker, checkboxes) VALUES ('prince@example.ex', 'Prince', '3', '2', '0^0^1^0^1')";

# RUN ALL SQL COMMANDS
foreach($SQLcommands as $c):
  $db->query($c);
  if(!$c):
    $success = FALSE;
    echo(mysqli_error($db) . "<br>");
  endif;
endforeach;

if($success):
  echo(str_repeat("<br>", 10));
  echo("<h2><p align=\"center\">Database initialization complete. Click <a href='login_page.php'>here</a> to proceed to login page.</h2>");
else:
  echo("Yeah, something went wrong.<br>");
endif;
  ?>