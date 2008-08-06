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
	'S_SUBSCRIPTION'	=> ($config['server_protocol'] . $config['server_name'] . $config['script_path'] . '/'),
	'S_TITLE'		=> $config['id_subscription_title'],
));

page_footer();

?>
