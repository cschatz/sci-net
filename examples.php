<?php

if (!isset($_GET['course']))
  {
    echo "No course specified for code examples - please try again!";
    exit;
  }

require("db.php");
$course = strtoupper($_GET['course']);
$section = strtoupper($_GET['section']);

?>
<body>
<head>
<title>Code Examples - <?=$course?></title>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="style.css">	
<script>
  $(document).ready(function () {
      $('#examples .excontent').hide();
      $('#examples .exname').click(function () {
	  $(this).next().toggle();
	  return false;
	});
    });

</script>
</head>

<body>

<div class="major">

<h1>Code Examples - <?=$course?> <?=$section?></h1>

  <p>(To see a complete set in one document, go 
<a href="examples_all.php?course=<?=$course?>&section=<?=$section?>">HERE</a>.)</p>

<div id="examples">

<?php
$result=mysql_query("select date_format(day, '%a %c/%e'), title, contents from pastebin where course='"
		    . $course . "' and section='" . $section . "' order by day desc");

while ($row = mysql_fetch_row($result))
{
  $day = $row[0];
  $title = $row[1];
  $contents = $row[2];

  $contents = str_replace("<", "&lt;", $contents);
  $contents = str_replace(">", "&gt;", $contents);

?>
<div class="chunk">
<div class="exname"><a href="#"><?=$day?> - <?=$title?></a></div>
<div class="excontent">
<pre>
<?=$contents?>
</pre>
</div>
</div>
<?php
 }
?>

</div>


</div>

</body>