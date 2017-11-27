<?php
require("db.php");
header("Content-type: text/plain");
?>
// <?=strtoupper($_SESSION['course'])?> EXAMPLES 

<?php

$result=mysql_query("select date_format(day, '%a %c/%e'), title, contents from pastebin where course='"
		    . $_GET['course'] . 
		    "' and section ='" .
		    $_GET['section'] . "' order by day");

while ($row = mysql_fetch_row($result))
{
  $day = $row[0];
  $title = $row[1];
  $contents = $row[2];
?>


<?php
    echo " * " . $day . " - " . $title . "\n";
?>

<?=$contents?>
<?php
   $n++;
 }
?>
