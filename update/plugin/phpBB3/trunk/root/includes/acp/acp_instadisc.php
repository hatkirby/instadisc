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
		$submit		= isset($_POST['submit']) ? true : false;

		if ($submit)
		{
			// Add config to the database
			set_config('id_subscription_title', $_POST['subscription_title']);
			set_config('id_central_server', $_POST['central_server']);

			trigger_error('The changes you made to your InstaDisc settings have been saved!' . adm_back_link($this->u_action), E_USER_NOTICE);
		} else {
			$idst	= isset($config['id_subscription_title']) ? $config['id_subscription_title'] : $config['sitename'];
			$idcs	= isset($config['id_central_server']) ? $config['id_central_server'] : '';

			$template->assign_vars(array(
				'S_SUBSCRIPTION_TITLE'	=> $idst,
				'S_CENTRAL_SERVER'	=> $idcs,
				'S_SUBMIT'		=> $this->u_action
			));
		}
	}
}
