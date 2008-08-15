<HTML>
	<HEAD>
		<TITLE><!--SITENAME--> InstaDisc Central Server</TITLE>
		<LINK REL="stylesheet" TYPE="text/css" HREF="uniform.css">
	</HEAD>

	<BODY>
		<CENTER>
			<H1>InstaDisc Sign-in</H1>

			<P>If you've registered and activated your account, you can sign in to modify your account here!
		</CENTER>

		<FORM CLASS="uniform" ACTION="./login.php?submit=" METHOD="POST">

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
				<LEGEND>User Details</LEGEND>

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

					<LABEL FOR="password"><EM>*</EM> Password: </LABEL>
					<INPUT TYPE="password" ID="password" NAME="password" CLASS="textInput" VALUE="<!--PASSWORD-->">
				</DIV>
			</FIELDSET>

			<DIV CLASS="buttonHolder">
				<INPUT TYPE="submit" NAME="submit" VALUE="Submit">
			</DIV>
		</FORM>
	</BODY>
</HTML>
