<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2004  Simon E Booth (simon.booth@giric.com)

	//This program is free software; you can redistribute it and/or
	//modify it under the terms of the GNU General Public License
	//as published by the Free Software Foundation; either version 2
	//of the License, or (at your option) any later version.

	//This program is distributed in the hope that it will be useful,
	//but WITHOUT ANY WARRANTY; without even the implied warranty of
	//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	//GNU General Public License for more details.

	//You should have received a copy of the GNU General Public License
	//along with this program; if not, write to the Free Software
	//Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

//=====================================================================================================================

//=====================================================================================================================
//  global definitions
//=====================================================================================================================

	$charset			= "ISO-8859-1";
	$clang				= "en";
	$dir				= "ltr";
	$datefmt 			= "'%d/%m/%Y'";
	// flags are from http://flags.sourceforge.net
	// I can't find a copyrigh to credit
	// but I'm sure somebody has it
	$flag				= "images/gb.gif";

//=====================================================================================================================
// some date stuff
// This is really out of place, but $restictdate is defined in config.inc.php and $datefmt here.
// Neither should be moved to the other == catch 22
// if anybody can think of a better way to set nulldate and dispdate - let me know!
//=====================================================================================================================
	$dquery = "SELECT DATE_FORMAT('0000-00-00', ".$datefmt." ) , DATE_FORMAT( '".$restrictdate."', ".$datefmt." )";
	$dresult = mysql_query($dquery) or die("OOOOOppppps");
	while ($row = mysql_fetch_array($dresult)) {
		$nulldate = $row[0];
		$dispdate = $row[1];
	}
	mysql_free_result($dresult);

//=====================================================================================================================
// strings for translation
//=====================================================================================================================

	$strOnFile			= "people on file";
	$strSelect			= "Select person";
	$strUnknown			= "unknown";
	$strLoggedIn		= "You are logged in as ";
	$strAdmin			= "admin";
	$strLoggedOut		= "You are not logged in: ";
	$strYes				= "Yes";
	$strNo				= "No";
	$strSubmit			= "Submit";
	$strReset			= "Reset";
	$strLogout			= "logout";
	$strHome			= "home";
	$strEdit			= "edit";
	$strAdd				= "add";
	$strDetails			= "Details";
	$strBorn			= "Born";
	$strCertified		= "Certified";
	$strFather			= "Father";
	$strRestricted		= "Restricted";
	$strDied			= "Died";
	$strMother			= "Mother";
	$strChildren		= "Children";
	$strSiblings		= "Siblings";
	$strMarried			= "Married";
	$strInsert			= "insert";
	$strNewMarriage		= "new marriage";
	$strNotes			= "Notes";
	$strGallery			= "Image Gallery";
	$strUpload			= "upload";
	$strNewImage		= "new image";
	$strNoImages		= "No images available";
	$strCensusDetails	= "Census Details";
	$strNewCensus		= "new census";
	$strNoInfo			= "No information available";
	$strYear			= "Year";
	$strAddress			= "Address";
	$strCondition		= "Condition";
	$strOf				= "of";
	$strAge				= "Age";
	$strProfession		= "Profession";
	$strBirthPlace		= "Place of Birth";
	$strDocTrans		= "Document Transcripts";
	$strNewTrans		= "new transcript";
	$strTitle			= "Title";
	$strDesc			= "Description";
	$strDate			= "Date";
	$strRightClick		= "Click the document title to download. (Might need to right click &amp; Save Target As.. in Internet Explorer)";
	$strStats			= "Site Statistics";
	$strArea			= "Area";
	$strNo				= "Number";
	$strCensusRecs		= "Census records";
	$strImages			= "Images";
	$strLast20			= "Last 20 People Updated";
	$strPerson			= "Person";
	$strUpdated			= "Updated";
	$strEditing			= "Editing";
	$strName			= "Name";
	$strDOB				= "Date of Birth";
	$strDateFmt			= "Please use format YYYY-MM-DD";
	$strDOD				= "Date of Death";
	$strCauseDeath		= "Cause of Death";
	$strMarriage		= "Marriage";
	$strSpouse			= "Spouse";
	$strDOM				= "Date of Marriage";
	$strMarriagePlace	= "Location of Marriage";
	$strCensus			= "Census";
	$strSchedule 		= "Schedule";
	$strDragons			= "Here be dragons!";
	$strGender			= "Gender";
	$strMale			= "Male";
	$strFemale			= "Female";
	$strNewPassword		= "New Password";
	$strOldPassword		= "Old Password";
	$strReOldPassword	= "Re-enter Old Password";
	$strChange			= "Change";
	$strPwdChange		= "Password Change";
	$strPwdChangeMsg	= "Please use this form if you wish to change your password.";
	$strLogin			= "Login";
	$strUsername		= "Username";
	$strPassword		= "Password";
	$strRePassword		= "Re-enter Password";
	$strForbidden		= "Forbidden";
	$strForbiddenMsg	= "The page that you have requested has reported that you do not have sufficient rights to view it.  Do not repeat this request.  Please click <a href=\"index.php\">here</a> to continue.";
	$strDelete			= "delete";
	$strFUpload			= "File to Upload";
	$strFTitle			= "File Title";
	$strFDesc			= "File Description";
	$strFDate			= "File Date";
	$strIUpload			= "Image to Upload";
	$strISize			= "JPEG only (max size 1MB)";
	$strITitle			= "Image Title";
	$strIDesc			= "Image Description";
	$strIDate			= "Image Date";
	$strOn				= "on";
	$strAt				= "at";
	$strAdminFuncs		= "Admin Functions";
	$strAction			= "action";
	$strUserCreate		= "Create new user";
	$strCreate			= "Create";
	$strBack			= "Back";
	$strToHome			= "to the homepage.";
	$strNewMsg			= "Please make sure that the person does not already exist in the database before creating!";
	$strIndex			= "All details for people born after $dispdate are restricted to protect their identities.  If you are a registered user you can view these details and edit record.  Everybody is free to browse the unrestricted records.  If you think anybody here matches into your family tree, please <a href=\"$1\">let me know</a>";
	$strNote			= "Note";
	$strFooter			= "Email the <a href=\"$1\">webmaster</a> with any problems.";
	$strPowered			= "Powered by";
	$strPedigreeOf		= "Pedigree of";
	$strBirths			= "Births";
	$strAnniversary		= "Anniversary";
	$strUpcoming		= "Upcoming Anniversaries";
	$strMarriages		= "Marriages";
	$strDeaths			= "Deaths";
	$strConfirmDelete	= "\"Are you sure you wish to delete the\\n'\" + year + \"' \" + section +\"?\"";
	$strTranscript		= "transcript";
	$strImage			= "image";
	$strDoubleDelete	= "\"Are you really sure you wish to DELETE this person\\nThis process is IRREVERSABLE!!\"";
	$strBirthCert		= "Birth Certified?";
	$strDeathCert		= "Death Certified?";
	$strMarriageCert	= "Marriage Certified?";
	$strNewPerson		= "a new person";
	$strPedigree		= "pedigree";
	$strToDetails		= "to details";
	$strSurnameIndex	= "Index of Surnames";
	$strTracking		= "Tracking";
	$strTrack			= "track";
	$strThisPerson		= "this person";
	$strTrackSpeel		= "Use the form below to track this person.  You will automatically be sent an email everytime this record is updated";
	$strEmail			= "Email";
	$strSubscribe		= "subscribe";
	$strUnSubscribe		= "unsubscribe";
	$strMonAccept		= "You request for monitoring has been accepted<br />You will now receive an email every time this person is updated.<br />";
	$strMonCease		= "You request to cease monitoring has been accepted<br />You will no longer receive any emails.<br />";
	$strMonError		= "There has been a problem with your monitoring request.<br />Please contact the site administrator for assistance";
	$strMonRequest		= "Your request for monitoring this person is being processed.<br />A confirmation email has been sent to your address and you should follow the instructions therein.<br />";
	$strCeaseRequest	= "Your request for ceasing monitoring this person is being processed.<br />A confirmation email has been sent to your address and you should follow the instructions therein.<br />";
	$strAlreadyMon		= "You already seem to be monitoring this person.<br />No action is required.<br />";
	$strNotMon			= "You do not seem to be monitoring this person.<br />No action is required.<br />";
	$strRandomImage		= "Random Image";
	$strMailTo			= "Send Message";
	$strSubject			= "Subject";
	$strNoEmail			= "\"You must provide an email address\"";
	$strEmailSent		= "Your email has been sent to the webmaster.";

//=====================================================================================================================
//  email definitions
//=====================================================================================================================

	$eTrackSubject		= "[phpmyfamily] $1 has been updated";
	$eTrackBodyTop		= "This is an automated message.  $1 at $2 has been changed.  Click below to see the updated record\n\n";
	$eTrackBodyBottom	= "This message has been sent because you previously signed up for tracking this person.  Click below to remove yourself from this monitoring\n\n";
	$eSubSubject		= "[phpmyfamily] monitoring request";
	$eSubBody			= "This is an automated message.  You have received this message because you have chosen to monitor the record of $1.  To confirm this subscription, please click the link below within the next 24 hours.\n\n";
	$eUnSubBody			= "This is an automated message.  You have received this message because you have chosen to cease monitoring the record of $1.  To confirm this cancellation, please click the link below within the next 24 hours.\n\n";

//=====================================================================================================================
//  error definitions
//=====================================================================================================================

	$err_listpeeps		= "Error listing people in database";
	$err_image_insert	= "Error inserting image into database";
	$err_list_enums		= "Error enumerating types on column";
	$err_list_census	= "Error listing available censuses";
	$err_keywords		= "Error retrieving names for keywords from database";
	$err_changed		= "Error retrieving list of last changed people";
	$err_father			= "Error retrieving father's details from database";
	$err_mother			= "Error retrieving mother's details from database";
	$err_spouse			= "Error retrieving spouse's details from database";
	$err_marriage		= "Error retrieving marriage details from database";
	$err_census_ret		= "Error retrieving census details from database";
	$err_children		= "Error retrieving childrens details from database";
	$err_siblings		= "Error retrieving sibling details from database";
	$err_transcript		= "Error inserting transcript into database";
	$err_trans			= "Error retrieving transcripts from database";
	$err_detail			= "Error inserting person details into database";
	$err_census			= "Error inserting census into database";
	$err_logon			= "Error logging on";
	$err_change			= "Error checking password change";
	$err_pwd_incorrect	= "Error - Incorrect password supplied";
	$err_pwd_match		= "Error - New passwords do not match";
	$err_update			= "Error updating new password";
	$err_pwd_success	= "Password successfully updated";
	$err_image			= "Error retrieving image from database";
	$err_images			= "Error retrieving images from database";
	$err_person			= "Error retrieving person from database";
	$err_new_user		= "Error inserting new user into database";
	$err_user_exist		= "Error - user already exists";
	$err_pwd			= "Error retrieving password from database";
	$err_delete_user	= "Error deleting user from database";
	$err_users			= "Error retrieving users from database";
	$err_census_delete	= "Error deleting census from database";
	$err_marriage_delete= "Error deleting marriage from database";
	$err_trans_delete	= "Error deleting transcript from database";
	$err_person_delete	= "Error deleting person from database";
	$err_trans_file		= "Error deleting transcript file";
	$err_image_file		= "Error deleting image file";
	$err_child_update	= "Error updating childrens records";
	$err_person_update	= "Error updating person details";
	$err_marriage_insert= "Error inserting marriage into database";

	// eof
?>
