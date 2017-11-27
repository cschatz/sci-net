<?php session_set_cookie_params (0);session_start(); ?>

<div class="inset">

<?php
require("db.php");

if (! file_exists(".quiz." . $_SESSION['course']))
  {
    if ($_SESSION['idnum'] == 1 &&
	file_exists(".quiz.me." . $_SESSION['course']))
      {
	$whichquiz = trim(file_get_contents(".quiz.me." . $_SESSION['course']));
	echo "<h1>" . strtoupper($_SESSION['course']) . " - Quiz " . $whichquiz . "</h1>\n";
	$whichquiz = "quiz" . $whichquiz;
	$_SESSION['whichquiz'] = $whichquiz;
      }
    else
      {
?>
<h1>No Quiz Available</h1>
<center>
<p>There is no quiz currently available.</p>
</center>

<?php
	exit;
      }
  }
else
  {
    $whichquiz = trim(file_get_contents(".quiz." . $_SESSION['course']));
    echo "<h1>" . strtoupper($_SESSION['course']) . " - Quiz " . $whichquiz . "</h1>\n";
    
    $whichquiz = "quiz" . $whichquiz;

    $_SESSION['whichquiz'] = $whichquiz;
  }

$result = mysql_query("select * from answers where short_name='" . 
		      $whichquiz . "' and course='" .
		      $_SESSION['course'] . "' and ID='" . 
		      $_SESSION['idnum'] . "'");
if (mysql_num_rows($result) > 0)
  {
    echo "<div class='msg_pos'>You have already completed the quiz.</div>\n";
    exit;
  }


$result = mysql_query("select start from quizstamps where short_name='" . 
		      $whichquiz . "' and course='" .
		      $_SESSION['course'] . "' and ID='" . 
		      $_SESSION['idnum'] . "'");

if (mysql_num_rows($result) > 0)
  {
    $row = mysql_fetch_row($result);
    $_SESSION['quiz_start'] = $row[0];
  }
else
  {
    $_SESSION['quiz_start'] = date("Y-m-d H:i");
     mysql_query("insert into quizstamps values ('" .
		$_SESSION['course'] . "', '" .
		$whichquiz . "', '" .
		$_SESSION['idnum'] . "', '" .
		$_SESSION['quiz_start'] . "')");
  }
?>

<?php
echo "<div class='msg_pos'>Your quiz start time: <b>" . $_SESSION['quiz_start'] . "</b></div>";
?>

<form action="quiz2.php" method="post">
<?php
      $quizon = "yes";
      include ("quizzes/" . $_SESSION['course'] . "/" . $whichquiz . ".php");
?>

      <?php if ($_SESSION['quiz_nosubmit'] != 1) { ?>
<input type="submit" class="button" value="I'm Done (Submit)" onclick="return confirmSubmit();">
	    <?php } ?>
</form>

</div>
