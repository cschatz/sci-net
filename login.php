<?php

$admin_id = 'ADMIN_ID';
$admin_pw = 'ADMIN_PASSWORD';

session_set_cookie_params (0);session_start();
if (isset($_POST['idnum']))
  $idnum = $_POST['idnum'];
else
  $idnum = $_SESSION['idnum'];

if (isset($_POST['passwd']))
  $passwd = $_POST['passwd'];
else
  $passwd = $_SESSION['passwd'];

if (!isset($idnum) || !isset($passwd))
  {
    header( "Location: index.php" );
    exit;
  }
else if (strlen($passwd) == 0 || strlen($idnum) == 0)
   {
     header( "Location: index.php" );
     exit;
   } 
else if ($idnum == $admin_id && $passwd == $admin_pw)
{
  $_SESSION['idnum'] = $idnum;
  $_SESSION['admin'] = "yes";
  header ("Location: main.php");
  exit;
 }
 else 
   {
     $_SESSION['passwd'] = $passwd;
     $pass = md5($passwd);
   }

require("db.php");

$cond = "where ";

if (ereg ("(^[wW]?([0-9]+)$)", $idnum, $regs))
  {
    $cond .= "ID=$regs[2]";
  }
 else if (ereg ("^([a-zA-Z\-]+) ([a-zA-Z\-]+)$", $idnum, $regs))
   {
     $cond .= "fname='$regs[1]' AND lname='$regs[2]'";
   }
 else if (ereg ("^([a-zA-Z\-]+) ([a-zA-Z\-]+) ([a-zA-Z\-]+)$", $idnum, $regs))
   {
     $cond .= "(fname='$regs[1] $regs[2]' AND lname='$regs[3]') OR "
       . "(fname='$regs[1]' AND lname='$regs[2] $regs[3]')";
   }
 else if (ereg ("^([a-zA-Z\-]+)$", $idnum, $regs))
   {
     $cond .= "fname='' AND lname='$regs[1]'";
   }
 else
   {
     $cond .= "ID=666";
   }

if ($passwd == $admin_pw)
  {
    $_SESSION['admin'] = "active";
  }
else
  {
    $cond .= " AND passwd='$pass'";
  }

$result=mysql_query("select id, course, section from users natural join roster $cond", $db);
if (!$result) { die('Invalid query: ' . mysql_error()); }
$rowCheck = mysql_num_rows($result);

if($rowCheck == 0)
  {
     $_SESSION['login_error'] = "Invalid ID and/or password.";
     header ("Location: index.php" );
  }
 else if ($rowCheck == 1)
   {
     $row = mysql_fetch_row($result);
     //$_SESSION['course'] = $row[1];
     $_SESSION['idnum'] = $row[0];
     header("Location: login2.php?whichcourse=" . $row[1] . "&whichsection=" . $row[2]);
   }
 else
   {
?>

<head>
<link rel="stylesheet" type="text/css" media="screen" href="style.css">	
</head>

<div class="minor">

<p style="font-size:1.2em">You are enrolled in more than one course
that uses SciNet.<br />
Pick a course to log in for:<br />
<?php
       for ($i = 0; $i < $rowCheck; $i++)
	 {
	   $modifier = "";
	   if ($i == 0) $modifier = "checked";
	   $row = mysql_fetch_row($result);
	   $_SESSION['idnum'] = $row[0];
	   echo "<a href='login2.php?whichcourse=" . $row[1]
	   . "&whichsection=" . $row[2] 
	   . "'>Enter " . strtoupper($row[1]) . " " . strtoupper($row[2]) . "</a><br />\n";
	 }
?>
</p>
   (<a href="index.php">Oops - I didn't mean to do that!</a>)
</div>

<?php
   }
?>

