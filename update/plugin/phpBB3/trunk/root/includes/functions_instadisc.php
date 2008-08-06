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
function sendItem($title, $author, $url, $fourm)
{
	global $config, $db, $phpbb_root_path;
	$verID = rand(1,65536);

	$url = str_replace($phpbb_root_path, generate_board_url() . '/', $url);

	$da = array('forum_id' => $fourm);
	$getfourm = "SELECT * FROM " . FORUMS_TABLE . " WHERE " . $db->sql_build_array('SELECT', $da);
	$getfourm2 = $db->sql_query($getfourm);
	$getfourm3 = $db->sql_fetchrow($getfourm2);
	$db->sql_freeresult($getfourm2);
	$semantics = array('forum' => $getfourm3['forum_name']);

	$client = new xmlrpc_client($config['id_central_server']);
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval($config['id_username'], 'string'),
								new xmlrpcval(md5($config['id_username'] . ':' . md5($config['id_password']) . ':' . $verID), 'string'),
								new xmlrpcval($verID, 'int'),
								new xmlrpcval(generate_board_url() . '/', 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($author, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval(serialize($semantics), 'string')));
	$client->setDebug(4);
	$client->send($msg);
}

?>
