<?php require ("../session.php"); 
require("../db.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<title>Sci:Net - Data Browser</title>
<link rel="stylesheet" type="text/css" media="screen" href="../style.css">
<script type="text/javascript" src="script/prototype.js"></script>
<script type="text/javascript" src="script/ui.js"></script>
</head>
	
<div id="all" class="small">

<div id="main">

<?php
if ($_SESSION['admin'] != "yes")
  {
    echo "Intruder alert! Your entry has been reported - Storm Troopers being deployed...";
    exit;
  }
?>

<?php 
if ($_POST['private'] == "yes")
   $_SESSION['confirmedprivate'] = "yes";

if ($_SESSION['confirmedprivate'] != "yes") { ?>

<div class="narrow">
<center>
<p><b>Dear Instructor:<br />
You are about to enter the SciNet data browsing tool, which includes
information from individual student records. Please
make sure this page is not being displayed publicly before
continuing.</p>

<p>Are you ready to proceed?<br />
<form action="data.php" method="post">
<input type="hidden" name="private" value="yes" />
<input type="submit" value="Yes, I am the only one looking at this." />
</form>

<button onclick="window.close(); return false;">No, I did not mean to do this right now.</button>

</center>
</div>

<?php 
exit;
} 
?>

<h1>Data Browser</h1>

<center>
<table>
<tr>
<td>
<div><b>Browse by student:</b></div>
<div id="studentpanel" style="margin-top: 5px; height: 220px; overflow-y: auto; border: 1px solid grey;">
</div>

</td>
<td>
<div><b>Browse by day:</b></div>
<div id="daypanel" style="margin-top: 5px; height: 220px; overflow-y: auto; border: 1px solid grey;">
</div>

</td>
</tr>
</table>
<br />
  <p><b>Or download the full data set:</b><br />
<a href="data_export.php"><?=$_SESSION['course']?>data.csv</a>
(open with Excel or OpenOffice Calc)
</p>
</center>

</div> <!-- main -->

</div> <!-- all -->

</body>
</html>


<script type="text/javascript">
  new Ajax.Updater ('studentpanel', 'data_dispatch.php?action=liststudents');
  new Ajax.Updater ('daypanel', 'data_dispatch.php?action=listdays');
</script>