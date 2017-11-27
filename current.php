<?php
session_set_cookie_params (0);
session_start();
exec("./wrap.pl current", &$output);
$what = implode("\n", $output);
$_SESSION['current'] = $what;
echo "$what";
?>
