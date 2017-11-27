<?php
session_set_cookie_params (0); session_start();
$passwd1 = $_POST['pw1'];
$passwd2 = $_POST['pw2'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$sid = $_POST['sid'];
$code = $_POST['code'];

if ($passwd1 != $passwd2)
  {
    {
      $_SESSION['reg_error'] = "The two passwords you gave did not match.";
    }
  }
else
  {
    require("db.php");
    $lname = addslashes($lname);
    $fname = addslashes($fname);
    $code = addslashes($code);
    $result = mysql_query ("select course from roster where ID=$sid and fname='$fname' and lname='$lname' " . "and access='$code'", $db);

    if (mysql_num_rows($result) < 1)
      {
	$_SESSION['reg_error'] = "The access code you entered was invalid, or no student with the information you gave is in the database.";
      }
    else
      {
	$course = mysql_result($result, 0);
	mysql_query("replace into users values ($sid, md5('$passwd1'), 0, '')", $db)
	  or die ("Couldn't insert:" . mysql_error());
	$_SESSION['success_msg'] = "You are now registered.";
      }
  }
header ("Location: index.php");
exit;
?>

