<?php require ("session.php");


?>
<head>
<link rel="stylesheet" type="text/css" media="screen" href="style.css">	
</head>

<div class="minor">

    <p>You are submitting the file <b><?=$_SESSION['fname']?></b>,
with contents as shown below. 
      In most cases, it will be graded by compiling it and running the resulting program. Please check that it matches what you expected and conforms
    to any submission instructions given for the assignment.
</p>

<p>
      By continuing, you are stating that you agree with these statements:
<ul>
      <li>I have tested the program in this form, and it successfully compiles.
      <li>The resulting program runs all tasks that I want graded, without
needing any changes to the code.
</ul>
      You also assert your understanding that:
<ul>
<li>If this document does not successfully compile,<br /> 
my score for the assignment will be 0.
<li>I will not receive any credit for any parts of
the assignment that do not run,<br />
regardless of what code is in the file.
<li>If there is a simple problem that prevents my
code from running the way it is supposed to, I have the option to
fix it and request a regrade, but the final score will still include 
a penalty for the problems that caused it to not compile or otherwise
break.
</ul>

<center>
<form action="upload3.php">
<input type="hidden" name="action" value="yes">
<input type="submit" value="Yes (confirm submission)">
</form>

<form action="upload3.php">
<input type="hidden" name="action" value="no">
<input type="submit" value="No (cancel submission)">
<form>
</center>

<hr />
<pre>
<?php
  exec ("./wrap.pl retrieve " . $_SESSION['course'] . " " . $_SESSION['assn'] . " " . $_SESSION['idnum'], $output);
    foreach ($output as $line)
      {
	echo htmlspecialchars($line) . "\n";
      }
?>
<pre>

</div>


