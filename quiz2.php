<?php session_set_cookie_params (0);session_start(); ?>
<?php 
require("db.php");
$answer = "";
$first = 1;
foreach ($_POST as $var => $val)
{
  if ($first == 0)
    {
      if (substr($var, -1) == "n")
	{
	  $answer = $answer . "\n" . $val;
	}
      else
	{
	  $answer = $answer . "\n------------\n" . $val;
	}
    }
  else
    {
      $answer = $val;
      $first = 0;
    }
}

$result = mysql_query("insert into answers values ('" . $_SESSION['course'] . "', " 
		      . "'" . $_SESSION['whichquiz'] . "', '" . $_SESSION['idnum'] . "', "
		      . "'" . addslashes($answer) . "', "
		      . "'" . $_SESSION['quiz_start'] . "', now())");

unset($_SESSION['whichquiz']);

$_SESSION['success_msg'] = "Your quiz was submitted.";

header ("Location: main.php");
?>