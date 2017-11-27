<?php
session_set_cookie_params (0); session_start(); 
require("../db.php");
if ($_SESSION['admin'] != "yes")
  {
    echo "Intruder alert! Your entry has been reported - Storm Troopers being deployed...";
    exit;
  }
?>
<head>
<title>Trend</title>
<link rel="stylesheet" type="text/css" media="screen" href="../style.css">
</head>

<div class="minor">

<h1>Recent submissions by student #<?=$_POST['who']?></h1>

<center>
<table class="answertab">
<tr><td><b>Prompt</b></td><td width="10px"> </td><td><b>Response</b></td></tr>
<?php
  $result = mysql_query("select prompt, response from daily where ID="
			. $_POST['who'] . " order by answerwhen asc");

while ($row = mysql_fetch_row($result))
  {
    echo "<td><pre>$row[0]</pre></td><td></td>\n";
    echo "<td><pre>$row[1]</pre></td></tr>\n";
  }

?>
</table>
</center>
</div>

</body>