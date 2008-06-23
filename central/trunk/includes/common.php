<?php

/* InstaDisc Server - A Four Island Project */

include('includes/db.php');

$getconfig = "SELECT * FROM config";
$getconfig2 = mysql_query($getconfig);
$config = mysql_fetch_array($getconfig2);

include('includes/template.php');
include('includes/header.php');

?>
