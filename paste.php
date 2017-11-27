<?php session_set_cookie_params (0); session_start(); ?>

<?php
if ($_SESSION['admin'] == "yes")
  {
    require("db.php");
    $result = mysql_query("insert into pastebin values ('" 
			  . $_SESSION['course'] . 
			  "', '" . $_SESSION['section'] . "', curdate(), '" 
			  . $_POST['title'] . "', '" . addslashes($_POST['contents']) . "')");
    echo "Success";
  }
else
  echo "Failure";
// header ("Location: main.php");
?>