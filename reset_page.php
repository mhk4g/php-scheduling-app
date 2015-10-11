<?php  
session_start();
?>

<html>
<head>
  <title>Maker password reset</title>
</head>
<body>
  <br><p align="center"><img src="./img/reset.png">
    <?php
    if(isset($_SESSION["error"])):
      $error = $_SESSION["error"];
      session_unset();
      echo("<p><pre><font color=\"red\"><p align=\"center\">$error</font></pre>");
  else:
  echo("<p><pre><font color=\"black\"><p align=\"center\">A password reset link will be sent to your email address.</font></pre>");
endif;
    ?>
<pre><form action="process_reset.php" method="post">
<p align="center">Email:   <input type="text" name="username" autocomplete="off" required><p align="center">
<p align="center"><input type="submit" name="reset" value="Reset my password"></form></pre>
<br><br><br><br><br><br><br><br><br>
<form action="redirect.php" method="post"><pre><p align="center"><input type="submit" name="login" value="Login">   <input type="submit" name="register" value="Register">
</pre></form></body>