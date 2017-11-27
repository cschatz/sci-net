<?php
session_set_cookie_params (0); session_start();
require ("db.php");

$err = "";
$coursephrase = "";

if(!(isset($_SESSION['idnum']) && ( isset($_GET['whichcourse'])
   || isset($_SESSION['course'])))) 
{
  header ("Location: index.php");
  exit;
}

if (isset($_GET['whichcourse']))
{
   $_SESSION['course'] = $_GET['whichcourse'];
   $_SESSION['section'] = $_GET['whichsection'];
   if ($_SESSION['course'] == 'cs7')
     {
       $_SESSION['limited'] = 1;
     }
   else
     {
       unset($_SESSION['limited']);
     }
}

$coursecheck = mysql_query("select ID from roster where ID=" . 
			   $_SESSION['idnum']
			   . " and course='" . $_SESSION['course'] . "'"
			   . " and section='" . $_SESSION['section'] . "'");

if (!$coursecheck) { die('Invalid query: ' . mysql_error()); }
if (mysql_num_rows($coursecheck) == 0)
  {
    unset($_SESSION['idnum']);
    header ("Location: index.php");
    exit;
  }

$result2=mysql_query("select ID from attendance where ID=" . 
		    $_SESSION['idnum'] . " and course='" .
		     $_SESSION['course'] . "' and section='" .
		     $_SESSION['section'] . "' and day = date(now())");

if (!$result2) { die('Invalid query: ' . mysql_error()); }
$rowCheck2 = mysql_num_rows($result2);

if ($rowCheck2 > 0)
  {
   header ("Location: main.php");
   exit;
  }

if ($_POST['studentishere'] == "No, I am somewhere else.")
{
   header ("Location: main.php");
   exit;
}

$result=mysql_query("select phrase from phrases where course='" .
		    $_SESSION['course'] . "' and section='" . $_SESSION['section'] . "'");
if (!$result) { die('Invalid query: ' . mysql_error()); }
$rowCheck = mysql_num_rows($result);
if ($rowCheck != 0)
{
   $row = mysql_fetch_row($result);
   $coursephrase = $row[0];
}

if ($_POST['studentishere'] == "Yes, I am here!")
{
  if (strtolower($_POST['userphrase']) != strtolower($coursephrase))
    {
      $err = "Incorrect attendance phrase";
    }
  else
    {
      mysql_query("insert into attendance values (" . $_SESSION['idnum']
		  . ", '" . $_SESSION['course'] . "', '" . $_SESSION['section'] . "', now(), now())"); 
      if ($_SESSION['course'] == "cs16notreally")
	{
	  header('Content-disposition: attachment; filename=Midterm.zip');
	  header ("Content-type: application/octet-stream");
	  passthru ("cat Midterm.zip");
	}

      header ("Location: main.php");
      exit;
    }
}

if ($coursephrase != "")
   {
?>

<head>
<link rel="stylesheet" type="text/css" media="screen" href="style.css">	
</head>

<div class="minor">

<?php if ($err != "") { ?>
<div class="msg_err">
<?php echo $err; ?>
</div>
<?php } ?>

<p style="font-size:1.2em"><?=strtoupper($_SESSION['course'])?>
 is in session now. Are you there?<br />
<i>(Select one below.)</i></p>

<form action="login2.php" method="post">
<hr />
<p>The attendance phrase is <input name="userphrase" type="text" class="textfield" size="40">.<br />
<input type="submit" name="studentishere" value="Yes, I am here!"></p>
<hr />
<p>
<input type="submit" name="studentishere" value="No, I am somewhere else."></p>
</p>
<hr />

</form>
</div>

<?php
     exit;
}
header ("Location: main.php");
exit;
?>

