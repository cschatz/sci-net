<?php session_set_cookie_params (0); session_start(); ?>

<h1><?=strtoupper($_SESSION['course'])?> Pastebin</h1>

<div class="inset">

<form action="paste.php" method="post">
Title: <input type="text" name="title"><br />
<textarea cols="60" rows="20" name="contents" class="code">

</textarea><br />
<input type="submit" value="Paste This">
</div>
