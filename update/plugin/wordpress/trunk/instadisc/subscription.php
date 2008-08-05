<?php

include('../../../wp-blog-header.php');

if (isset($_GET['comment']))
{
	echo('Subscription: ');
	echo(get_option('siteurl'));
	echo('/comments/');	
	echo("\n");

	echo('Title: ');
	echo(get_option('instaDisc_subscription_title'));
	echo("\n");

	echo('Category: blog-comment');
	echo("\n");

	if (get_option('instaDisc_comment_centralServer_activationKey') != '')
	{
		echo('Key: ');
		echo(get_option('instaDisc_comment_centralServer_activationKey'));
	}
} else {
	echo('Subscription: ');
	echo(get_option('siteurl'));
	echo('/');	
	echo("\n");

	echo('Title: ');
	echo(get_option('instaDisc_subscription_title'));
	echo("\n");

	echo('Category: blog-post');
	echo("\n");

	if (get_option('instaDisc_blogPost_centralServer_activationKey') != '')
	{
		echo('Key: ');
		echo(get_option('instaDisc_blogPost_centralServer_activationKey'));
	}
}

?>
