<?php

/* InstaDisc Server - A Four Island Project */

if (!isset($_GET['step']))
{
?>Welcome to the InstaDisc Central Server installation! Please input your database details here:<P><FORM ACTION="./install.php?step=2" METHOD="POST">Database host: <INPUT TYPE="text" NAME="host" VALUE="localhost"><BR>Username: <INPUT TYPE="text" NAME="username" VALUE="root"><BR>Password: <INPUT TYPE="text" NAME="password"><BR>Database name: <INPUT TYPE="text" NAME="name"> (Note: You must create this database BEFORE running installation)<BR><INPUT TYPE="submit"></FORM><?php
} else if ($_GET['step']==2)
{
	if (!mysql_connect($_POST['host'], $_POST['username'], $_POST['password']))
	{
?>Oops! Something went wrong!<?php exit();
	}
	if (!mysql_select_db($_POST['name']))
	{
?>Oops! Something went wrong!<?php exit();
	}
}

?>
