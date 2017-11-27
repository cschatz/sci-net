<?php require("../session.php"); ?>
<head>
<link rel="stylesheet" type="text/css" media="screen" href="../style.css">
<title>Contest Control Panel</title>
<script type="text/javascript" src="script/prototype.js"></script>

</head>

<body>

<div class="minor">

<div id="contest">

<center>

<div class="inset">

Current Prompt: 
<form id="updatecurrent" action="update.php" method="post">
<textarea id='currentitem' cols="60" rows="6" name="current" style="font-size: 2em;">

</textarea>
</form>
<p>
<button onClick="
$('updatecurrent').request();
new Ajax.Request('info.php?action=go');
return false;">Go!</button>
</p>

<div id="prompt">

</div>

<div id="responses_panel">
<div id="responsebox">
    (Retrieving responses...)
</div>
</div>

</div>
</div>

</center>

</div><!-- end contest -->

<script type="text/javascript">
     new Ajax.PeriodicalUpdater('responsebox', 'info.php?action=contest', {
       method: 'get', frequency: 1 } );
</script>

</body>
</html>


