<?php
require("../session.php"); 
require("../db.php");


mysql_query("delete from participation where course='"
	    . $_SESSION['course'] . "' and day=date(now()) "
	    . "and description like 'In-class%'");

foreach ($_POST as $key => $value)
{
  echo "$key:+$value ";
  mysql_query("insert into participation values ($key, '"
	      . $_SESSION['course'] . "', date(now()), "
	      . "'In-class questions/answers', $value)");
}

?>
