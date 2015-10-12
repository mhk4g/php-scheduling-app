<?php
  session_start();
    
  // On MAC the path is usually:
  $mailpath = '/Applications/XAMPP/xamppfiles/PHPMailer';

    // Add the new path items to the previous PHP path
  $path = get_include_path();
  set_include_path($path . PATH_SEPARATOR . $mailpath);
  require 'PHPMailerAutoload.php';

  $mail = new PHPMailer();

  $mail->IsSMTP(); // telling the class to use SMTP
  $mail->SMTPAuth = true; // enable SMTP authentication
  $mail->SMTPSecure = "tls"; // sets tls authentication
  $mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server; or your email service
  $mail->Port = 587; // set the SMTP port for GMAIL server; or your email server port
  $mail->IsHTML(true);
  $mail->Username = "DBMailer.mhk4g@gmail.com"; // email recipient
  $mail->Password = "cs4501pass"; // email password

  #$sender = strip_tags($_POST["sender"]);
  $sender = "MakerDB";
  $receiver = strip_tags($_POST["recipient"]);
  $receiverGET = strtr($receiver, "@", "*");
  
  $subj = "Password reset";
  $msg = "<pre><h2>Password Reset</h2><br><p>Hello! 
  
  This email will allow you to reset your MakerDB password. Please click this link: <a href=\"localhost/maker-schedule-db/security_question.php?recipient=$receiverGET\">Reset password</a><br>
  
  Regards, <br>      MakerDB Staff";

  // Put information into the message
  $mail->addAddress($receiver);
  $mail->SetFrom($sender);
  $mail->Subject = "$subj";
  $mail->Body = "$msg";

  // echo 'Everything ok so far' . var_dump($mail);
  if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
   }
   else { echo 'Password reset instructions have been sent to your email address.'; }
?>
</body>
</html>