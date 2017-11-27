<?php session_set_cookie_params (0); session_start(); ?>

<?php
if (isset($_SESSION['quiz_start']))
  {
    include('quiz.php');
    exit;
  }
?>

<?php
if (file_exists(".quiz." . $_SESSION['course']) ||
    file_exists(".quiz.me." . $_SESSION['course']))
  {
?>

    <p>You are about to begin the current <?=strtoupper($_SESSION['course'])?> quiz. Once you begin, it will
be timestamped and you will have 60 minutes to submit it.</p>

<p>If/when you are ready, click the button below.</p>

<button onclick="
  new Ajax.Updater('main', 'quiz.php');
">Start the Quiz</button>

      <?php } else { ?>

<h1>No Quiz Available</h1>
<center>
<p>There is no quiz currently available.</p>
</center>
  <?php } ?>
