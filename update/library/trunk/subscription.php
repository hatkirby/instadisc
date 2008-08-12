<?php

/* InstaDisc Update - A Four Island Project */

include('instadisc.php'); // Make sure that if you move me away from instadisc.php that you update this include!

$id = (isset($_GET['id']) ? $_GET['id'] : 0);

echo('Subscription: ' . $idusSubscriptionURI[$id] . "\n");
echo('Title: ' . $idusSubscriptionTitle[$id] . "\n");
echo('Category: ' . $idusSubscriptionCategory[$id] . "\n");

if ($idusActivationKey[$id] != '')
{
	echo('Key: ' . $idusActivationKey[$id] . "\n");
}

if ($idusEncryptionKey[$id] != '')
{
	$verID = rand(1,65536);

	echo('Verification: ' . md5($idusSubscriptionTitle[$id] . ':' . md5($idusEncryptionKey[$id]) . ':' . $verID) . "\n");
	echo('Verification-ID: ' . $verID . "\n");
}

?>
