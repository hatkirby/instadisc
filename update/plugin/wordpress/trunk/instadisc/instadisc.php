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
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Subscription File URL</LABEL>
 <TD>
  <?php echo(get_option('siteurl') . '/wp-content/plugins/instadisc/subscription.php'); ?>
  <BR>This is the URL that you advertise, the URL people use to subscribe to your subscription.
 </TD>
</TR>
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Central Server Username</LABEL>
 <TD>
  <INPUT TYPE="text" NAME="instaDisc_blogPost_centralServer_username" VALUE="<?php echo(get_option('instaDisc_blogPost_centralServer_username')); ?>" SIZE="40">
  <BR>This is the username you signed up with at your central server, and the one that you will/have register(ed) this subscription under.
 </TD>
</TR>
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Central Server Password</LABEL>
 <TD>
  <INPUT TYPE="password" NAME="instaDisc_blogPost_centralServer_password" VALUE="<?php echo(get_option('instaDisc_blogPost_centralServer_password')); ?>" SIZE="40">
  <BR>This is the password for the user above
 </TD>
</TR>
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Central Server Activation Key</LABEL>
 <TD>
  <INPUT TYPE="text" NAME="instaDisc_blogPost_centralServer_activationKey" VALUE="<?php echo(get_option('instaDisc_blogPost_centralServer_activationKey')); ?>" SIZE="40">
  <BR>When activating your subscription with a Central Server, it will require you to add an "activation key" to your "Subscription File" so as to prove that you actually do own the subscription.
 </TD>
</TR>
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Central Server URL</LABEL>
 <TD>
  <INPUT TYPE="text" NAME="instaDisc_blogPost_centralServer" VALUE="<?php echo(get_option('instaDisc_blogPost_centralServer')); ?>" SIZE="40">
  <BR>Both after registration and after activation, the Central Server you are using should tell you it's XML-RPC URL (usually a URL containing the string "xmlrpc.php"). Copy that URL into this field.
 </TD>
</TR>
</TABLE>
<H3>Comments Subscription</H3>
<TABLE CLASS="form-table">
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Subscription File URL</LABEL>
 <TD>
  <?php echo(get_option('siteurl') . '/wp-content/plugins/instadisc/subscription.php?comment='); ?>
  <BR>This is the URL that you advertise, the URL people use to subscribe to your subscription.
 </TD>
</TR>
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Central Server Username</LABEL>
 <TD>
  <INPUT TYPE="text" NAME="instaDisc_comment_centralServer_username" VALUE="<?php echo(get_option('instaDisc_comment_centralServer_username')); ?>" SIZE="40">
  <BR>This is the username you signed up with at your central server, and the one that you will/have register(ed) this subscription under.
 </TD>
</TR>
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Central Server Password</LABEL>
 <TD>
  <INPUT TYPE="password" NAME="instaDisc_comment_centralServer_password" VALUE="<?php echo(get_option('instaDisc_comment_centralServer_password')); ?>" SIZE="40">
  <BR>This is the password for the user above
 </TD>
</TR>
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Central Server Activation Key</LABEL>
 <TD>
  <INPUT TYPE="text" NAME="instaDisc_comment_centralServer_activationKey" VALUE="<?php echo(get_option('instaDisc_comment_centralServer_activationKey')); ?>" SIZE="40">
  <BR>When activating your subscription with a Central Server, it will require you to add an "activation key" to your "Subscription File" so as to prove that you actually do own the subscription.
 </TD>
</TR>
<TR VALIGN="top">
 <TH SCOPE="row"><LABEL>Central Server URL</LABEL>
 <TD>
  <INPUT TYPE="text" NAME="instaDisc_comment_centralServer" VALUE="<?php echo(get_option('instaDisc_comment_centralServer')); ?>" SIZE="40">
  <BR>Both after registration and after activation, the Central Server you are using should tell you it's XML-RPC URL (usually a URL containing the string "xmlrpc.php"). Copy that URL into this field.
 </TD>
</TR>
</TABLE>
<INPUT TYPE="hidden" NAME="action" VALUE="update">
<INPUT TYPE="hidden" NAME="page_options" VALUE="instaDisc_subscription_title,instadisc_blogPost_centralServer_activationKey,instaDisc_blogPost_centralServer,instaDisc_comment_centralServer_activationKey,instaDisc_comment_centralServer,instaDisc_blogPost_centralServer_username,instaDisc_blogPost_centralServer_password,instaDisc_comment_centralServer_username,instaDisc_comment_centralServer_password">
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

	$verID = rand(1,2147483647);

	$client = new xmlrpc_client(get_option('instaDisc_blogPost_centralServer'));
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval(get_option('instaDisc_blogPost_centralServer_username'), 'string'),
								new xmlrpcval(md5(get_option('instaDisc_blogPost_centralServer_username') . ':' . md5(get_option('instaDisc_blogPost_centralServer_password')) . ':' . $verID), 'string'),
								new xmlrpcval($verID, 'int'),
								new xmlrpcval(get_option('siteurl') . '/', 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($authorName, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval(serialize(array()), 'string')));
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
	$url = get_permalink($comment->comment_post_ID) . "#comments";

	$verID = rand(1,2147483647);

	$client = new xmlrpc_client(get_option('instaDisc_comment_centralServer'));
	$msg = new xmlrpcmsg("InstaDisc.sendFromUpdate", array(	new xmlrpcval(get_option('instaDisc_comment_centralServer_username'), 'string'),
								new xmlrpcval(md5(get_option('instaDisc_comment_centralServer_username') . ':' . md5(get_option('instaDisc_comment_centralServer_password')) . ':' . $verID), 'string'),
								new xmlrpcval($verID, 'int'),
								new xmlrpcval(get_option('siteurl') . '/comments/', 'string'),
								new xmlrpcval($title, 'string'),
								new xmlrpcval($author, 'string'),
								new xmlrpcval($url, 'string'),
								new xmlrpcval(serialize(array()), 'string')));
	$resp = $client->send($msg);
	$val = $resp->value()->scalarVal();

	if ($val == 2)
	{
		sendComment($id);
	}
}

?>
