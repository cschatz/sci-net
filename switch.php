<?php
require ("session.php");
require ("db.php");

if (!isset($_SESSION['idnum']))
  {
    header( "Location: index.php" );
    exit;
  }

  header ( "Location: login.php" );
?>