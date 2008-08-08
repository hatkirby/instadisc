<?php

/* InstaDisc Server - A Four Island Project */

include('instadisc.php');

?>
<HTML>
 <HEAD>
  <TITLE>Four Island InstaDisc Central Server</TITLE>
 </HEAD>
 <BODY>
  <CENTER>
   <H1><?php echo(instaDisc_getConfig('siteName')); ?> InstaDisc</H1>

   <P>Welcome to <?php instaDisc_getConfig('siteName')); ?>'s InstaDisc Central Server!
   <P>InstaDisc is a wonderful productivity-increasing notification program. For more information about the project itself, see
    <A HREF="http://fourisland.com/projects/instadisc/">its project site</A>.<P>An InstaDisc "Central Server" is where you can 
    register for the InstaDisc service. There are many Central Servers around the world, you generally pick the one that is 
    closest to you. If you would like to choose <?php echo(instaDisc_getConfig('siteName')); ?>'s, why not register now?
   <P><A HREF="register.php">Register</A>
    <BR><A HREF="login.php">Login</A>
    <BR><A HREF="activate.php">Activation page</A>
  </CENTER>
 </BODY>
</HTML>
