<?php require("sessionwithadmin.php"); ?>

<?php passthru ("./data.pl " . $_SESSION['course'] . " "
		. $_GET['action']); ?>


