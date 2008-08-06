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

		// Set up general vars
		$greeter	= request_var('hello', '', true);
		$submit		= isset($_POST['submit']) ? true : false;
		$hello		= 'Starla';

		if ($submit)
		{
			trigger_error(sprintf($user->lang['SAY_HELLO'], $greeter, $hello) . adm_back_link($this->u_action), E_USER_WARNING);
		} else {
			$template->assign_vars(array(
				'S_HELLO'	=> $hello,
				'S_SUBMIT'	=> $this->u_action
			));
		}
	}
}
