<?php 
require("../session.php"); 
require("../db.php");
?>
<head>
<link rel="stylesheet" type="text/css" media="screen" href="../style.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script src="participation.js"></script>
</head>

<div class="major">

<h1><?=strtoupper($_SESSION['course'])?></h1>

<?php
if ($_SESSION['admin'] != "yes")
  {
    echo "Intruder alert! Your entry has been reported - Storm Troopers being deployed...";
    exit;
  }
?>

<center>
<table class="grid">
<tr>
<td>
<?php
$result=mysql_query("select id, fname, lname from users natural join roster where course='"
		    . $_SESSION['course'] . "' and ID>1 order by fname, lname", $db);
if (!$result) { die('Invalid query: ' . mysql_error()); }
$numrows = mysql_num_rows($result);
$percol = ($numrows+2) / 3;
for ($i = 0; $i < $numrows; $i++)
  {
    $row = mysql_fetch_row($result);
    $firstname = substr($row[1], 0, 1) . strtolower(substr($row[1], 1));
    $lastinitial = substr($row[2], 0, 1);
?>
<div class="entry" id="<?=$row[0]?>">
<button class="add">+</button>
<button class="sub">-</button>
   &nbsp;
<span class="pp"> </span>
   &nbsp;
<?=$firstname?> <?=$lastinitial?>
&nbsp;&nbsp;&nbsp;&nbsp;
</div>

<?php
     if (($i+1) % $percol == 0)
       {
	 echo "</td>\n";
	 if ($i < $numrows-1)
	   {
	     echo "<td>\n";
	   }
       }

  }
?>
</td></tr>
</table>
<button id='finalize'>Record</button>
</center>

<div id='results'>
</div>

</div>
