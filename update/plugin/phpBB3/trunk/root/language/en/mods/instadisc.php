<?php
/** 
*
* instadisc [English]
*
* @package language
* @version $Id: v3_modules.xml 52 2008-08-05 19:03:00Z hatkirby $
* @copyright (c) 2008 Starla Insigna
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
					
/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}
						
// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
						
$lang = array_merge($lang, array(
	'ACP_INSTADISC'		=> 'InstaDisc',

	'INSTADISC'			=> 'InstaDisc',
	'INSTADISC_EXPLAIN'		=> 'Welcome to the InstaDisc Update Server admin panel. To set up your subscriptions, you have to do a few things. For more information, see',
	'INSTADISC_LINK_TEXT'		=> 'the InstaDisc phpBB3 plugin page',

	'SUBSCRIPTION'			=> 'Subscription',

	'SUBSCRIPTION_TITLE'		=> 'Subscription Title',
	'SERIES_URL'			=> 'Series Control URL',
	'SUBSCRIPTION_ID'		=> 'Subscription ID',
	'ENCRYPTION'			=> 'Encryption Key',

	'SERIES_URL_DESC'		=> 'This is the XML-RPC URL of your Series Control.',
	'SUBSCRIPTION_ID'		=> 'This is this subscription's unique identifier on your Series Control.',
	'ENCRYPTION_DESC'		=> 'If you have the PHP module "mcrypt" installed, you can password protect this subscription so that users who wish to subscribe to it must provide the correct password. Leave this field blank if you do not wish to encrypt this subscription.',

	'ID_CHANGES_SAVED'		=> 'The changes you made to your InstaDisc settings have been saved!',

	'LN'				=> "\n",
));
			
?>
