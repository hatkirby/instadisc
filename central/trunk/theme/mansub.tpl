<HTML>
        <HEAD>
                <TITLE><!--SITENAME--> InstaDisc Central Server</TITLE>
		<LINK REL="stylesheet" HREF="theme/table.css">
        </HEAD>

        <BODY>
                <CENTER>
                        <H1>InstaDisc Subscription Management</H1>

			<P>If you've sponsered subscriptions here but have decided you want to move elsewhere you can delete subscriptions using the table below:

			<TABLE>
				<TR>
					<TH>Subscription URL</TH>
					<TH>Actions</TH>
				</TR>

				<!--BEGIN SUBSCRIPTIONS-->
				<TR<!--SUBSCRIPTIONS.EVEN-->>
					<TD><!--SUBSCRIPTIONS.URL--></TD>
					<TD><A HREF="deletesub.php?id=<!--SUBSCRIPTIONS.ID-->">Delete</A></TD>
				</TR>
				<!--END SUBSCRIPTIONS-->
			</TABLE>

			<P><A HREF="userpanel.php">Back to User Panel</A>
		</CENTER>
	</BODY>
</HTML>
