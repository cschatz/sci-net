<?php
if ($_POST['answer'] != "")
  {
    $answer = addslashes ($_POST['answer']);
    $output = exec ("./wrap.pl inclass \"-1\" \"$answer\"");	
  }
?>
<!DOCTYPE HTML>
<title>SciNet</title>
<link rel="stylesheet" type="text/css" media="screen" href="style.css">
<link rel="stylesheet" type="text/css" media="screen" href="dropdown.css">
<script type="text/javascript" src="script/prototype.js"></script>
<script>
  new Ajax.PeriodicalUpdater('infobox', 'current.php', {
    method: 'get', frequency: 2
	});
</script>


<div id="all">
<div id="main">

<h1>Live Practice</h1>

<script type="text/javascript" src="script/prototype.js"></script>

<div class="inset">

<?php
if ($_POST['answer'] != "")
  {
?>

<?php if ($output == "already") { ?>

<div class="msg_err">
<p>You cannot submit another response to the same item.</p>
</div>

<?php } else { ?>

<div class="msg_pos">
<p>Response sent:<br />
<b><?=$_POST['answer']?></b>
<?php if ($output == "late") { ?> <br />(LATE) <?php } ?>
</p>
</div>

<?php } ?>

<?php
  }
?>

<b>Question:</b>
<div id="infobox"><?=$_SESSION['current']?>
</div>

<form id="responseform" action="public.php" method="post">
<table>
<tr><td align="right"><b>Your response</b>:</td>
<td><input class="textfield" type="text" name="answer" size="80"></td></tr>
<tr><td align="right">&nbsp;</td><td>
<input type="submit" value="Submit Response" />
</td></tr>
</table>
</form>

</div>
