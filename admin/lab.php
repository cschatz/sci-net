<?php require("../session.php"); ?>
<head>
<link rel="stylesheet" type="text/css" media="screen" href="../style.css">
<script type="text/javascript" src="script/prototype.js"></script>

<script type="text/javascript">
    document.onkeydown = function (e) {
    if (e.which == 116 || e.which == 27)
      {
	new Ajax.Updater('queue', 'labget.php?action=next');	
	return false;
      }
    return true;
  };
</script>

</head>

<div class="minor">

<?php
if ($_SESSION['admin'] != "yes")
  {
    echo "Intruder alert! Your entry has been reported - Storm Troopers being deployed...";
    exit;
  }
?>


<div class="inset">
<p>Lab Help Queue
<button onclick="new Ajax.Updater('queue', 'labget.php?action=next');">NEXT</button>
</p>

<hr />
<div id="queue">
  (Queue loading...)
</div>
<hr />

</div>

<script type="text/javascript">
new Ajax.PeriodicalUpdater('queue', 'labget.php', {                     
  method: 'get', frequency: 5                                                 
      }); 

</script>