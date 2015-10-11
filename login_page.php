<?php  
session_start();
?>

<html>
<head>
  <title>Maker login</title>
</head>
<body>
  <br><p align="center"><img src="./img/login.png">
    <?php
    if(isset($_SESSION["error"])):
      $error = $_SESSION["error"];
      unset($_SESSION["error"]);
      echo("<p><pre><font color=\"red\"><p align=\"center\">$error</font></pre>");
  else:
  echo("<p><pre><font color=\"white\"><p align=\"center\"> </font></pre>");
endif;
    ?>
<pre><form action="process_login.php" method="post">
<p align="center">Email:   <input type="text" name="username" autocomplete="off" required><p align="center">Password:<input type="password" name="password" required><p align="center">
<p align="center"><input type="submit" name="login" value="Enter"></form></pre>
<br><br><br><br><br><br><br>
<form action="redirect.php" method="post"><pre><p align="center"><input type="submit" name="register" value="Register">   <input type="submit" name="reset" value="Reset password">
</pre></form></body>