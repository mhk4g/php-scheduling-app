<?php
session_start();
print_r($_SESSION);
print_r($_POST);

$dbuser = "mhk4g";
$dbpass = "password";
$numusers = $_POST["numusers"];
$numslots = $_POST["numslots"];
$schedulename = strtr($_POST["schedulename"], "*", " ");
$maker_ID = $_SESSION["maker_ID"];

$mailpath = '/Applications/XAMPP/xamppfiles/PHPMailer';

  // Add the new path items to the previous PHP path
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . $mailpath);
require 'PHPMailerAutoload.php';

$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

# If register was clicked...
if(isset($_POST["addtoDB"])) {
  
  # STEP 1: CREATE SCHEDULE
  $schedulequery = "INSERT INTO Schedules (maker, name, numslots, finalized) VALUES ('$maker_ID', '$schedulename', '$numslots', '0')";
  $scheduleresult = $db->query($schedulequery);
  
  # STEP 2: GET THE ID OF NEWLY CREATED SCHEDULE
  $IDquery = "SELECT ID FROM Schedules WHERE maker = '$maker_ID' AND name = '$schedulename'";
  $IDresult = $db->query($IDquery);
  $scheduleID = $IDresult->fetch_assoc()["ID"];
  
  # STEP 3: INSERT THE TIMESLOTS
  for($i = 1; $i < $numslots + 1; $i++){
    $slotname = "s" . $i;
    $slotstring = $_POST[$slotname];
    $timequery = "INSERT INTO Timeslots (schedule, maker, datestring) VALUES ('$scheduleID', '$maker_ID', '$slotstring')";
    $timeresult = $db->query($timequery);
  }
  
  # STEP 4: INSERT THE USERS
  for($j = 1; $j < $numusers + 1; $j++){
    $tempname = "n" . $j;
    $tempemail = "e" . $j;
    $namestring = $_POST[$tempname];
    $emailstring = $_POST[$tempemail];
    $checkboxes = (string)"0" . str_repeat("^0", $numslots - 1);
    $userquery = "INSERT INTO Users (email, name, schedule, maker, checkboxes) VALUES ('$emailstring', '$namestring', '$scheduleID', '$maker_ID', '$checkboxes')";
    $userresult = $db->query($userquery);
  }
  
  # If the entry is successfully created...
  if($scheduleresult && $timeresult && $userresult):
    $mail = new PHPMailer();
    $mail->IsSMTP(); 
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = "tls"; // sets tls authentication
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587; // set the SMTP port for GMAIL server
    $mail->IsHTML(true);
    $mail->Username = "DBMailer.mhk4g@gmail.com"; // sender
    $mail->Password = "cs4501pass"; // sender password

    #$sender = strip_tags($_POST["sender"]);
    $sender = "MakerDB";
    $subj = "Welcome to MakerDB!";
    

    # SEND EMAIL TO EACH USER
    $userIDarray = $db->query("SELECT ID FROM Users WHERE schedule = '$scheduleID'");
        
    for($k = 0; $k < $numusers; $k++) {
        $userID = $userIDarray->fetch_row()[0];
        echo("UserID: $userID ");
        $temprecipient = $db->query("SELECT email FROM Users WHERE ID = '$userID'");
        $currentrecipient = $temprecipient->fetch_row()[0];
        echo("Current recipient: $currentrecipient<br>");
        
        $msg = "<pre><h2>MakerDB Schedule Created!</h2><br><p>Hello! 
        
        You have been signed up for a ScheduleDB public schedule. <a href=\"localhost/maker-schedule-db/user_page.php?scheduleID=$scheduleID&userID=$userID\">Please click this link to RSVP!</a><br>
        
        Regards, <br>      MakerDB Staff";
      
        // Put information into the message
        $mail->addAddress($currentrecipient);
        $mail->SetFrom($sender);
        $mail->Subject = "$subj";
        $mail->Body = "$msg";

        if(!$mail->send()) {
          echo 'Message could not be sent.';
          echo 'Mailer Error: ' . $mail->ErrorInfo;
          }
         else { 
           echo "Mail successfully sent to $currentrecipient.<br><br>"; 
          }
        $mail->addAddress("NULL");
    }
    
    #$_SESSION["error"] = "Database created successfully!";
    #header("Location: maker_page.php");
    
    /*IDEA: USE THIS
    DELETE FROM `ScheduleDB`.`Timeslots` WHERE `timeslots`.`maker` = 21;
    DELETE FROM `ScheduleDB`.`Users` WHERE `users`.`maker` = 21;
    */
      
  # Otherwise, the insert fails because 
  else:
      echo("SOMETHING WENT WRONG");
  endif;
}
?>