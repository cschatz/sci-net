<?php
require("session.php");

$all = file_get_contents("quotes.txt");

$quoteArray = split ("#\n", $all);

$indexCount = count($quoteArray);

if (!isset($_SESSION['quoteindex']))
  {
    $_SESSION['quoteindex'] = rand(0, $indexCount-1);
  }
else
  {
    
    $_SESSION['quoteindex'] = ($_SESSION['quoteindex'] + 1) % $indexCount;
  }
echo $quoteArray[$_SESSION['quoteindex']];
?>