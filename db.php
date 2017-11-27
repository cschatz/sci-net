<?php
$dbHost = "DATABASE_HOST"
$dbUser = "USERNAME";
$dbPass = "PASSWORD";
$dbDatabase = "DATABASE_NAME";
$db = mysql_connect("$dbHost", "$dbUser", "$dbPass") or die ("Error connecting to database.");
mysql_select_db("$dbDatabase", $db) or die ("Couldn't select the database.");
?>
