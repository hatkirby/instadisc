<HTML>
	<HEAD>
		<TITLE><!--SITENAME--> InstaDisc Central Server</TITLE>
		<LINK REL="stylesheet" TYPE="text/css" HREF="uniform.css">
	</HEAD>

	<BODY>
		<CENTER>
			<H1>InstaDisc Activation</H1>

			<P>If you've already registered and an activation email has been sent to your address, please fill in the form below.
		</CENTER>

		<FORM CLASS="uniform" ACTION="./activate.php?submit=" METHOD="POST">

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

				<DIV CLASS="ctrlHolder<!--CODE_ERR-->">
					<!--BEGIN CODE_ERRS-->
						<P ID="error<!--CODE_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--CODE_ERRS.MSG-->
						</P>
					<!--END CODE_ERRS-->

					<LABEL FOR="code"><EM>*</EM> Activation Code: </LABEL>
					<INPUT TYPE="text" ID="code" NAME="code" CLASS="textInput" VALUE="<!--CODE-->">
				</DIV>
			</FIELDSET>

			<DIV CLASS="buttonHolder">
				<INPUT TYPE="submit" NAME="submit" VALUE="Verify">
				<INPUT TYPE="submit" NAME="submit" VALUE="Delete">
			</DIV>
		</FORM>
	</BODY>
</HTML>
