<?php
/**
*
* @package acp
* @version $Id: v3_modules.xml 52 2008-08-05 18:55:00Z hatkirby $
* @copyright (c) 2008 Starla Insigna
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package acp
*/
class acp_instadisc
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$user->add_lang('mods/instadisc');

		// Set up the page
		$this->tpl_name		= 'acp_instadisc';
		$this->page_title	= 'ACP_INSTADISC';
		$submit			= isset($_POST['submit']) ? true : false;

		if ($submit)
		{
			// Add config to the database
			set_config('id_subscription_title', $_POST['subscription_title']);
			set_config('id_subscription_url', $_POST['subscription_url']);
			set_config('id_encryption_key', $_POST['encryption_key']);

			trigger_error($user->lang['ID_CHANGES_SAVED'] . adm_back_link($this->u_action), E_USER_NOTICE);
		} else {
			$idst	= isset($config['id_subscription_title']) ? $config['id_subscription_title'] : $config['sitename'];
			$idsu	= isset($config['id_subscription_url']) ? $config['id_subscription_url'] : '';
			$idec	= isset($config['id_encryption_key']) ? $config['id_encryption_key'] : '';

			$template->assign_vars(array(
				'S_SUBSCRIPTION_TITLE'	=> $idst,
				'S_SUBSCRIPTION_URL'	=> $idsu,
				'S_ENCRYPTION_KEY'	=> $idec,
				'S_SUBMIT'		=> $this->u_action
			));
		}
	}
}
