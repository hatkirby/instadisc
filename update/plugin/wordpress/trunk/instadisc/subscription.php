<?php

include('../../../wp-blog-header.php');

if (isset($_GET['comment']))
{
	echo('Subscription: ');
	echo('http://' . $_SERVER['SERVER_NAME'] . '/blog-comment/' . generateSlug(get_option('instaDisc_subscription_title')) . '/');
	echo("\n");

	echo('Title: ');
	echo(get_option('instaDisc_subscription_title'));
	echo(' Comments');
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
	echo('http://' . $_SERVER['SERVER_NAME'] . '/blog-post/' . generateSlug(get_option('instaDisc_subscription_title')) . '/');
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
