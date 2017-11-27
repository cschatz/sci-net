<?php require("sessionwithadmin.php"); 
header('Content-disposition: attachment; filename=' . $_SESSION['course']
       . 'data.csv');
header('Content-type: text/csv');
passthru ("./data.pl " . $_SESSION['course'] . " fulldata");
?>