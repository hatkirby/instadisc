<HTML>
        <HEAD>
                <TITLE><!--SITENAME--> InstaDisc Series Control</TITLE>
		<LINK REL="stylesheet" HREF="theme/table.css">
        </HEAD>

        <BODY>
                <CENTER>
                        <H1>InstaDisc User Management</H1>

			<P>You can manage your users here.

			<TABLE>
				<TR>
					<TH>Username</TH>
					<TH>Actions</TH>
				</TR>

				<!--BEGIN USERS-->
				<TR>
					<TD><!--USERS.USERNAME--></TD>
					<TD>
						<A HREF="admin.php?id=deleteuser&amp;userid=<!--USERS.ID-->">Delete</A>
					</TD>
				</TR>
				<!--END USERS-->
			</TABLE>

			<P><A HREF="admin.php?id=main">Back to User Panel</A>
		</CENTER>
	</BODY>
</HTML>
