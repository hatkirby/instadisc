<?php

/**
* InstaDisc Update (SF Generator) - A Four Island Project
*
* This is the InstaDisc SF Generator (part of the Update component),
* which creates a Subscription File for you. For more information on
* how to use this, please see:
*
* @url http://instadisc.org/Subscription_File_Generator
*/

/**
* CONFIGURATION - You MUST edit this for the SF Generator to work
*/
$idusTitle = ''; // Subscription Title
$idusCategory = ''; // Subscription Category
$idusPassword = ''; // Encryption Keyword (OPTIONAL)

/* END CONFIGURATION - Do NOT edit below this point */

if ($idusTitle == '')
{
	die('$idusTitle is unset, please set it to your Subscription\'s Title');
}

if ($idusCategory == '')
{
	die('$idusCategory is unset, please set it to your Subscription\'s Category');
}

$idusSubscriptionURL =  'http://' . $_SERVER['SERVER_NAME'] . '/' . $idusCategory . '/' . generateSlug($idusTitle) . '/';

echo('<DIV STYLE="display: none">: ' . "\n");
echo('Subscription: ' . $idusSubscriptionURL . "\n");
echo('Title: ' . $idusTitle . "\n");
echo('Category: ' . $idusCategory . "\n");

if (($idusPassword != '') && extension_loaded('mcrypt'))
{
	$verID = rand(1,2147483647);

	echo('Verification: ' . md5($idusTitle . ':' . md5($idusPassword) . ':' . $verID) . "\n");
	echo('Verification-ID: ' . $verID . "\n");
}

echo('End: </DIV><DIV STYLE="margin: 0 auto; margin-top: 5em; width: 500px; background-color: #FBEC5D; text-align: center; padding: 4px;"><B>This is an InstaDisc Subscription file.</B><P>If you would like to subscribe to the InstaDisc subscription:<BR><I>' . $idusTitle . '</I> (<U>' . $idusCategory . '</U>)<BR>Copy the address of this file to the "Add Subscription" form on your InstaDisc client.</DIV>' . "\n");

if (($idusPassword != '') && extension_loaded('mcrypt'))
{ 
	echo('<!--Notice: --><CENTER><B>Please note that this is an <I>encrypted</I> subscription.</B><BR>To subscribe to it, you must know its password.</CENTER>' . "\n");
}

function generateSlug($title)
{
        $title = preg_replace('/[^A-Za-z0-9]/','-',$title);
        $title = preg_replace('/-{2,}/','-',$title);
        if (substr($title,0,1) == '-')
        {
                $title = substr($title,1);
        }
        if (substr($title,strlen($title)-1,1) == '-')
        {
                $title = substr($title,0,strlen($title)-1);
        }
        $title = strtolower($title);

        return($title);
}

?>
