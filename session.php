<?php
session_set_cookie_params (0); // Until browser closed
session_start();
// LOGIN CHECK
if((!isset($at_index) || !$at_index) && ! (isset($_SESSION['idnum']) && 
		    (isset($_SESSION['course']) || $_SESSION['admin'] == "yes"  ))) 
{
  header( "Location: /sn/index.php" );
  exit;
}
$at_index = 0;
?>
