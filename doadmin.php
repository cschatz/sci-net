<?php
session_set_cookie_params (0); // Until browser closed
session_start();
$_SESSION['savedidnum'] = $_SESSION['idnum'];
unset($_SESSION['idnum']);
$_SESSION['adminlogin'] = 'yes';
header ("Location: index.php");
?>


