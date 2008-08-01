<?php

/* InstaDisc Server - A Four Island Project */

include_once('db.php');

function instaDisc_checkVerification($username, $verification, $verificationID, $table, $nameField, $passField)
{
	$getitem = "SELECT * FROM " . $table . " WHERE " . $nameField . " = \"" . $username . "\"";
	$getitem2 = mysql_query($getitem);
	$getitem3 = mysql_fetch_array($getitem2);
	if ($getitem3[$nameField] == $username)
	{
		$test = $username . ':' . $getitem3[$passField] . ':' . $verificationID;

		return (md5($test) == $verification);
	}

	return false;
}

?>
