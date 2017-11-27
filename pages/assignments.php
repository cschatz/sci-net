<?php session_set_cookie_params (0); session_start(); ?>

<?php
if ($_SESSION['idnum'] == 0)
    {
      echo "<p>(Guest account - no assignments.)</p>";
      exit;
    }
?>

<?php if (isset($_SESSION['submit_error'])) { ?>
<div class="msg_err">
<p><b>No Submission</b></p>
<p><?=$_SESSION['submit_error']?></p>
</div>
<?php unset($_SESSION['submit_error']); } ?>

<?php if (isset($_SESSION['success_msg'])) { ?>
<div class="msg_pos">
<p><b>Success</b></p>
<p><?=$_SESSION['success_msg']?></p>
</div>
<?php unset($_SESSION['success_msg']); } ?>

<?php
require("../db.php");
$result=mysql_query("select short_name, unix_timestamp(whendue), doc, long_name, optional, uploadable from "
	    ."assignments where course='" . $_SESSION['course'] . "' and doc != 'exam' order by whendue desc");
if (!$result) { die('Invalid query: ' . mysql_error()); }
?>

<h1>List of Current Assignments</h1>

<!--
<div class="msg_err">
Note: The code that "runs" this page is in the process of being modified.<br />
The information it displays may temporarily be inaccurate or otherwise broken.
</div>
-->

<div class="longlisting">

<table>
<tr class='heading'>
<td>#</td>
<td width="120px">Assignment</td>
<td>Due</td>
<td>Status</td>
<td>Comments</td>
<td>File to Submit</td>
</tr>

<?php  
while ($row = mysql_fetch_row($result))
{
?>

<tr>
<?php

$assn = $row[0];
$due = $row[1];
$doc = $row[2];
$name = $row[3];
$optional = $row[4];
$uploadable = $row[5];

if ($assn == "final")
  {
    $counter = "99";
  }
 else if (substr($assn, 0, 4) == "quiz")
   {
     $counter = " ";
   }
else if ($_SESSION['course'] == "cs46" || $_SESSION['course'] == "cs41")
  {
    $counter = strtoupper(substr($assn, 1));
  }
else
  {
    $counter = substr($assn, 2);
  }
$output = exec ("./wrap.pl check " . $_SESSION['course'] . " " . $assn 
  . " w" . $_SESSION['idnum'] . " " . $due . " " . $optional);

?>

<td><?=$counter?></td><td>

<?php if (substr($assn, 0, 4) != "quiz") { ?>

<a onclick="ShowAssignment('<?=$counter?>','<?=$name?>','<?=$_SESSION['course']?>','<?=$assn?>')">
<?=$name?></a></td>
<?=$output?>

<?php } else { ?>

<?=$name?></td>
<?=$output?>

<?php } ?>

<td>
<?php if ($uploadable == 1)
   {
?>

<span class='code'><?=$doc?></span><br >
<button onclick="Upload('<?=$assn?>','<?=$name?>','<?=$doc?>')">Upload</button>
</td></tr>

<?php
       }
  }
?>

</table>
</center>

