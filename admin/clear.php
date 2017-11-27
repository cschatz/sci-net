<?php
require ("sessionwithadmin.php");
require("../db.php");

$course = $_SESSION['course'];
$section = $_SESSION['section'];
if (isset($_POST['numrows']))
  {
    for ($i = 1; $i <= $_POST['numrows']; $i++)
      {
	if ($_POST['eval_' . $i] != "")
	  {
	    //echo "**" . $_POST['answer_' . $i] . ": " . $_POST['eval_' . $i];
	    //echo "<br />\n";
	    mysql_query("update inclass set score=" . 
			$_POST['eval_' . $i] . " where content='" .
			addslashes($_POST['answer_' . $i]) . "'");
	  }
      }
    
    mysql_query("update inclass set score=0 where score is null");
    
    mysql_query("insert into inclass select ID, now(), '', -1 from roster where course='$course' and ID in (select ID from attendance where course='$course' and section='$section' and day=date(now())) and ID not in (select ID from inclass) and ID > 1");

    mysql_query ("insert into daily select ID, '" 
		 . $_SESSION['course'] 
		 . "', '" . $_SESSION['section'] 
		 . "', now(), item, content, score from inclass, current where ID > 1 and ID < 11000000") || die(mysql_error());
  }
mysql_query ("delete from inclass");
mysql_query ("delete from inclass_late");
mysql_query ("delete from current");
?>
