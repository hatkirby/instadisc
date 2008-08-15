<HTML>
	<HEAD>
		<TITLE><!--SITENAME--> InstaDisc Central Server</TITLE>
		<LINK REL="stylesheet" TYPE="text/css" HREF="theme/uniform.css">
	</HEAD>

	<BODY>
		<CENTER>
			<H1>InstaDisc Change Password</H1>

			<P>If you would like to change your password, please fill out the form below.
		</CENTER>

		<FORM CLASS="uniform" ACTION="./changepassword.php?submit=" METHOD="POST">

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
				<LEGEND>Password Details</LEGEND>

				<DIV CLASS="ctrlHolder<!--OLD_ERR-->">
					<!--BEGIN OLD_ERRS-->
						<P ID="error<!--OLD_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--OLD_ERRS.MSG-->
						</P>
					<!--END OLD_ERRS-->

					<LABEL FOR="old"><EM>*</EM> Old Password: </LABEL>
					<INPUT TYPE="password" ID="old" NAME="old" CLASS="textInput" VALUE="<!--OLD-->">			
				</DIV>

				<DIV CLASS="ctrlHolder<!--NEW_ERR-->">
					<!--BEGIN NEW_ERRS-->
						<P ID="error<!--NEW_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--NEW_ERRS.MSG-->
						</P>
					<!--END NEW_ERRS-->

					<LABEL FOR="new"><EM>*</EM> New Password: </LABEL>
					<INPUT TYPE="password" ID="new" NAME="new" CLASS="textInput" VALUE="<!--NEW-->">
				</DIV>

				<DIV CLASS="ctrlHolder<!--EMAIL_ERR-->">
					<!--BEGIN CONFIRM_ERRS-->
						<P ID="error<!--CONFIRM_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--CONFIRM_ERRS.MSG-->
						</P>
					<!--END CONFIRM_ERRS-->

					<LABEL FOR="confirm"><EM>*</EM> Confirm New Password: </LABEL>
					<INPUT TYPE="password" ID="confirm" NAME="confirm" CLASS="textInput" VALUE="<!--CONFIRM-->">

					<P CLASS="formHint">Please re-type your new password</P>
				</DIV>
			</FIELDSET>

			<DIV CLASS="buttonHolder">
				<INPUT TYPE="submit" NAME="submit" VALUE="Submit">
			</DIV>
		</FORM>
	</BODY>
</HTML>
