<?php 

session_start();

# Redirects to registration page
if(isset($_POST["register"])):
  header("Location: register_page.php");
  die;

# Redirects to password reset page
elseif(isset($_POST["reset"])):
  header("Location: reset_page.php");
  die;
  
# Redirects to login page
elseif(isset($_POST["login"])):
  header("Location: login_page.php");
  die;
  
# Redirects to edit page
elseif(isset($_POST["edit"])):
  header("LocationL edit_page.php");
  die;

endif;
?>