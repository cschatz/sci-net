<?php
require("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST')
  {
    if (isset($_POST['id']) && ! empty ($_POST['id'])
	&& isset($_POST['msg']) && ! empty($_POST['msg']))
      {
	mysql_query("insert into guestbook values (" 
		    . $_POST['id'] . ", date(now()), '"
		    . addslashes($_POST['msg']) . "')");
	echo "Data submitted\n";
      }
    else
      {
	echo "Post request failed - missing or empty fields\n";
      }
    exit;
  }

if ($_GET['q'] == "count")
  {
    $result = mysql_query("select count(message) from guestbook natural join roster where course='cs31'");
    $row = mysql_fetch_row($result);
    echo $row[0];
  }
 else if ($_GET['q'] == "list")
   {
     $result = mysql_query("select day, fname, lname, message from guestbook natural join roster where course='cs31' order by day");
     while ($row = mysql_fetch_row($result))
       {
	 echo "$row[0], $row[1] $row[2], $row[3]\n";
       }
   }
 else
   {
     echo "[Invalid query.]";
   }

?>