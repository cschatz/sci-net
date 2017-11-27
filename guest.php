<?php 
  //require("session.php");
require("db.php");
?>

<head>
<link rel="stylesheet" type="text/css" media="screen" href="style.css">	
</head>

<div class="minor">

<?php
if (isset($_POST['msg']) && ! empty($_POST['msg']))
  {
    mysql_query("insert into guestbook values (" 
		. $_POST['id'] . ", date(now()), '"
		. addslashes($_POST['msg']) . "')");
?>

<p>Thank you! Your message was received and recorded.<br />
   (<a href="/sn/guest.php">Leave another message</a>)</p>

<?php
      } else {
?>

<h1>CS31 Guestbook</h1>

<p>To enter a message in the guestbook, type it below.<br />
<b>Note that only the first 70 characters will be stored.</b>
</p>

<form action="guest.php" method="post">
W Number:<input type="text" name="id" size="20" /><br />
Message: <input type="text" name="msg" size="30" />
<input type="submit" value="Submit" />
</form>

    <?php } ?>

<hr />

<p><u>API access for queries</u><br />
Use this URL:<br />
<span class="code">thumper.laspositascollege.edu/sn/gb.php?q=<i>query</i></span><br />
  Where <i>query</i> is one of:
<ul>
<li><b>count</b> - return the number of entries in the guestbook
<li><b>list</b> - return the complete list of entries in the guestbook
</ul>
</p>
<p><u>API access for posting</u><br />
Use this URL:<br />
<span class="code">thumper.laspositascollege.edu/sn/gb.php</span><br />
and send post data including two fields:
<ul>
<li><b>id</b> - an individual's W number
<li><b>msg</b> - the content of the guestbook message to add
</ul>
</p>
