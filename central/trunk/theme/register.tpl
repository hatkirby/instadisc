<HTML>
	<HEAD>
		<TITLE><!--SITENAME--> InstaDisc Central Server</TITLE>
		<LINK REL="stylesheet" TYPE="text/css" HREF="uniform.css">
	</HEAD>

	<BODY>
		<CENTER>
			<H1>InstaDisc Registration</H1>

			<P>If you would like to sign up for our InstaDisc service, please fill out the form below.
		</CENTER>

		<FORM CLASS="uniform" ACTION="./register.php?submit=" METHOD="POST">

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

				<DIV CLASS="ctrlHolder<!--EMAIL_ERR-->">
					<!--BEGIN EMAIL_ERRS-->
						<P ID="error<!--EMAIL_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--EMAIL_ERRS.MSG-->
						</P>
					<!--END EMAIL_ERRS-->

					<LABEL FOR="email"><EM>*</EM> Email: </LABEL>
					<INPUT TYPE="text" ID="email" NAME="email" CLASS="textInput" VALUE="<!--EMAIL-->">			
				</DIV>
			</FIELDSET>

			<DIV CLASS="buttonHolder">
				<INPUT TYPE="submit" NAME="submit" VALUE="Submit">
			</DIV>
		</FORM>
	</BODY>
</HTML>
