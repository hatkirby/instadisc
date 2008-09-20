<?php
/*
Plugin Name: InstaDisc Update Server
Plugin URI: http://fourisland.com/projects/instadisc/wiki/Update_Wordpress_Plugin
Description: This plugin provides two InstaDisc feeds for your Wordpress blog, a post subscription and a comment subscription.
Version: 1.0
Author: Starla Insigna
Author URI: http://fourisland.com
*/

/*  Copyright 2008  Starla Insigna  (email : hatkirby@fourisland.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (get_option('instaDisc_subscription_title') === FALSE)
{
	add_option('instaDisc_subscription_title',get_option('blogname'));
}

if ((get_option('instaDisc_blogPost_password') === FALSE) || !extension_loaded('mcrypt'))
{
	add_option('instaDisc_blogPost_password','');
}

if ((get_option('instaDisc_comment_password') === FALSE) || !extension_loaded('mcrypt'))
{
	add_option('instaDisc_comment_password','');
}

add_action('admin_menu', 'am_pages');

function am_pages()
{
	add_options_page('InstaDisc Settings', 'InstaDisc', 8, 'instadisc', 'id_settings_page');
}

function id_settings_page()
{
?><DIV CLASS="wrap"><H2>InstaDisc Settings</H2>
<FORM METHOD="post" ACTION="options.php"><?php
	wp_nonce_field('update-options');
?>
<H3>General</H3>
<TABLE CLASS="form-table">
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Subscription Title</LABEL>
 <TD>
  <INPUT TYPE="text" NAME="instaDisc_subscription_title" VALUE="<?php echo(get_option('instaDisc_subscription_title')); ?>" SIZE="40">
 </TD>
</TR>
</TABLE>
<H3>Blog Posts Subscription</H3>
<TABLE CLASS="form-table">
<?php
	if (extension_loaded('mcrypt'))
	{
?>
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Encryption Password</LABEL>
 <TD>
  <INPUT TYPE="password" NAME="instaDisc_blogPost_password" VALUE="<?php echo(get_option('instaDisc_blogPost_password')); ?>" SIZE="40">
  <BR>If you would like to password-protect your feed, enter a password into this box. That password will need to be known by anyone allowed to view your subscription. If you don't want to password protect this feed, leave this field blank.
 </TD>
</TR>
<?php
	}
?>
</TABLE>
<H3>Comments Subscription</H3>
<TABLE CLASS="form-table">
<?php
	if (extension_loaded('mcrypt'))
	{
?>
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Encryption Password</LABEL>
 <TD>
  <INPUT TYPE="password" NAME="instaDisc_comment_password" VALUE="<?php echo(get_option('instaDisc_comment_password')); ?>" SIZE="40">
  <BR>If you would like to password-protect your feed, enter a password into this box. That password will need to be known by anyone allowed to view your subscription. If you don't want to password protect this feed, leave this field blank.
 </TD>
</TR>
<?php
	}
?>
</TABLE>
<INPUT TYPE="hidden" NAME="action" VALUE="update">
<INPUT TYPE="hidden" NAME="page_options" VALUE="instaDisc_subscription_title,instaDisc_blogPost_password,instaDisc_comment_password">
<P CLASS="submit"><INPUT TYPE="submit" NAME="Submit" VALUE="<?php _e('Save Changes') ?>"></P>
</FORM></DIV><?php
}

include('xmlrpc/xmlrpc.inc');

add_action('publish_post','sendPost');

function sendPost($id)
{
	$post = get_post($id);
	$title = $post->post_title;
	$author = get_userdata($post->post_author);
	$authorName = $author->display_name;
	$url = get_permalink($id);

	$subscriptionURL = 'http://' . $_SERVER['SERVER_NAME'] . '/blog-post/' . generateSlug(get_option('instaDisc_subscription_title')) . '/';

	$encID = 0;
	if (get_option('instaDisc_blogPost_password') != '')
	{
		$encID = encryptData($title, $author, $url, get_option('instaDisc_blogPost_password'));
	}

	$verID = rand(1,2147483647);

	$client = new xmlrpc_client('http://central.fourisland.com/xmlrpc.php');
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array( new xmlrpcval($subscriptionURL, 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($authorName, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval(serialize(array()), 'string'),
								new xmlrpcval($encID, 'int')));
	$resp = $client->send($msg);
	$val = $resp->value()->scalarVal();

	if ($val == 2)
	{
		sendPost($id);
	}
}

add_action('comment_post','sendComment');

function sendComment($id)
{
	$comment = get_comment($id);
	$post = get_post($comment->comment_post_ID);
	$title = $post->post_title;
	$author = $comment->comment_author;
	$url = get_permalink($comment->comment_post_ID) . "#comments-" . $id;

	$subscriptionURL = 'http://' . $_SERVER['SERVER_NAME'] . '/blog-comment/' . generateSlug(get_option('instaDisc_subscription_title')) . '/';

	$encID = 0;
	if (get_option('instaDisc_comment_password') != '')
	{
		$encID = encryptData($title, $author, $url, get_option('instaDisc_comment_password'));
	}

	$verID = rand(1,2147483647);

	$client = new xmlrpc_client('http://central.fourisland.com/xmlrpc.php');
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval($subscriptionURL, 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($author, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval(serialize(array()), 'string'),
								new xmlrpcval($encID, 'int')));
	$resp = $client->send($msg);
	$val = $resp->value()->scalarVal();

	if ($val == 2)
	{
		sendComment($id);
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

function encryptData(&$title, &$author, &$url, $password)
{
	$encID = rand(1,2147483647);

	$cipher = "rijndael-128";
	$mode = "cbc";
	$key = substr(md5(substr(str_pad($password,16,$encID),0,16)),0,16);

	$td = mcrypt_module_open($cipher, "", $mode, "");

	$title = encryptString($td, $key, $title);
	$author = encryptString($td, $key, $author);
	$url = encryptString($td, $key, $url);

	mcrypt_module_close($td);

	return $encID;
}

function encryptString($td, $key, $string)
{
	mcrypt_generic_init($td, $key, strrev($key));
	$string = bin2hex(mcrypt_generic($td, $string));
	mcrypt_generic_deinit($td);

	return $string;
}

?>
