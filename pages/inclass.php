<?php session_set_cookie_params (0); session_start(); ?>

<?php
if (!isset($_SESSION['idnum']))
  {
    echo "These are not the droids you're looking for.";
    exit;
  }

$id = addslashes ($_SESSION['idnum']);

if ($_GET['doh'] == "yes")
  {
    exec ("./wrap.pl inclassretract \"" . $id . "\"");	
    
  }

else if ($_POST['answer'] != "")
  {
    $answer = addslashes ($_POST['answer']);
    $output = exec ("./wrap.pl inclass \"" . $id . "\" \"$answer\"");	
  }
?>

<script type="text/javascript" src="script/prototype.js"></script>
<script type="text/javascript">

</script>

<div class="inset">

<h1>Live Practice</h1>

<?php
  if ($_GET['doh'] == "yes")
    {
?>

<div class="msg_pos">
(Response retracted -- you can submit a different response now.)
</div>


<?php
    }
else if ($_POST['answer'] != "")
  {
?>


<?php if ($output == "already") { ?>

<div class="msg_err">
<p>You cannot submit another response to the same item.</p>
</div>

<?php } else {

	       $answer = $_POST['answer'];
	       $answer = str_replace(array('<', '>'), array('&lt;', '&gt;'), $answer);
?>
<div class="msg_pos">
<p>Response sent:<br />
	       <b><?=$answer?></b>
<?php if ($output == "late") { ?> <br />(LATE) <?php } ?>
</p>
<div id="doh">
<button id='dohbutton' style='font-weight: bold; font-size: 1.1em'
onclick="
new Ajax.Updater('main', 'pages/inclass.php?doh=yes', {
  method: 'post',
  parameters: $('responseform').serialize(true),
  onComplete: function(response) {
   StartDoh();
  }
}); 
return false;
">D&#39;OH!</button><br />
	       <i>(Click above to un-submit your answer &mdash; 
		   <span id='dohcount'>___</span>s...)</i>
</div>
</div>

<?php } ?>

<?php
  }
?>

<b>Question:</b>
<div id="infobox"><?=$_SESSION['current']?>
</div>

<form id="responseform" onsubmit="
new Ajax.Updater('main', 'pages/inclass.php', {
  method: 'post',
  parameters: $('responseform').serialize(true),
  onComplete: function(response) {
   StartDoh();
  }
}); return false; ">
<table>
<input name="team" type="hidden" value="<?=$_SESSION['idnum']?>" />
<tr><td align="right"><b>Your response</b>:</td>
<td><input class="textfield" type="text" name="answer" size="80"></td></tr>
<tr><td align="right">&nbsp;</td><td>
<input type="submit" value="Submit Response">
</td></tr>
</table>
</form>

</div>
