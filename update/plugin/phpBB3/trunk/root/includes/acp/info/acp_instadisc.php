<?php
/**
*
* @package acp
* @version $Id: v3_modules.xml 52 2008-08-05 18:46:00Z hatkirby $
* @copyright (c) 2008 Starla Insigna
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @package module_install
*/
class acp_instadisc_info
{
	function module()
	{
		return array(
				'filename'	=> 'acp_instadisc',
				'title'		=> 'ACP_INSTADISC',
				'version'	=> '1.0.0',
				'modes'		=> array(
								'instadisc'	=> array(
												'title' => 'INSTADISC',
												'auth' => 'acl_a_board && acl_a_server',
												'cat' => array('ACP_AUTOMATION')
											)
							)
				);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}
