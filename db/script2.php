<?php 
  $con = mysql_connect("localhost","web_user","railabs");

  if (!$con)
  {
    die('Could not connect: ' . mysql_error());
  }

  mysql_select_db("mafc", $con);

  $query = "
  INSERT INTO `results` (`id`, `user`, `choice`) VALUES (NULL, 'taylor','2');";

  mysql_query($query);

  mysql_close($con);
 ?>
