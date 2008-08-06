<?php
/**
*
* @package phpBB3
* @version $Id: functions_instadisc.php 2008-08-06 07:12:00Z hatkirby $
* @version (c) 2008 Starla Insigna
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

include($phpbb_root_path . 'includes/xmlrpc/xmlrpc.inc');

/**
* Send an InstaDisc Item
*/
function sendItem($title, $author, $url, $semantics)
{
	$verID = rand(1,65536);

	$client = new xmlrpc_client($config['id_central_server']);
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval($config['id_username'], 'string'),
								new xmlrpcval(md5($config['id_username'] . ':' . md5($config['id_password']) . ':' . $verID), 'string'),
								new xmlrpcval($verID, 'int'),
								new xmlrpcval($config['server_protocol'] . $config['server_name'] . $config['script_path'] . '/', 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($author, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval($semantics, 'array')));
	$client->send($msg);
}

?>
