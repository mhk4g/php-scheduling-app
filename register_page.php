<?php  
session_start();
?>

<html>
<head>
  <title>Maker registration</title>
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
<p align="center">Email:             <input type="text" name="username" autocomplete="off" required>
<p align="center">Password:          <input type="password" name="password" required>
<p align="center"> Confirm Password:  <input type="password" name="password2" required> 
<p align="center">Name:              <input type="text" name="newname" autocomplete="off" required>
<p align="center">                      Security question: <input type="text" name="question" autocomplete="off" size="50" required>   
<p align="center">  Security answer:   <input type="text" name="answer" autocomplete="off" required>  

<p align="center"><input type="submit" name="register" value="Register"></form></pre>
<br><br><br><br><br><p align="center">
<form action="redirect.php" method="post"><pre><p align="center"><input type="submit" name="login" value="Login">   <input type="submit" name="reset" value="Reset password">
</pre></form></body>