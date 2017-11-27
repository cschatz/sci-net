<?php session_set_cookie_params (0); session_start(); ?>

<h1>Grades</h1>

<div class="longlisting">

<?php
       exec ("./wrap.pl grades " . $_SESSION['course'] . " " . $_SESSION['idnum'], &$result);
     
     foreach ($result as $line)
       {
	 echo "$line\n";
       }
?>


</div>

