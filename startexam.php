<?
require("session.php");
require("db.php");
?>

<head>
<link rel="stylesheet" type="text/css" media="screen" href="style.css">	
</head>

<div class="minor">

<?php if ($_SESSION['course'] != "cs46") { ?>
<p>You should not be accessing this page - please try something else.</p>
<?php 								 
 exit;
} 
?>

<?php
   
$_SESSION['wasatstart'] = 1;
$result = mysql_query("select * from quizstamps where short_name='mid'
			  and course='cs46' and ID='" . $_SESSION['idnum'] . "'");
if (mysql_num_rows($result) > 0)
  {
    header("Location: exam.php");
    exit;
  }
?>

<p>You are about to begin the 
<?=strtoupper($_SESSION['course'])?> exam.
quiz. Once you begin, you will have 180 minutes (3 hours) to submit it.</p>

  <p>When you click the link below, you will be given access to the
exam in PDF form.<br />
I recommend printing a hardcopy of it.</p>

<p>When you are ready, click the link below.</p>

<div class="msg_strong">
<a href="/sn/exam.php">Start the Exam</a>
</div>

</div>
