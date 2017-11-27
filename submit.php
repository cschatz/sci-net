<?php session_set_cookie_params (0); session_start(); ?>

<?php
$_SESSION['assn'] = $_GET['assn'];
$_SESSION['fname'] = $_GET['fname'];
?>
<div class="inset" style="font-size: 1.2em">

<h1>Assignment Submission</h1>

<form action="upload.php" method="post" enctype="multipart/form-data">
<table width=80%>
  <tr><td align="right">Assignment:</td><td><b><?=$_GET['name']?></b></td></tr>
  <tr><td align="right">File required:</td><td><span class="code"><?=$_GET['fname']?></span</td></tr>
  <tr><td align="right">Find and submit your file:</td><td><input class="textfield" type="file" name="thefile" size=35></td></tr>
<tr><td> </td><td><input class="button" type=submit value="Submit"></td></tr>
</table>
</form>

</div>
