<?php
require("session.php");

if (isset($_SESSION['course']))
  {
    passthru ("./responses.pl " . $_SESSION['course'] . " '" . $_SESSION['section'] . "' " . $_GET['action']);
  }
else
  {
    passthru ("./responses.pl boguscourse bogussection" . $_GET['action']);
  }
?>
