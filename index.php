<?php

$admin_id = 'ADMIN_ID';

session_set_cookie_params (0); // Until browser closed
session_start();
if(isset($_SESSION['idnum']) && isset($_SESSION['course']))
{
  header( "Location: main.php" );
  exit;
 }
$at_index = 1;

require ("top.php");

$adminlogin = ($_SESSION['adminlogin'] == 'yes');
unset($_SESSION['adminlogin']);
?>

<?php
if (!$adminlogin)
  {
?>

<center>
<p>If you have an account, enter your login information below.<br />
Otherwise, select "Register" from the menu above.</p>
</center>

<noscript>
<div class="msg_err">
<p><b>Javascript problem</b></p>
<p>Your browser has Javascript turned off.<br />
Please turn on Javascript and then 
<a href="index.php">click here to reload</a>.</p>
</div>
</noscript>
  
<?php if (preg_match("/MSIE/i", getenv("HTTP_USER_AGENT"))) { ?>
<div class="msg_err">
<p><b>WARNING</b></p>
<p>You appear to be using Internet Explorer.</p>
<p>This may cause problems with this site. It is strongly recommended that you use<br />
Firefox, Chrome or Safari instead.</p>
</div>
<?php } ?>

<?php if (isset($_SESSION['login_error'])) { ?>
<div class="msg_err">
<p><b>Login Error</b></p>
<p><?=$_SESSION['login_error']?></p>
</div>
<?php unset($_SESSION['login_error']); } ?>

<?php if (isset($_SESSION['reg_error'])) { ?>
<div class="msg_err">
<p><b>Registration Error</b></p>
<p><?=$_SESSION['reg_error']?></p>
</div>
<?php unset($_SESSION['reg_error']); } ?>

<?php if (isset($_SESSION['success_msg'])) { ?>
<div class="msg_pos">
<p><b>Success</b></p>
<p><?=$_SESSION['success_msg']?></p>
</div>
<?php unset($_SESSION['success_msg']); } ?>

<div class='hidden'>
<script type="text/javascript">
  document.write("</div>");
</script>

    <?php } else { ?>

<h1>Admin Login</h1>

  <?php } ?>

<div id="loginform">
<form method="post" action="login.php">
<center>
<table>
  <?php if (!$adminlogin) { ?>
  <tr><td align="right">ID Number:<br />
			    (or first and last name)
</td><td><input class="textfield" type="text" name="idnum" size=20></td></tr>
<?php } ?>
<tr><td align="right">Password:</td><td><input class="textfield" type="password" name="passwd" size=20></td></tr>
<tr><td align="right">&nbsp;</td><td><input class="button" type=submit value="Enter"></td></tr>
</table>
</center>
  <?php if ($adminlogin) { ?>
<input type="hidden" name="idnum" value="<?=$admin_id?>" />
			   <?php } ?>
</form>


</div>


<?php require ("bottom.php"); ?>

