<HTML>
	<HEAD>
		<TITLE><!--SITENAME--> InstaDisc Series Control</TITLE>
		<LINK REL="stylesheet" TYPE="text/css" HREF="theme/uniform.css">
	</HEAD>

	<BODY>
		<CENTER>
			<H1>InstaDisc Add User</H1>

			<P>If you would like to add a new user to Series Control, please fill out the form below.
		</CENTER>

		<FORM CLASS="uniform" ACTION="./admin.php?id=adduser&amp;submit=" METHOD="POST">

			<!--BEGIN ERROR-->
			<DIV ID="errorMsg">Uh oh! Validation errors!<P>
				<OL>
			<!--END ERROR-->

			<!--BEGIN ERRORS-->
					<LI><A HREF="#error<!--ERRORS.NAME-->"><!--ERRORS.MSG--></A></LI>
			<!--END ERRORS-->

			<!--BEGIN ERROR-->
				</OL>
			</DIV>
			<!--END ERROR-->

			<FIELDSET CLASS="inlineLabels">
				<LEGEND>User Info</LEGEND>

				<DIV CLASS="ctrlHolder<!--USERNAME_ERR-->">
					<!--BEGIN USERNAME_ERRS-->
						<P ID="error<!--USERNAME_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--USERNAME_ERRS.MSG-->
						</P>
					<!--END USERNAME_ERRS-->

					<LABEL FOR="username"><EM>*</EM> Username: </LABEL>
					<INPUT TYPE="text" ID="username" NAME="username" CLASS="textInput" VALUE="<!--USERNAME-->">
				</DIV>

				<DIV CLASS="ctrlHolder<!--PASSWORD_ERR-->">
					<!--BEGIN PASSWORD_ERRS-->
						<P ID="error<!--PASSWORD_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--PASSWORD_ERRS.MSG-->
						</P>
					<!--END PASSWORD_ERRS-->

					<LABEL FOR="password">Password: </LABEL>
					<INPUT TYPE="password" ID="password" NAME="password" CLASS="textInput" VALUE="<!--PASSWORD-->">
				</DIV>
			</FIELDSET>

			<DIV CLASS="buttonHolder">
				<INPUT TYPE="submit" NAME="submit" VALUE="Submit">
			</DIV>
		</FORM>
	</BODY>
</HTML>