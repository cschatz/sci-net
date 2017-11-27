<?php require ("session.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
<title>SciNet</title>
<link rel="stylesheet" type="text/css" media="screen" href="style.css">
<link rel="stylesheet" type="text/css" media="screen" href="dropdown.css">
<link rel="stylesheet" type="text/css" media="print" href="style.css">
<link rel="stylesheet" type="text/css" media="print" href="dropdown.css">
<script type="text/javascript" src="script/prototype.js"></script>
<script type="text/javascript" src="script/ui.js"></script>
<script type="text/javascript">

var timer_var;

function ReSubmit()
{
window.open('resub.php','infowindow', 'width=680,height=500,status=0,toolbar=0,scrollbars=1,location=0,menubar=0,resizable=1');
}

function confirmSubmit()
{
  var agree=confirm("Are you sure you are ready to submit?");
  if (agree)
    return true;
  else
    return false;
}

function Toggle(which)
{
  $what = $('a' + which).className;
  if ($what == 'answer')
    $('a' + which).className = 'answer_hidden';
  else if ($what == 'answer_hidden')
    $('a' + which).className = 'answer';
  if ($what == 'excontent')
    $('a' + which).className = 'excontent_hidden';
  else if ($what == 'excontent_hidden')
    $('a' + which).className = 'excontent';
}
function Goto(loc, extra)
{
  var $url = "pages/" + loc + ".php";
  if (extra != "")
    $url = $url + "?arg=" + extra;
  $whatisdisplayed = $url;
  new Ajax.Updater("main", $url);
}

function Assignment(name)
{
  var $url = "assignments/" + name + ".php";
  new Ajax.Updater("main", $url);
}
function ShowAssignment(counter, name, course, what)
{

  var $url = "showassignment.php?counter=" + counter + 
    "&name=" + encodeURIComponent(name) + 
    "&course=" + encodeURIComponent(course) + 
    "&what=" + encodeURIComponent(what);
  window.open($url, "_blank");
}
function Upload(which, name, docname)
{
  var $url = "submit.php?assn=" + which + "&name=" + name + "&fname=" + docname;
  new Ajax.Updater("main", $url);
}

function Startquiz(name)
{
  new Ajax.Updater('main', 'startquiz.php');
}

function Quiz(name)
{
  new Ajax.Updater('main', 'quiz.php');
}

function StartInfobox()
{
  new Ajax.PeriodicalUpdater('infobox', 'current.php', {
    method: 'get', frequency: 2
	});
}

function DohTick()
{
  var $dc = parseInt($('dohcount').innerHTML);
  $dc --;
  if ($dc == 0)
    {
      clearInterval(timer_var);
      $('doh').hide();
    }
  else
    {
      $('dohcount').innerHTML = $dc;
    }
}

function StartDoh()
{
  clearInterval(timer_var);
  $('dohcount').innerHTML = 6;
  timer_var = setInterval("DohTick()", 1000);
}

</script>
</head>
	
<div id="all">

<div id="topmenu" class="menu">
<ul id="topmenu_ul">

<?php if ($_SESSION['admin'] == "yes")
    {
?>
<li><a href="logout.php">Logout</a></li>
<li><a onclick="Goto('pastebin')">Pastebin</a></li>
<li><a href="admin/participation.php" target="_blank">Participation</a></li>
<li><a href="admin/contest.php" target="_blank">Contest</a></li>
<li><a href="admin/lab.php" target="_blank">Lab Queue</a></li>
<li><a href="admin/details.php" target="_blank">Data</a></li>
<?php } else if (isset($_SESSION['idnum'])) { ?>

<li id="menu1" onmousedown="this.className='active';"><a>Student</a>
<ul>

<!--      <li><a onclick="Goto('grades')">Grades</a></li>-->
   <li><a onclick="Goto('whoami')">Who Am I?</a></li>
<li><a href="logout.php">Logout</a></li>
<li><a href="login.php">Switch Course</a></li>
<!--    <li><a onclick="Goto('stats')">Stats / XP</a></li> -->
</ul>
</li>

<li id="menu5" onmousedown="this.className='active';"><a>Actions</a>
  <ul>
    <li><a onclick="Goto('inclass'); StartInfobox();">Live Practice</a></li>
    <li><a href="contest.php" target="_blank">Contest</a></li>
   <li><a onclick="
window.open('lab.php','labwindow', 'width=680,height=400,status=0,toolbar=0,scrollbars=0,location=0,menubar=0,resizable=1');
">Lab Help</a></li>
   <?php if ($_SESSION['course'] == 'cs46') { ?>
 <li><a href="/sn/boolean/index.php" target="_blank">Condition Blocks</a></li>
<?php } ?>
       <?php if ($_SESSION['course'] == 'cs2') { ?>
 <li><a href="/sn/typedeterm/index.php" target="_blank">Practice Types</a></li>
	     <?php } ?>

</ul></li>


<li id="menu6" onmousedown="this.className='active';"><a>Info / Contact</a>
 <ul>
   <li><a href="mailto:lpc.cschatz&#43;<?=$_SESSION['course']?>@gmail.com">Email Instructor</a></li>
   <li><a href="http://scinett.com/~cschatz/" target="_blank">Office Hours</a></li>
    <li><a onclick="Goto('prime')">Prime Directives</a></li>
 </ul></li>

<li id="menu7" onmousedown="this.className='active';"><a>Examples</a>
<ul>
<li><a href="/sn/examples.php?course=<?=$_SESSION['course']?>&section=<?=$_SESSION['section']?>" target="_blank">Examples</a>
 </ul></li>

   <?php } else { ?>

<li><a href="index.php">Home</a></li>
<li><a onclick="Goto('register')">Register</a></li>

<?php } ?>

</ul>
</div>

<div id="banner">
<?php if ($_SESSION['admin'] != "yes") { ?>

   <a href="index.php">SciNet</a>
   <?php if (isset($_SESSION['idnum'])) { ?>
   <span class="userinfo">&nbsp;&nbsp;&bull;&nbsp;&nbsp;<?=strtoupper($_SESSION['course'])?>
					  &nbsp;<?=strtoupper($_SESSION['section'])?>
<?php
$info = exec ("./wrap.pl attendstatus " . $_SESSION['course'] . " '" . $_SESSION['section'] . "' " . $_SESSION['idnum']);
if (substr($info, 1, 3) != "N/A")
{
   $mod = "";
   if ($info == "Absent") 
   { 
     $mod = "class='neg'"; 
     $info .= " (logout to change)";
   }
   echo "&nbsp;&nbsp&bull;&nbsp;&nbsp;<span " . $mod . ">" . $info . "</span>";
} 
?>
</span>
   <?php } ?>
<?php } else { 
  $modifier = "";
  if (isset($_SESSION['course']) && $_SESSION['course'] != "public") $modifier=" &bull; " . strtoupper($_SESSION['course']) . " " . strtoupper($_SESSION['section']);
?>
  <i>Instructor Panel<?=$modifier?></i>
<?php } ?>
</div>

<div id="main">
