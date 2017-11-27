<?php require("session.php"); require("db.php"); 

$test = exec ("cat .labhelp");

if ($test != "open")
  {
    $_SESSION['help_error'] = "The lab help queue is not open at this time.";
  }
 else if ($_POST['probtype'] != "blank" && $_POST['details'] != "")
   {
     $details = addslashes ($_POST['probtype'] . " - " . $_POST['details']);
     $result= mysql_query("select fname, lname from roster where ID=" . $_SESSION['idnum']);
     $row = mysql_fetch_row($result);
     mysql_query ("insert into requests values ('" . $row[0] . " " . $row[1] . "', now(), 'request', '"
		  . $details . "')");
     $_SESSION['success_msg'] = "Request submitted:<br />(" 
       . urldecode($_POST['probtype']) . ") <b>'" . urldecode($_POST['details']) . "'</b>";
   }
 else
   {
     $_SESSION['help_error'] = "You must fill in both pieces of information below.";
   }

header("Location: lab.php");
?> 


