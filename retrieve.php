<?php
session_set_cookie_params (0); session_start(); 
exec ("./wrap.pl retrievename " . $_SESSION['course'] . " " . $_GET['a'] . " " . $_SESSION['idnum'], $result);

$filename = $result[0];

header ("Content-type: application/octet-stream");
header ("Content-Disposition: attachment; filename=$filename");

passthru ("./wrap.pl retrieve " . $_SESSION['course'] . " " . $_GET['a'] . " " . $_SESSION['idnum'] . " 1");
exit;
?>