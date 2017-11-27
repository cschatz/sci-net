<?php
$count = file_get_contents(".count");
$count = $count + 1;
file_put_contents(".count", $count);
?>

<p>Current count is <?=$count?></p>
  <p>Now is <?=date("c")?></p>