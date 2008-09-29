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
function sendItem($title, $userID, $url, $fourm)
{
	global $config, $db, $phpbb_root_path;
	$verID = rand(1,2147483647);

	$da = array('user_id' => $userID);
	$getuser = "SELECT * FROM " . USERS_TABLE . " WHERE " . $db->sql_build_array('SELECT', $da);
	$getuser2 = $db->sql_query($getuser);
	$getuser3 = $db->sql_fetchrow($getuser2);
	$db->sql_freeresult($getuser2);
	$author = $getuser3['username'];

	$url = str_replace($phpbb_root_path, generate_board_url() . '/', $url);

	$da = array('forum_id' => $fourm);
	$getfourm = "SELECT * FROM " . FORUMS_TABLE . " WHERE " . $db->sql_build_array('SELECT', $da);
	$getfourm2 = $db->sql_query($getfourm);
	$getfourm3 = $db->sql_fetchrow($getfourm2);
	$db->sql_freeresult($getfourm2);
	$semantics = array('forum' => $getfourm3['forum_name']);

	$subscriptionURL = 'http://' . $_SERVER['SERVER_NAME'] . '/forum-post/' . generateSlug($config['id_subscription_title']) . '/';

	$encID = 0;
	if (($config['id_encryption_key'] != '') && extension_loaded('mcrypt'))
	{
		$encID = rand(1,2147483647);

		$cipher = 'rijndael-128';
		$mode = 'cbc';
		$key = substr(md5(substr(str_pad($config['id_encryption_key'],16,$encID),0,16)),0,16);

		$td = mcrypt_module_open($cipher, "", $mode, "");

		$title = encryptString($td, $key, $title);
		$author = encryptString($td, $key, $author);
		$url = encryptString($td, $key, $url);

                foreach ($semantics as $name => $value)
                {
                        $semantics[$name] = encryptString($td, $key, $value);
                }

                mcrypt_module_close($td);
	}

	$client = new xmlrpc_client('http://rpc.instadisc.org');
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval($subscriptionURL, 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($author, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval(serialize($semantics), 'string'),
								new xmlrpcval($encID, 'int')));
	$resp = $client->send($msg);
	$val = $resp->value()->scalarVal();

	if ($val == 2)
	{
		sendItem($title, $userID, $url, $fourm);
	}
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

function encryptString($td, $key, $string)
{
        mcrypt_generic_init($td, $key, strrev($key));
        $string = bin2hex(mcrypt_generic($td, $string));
        mcrypt_generic_deinit($td);

        return $string;
}

?>
