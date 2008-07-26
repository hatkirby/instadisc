<?php

/* InstaDisc Server - A Four Island Project */

if (!isset($_GET['step']))
{
?>Welcome to the InstaDisc Central Server installation! Please input your database details here:<P><FORM ACTION="./install.php?step=2" METHOD="POST">Database host: <INPUT TYPE="text" NAME="host" VALUE="localhost"><BR>Username: <INPUT TYPE="text" NAME="username" VALUE="root"><BR>Password: <INPUT TYPE="text" NAME="password"><BR>Database name: <INPUT TYPE="text" NAME="name"> (Note: You must create this database BEFORE running installation)<BR><INPUT TYPE="submit"></FORM><?php
} else if ($_GET['step']==2)
{
	if (!mysql_connect($_POST['host'], $_POST['username'], $_POST['password']))
	{
?>Cannot connect to your database server! Please verify you typed you host, username and password correctly. <A HREF="./install.php">Back</A><?php exit();
	}
	if (!mysql_select_db($_POST['name']))
	{
?>Cannot connect to your database! Please verify that the database you specified already exists and that you spelled it correctly. <A HREF="./install.php">Back</A><?php exit();
	}

?>Congradulations! You've successfully set up your InstaDisc Central Server's database! Now, the next step for you is to implement the functions in instadisc.php into your web application. Read README.txt for more information.<?php
}
?>
