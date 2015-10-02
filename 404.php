<?php  
session_start();
?>

<html>
<head>
  <title>Error 404</title>
</head>
<body>
  <br><p align="center"><img src="./img/oops.png">
    <?php
    if(isset($_SESSION["error"])):
      $error = $_SESSION["error"];
      session_unset();
      echo("<p><pre><font color=\"red\"><p align=\"center\">$error</font></pre>");
  else:
  echo("<p><pre><font color=\"black\"><p align=\"center\">The page you requested does not exist. Sorry!</font></pre>");
endif;
    ?>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<form action="redirect.php" method="post">
<pre><p align="center"><input type="submit" name="login" value="Login">   <input type="submit" name="register" value="Register">   <input type="submit" name="reset" value="Reset password">  
</pre></form></body>