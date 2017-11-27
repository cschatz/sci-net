<?php
session_set_cookie_params (3600*24*30);
session_start(); 

passthru ("./responses.pl " . $_SESSION['course'] . " distrib");

?>
