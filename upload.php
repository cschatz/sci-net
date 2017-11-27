<?php session_set_cookie_params (0); session_start(); ?>

<?php
if ($_SESSION['course'] == 'cs46')
  {
    $file_limit = 256 * 1024 * 1024;
  }
 else if ($_SESSION['course'] == 'cs16')
  {
   $file_limit = 16 * 1024 * 1024;
  }
else
  {
    $file_limit = 2 * 1024 * 1024;
  }

$error = "";

if($_FILES['thefile']['name'] != "") 
  {
    if (strtolower($_FILES['thefile']['name']) == strtolower($_SESSION['fname']))
      {
	if (is_uploaded_file($_FILES['thefile']['tmp_name'])) 
	  {
	    // if the file is clean, move it to final location
	    if(file_exists($_FILES['thefile']['tmp_name'])) 
	      {
		// check the size of the file
		if($_FILES['thefile']['size'] < $file_limit) 
		  {
		    $target_path = "holding/";
		    $target_path = $target_path . basename( $_FILES['thefile']['tmp_name']); 
		    if(!copy($_FILES['thefile']['tmp_name'], $target_path))
		      {
			$error = "Internal copying error";
		      }
		    else
		      {
			$NAME = $_SESSION['fname'];
			exec ("./wrap.pl submit "
			      . $_SESSION['course'] . " " . $_SESSION['assn'] . " " 
			      . "w" . $_SESSION['idnum'] . " " . $target_path . " "
			      . $NAME, &$result);
			if ($result[0] == "Ok")
			  $_SESSION['success_msg'] = "Successfully uploaded <span class='code'>$NAME</span> for " . strtoupper($_SESSION['assn']);
			else
			  $error = "Internal: $result[0]";
		      }
		  }
		else
		  {
		    $error = "File exceeded size limit";
		  }
	      }
	    else
	      {
		$error = "Internal upload error";
	      }
	  }
	else
	  {
	    $error = "File security error";
	  }
      }
    else
      {
	$error = "File provided doesn't match the requested filename '" .
	  $_SESSION['fname'] ."'";
      }
  }
 else
   {
     $error = "No file given";
   }

if ($error != "")
  $_SESSION['submit_error'] = "Upload failed: $error";


//if ($error == "")
// {
//   header ("Location: upload2.php");
//  }
//else
// {
$_SESSION['atassignments'] = 1;
header("Location: main.php");
//  }
?>