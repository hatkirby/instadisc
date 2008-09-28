<?php
/**
*
* @package phpBB3
* @version $Id: instadisc.php 2008-08-06 07:12:00Z hatkirby $
* @version (c) 2008 Starla Insigna
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);

// Start session
$user->session_begin();
$auth->acl($user->data);

$user->setup('mods/instadisc', $forum_data['forum_style']);

$template->set_filenames(array(
	'body' => 'instadisc.html')
);

$template->assign_vars(array(
	'S_SUBSCRIPTION'	=> ('http://' . $_SERVER['SERVER_NAME'] . '/forum-post/' . generateSlug($config['id_subscription_title']) . '/'),
	'S_TITLE'		=> $config['id_subscription_title'],
));

if ($config['id_encryption_key'] != '')
{
	$verID = rand(1,2147483647);

	$template->assign_block_vars('encrypted', array(	'S_VERIFICATION' => md5($config['id_subscription_title'] . ':' . md5($config['id_encryption_key']) . ':' . $verID),
								'S_VERID' => $verID));
}

page_footer();

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
