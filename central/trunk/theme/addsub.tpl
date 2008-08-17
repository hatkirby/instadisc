<HTML>
	<HEAD>
		<TITLE><!--SITENAME--> InstaDisc Central Server</TITLE>
		<LINK REL="stylesheet" TYPE="text/css" HREF="theme/uniform.css">
	</HEAD>

	<BODY>
		<CENTER>
			<H1>InstaDisc Add Subscription</H1>

			<P>If you would like to have us sponser a subscription for you, please fill out the form below:
		</CENTER>

		<FORM CLASS="uniform" ACTION="./addsub.php?submit=" METHOD="POST">

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
				<LEGEND>Subscription Details</LEGEND>

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
				<INPUT TYPE="submit" NAME="submit" VALUE="Submit">
			</DIV>
		</FORM>
	</BODY>
</HTML>
