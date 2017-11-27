<?php
session_set_cookie_params (0); session_start(); 
require ("db.php");


mysql_query ("select name, timestamp, details from requests where event='request' order by timestamp asc limit 1");


?>

