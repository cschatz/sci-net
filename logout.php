<?php
session_set_cookie_params (0);
session_start();

if (isset($_SESSION['savedidnum']))
  {
    $_SESSION['idnum'] == $_SESSION['savedidnum'];
    unset($_SESSION['savedidnum']);
  unset($_SESSION['phase']);
  }
else
  {
  unset($_SESSION['idnum']);
  unset($_SESSION['course']);
  unset($_SESSION['phase']);
  }

unset($_SESSION['admin']);
unset($_SESSION['adminlogin']);
header( "Location: index.php" );
?> 