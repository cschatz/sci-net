<?php  
require("session.php");
$path = "assignments/" . $_GET['course'] . "/" . $_GET['what'];
if (file_exists($path . ".php"))
  {
    $path = $path . ".php";
  }
 else if (file_exists($path . ".pdf"))
   {
     $path = $path . ".pdf";
     $filename = $_GET['course'] . "-" . $_GET['what'] . ".pdf";
     header ("Content-type: application/octet-stream");
     header ("Content-Disposition: attachment; filename=$filename");
     readfile($path);
   }
else
  {
    echo "Assignment Link Bad - this shouldn't happen.";
    exit;
  }
?>

<head>
<link rel="stylesheet" type="text/css" media="screen" href="style.css">	
<link rel="stylesheet" type="text/css" media="print" href="style.css">	
<title>Assignment <?=$_GET['counter']?></title>
</head>

<div class="major">

   <h1><?=strtoupper($_GET['course'])?>, Assignment <?=$_GET['counter']?><br />
<?=$_GET['name']?></h1>

  <?php include ($path); ?>


</div>
