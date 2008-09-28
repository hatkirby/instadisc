<?php

include('../../../wp-blog-header.php');

echo('<DIV STYLE="display: none">: ' . "\n");

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

	if (get_option('instaDisc_comment_password') != '')
	{
		$verID = rand(1,2147483647);

		echo('Verification: ' . md5(get_option('instaDisc_subscription_title') . ':' . md5(get_option('instaDisc_comment_password')) . ':' . $verID) . "\n");
		echo('Verification-ID: ' . $verID . "\n");
	}

?>
End: </DIV><DIV STYLE="margin: 0 auto; margin-top: 5em; width: 500px; background-color: #FBEC5D; text-align: center; padding: 4px;"><B>This is an InstaDisc Subscription file.</B><P>If you would like to subscribe to the InstaDisc subscription:<BR><I><?php echo(get_option('instaDisc_subscription_title')); ?></I> (<U>blog-comment</U>)<BR>Copy the address of this file to the "Add Subscription" form on your InstaDisc client.</DIV>
<?php

	if (get_option('instaDisc_comment_password') != '')
	{
?><!--Notice-->: <CENTER><B>Please note that this is an <I>encrypted</I> subscription.</B><BR>To subscribe to it, you must know its password.</CENTER><?php
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

	if (get_option('instaDisc_blogPost_password') != '')
	{
		$verID = rand(1,2147483647);

		echo('Verification: ' . md5(get_option('instaDisc_subscription_title') . ':' . md5(get_option('instaDisc_blogPost_password')) . ':' . $verID) . "\n");
		echo('Verification-ID: ' . $verID . "\n");
	}

?>
End: </DIV><DIV STYLE="margin: 0 auto; margin-top: 5em; width: 500px; background-color: #FBEC5D; text-align: center; padding: 4px;"><B>This is an InstaDisc Subscription file.</B><P>If you would like to subscribe to the InstaDisc subscription:<BR><I><?php echo(get_option('instaDisc_subscription_title')); ?></I> (<U>blog-post</U>)<BR>Copy the address of this file to the "Add Subscription" form on your InstaDisc client.</DIV>
<?php

	if (get_option('instaDisc_blogPost_password') != '')
	{
?><!--Notice-->: <CENTER><B>Please note that this is an <I>encrypted</I> subscription.</B><BR>To subscribe to it, you must know its password.</CENTER><?php
	}	
}

?>
