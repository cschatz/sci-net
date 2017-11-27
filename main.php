<?php
require ("session.php");
?>

<?php require ("top.php"); ?> 

<div class="inset">

<?php
    if ($_SESSION['atassignments'] == 1)
      {
	unset($_SESSION['atassignments']);
	include("pages/assignments.php");
	exit;
      }

if ($_SESSION['admin'] == "yes")
  {
    if (isset($_SESSION['course']) && !isset($_SESSION['phase']))
      {
	$_SESSION['phase'] = "Collection";
	header("Location: main.php");
      }
    if (!isset($_SESSION['phase']) || $_GET['cntl']=="reset")
      {
	$_SESSION['phase'] = "Preliminary";
	passthru ("admin/responses.pl boguscourse reset");
	header("Location: main.php");
	exit;
      }
    else if ($_SESSION['phase'] == "Preliminary" && $_POST['courseandsection'] != "")
      {
	$_SESSION['phase'] = "Collection";
	$pieces = explode(" ", $_POST['courseandsection']);
	$_SESSION['course'] = $pieces[0];
	$_SESSION['section'] = $pieces[1];
	header("Location: main.php");
	exit;
      }
    include("admin/index.php");
    exit;
  }



?>

<h1><i>Welcome to <?=strtoupper($_SESSION['course'])?></i></h1>



  <?php if (1) { //($_SESSION['course'] != "cs46xxx") { ?>
<div id="quotecontainer">
<div id="csquote">
<?php
     include ("quote.php"); 
?>
</div>
</div>

    <?php } ?>

    <?php if ($_SESSION['course'] == "cs2xxx") { ?>
  Starter code for Question 4 on the Midterm is here:<br />
<a href="/sn/cs2midterm1.cpp">cs2midterm1.cpp</a>
  <?php } ?>

    <?php if ($_SESSION['course'] == 'cs20') { 
?>

<?php } ?>


    <?php if ($_SESSION['course'] == 'cs31xxx') { 
?>

<div class="msg_strong">
The starter code for the last item<br />
on the final exam is
<a href="/sn/cs31final.java">HERE</a>
</div>

<?php } ?>

<?php if (0) { ?>

<div class="msg_strong">
Please fill out the
<a href="https://docs.google.com/forms/d/1VHmnCrkmjTKZXMZrB99IHQdzPYnK2VN6kqI65XvzCfg/viewform"
target="_blank">
Spring 2015 Initial Survey</a>
</div>

      <?php } ?>

<?php
if ($_SESSION['course'] == 'cs20xxx') 
  {
?>

<div class="msg_strong">
Random art submissions are <a href="/sn/art/" target="_blank">HERE</a>
</div>

<?php
      }
?>


<?php if (isset($_SESSION['success_msg'])) { ?>
<div class="msg_pos">
<p><b>Success</b></p>
<p><?=$_SESSION['success_msg']?></p>
</div>

<?php unset($_SESSION['success_msg']); } ?>

<?php if($_SESSION['course'] == "cs46xxx") { ?>
<div class="msg_strong">
      To take the midterm exam,
<a href="startexam.php">CLICK HERE</a>.
</div>

      <?php } ?>

<?php if ($_SESSION['course'] == "cs16xxx") { ?>
<div class="status">
      <p>Looking for the remove mac availability calendar? <a><a href="http://thumper.laspositascollege.edu/sn/remote_calendar.php" target="_blank">Here it is</a>.</p>
</div>
  <? } ?>


<p>Please select something from the menus above.</p>

</div>

<?php require ("bottom.php"); ?>
