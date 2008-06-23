<?php

/* InstaDisc Server - A Four Island Project */

include('db.php');

$getconfig = "SELECT * FROM config";
$getconfig2 = mysql_query($getconfig);
$config = mysql_fetch_array($getconfig2);

include('header.php');

?>
