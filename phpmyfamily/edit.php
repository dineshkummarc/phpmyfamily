<?php
	
	// family tree software
	// (c)2002 - 2003 Simon E Booth
	// All rights reserved
	// File to control editing and creation of new data.

	// include the database parameters
	include "inc/session.inc.php";
	include "inc/db.inc.php";
	include "inc/functions.inc.php";

	// include the browser 
	include "inc/browser.inc.php";
	include "inc/css.inc.php";

	if ($_SESSION["id"] == 0)
		die("Security Breach");

	// fill out the header
	echo "<HTML>\n";
	echo "<HEAD>\n";
	css_site();

	switch ($_REQUEST["func"]) {

		// user wants to edit a record
		case "edit":
			switch ($_REQUEST["area"]) {
				case "detail":
					// get the person to edit
					$edquery = "SELECT * FROM people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die("Editing query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {

						// fill out the header
						echo "<title>Editing: ".$edrow["name"]."</title>\n";
						echo "</HEAD>\n";
						echo "<BODY>\n";

						echo "<h2>".$edrow["name"]."</h2>\n";
						echo "<hr>\n";
						
						echo "<form method=post action=passthru.php?func=update&area=detail&person=".$_REQUEST["person"].">\n";
							echo "<table>\n";
								echo "<tr>\n";
									echo "<td>Name</td>\n";
									echo "<td><input type=text name=frmName value='".$edrow["name"]."' size=30 maxlength=50></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Date of Birth</td>\n";
									echo "<td><input type=text name=frmDOB value='".$edrow["date_of_birth"]."' size=30></td>\n";
									echo "<td>Please use format YYYY-MM-DD</td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Birth Place</td>\n";
									echo "<td><input type=text name=frmBirthPlace value='".$edrow["birth_place"]."' size=30 maxlenght=50></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Date of Death</td>\n";
									echo "<td><input type=text name=frmDOD value='".$edrow["date_of_death"]."' size=30></td>\n";
									echo "<td>Please use format YYYY-MM-DD</td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Cause of Death</td>\n";
									echo "<td><input type=text name=frmDeathReason value='".$edrow["death_reason"]."' size=30 maxlength=50></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Mother</td>\n";
									echo "<TD>\n";
									listpeeps("frmMother", $_REQUEST["person"], "F", $edrow["mother_id"]);
									echo "</td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Father</td>\n";
									echo "<td>\n";
									listpeeps("frmFather", $_REQUEST["person"], "M", $edrow["father_id"]);
									echo "</td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Narrative</td>\n";
									echo "<td colspan=2><textarea name=frmNarrative rows=10 cols=80>".$edrow["narrative"]."</textarea></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></td>\n";
									echo "<td><INPUT TYPE=RESET NAME=Reset1 VALUE=Reset></td>\n";
								echo "</tr>\n";
							echo "</table>\n";
						echo "</form>\n";
					}
					break; 

				case "marriage":
					// get the person to edit
					$pquery = "SELECT * FROM people WHERE person_id = '".$_REQUEST["person"]."'";
					$presult = mysql_query($pquery) or die("Editing query failed");

					$squery = "SELECT name FROM people WHERE person_id = '".$_REQUEST["spouse"]."'";
					$sresult = mysql_query($squery) or die("Spouse name query failed");
					while ($srow = mysql_fetch_array($sresult)) {
						$spousename = $srow["name"];
					}
					mysql_free_result($sresult);

					// fill out the form with retrieved data
					while ($prow = mysql_fetch_array($presult)) {

						if ($prow["gender"] == "M")
							$edquery = "SELECT * FROM spouses WHERE groom_id = '".$_REQUEST["person"]."' AND bride_id = '".$_REQUEST["spouse"]."'";
						else
							$edquery = "SELECT * FROM spouses WHERE bride_id = '".$_REQUEST["person"]."' AND groom_id = '".$_REQUEST["spouse"]."'";

						$edresult = mysql_query($edquery) or die("Spouse query failed");

						while ($edrow = mysql_fetch_array($edresult)) {

							// fill out the header
							echo "<title>Editing Marriage: ".$prow["name"]." & ".$spousename."</title>\n";
							echo "</HEAD>\n";
							echo "<BODY>\n";

							echo "<h2>Marriage: ".$prow["name"]." & ".$spousename."</h2>\n";
							echo "<hr>\n"; 

							echo "<form method=post action=passthru.php?func=update&area=marriage&person=".$_REQUEST["person"]."&oldspouse=".$_REQUEST["spouse"]."&gender=".$prow["gender"].">\n";
								echo "<table>\n";
									echo "<tr>\n";
										echo "<td>Spouse</td>\n";
										echo "<td>\n";
										if ($prow["gender"] == "M")
											listpeeps("frmSpouse", $_REQUEST["person"], "F", $_REQUEST["spouse"]);
										else
											listpeeps("frmSpouse", $_REQUEST["person"], "M", $_REQUEST["spouse"]);
										echo "</td>\n";
									echo "</tr>\n";
									echo "<tr>\n";
										echo "<td>Date of marriage</td>\n";
										echo "<td><input type=text name=frmDate value='".$edrow["marriage_date"]."' size=15 maxlength=10></td>\n";
										echo "<td>Please use format YYYY-MM-DD</td>\n";
									echo "</tr>\n";
									echo "<tr>\n";
										echo "<td>Marriage Place</td>\n";
										echo "<td><input type=text name=frmPlace value='".$edrow["marriage_place"]."' size=30 maxlength=50></td>\n";
									echo "</tr>\n";
									echo "<tr>\n";
										echo "<td><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></td>\n";
										echo "<td><INPUT TYPE=RESET NAME=Reset1 VALUE=Reset></td>\n";
									echo "</tr>\n";
								echo "</table>\n";
							echo "</form>\n";
						}
						mysql_free_result($edresult);
					}
					mysql_free_result($presult);
					break;

				case "census":
					// get the person to edit
					$edquery = "SELECT * FROM people, census WHERE people.person_id = '".$_REQUEST["person"]."' AND census.year = '".$_REQUEST["year"]."' AND people.person_id = census.person_id";
					$edresult = mysql_query($edquery) or die("Editing query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {

						// fill out the header
						echo "<title>Editing Census: ".$edrow["name"]." (".$edrow["year"].")</title>\n";
						echo "</HEAD>\n";
						echo "<BODY>\n";

						echo "<h2> Census: ".$edrow["name"]." (".$edrow["year"].")</h2>\n";
						echo "<hr>\n"; 

						echo "<form method=post action=passthru.php?func=update&area=census&person=".$_REQUEST["person"]."&year=".$_REQUEST["year"].">\n";
							echo "<table>\n";
								echo "<tr>\n";
									echo "<td>Schedule</td>\n";
									echo "<td><input type=text name=frmSchedule value='".$edrow["schedule"]."' size=30 maxlength=20></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Address</td>\n";
									echo "<td><input type=text name=frmAddress value='".$edrow["address"]."' size=50 maxlength=70></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Condition</td>\n";
									echo "<td>\n";
									list_enums("census", "condition", "frmCondition", $edrow["condition"]);
									echo "</td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Age</td>\n";
									echo "<td><input type=text name=frmAge value='".$edrow["age"]."' size=30 maxlength=3></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Profession</td>\n";
									echo "<td><input type=text name=frmProfession value='".$edrow["profession"]."' size=30 maxlength=40></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Place of Birth</td>\n";
									echo "<td><input type=text name=frmBirthPlace value='".$edrow["where_born"]."' size=30 maxlength=40></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></td>\n";
									echo "<td><INPUT TYPE=RESET NAME=Reset1 VALUE=Reset></td>\n";
								echo "</tr>\n";
							echo "</table>\n";
						echo "</form>\n";
					}
					break;

				default:
					echo "Here be editing area dragons";
					break;
			}

			break;

		// user wants to create a new person
		case "add":
			switch($_REQUEST["area"]) {
				case "detail":
					// fill out the header
					echo "<title>Creating new family member</title>\n";
					echo "</HEAD>\n";
					echo "<BODY>\n";

					echo "<h2>Create new person</h2>\n";
					echo "<hr>\n";
					echo "<b>Please make sure person doesn't exist before creating!!!</b><br>\n";

					echo "<form method=post action=passthru.php?func=insert&area=detail>\n";
						echo "<table>\n";
							echo "<tr>\n";
								echo "<td>Name</td>\n";
								echo "<td><input type=text name=frmName size=30 maxlength=50></td>";
							echo "</tr>\n";
							echo "<tr>\n";
								echo "<td>Date of Birth</td>\n";
								echo "<td><input type=text name=frmDOB size=30 maxlength=10 value='0000-00-00'></td>\n";
								echo "<td>Please use format YYYY-MM-DD</td>\n";
							echo "</tr>\n";
							echo "<tr>\n";
								echo "<td>Birth Place</td>\n";
								echo "<td><input type=text name=frmBirthPlace size=30 maxlength=50></td>\n";
							echo "</tr>\n";
							echo "<tr>\n";
								echo "<td>Date of Death</td>\n";
								echo "<td><input type=text name=frmDOD size=30 maxlength=10 value='0000-00-00'></td>\n";
								echo "<td>Please use format YYYY-MM-DD</td>\n";
							echo "</tr>\n";
							echo "<tr>\n";
								echo "<td>Cause of Death</td>\n";
								echo "<td><input type=text name=frmDeathReason size=30 maxlength=50></td>\n";
							echo "</tr>\n";
							echo "<tr>\n";
								echo "<td>Gender</td>\n";
								echo "<td><input type=radio name=frmGender value=M checked=checked>M<input type=radio name=frmGender value=F>F</td>\n";
							echo "</tr>\n";
							echo "<tr>\n";
								echo "<td>Mother</td>\n";
								echo "<td>\n";
								listpeeps("frmMother", 0, "F");
								echo "</td>\n";
							echo "</tr>\n";
							echo "<tr>\n";
								echo "<td>Father</td>\n";
								echo "<td>\n";
								listpeeps("frmFather", 0, "M");
								echo "</td>\n";
							echo "</tr>\n";
							echo "<tr>\n";
								echo "<td>Narrative</td>\n";
								echo "<td colspan=2><textarea name=frmNarrative rows=10 cols=80></textarea></td>\n";
							echo "</tr>\n";
							echo "<tr>\n";
								echo "<td><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></td>\n";
								echo "<td><INPUT TYPE=RESET NAME=Reset1 VALUE=Reset></td>\n";
							echo "</tr>\n";
						echo "</table>\n";
					echo "</form>\n";
					break;
				
				case "transcript":
					// get the person to insert marriage for
					$edquery = "SELECT * FROM people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die("New marriage person query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {

						// fill out the header
						echo "<title>New Transcript: ".$edrow["name"]."</title>\n";
						echo "</HEAD>\n";
						echo "<BODY>\n";

						echo "<h2> New Transcript: ".$edrow["name"]."</h2>\n";
						echo "<hr>\n"; 

						echo "<form enctype=multipart/form-data method=post action=passthru.php?func=insert&area=transcript&person=".$_REQUEST["person"].">\n";
						echo "<TABLE>\n";
							echo "<TR>\n";
								echo "<TD>File to Upload</TD>\n";
								echo "<TD><INPUT TYPE=FILE NAME=userfile></TD>\n";
							echo "<TR>\n";
							echo "<TR>\n";
								echo "<TD>File Title</TD>\n";
								echo "<TD><INPUT TYPE=TEXT NAME=frmTitle SIZE=30 MAXLENGTH=30></TD>\n";
							echo "<TR>\n";
							echo "<TR>\n";
								echo "<TD>File Description</TD>\n";
								echo "<TD><INPUT TYPE=TEXT NAME=frmDesc SIZE=60 MAXLENGTH=60></TD>\n";
							echo "<TR>\n";
							echo "<TR>\n";
								echo "<TD>File Date</TD>\n";
								echo "<TD><INPUT TYPE=TEXT NAME=frmDate MAXLENGTH=10 value='0000-00-00'></TD>\n";
								echo "<TD>Please use format YYYY-MM-DD</TD>\n";
							echo "<TR>\n";
							echo "<TR>\n";
								echo "<TD><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></TD>\n";
								echo "<TD></TD>\n";
							echo "<TR>\n";
						echo "</TABLE>\n";
						echo "</FORM>\n";
					}
					break;

				case "image":
					// get the person to insert marriage for
					$edquery = "SELECT * FROM people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die("New marriage person query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {

						// fill out the header
						echo "<title>New Image: ".$edrow["name"]."</title>\n";
						echo "</HEAD>\n";
						echo "<BODY>\n";

						echo "<h2> New Image: ".$edrow["name"]."</h2>\n";
						echo "<hr>\n"; 

						echo "<form enctype=multipart/form-data method=post action=passthru.php?func=insert&area=image&person=".$_REQUEST["person"].">\n";
						echo "<TABLE>\n";
							echo "<TR>\n";
								echo "<TD>Image to Upload</TD>\n";
								echo "<TD><INPUT TYPE=FILE NAME=userfile></TD>\n";
								echo "<td>JPEG only.</td>\n";
							echo "<TR>\n";
							echo "<TR>\n";
								echo "<TD>Image Title</TD>\n";
								echo "<TD><INPUT TYPE=TEXT NAME=frmTitle SIZE=30 MAXLENGTH=30></TD>\n";
							echo "<TR>\n";
							echo "<TR>\n";
								echo "<TD>Image Description</TD>\n";
								echo "<TD><INPUT TYPE=TEXT NAME=frmDesc SIZE=60 MAXLENGTH=60></TD>\n";
							echo "<TR>\n";
							echo "<TR>\n";
								echo "<TD>Image Date</TD>\n";
								echo "<TD><INPUT TYPE=TEXT NAME=frmDate MAXLENGTH=10></TD>\n";
								echo "<TD>Please use format YYYY-MM-DD</TD>\n";
							echo "<TR>\n";
							echo "<TR>\n";
								echo "<TD><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></TD>\n";
								echo "<TD></TD>\n";
							echo "<TR>\n";
						echo "</TABLE>\n";
						echo "</FORM>\n";
					}
					break;

				case "marriage":
					// get the person to insert marriage for
					$edquery = "SELECT * FROM people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die("New marriage person query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {

						// fill out the header
						echo "<title>New Marriage: ".$edrow["name"]."</title>\n";
						echo "</HEAD>\n";
						echo "<BODY>\n";

						echo "<h2> New Marriage: ".$edrow["name"]."</h2>\n";
						echo "<hr>\n"; 

						echo "<form method=post action=passthru.php?func=insert&area=marriage&person=".$_REQUEST["person"]."&gender=".$edrow["gender"].">\n";
							echo "<table>\n";
								echo "<tr>\n";
									echo "<td>Spouse</td>\n";
									echo "<td>\n";
									if ($edrow["gender"] == "M")
										listpeeps("frmSpouse", 0, "F");
									else
										listpeeps("frmSpouse", 0, "M");
									echo "</td>\n";
								echo "<tr>\n";
								echo "<tr>\n";
									echo "<td>Date of marriage</td>\n";
									echo "<td><input type=text name=frmDate size=15 maxlength=10 value='0000-00-00'></td>\n";
									echo "<td>Please use format YYYY-MM-DD</td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Marriage Place</td>\n";
									echo "<td><input type=text name=frmPlace size=30 maxlength=50></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
								echo "<td><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></td>\n";
							echo "</tr>\n";
							echo "</table>\n";
						echo "</form>\n";
					}
					mysql_free_result($edresult);
					break;

				case "census":
					// get the person to insert census for
					$edquery = "SELECT * FROM people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die("New census person query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {

						// fill out the header
						echo "<title>New Census: ".$edrow["name"]."</title>\n";
						echo "</HEAD>\n";
						echo "<BODY>\n";

						echo "<h2> New Census: ".$edrow["name"]."</h2>\n";
						echo "<hr>\n"; 

						echo "<form method=post action=passthru.php?func=insert&area=census&person=".$_REQUEST["person"].">\n";
							echo "<table>\n";
								echo "<tr>\n";
									echo "<td>Year</td>\n";
									echo "<td>\n";
									list_enums("census", "year", "frmYear");
									echo "</td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Schedule</td>\n";
									echo "<td><input type=text name=frmSchedule size=30 maxlength=20></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Address</td>\n";
									echo "<td><input type=text name=frmAddress size=50 maxlength=70></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Condition</td>\n";
									echo "<td>\n";
									list_enums("census", "condition", "frmCondition");
									echo "</td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Age</td>\n";
									echo "<td><input type=text name=frmAge size=10 maxlength=3></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Profession</td>\n";
									echo "<td><input type=text name=frmProfession size=30 maxlength=40></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td>Place of Birth</td>\n";
									echo "<td><input type=text name=frmWhereBorn size=30 maxlength=40></td>\n";
								echo "</tr>\n";
								echo "<tr>\n";
									echo "<td><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></td>\n";
									echo "<td></td>\n";
								echo "</tr>\n";
							echo "</table>\n";
						echo "</form>\n";
					}
					mysql_free_result($edresult);
					break;

				default:
					echo "Here be adding area dragons";
					break;
			}
		break;

		// don't know what else to do
		default:
			echo "Here be function dragons";
			break;
	}

?>

<script language="JavaScript" type="text/javascript" src="pphlogger.js"></script>
<noscript><img alt="" src="http://logger.giric.com/pphlogger.php?id=family&st=img"></noscript>

<?php

	echo "</BODY>\n";
	echo "</html>\n";
	// eof
?>