<?php 
require ("session.php");
if ($_SESSION['admin'] != "yes")
  {
    echo "Intruder alert! Your entry has been reported - Storm Troopers being deployed...";
    exit;
  }
?>