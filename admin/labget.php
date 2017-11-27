<?php
session_set_cookie_params (0); session_start(); 
passthru ("./lab.pl " . $_GET['action']);
?>

