<HTML>
	<HEAD>
		<TITLE><!--SITENAME--> InstaDisc Series Control</TITLE>
		<LINK REL="stylesheet" TYPE="text/css" HREF="theme/uniform.css">
	</HEAD>

	<BODY>
		<CENTER>
			<H1>InstaDisc Add Subscription</H1>

			<P>If you would like to add a new subscription to Series Control, please fill out the form below.
		</CENTER>

		<FORM CLASS="uniform" ACTION="./admin.php?id=addsub&amp;submit=" METHOD="POST">

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
				<LEGEND>Series Control Info</LEGEND>

				<DIV CLASS="ctrlHolder<!--ID_ERR-->">
					<!--BEGIN ID_ERRS-->
						<P ID="error<!--ID_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--ID_ERRS.MSG-->
						</P>
					<!--END ID_ERRS-->

					<LABEL FOR="id"><EM>*</EM> Subscription ID: </LABEL>
					<INPUT TYPE="text" ID="id" NAME="id" CLASS="textInput" VALUE="<!--ID-->">

					<P CLASS="formHint">This is a short, unique string used to identify this subscription in Series Control.</P>		
				</DIV>
			</FIELDSET>

			<FIELDSET CLASS="inlineLabels">
				<LEGEND>Subscription Info</LEGEND>

				<DIV CLASS="ctrlHolder<!--TITLE_ERR-->">
					<!--BEGIN TITLE_ERRS-->
						<P ID="error<!--TITLE_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--TITLE_ERRS.MSG-->
						</P>
					<!--END TITLE_ERRS-->

					<LABEL FOR="title"><EM>*</EM> Title: </LABEL>
					<INPUT TYPE="text" ID="title" NAME="title" CLASS="textInput" VALUE="<!--TITLE-->">
				</DIV>

				<DIV CLASS="ctrlHolder<!--URL_ERR-->">
					<!--BEGIN URL_ERRS-->
						<P ID="error<!--URL_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--URL_ERRS.MSG-->
						</P>
					<!--END URL_ERRS-->

					<LABEL FOR="url"><EM>*</EM> Subscription URL: </LABEL>
					<INPUT TYPE="text" ID="url" NAME="url" CLASS="textInput" VALUE="<!--URL-->">

					<P CLASS="formHint">This is a unique URL used to identify this subscription on the Client.</P>		
				</DIV>

				<DIV CLASS="ctrlHolder<!--CATEGORY_ERR-->">
					<!--BEGIN CATEGORY_ERRS-->
						<P ID="error<!--CATEGORY_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--CATEGORY_ERRS.MSG-->
						</P>
					<!--END CATEGORY_ERRS-->

					<LABEL FOR="category"><EM>*</EM> Category: </LABEL>
					<INPUT TYPE="text" ID="category" NAME="category" CLASS="textInput" VALUE="<!--CATEGORY-->">			
				</DIV>

				<DIV CLASS="ctrlHolder<!--PASSWORD_ERR-->">
					<!--BEGIN PASSWORD_ERRS-->
						<P ID="error<!--PASSWORD_ERRS.NAME-->" CLASS="errorField"><EM>*</EM>
							<!--PASSWORD_ERRS.MSG-->
						</P>
					<!--END PASSWORD_ERRS-->

					<LABEL FOR="password">Password: </LABEL>
					<INPUT TYPE="password" ID="password" NAME="password" CLASS="textInput" VALUE="<!--PASSWORD-->">

					<P CLASS="formHint">If this subscription is encrypted, enter it's password here. Otherwise, leave it blank.</P>
				</DIV>
			</FIELDSET>

			<DIV CLASS="buttonHolder">
				<INPUT TYPE="submit" NAME="submit" VALUE="Submit">
			</DIV>
		</FORM>
	</BODY>
</HTML>
