<?php require("session.php"); ?>
<head>
<link rel="stylesheet" type="text/css" media="screen" href="style.css">	
</head>

<div class="minor">

<h1>Lab Help</h1>

    <p>This tool is used to request live help during lab/work time <b>in class</b>. Once you submit a request, your name and question goes into a 
<b><a href="http://en.wikipedia.org/wiki/Queue_%28data_structure%29"
    target="_blank">queue</a></b>. This helps ensure that I allocate my
time and assistance as fairly as possible.</p>

<p>If you are trying to get help 
				       <b>outside</b> of class time, 
    please contact me or find me in person (see the <b>Info/Contact</b> menu
					    for details).</p>

<p>To add your request to the help queue, fill out the information below.</p>

<hr />

<?php if (isset($_SESSION['help_error'])) { ?>
<div class="msg_err">
<p><?=$_SESSION['help_error']?></p>
</div>
<?php unset($_SESSION['help_error']); } ?>

<?php if (isset($_SESSION['success_msg'])) { ?>
<div class="msg_pos">
<p><?=$_SESSION['success_msg']?></p>
</div>
<?php unset($_SESSION['success_msg']); } ?>

<form action="lab2.php" method="post">

Pick the appropriate category for your question or problem:<br />
<select type="text" name="probtype">
<option value="blank" selected></option>
<option value="Compile error">I have a compile error I do not understand.</option>
<option value="Runtime problem">My program runs, but doesn't work correctly.</option>
<option value="Quick question">I have a quick question / I forgot something.</option>
<option value="Check">Check please!</option>
<option value="Lost!">I am lost.</option>
<option value="Just curious">I am just curious about something.</option>
</select>
<br />
<b>Briefly</b> describe the error or question you have:<br />
<input class="textfield" type="text" name="details" size=60><br />
<input type="submit" value="Submit Request">
</form>
</center>

</div>

