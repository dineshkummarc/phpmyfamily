<?php
	
	// family tree software
	// (c)2002 Simon E Booth
	// All rights reserved
	// File to control editing and creation of new data.

	// include the database parameters
	include "db.inc.php";

	// include the browser 
	include "browser.inc.php";
	include "css.inc.php";

	// fill out the header
	echo "<HTML>";
	echo "<HEAD>";
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
						echo "<title>Editing: ".$edrow["name"]."</title>";
						echo "</HEAD>";
						echo "<BODY>\n";

						echo "<h2>".$edrow["name"]."</h2>";
						echo "<hr>";
						
						echo "<form method=post action=people.php?func=update&person=".$_REQUEST["person"]."&area=detail&spouse=&year=>";
							echo "<table>";
								echo "<tr>";
									echo "<td>Name</td>";
									echo "<td><input type=text name=frmName value='".$edrow["name"]."' size=30></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Date of Birth</td>";
									echo "<td><input type=text name=frmDOB value='".$edrow["date_of_birth"]."' size=30></td>";
									echo "<td>Please use format YYYY-MM-DD</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Birth Place</td>";
									echo "<td><input type=text name=frmBirthPlace value='".$edrow["birth_place"]."' size=30></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Date of Death</td>";
									echo "<td><input type=text name=frmDOD value='".$edrow["date_of_death"]."' size=30></td>";
									echo "<td>Please use format YYYY-MM-DD</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Death Reason</td>";
									echo "<td><input type=text name=frmDeathReason value='".$edrow["death_reason"]."' size=30></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Mother</td>";
									echo "<td><select name=frmMother size=1>";
									$mquery = "SELECT person_id, SUBSTRING_INDEX(name, ' ', -1) AS surname, name FROM people WHERE gender = 'F' AND person_id <> '".$_REQUEST["person"]."' ORDER BY surname, name";
									$mresult = mysql_query($mquery) or die("Mother query failed");
							
									if ($edrow["mother_id"] == 0)
											echo "<option value=NULL selected=selected>Select mother</option>";
							
									while ($mrow = mysql_fetch_array($mresult)) {
										echo "<option value=".$mrow["person_id"];
										if ($mrow["person_id"] == $edrow["mother_id"])
											echo " selected=selected";
										echo ">".$mrow["surname"].", ".substr($mrow["name"], 0, strlen($mrow["name"]) - strlen($mrow["surname"]))."</option>";
									}
									mysql_free_result($mresult);
									echo "</select></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Father</td>";
									echo "<td><select name=frmFather size=1>";
									$fquery = "SELECT person_id, SUBSTRING_INDEX(name, ' ', -1) AS surname, name FROM people WHERE gender = 'M' AND person_id <> '".$_REQUEST["person"]."' ORDER BY surname, name";
									$fresult = mysql_query($fquery) or die("Father query failed");
							
									if ($edrow["father_id"] == 0)
											echo "<option value=null selected=selected>Select father</option>";

									while ($frow = mysql_fetch_array($fresult)) {
										echo "<option value=".$frow["person_id"];
										if ($frow["person_id"] == $edrow["father_id"])
											echo " selected=selected";
										echo ">".$frow["surname"].", ".substr($frow["name"], 0, strlen($frow["name"]) - strlen($frow["surname"]))."</option>";
									}
									mysql_free_result($fresult);
									echo "</select></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Narrative</td>";
									echo "<td><textarea name=frmNarrative rows=10 cols=40>".$edrow["narrative"]."</textarea></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></td>";
									echo "<td><INPUT TYPE=RESET NAME=Reset1 VALUE=Reset></td>";
								echo "</tr>";
							echo "</table>";
						echo "</form>";
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
							echo "<title>Editing Marriage: ".$prow["name"]." & ".$spousename."</title>";
							echo "</HEAD>";
							echo "<BODY>\n";

							echo "<h2>Marriage: ".$prow["name"]." & ".$spousename."</h2>";
							echo "<hr>"; 

							echo "<form method=post action=people.php?func=update&person=".$_REQUEST["person"]."&area=marriage&oldspouse=".$_REQUEST["spouse"]."&gender=".$prow["gender"]."&spouse=&year=>";
								echo "<table>";
									echo "<tr>";
										echo "<td>Spouse</td>";
										echo "<td><select name=frmSpouse size=1>";
											$squery = "SELECT person_id, SUBSTRING_INDEX(name, ' ', -1) AS surname, name FROM people WHERE gender <> '".$prow["gender"]."' ORDER BY surname, name";
											$sresult = mysql_query($squery) or die("Spouse list query failed");
							
											while ($srow = mysql_fetch_array($sresult)) {
												echo "<option value=".$srow["person_id"];
												if ($srow["person_id"] == $_REQUEST["spouse"])
													echo " selected=selected";
												echo ">".$srow["surname"].", ".substr($srow["name"], 0, strlen($srow["name"]) - strlen($srow["surname"]))."</option>";
											}
											mysql_free_result($fresult);
										echo "</select></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td>Date</td>";
										echo "<td><input type=text name=frmDate value='".$edrow["marriage_date"]."'</td>";
										echo "<td>Please use format YYYY-MM-DD</td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td>Place</td>";
										echo "<td><input type=text name=frmPlace value='".$edrow["marriage_place"]."'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<td><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></td>";
										echo "<td><INPUT TYPE=RESET NAME=Reset1 VALUE=Reset></td>";
									echo "</tr>";
								echo "</table>";
							echo "</form>";
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
						echo "<title>Editing Census: ".$edrow["name"]." (".$edrow["year"].")</title>";
						echo "</HEAD>";
						echo "<BODY>\n";

						echo "<h2> Census: ".$edrow["name"]." (".$edrow["year"].")</h2>";
						echo "<hr>"; 

						echo "<form method=post action=people.php?func=update&person=".$_REQUEST["person"]."&area=census&year=".$_REQUEST["year"].">";
							echo "<table>";
								echo "<tr>";
									echo "<td>Schedule</td>";
									echo "<td><input type=text name=frmSchedule value='".$edrow["schedule"]."' size=30></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Address</td>";
									echo "<td><input type=text name=frmAddress value='".$edrow["address"]."' size=50></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Condition</td>";
									$cquery = "SHOW COLUMNS FROM census LIKE 'condition'";
									$cresult = mysql_query($cquery);
									while ($crow = mysql_fetch_array($cresult)) {
										$enum        = str_replace('enum(', '', $crow['Type']);
										$enum        = ereg_replace('\\)$', '', $enum);
										$enum        = explode('\',\'', substr($enum, 1, -1));
										$enum_cnt    = count($enum);
									}
									echo "<td>";
										echo "<select name=frmCondition size=1>";
											for ($j = 0; $j < $enum_cnt; $j++) {
												$enum_atom = str_replace('\'\'', '\'', str_replace('\\\\', '\\', $enum[$j]));
												echo '<option value="' . urlencode($enum_atom) . '"';
												if ($enum_atom == $edrow["condition"]) 
													echo ' selected="selected"';
												echo '>' . htmlspecialchars($enum_atom) . '</option>' . "\n";
											}
										echo "</select>";
									mysql_free_result($cresult);
									echo "</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Age</td>";
									echo "<td><input type=text name=frmAge value='".$edrow["age"]."' size=30></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Profession</td>";
									echo "<td><input type=text name=frmProfession value='".$edrow["profession"]."' size=30></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Place of Birth</td>";
									echo "<td><input type=text name=frmBirthPlace value='".$edrow["where_born"]."' size=30></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></td>";
									echo "<td><INPUT TYPE=RESET NAME=Reset1 VALUE=Reset></td>";
								echo "</tr>";
							echo "</table>";
						echo "</form>";
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
					echo "<title>Creating new family member</title>";
					echo "</HEAD>";
					echo "<BODY>\n";

					echo "<h2>Create new person</h2>";
					echo "<hr>";
					echo "Please make sure person doesn't exist before creating!!!<br>";

					echo "<form method=post action=people.php?func=insert&person=&area=detail>";
						echo "<table>";
							echo "<tr>";
								echo "<td>Name</td>";
								echo "<td><input type=text name=frmName size=30></td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Date of Birth</td>";
								echo "<td><input type=text name=frmDOB size=30></td>";
								echo "<td>Please use format YYYY-MM-DD</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Birth Place</td>";
								echo "<td><input type=text name=frmBirthPlace size=30></td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Date of Death</td>";
								echo "<td><input type=text name=frmDOD size=30></td>";
								echo "<td>Please use format YYYY-MM-DD</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Death Reason</td>";
								echo "<td><input type=text name=frmDeathReason size=30></td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Gender</td>";
								echo "<td><input type=radio name=frmGender value=M checked=checked>M<input type=radio name=frmGender value=F>F</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Mother</td>";
								echo "<td><select name=frmMother size=1>";
								$mquery = "SELECT person_id, SUBSTRING_INDEX(name, ' ', -1) AS surname, name FROM people WHERE gender = 'F' AND person_id <> '".$_REQUEST["person"]."' ORDER BY surname, name";
								$mresult = mysql_query($mquery) or die("Mother query failed");
							
								echo "<option value=NULL selected=selected>Select mother</option>";
							
								while ($mrow = mysql_fetch_array($mresult)) {
									echo "<option value=".$mrow["person_id"].">".$mrow["surname"].", ".substr($mrow["name"], 0, strlen($mrow["name"]) - strlen($mrow["surname"]))."</option>";
								}
								mysql_free_result($mresult);
								echo "</select></td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Father</td>";
								echo "<td><select name=frmFather size=1>";
								$fquery = "SELECT person_id, SUBSTRING_INDEX(name, ' ', -1) AS surname, name FROM people WHERE gender = 'M' AND person_id <> '".$_REQUEST["person"]."' ORDER BY surname, name";
								$fresult = mysql_query($fquery) or die("Father query failed");
							
								echo "<option value=null selected=selected>Select father</option>";

								while ($frow = mysql_fetch_array($fresult)) {
									echo "<option value=".$frow["person_id"].">".$frow["surname"].", ".substr($frow["name"], 0, strlen($frow["name"]) - strlen($frow["surname"]))."</option>";
								}
								mysql_free_result($fresult);
								echo "</select></td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Narrative</td>";
								echo "<td><textarea name=frmNarrative rows=10 cols=40></textarea></td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></td>";
								echo "<td><INPUT TYPE=RESET NAME=Reset1 VALUE=Reset></td>";
							echo "</tr>";
						echo "</table>";
					echo "</form>";
					break;

				case "marriage":
					// get the person to insert marriage for
					$edquery = "SELECT * FROM people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die("New marriage person query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {

						// fill out the header
						echo "<title>New Marriage: ".$edrow["name"]."</title>";
						echo "</HEAD>";
						echo "<BODY>\n";

						echo "<h2> New Marriage: ".$edrow["name"]."</h2>";
						echo "<hr>"; 

						echo "<form method=post action=people.php?func=insert&person=".$_REQUEST["person"]."&area=marriage&gender=".$edrow["gender"].">";
							echo "<table>";
								echo "<tr>";
									echo "<td>Spouse</td>";
									echo "<td><select name=frmSpouse size=1>";
										$squery = "SELECT person_id, SUBSTRING_INDEX(name, ' ', -1) AS surname, name FROM people WHERE gender <> '".$edrow["gender"]."' ORDER BY surname, name";
										$sresult = mysql_query($squery) or die("Spouse list query failed");
							
										while ($srow = mysql_fetch_array($sresult)) {
											echo "<option value=".$srow["person_id"].">".$srow["surname"].", ".substr($srow["name"], 0, strlen($srow["name"]) - strlen($srow["surname"]))."</option>";
										}
										mysql_free_result($fresult);
									echo "</select></td>";
								echo "<tr>";
								echo "<tr>";
									echo "<td>Date of marriage</td>";
									echo "<td><input type=text name=frmDate size=30></td>";
									echo "<td>Use format YYYY-MM-DD</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Marriage Place</td>";
									echo "<td><input type=text name=frmPlace size=30></td>";
								echo "</tr>";
								echo "<tr>";
								echo "<td><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></td>";
							echo "</tr>";
							echo "</table>";
						echo "</form>";
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
						echo "<title>New Census: ".$edrow["name"]."</title>";
						echo "</HEAD>";
						echo "<BODY>\n";

						echo "<h2> New Census: ".$edrow["name"]."</h2>";
						echo "<hr>"; 

						echo "<form method=post action=people.php?func=insert&person=".$_REQUEST["person"]."&area=census>";
							echo "<table>";
								echo "<tr>";
									echo "<td>Year</td>";
									$yquery = "SHOW COLUMNS FROM census LIKE 'year'";
									$yresult = mysql_query($yquery);
									while ($yrow = mysql_fetch_array($yresult)) {
										$enum        = str_replace('enum(', '', $yrow['Type']);
										$enum        = ereg_replace('\\)$', '', $enum);
										$enum        = explode('\',\'', substr($enum, 1, -1));
										$enum_cnt    = count($enum);
										$default	 = $yrow["Default"];
									}
									echo "<td>";
										echo "<select name=frmYear size=1>";
											for ($j = 0; $j < $enum_cnt; $j++) {
												$enum_atom = str_replace('\'\'', '\'', str_replace('\\\\', '\\', $enum[$j]));
												echo '<option value="' . urlencode($enum_atom) . '"';
												if ($enum_atom == $default) 
													echo ' selected="selected"';
												echo '>' . htmlspecialchars($enum_atom) . '</option>' . "\n";
											}
										echo "</select>";
									mysql_free_result($yresult);
									echo "</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Schedule</td>";
									echo "<td><input type=text name=frmSchedule size=30></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Address</td>";
									echo "<td><input type=text name=frmAddress size=50></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Condition</td>";
									$cquery = "SHOW COLUMNS FROM census LIKE 'condition'";
									$cresult = mysql_query($cquery);
									while ($crow = mysql_fetch_array($cresult)) {
										$enum        = str_replace('enum(', '', $crow['Type']);
										$enum        = ereg_replace('\\)$', '', $enum);
										$enum        = explode('\',\'', substr($enum, 1, -1));
										$enum_cnt    = count($enum);
										$default	 = $crow["Default"];
									}
									echo "<td>";
										echo "<select name=frmCondition size=1>";
											for ($j = 0; $j < $enum_cnt; $j++) {
												$enum_atom = str_replace('\'\'', '\'', str_replace('\\\\', '\\', $enum[$j]));
												echo '<option value="' . urlencode($enum_atom) . '"';
												if ($enum_atom == $default) 
													echo ' selected="selected"';
												echo '>' . htmlspecialchars($enum_atom) . '</option>' . "\n";
											}
										echo "</select>";
									mysql_free_result($cresult);
									echo "</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Age</td>";
									echo "<td><input type=text name=frmAge size=10></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Profession</td>";
									echo "<td><input type=text name=frmProfession size=30></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Birth Place</td>";
									echo "<td><input type=text name=frmWhereBorn size=30></td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Submit></td>";
									echo "<td></td>";
								echo "</tr>";
							echo "</table>";
						echo "</form>";
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
	echo "</html>";
	// eof
?>