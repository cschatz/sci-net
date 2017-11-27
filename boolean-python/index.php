<!DOCTYPE HTML>
<head>
<title>Conditions Practice</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="boolean.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../style.css">-
<link rel="stylesheet" type="text/css" media="screen" href="toolstyle.css">

</head>

<div class="minor" style="font-size: 1.2em">

<h1>Conditions Practice</h1>

<div style="font-size: 0.9em; border: 1px solid blue; background: #cce; padding: 3px">Note: This page <b>requires a CSS3-compliant browser</b>. If 
the display below looks weird, try a different or newer browser.
</div>

<p>This is a tool to practice reasoning about the logic of
  <b>conditional expressions</b>.
<!--, which in Stencyl take the
   form of green condition blocks:<br />
   (*The real stencyl ones are pointed, and not quite the same color.)
<center>
<div class="condblock">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
</center>
-->
</p>

<!--
<p>Every time one of those expressions is reached in the process of running a Stencyl
program, the Stencyl system figures out its <b>value</b>. A condition block's value
will only ever be <b>true</b> or <b>false</b>, and understanding how true or false
values are determined is the key to many fundamental programming concepts.</p>

<p>Try the randomized questions this tool gives you. Over time, your skills should
improve until you are always able to get 100% of the questions correct.</p>
-->

<p>Every time one of those expressions is reached,
Python figures out its <b>value</b>, which will only ever be
<b>True</b> or <b>False</b>.
Understanding how true or false
values are determined is the key to many fundamental programming concepts.</p>

<p>Try the randomized questions this tool gives you. Over time, your skills should
improve until you are always able to get 100% of the questions correct.</p>

<hr />

<div class="inset">

<p>Your record so far: <span id='correctcount'>0</span> / <span id='totalcount'>0</span> <span id='percentnote'></span> <button id='reset'>Reset Counts</button>
</p>

<div style="border: 1px solid grey; padding: 3px;">

<p><b>Current Question</b></p>

<p id='xposnote'></p>

<center>

<div id="question">

</div>

<p><b>Is the condition above true or false?</b></p>

<div id='answers'>
<button class='answerbutton'>True</button>&nbsp;&nbsp;&nbsp;
<button class='answerbutton'>False</button>
</div>

<div id='feedbackarea'>
<div id='feedback'>
...
</div>
<button id='nextbutton'>Next Question</button>
</div>

</center>

</div><!-- box -->

</div><!-- inset -->

</div><!-- minor -->

<div id="debug">

</div>

</div>

