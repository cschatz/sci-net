<?php
session_set_cookie_params (0); session_start();
$arg = $_GET['arg'];
$course = preg_replace("/\\D$/", "", $_SESSION['course']);


if ($arg == undefined)
  {
    $arg = $whattolist;
  }
?>

<?php if ($arg != "oldquizzes") { ?>
<h1><?=ucwords($arg)?></h1>
      <?php } ?>

<div class="listing">

<table>

  <tr class="heading"><td>&#35;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="150px">Title</td><td>Document(s)</td></tr>

<?php


$myDirectory = opendir("../$arg/" . strtoupper($course));

while($entryName = readdir($myDirectory))
    {
      $dirArray[] = "$arg/" . strtoupper($course) . "/" . $entryName;
    }
closedir($myDirectory);

$indexCount= count($dirArray);
sort($dirArray);

$prevname = "";
for($index=0; $index < $indexCount; $index++) 
  {
    $base = basename ($dirArray[$index]);
    // Check for handout naming pattern
    if (strtolower(substr($base, 0, strlen($course)+2)) == ($course . "-h"))
      {
	$pieces = explode (".", substr($base, strlen($course)+2) );
	$ext = $pieces[1];
	$fname = $pieces[0];
	if ($ext == "php") { $ext = "html"; }
	if ($ext == "pdf" || $ext == "txt" || $ext == "html" || $ext == "ppt")
	  {
	    if ($prevname != $fname)
	      {
		if ($prevname != "")
		  echo "</td></tr>\n";
		echo "<tr>";
		$pieces = explode ("-", $fname);
		$num = array_shift ($pieces);
		echo "<td>" . $num . "</td>";
		$title = implode (" ", $pieces);
		$title = preg_replace ('/(\d+)/', ' ${1}', $title);
		echo "<td>$title</td><td>";
	      }
	    echo "<a target='_blank' href='";
	    echo $dirArray[$index];
	    echo "'>". strtoupper($ext) . "</a>&nbsp;&nbsp;";
	    $prevname = $fname;
	  }
      }
    else if ($arg != "handouts" && strtolower(substr($base, 0, 4)) == "quiz")
      {
	$pieces = explode (".", substr($base, 4));
	$ext = $pieces[1];
	$fname = $pieces[0];
	if ($ext == "pdf" || $ext == "txt" || $ext == "html")
	  {
	    // no "number" as such for quizzes
	    echo "<td>  </td>";
	    $title = "Quiz " . $fname;
	    echo "<td>$title</td><td>";
	  }
	echo "<a target='_blank' href='";
	echo $dirArray[$index];
	echo "'>". strtoupper($ext) . "</a></td></tr>\n";
	$prevname = $fname;
      }
  }
echo "</td></tr>\n";
?>

</table>

</div>