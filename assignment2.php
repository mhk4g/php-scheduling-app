<?php  
session_start();
?>

<html>
<head>
  <title>Maker login</title>
</head>
<body>
  <pre>
  <form action="process_login.php" method="post">
    <br><br>
        <p align="center"><b>MAKER LOGIN</b>
        <p align="center">Email:   <input type="text" name="username" autocomplete="off"><p align="center">Password:<input type="password" name="password"><p align="center">Name:    <input type="text" name="newname" autocomplete="off">
<p align="center"><input type="submit" name="login" value="login"> <input type="submit" name="register" value="register"> <input type="submit" name="forgot" value="reset password"></form></pre>
  <?php
  if(isset($_SESSION["error"])) {
    $error = $_SESSION["error"];
    unset($_SESSION["error"]);
    echo("<pre><font color=\"red\"><p align=\"center\">$error</font></pre>");
  }
  ?>
</body>