<?php  
session_start();
?>

<html>
<head>
  <title>Maker login</title>
</head>
<body>
  <br><p align="center"><img src="./img/register.png">
    <?php
    if(isset($_SESSION["error"])):
      $error = $_SESSION["error"];
      session_unset();
      echo("<p><pre><font color=\"red\"><p align=\"center\">$error</font></pre>");
  else:
  echo("<p><pre><font color=\"white\"><p align=\"center\"> </font></pre>");
endif;
    ?>
<pre><form action="process_register.php" method="post">
<p align="center">Email:   <input type="text" name="username" autocomplete="off"><p align="center">Password:<input type="password" name="password"><p align="center">Name:    <input type="text" name="newname" autocomplete="off"><p align="center">
<p align="center"><input type="submit" name="register" value="Register"></form></pre>
<br><br><br><br><br><p align="center">
<form action="redirect.php" method="post"><pre><p align="center"><input type="submit" name="login" value="Login">   <input type="submit" name="reset" value="Reset password">
</pre></form></body>