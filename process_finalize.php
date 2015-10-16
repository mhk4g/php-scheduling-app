<?php 
session_start();

if(isset($_POST["which"])):
  $IDtoFinalize = $_POST["which"];
else:
  $_SESSION["error"] = "Failed to finalize schedule.";
  header("Location: maker_page.php");
endif;

$winnerID = (int)$_SESSION["best_slot_ID$IDtoFinalize"];
$winnerValue = $_SESSION["best_slot_value$IDtoFinalize"];
$winnerIndex = $_SESSION["best_slot_index$IDtoFinalize"];

# Initialize mailer
$mailpath = '/Applications/XAMPP/xamppfiles/PHPMailer';
$path = get_include_path();
set_include_path($path . PATH_SEPARATOR . $mailpath);
require 'PHPMailerAutoload.php';

# Database user/pass
$dbuser = "mhk4g";
$dbpass = "password";

# Connect to DB
$db = new mysqli('localhost', $dbuser, $dbpass, "ScheduleDB");
if ($db->connect_error) {
    die("Could not connect to database: " . $db->connect_error);
  }

# Sets the finalize flag and copies the winning slot's index to the schedule's winning slot index attribute
$finalize1 = $db->query("UPDATE Schedules SET finalized = '1' WHERE ID = '$IDtoFinalize'");
$finalize2 = $db->query("UPDATE Schedules SET winningslotID = '$winnerID' WHERE ID = '$IDtoFinalize'");
$scheduleArray = $db->query("SELECT * FROM Schedules WHERE ID = '$IDtoFinalize'");
$scheduleRow = $scheduleArray->fetch_assoc();
$winningSlotArray = $db->query("SELECT * FROM Timeslots WHERE ID = '$winnerID'");

/* TEST METHODS

echo "Winner ID: " . $winnerID . "<br>";
echo "Finalize1: " . (int)$finalize1 . "<br>";
echo "Finalize2: " . (int)$finalize2 . "<br>";
echo "WinningSlotArray: " . (int)$winningSlotArray . "<br>";
print_r($scheduleRow);
*/


# If everything went correctly, send emails informing users of the chosen time
if($finalize1 && $finalize2 && $winningSlotArray && $scheduleRow):
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
  $scheduleName = $scheduleRow["name"];
  $winningSlotString = $winningSlotArray->fetch_assoc()["datestring"];
  $subj = "Time confirmed for Event: \"$scheduleName\"";
  
  # SEND EMAIL TO EACH USER
  $userArray = $db->query("SELECT * FROM Users WHERE schedule = '$IDtoFinalize'");
      
  for($k = 0; $k < $userArray->num_rows; $k++) {
      $currentRow = $userArray->fetch_assoc();
      $userID = $currentRow["ID"];
      $currentrecipient = $currentRow["email"];
      
      $msg = "<pre><h2>MakerDB Schedule Update</h2><br><p>Hello! 
      
      Your MakerDB schedule has been finalized! 
      
      The event titled \"$scheduleName\" has been finalized and will occur on <b>$winningSlotString</b>.<br>
      
      Regards, <br>      MakerDB Staff";
    
      // Put information into the message
      $mail->addAddress($currentrecipient);
      $mail->SetFrom("DBMailer.mhk4g@gmail.com", "MakerDB");
      $mail->Subject = "$subj";
      $mail->Body = "$msg";

      if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
       else { 
         echo "Mail successfully sent to $currentrecipient.<br><br>"; 
        }
      $mail->clearAddresses();
  }
      
# Otherwise, the insert fails because 
else:
    echo("SOMETHING WENT WRONG");
endif;
$_SESSION["error"] = "Schedule finalized successfully!";
header("Location: maker_page.php");
?>