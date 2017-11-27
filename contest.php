<?php require ("session.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<title>SciNet</title>
<link rel="stylesheet" type="text/css" media="screen" href="style.css">
<link rel="stylesheet" type="text/css" media="screen" href="dropdown.css">
<script type="text/javascript" src="script/prototype.js"></script>

<script type="text/javascript">
  new Ajax.PeriodicalUpdater('infobox', 'current.php', {
    method: 'get', frequency: 2
	});
</script>

<div id="all">
<div id="main">

<h1>Contest Entry</h1>

<?php
if ($_POST['team'] != "")
  {
    $_SESSION['team'] = $_POST['team'];
  }
if ($_POST['answer'] != "")
  {
    $answer = addslashes ($_POST['answer']);
    if (isset($_SESSION['team']))
      $team = addslashes ($_SESSION['team']);
    else
      $team = addslashes ($_POST['team']);
    $output = exec ("./wrap.pl contest \"" . $team . 
		    "\" \"$answer\"");	
  }
?> 


<form action="contest.php" method="post">
<table>
<tr><td>Team Name:</td>
<?php
  if (!isset($_SESSION['team']))
    {
?>
<td>
<input name="team" type="text" value="" />
</td>
<?php
    }
else
  {
?>
<td><b><?=$_SESSION['team']?></b></td>
<?php
      }
?>
</tr>
<tr><td align="right">Your Entry:</td>
<td><input class="textfield" type="text" name="answer" size=80></td></tr>
<tr><td align="right">&nbsp;</td><td><input class="button" type=submit value="Submit"></td></tr>
</table>
</form>

<div id="infobox"><?=$_SESSION['current']?>
</div>


</div>
</div>


