<HTML>
        <HEAD>
                <TITLE><!--SITENAME--> InstaDisc Series Control</TITLE>
		<LINK REL="stylesheet" HREF="theme/table.css">
        </HEAD>

        <BODY>
                <CENTER>
                        <H1>InstaDisc Subscription Management</H1>

			<P>You can manage your subscriptions here.

			<TABLE>
				<TR>
					<TH>Subscription URL</TH>
					<TH>Actions</TH>
				</TR>

				<!--BEGIN SUBSCRIPTIONS-->
				<TR>
					<TD><!--SUBSCRIPTIONS.IDENTITY--></TD>
					<TD>
						<A HREF="admin.php?id=editsub&amp;subid=<!--SUBSCRIPTIONS.ID-->">Edit</A><BR>
						<A HREF="admin.php?id=deletesub&amp;subid=<!--SUBSCRIPTIONS.ID-->">Delete</A>
					</TD>
				</TR>
				<!--END SUBSCRIPTIONS-->
			</TABLE>

			<P><A HREF="admin.php?id=main">Back to User Panel</A>
		</CENTER>
	</BODY>
</HTML>
