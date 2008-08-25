<HTML>
	<HEAD>
		<TITLE><!--SITENAME--> InstaDisc Central Server</TITLE>
		<LINK REL="stylesheet" TYPE="text/css" HREF="theme/uniform.css">
	</HEAD>

	<BODY>
		<CENTER>
			<H1>InstaDisc Subscription Activation</H1>

			<P>If you've already requested a subscription be sponsered and added its Activation Key to its Subscription File, you can activate the subscription here:
		</CENTER>

		<FORM CLASS="uniform" ACTION="./activatesub.php?submit=" METHOD="POST">

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

				<DIV CLASS="ctrlHolder<!--URL_ERR-->">
					<!--BEGIN URL_ERRS-->
						<P ID="error<!--URL_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--URL_ERRS.MSG-->
						</P>
					<!--END URL_ERRS-->

					<LABEL FOR="url"><EM>*</EM> Subscription File URL: </LABEL>
					<INPUT TYPE="text" ID="url" NAME="url" CLASS="textInput" VALUE="<!--URL-->">			
				</DIV>
			</FIELDSET>

			<DIV CLASS="buttonHolder">
				<INPUT TYPE="submit" NAME="submit" VALUE="Verify">
				<INPUT TYPE="submit" NAME="submit" VALUE="Delete">
			</DIV>
		</FORM>
	</BODY>
</HTML>
