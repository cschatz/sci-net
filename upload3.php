<?php
require ("session.php");

if ($_GET['action'] == "no")
  {
    unset($_SESSION['success_msg']);
    $_SESSION['submit_error'] = "You cancelled your last submission.";
        $result = 
    exec ("./wrap.pl unsubmit "
	  . $_SESSION['course'] . " " . $_SESSION['assn'] . " " 
	  . "w" . $_SESSION['idnum']);
	echo "$result";
  }
$_SESSION['atassignments'] = 1;
header("Location: main.php");
?>
