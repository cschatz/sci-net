<?php session_set_cookie_params (0); session_start(); ?>
<div class="inset">

<h1>Who Am I?</h1>

<p>Course: <b><?=strtoupper($_SESSION['course'])?></b></p>

<p>ID number: <b><?=$_SESSION['idnum']?></b></p>

<p>Group number: <b>
<?php passthru ("./wrap.pl groupnum " . $_SESSION['course'] . "  " . $_SESSION['idnum']);
?></b>
</p>

<?php if ($_SESSION['course'] == 'cs31') { ?>
    <p><b>CS31 Database Access Information</b><br />
<?php passthru ("./wrap.pl dbinfo " . $_SESSION['idnum']);
?>
</p>
<?php } ?>


<?php if ($_SESSION['course'] == 'cs46') { ?>
Your LPC Game Server API Key:</b>
<b><span class="code"><?php passthru ("./wrap.pl apikey " . $_SESSION['idnum']);
?></span></b>
</p>
<?php } ?>


<br /><br /><br /><br />

<p><i>Probably useless information: the current session ID is <?=session_id()?></p>

</div>

