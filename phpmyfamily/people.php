<?php

	// family tree software
	// (c)2002 Simon E Booth
	// All rights reserved
	// file to control display of personal information

	// include the database parameters
	include "db.inc.php";

	// include the browser 
	include "browser.inc.php";
	include "css.inc.php";

	// update the persons record
	if ($_REQUEST["func"] == "update") {
		switch ($_REQUEST["area"]) {
			case "detail":
				$uquery = "UPDATE people SET name = '".$_POST["frmName"]."', date_of_birth = '".$_POST["frmDOB"]."', birth_place = '".$_POST["frmBirthPlace"]."', date_of_death = '".$_POST["frmDOD"]."', death_reason = '".$_POST["frmDeathReason"]."', mother_id = '".$_POST["frmMother"]."', father_id = '".$_POST["frmFather"]."', narrative = '".$_POST["frmNarrative"]."' WHERE person_id = '".$_REQUEST["person"]."'";
				break;

			case "marriage":
				$tquery = "UPDATE people SET updated = NOW() WHERE person_id = '".$_REQUEST["person"]."'";
				$tresult = mysql_query($tquery) or die("Timestamp update failed");

				if ($_REQUEST["gender"] == "M")
					$uquery = "UPDATE spouses SET bride_id = '".$_POST["frmSpouse"]."', marriage_date = '".$_POST["frmDate"]."', marriage_place = '".$_POST["frmPlace"]."' WHERE groom_id = '".$_REQUEST["person"]."' AND bride_id = '".$_REQUEST["oldspouse"]."'";
				else
					$uquery = "UPDATE spouses SET groom_id = '".$_POST["frmSpouse"]."', marriage_date = '".$_POST["frmDate"]."', marriage_place = '".$_POST["frmPlace"]."' WHERE bride_id = '".$_REQUEST["person"]."' AND groom_id = '".$_REQUEST["oldspouse"]."'";
				break;

			case "census":
				$tquery = "UPDATE people SET updated = NOW() WHERE person_id = '".$_REQUEST["person"]."'";
				$tresult = mysql_query($tquery) or die("Timestamp update failed");

				$uquery = "UPDATE census SET schedule = '".$_POST["frmSchedule"]."', address = '".$_POST["frmAddress"]."', condition = '".$_POST["frmCondition"]."', age = '".$_POST["frmAge"]."', profession = '".$_POST["frmProfession"]."', where_born = '".$_POST["frmBirthPlace"]."' WHERE person_id = '".$_REQUEST["person"]."' AND year = '".$_REQUEST["year"]."'";
				break;

			case "narrative";
				break;
			
			default:
				break;
		}

		$uresult = mysql_query($uquery) or die("Update query failed");
	}

	// add a new person
	if ($_REQUEST["func"] == "insert") {
		switch ($_REQUEST["area"]) {
			case "detail":
				$iquery = "INSERT INTO people (person_id, name, date_of_birth, birth_place, date_of_death, death_reason, gender, mother_id, father_id, narrative, updated) VALUES ('', '".$_POST["frmName"]."', '".$_POST["frmDOB"]."', '".$_POST["frmBirthPlace"]."', '".$_POST["frmDOD"]."', '".$_POST["frmDeathReason"]."', '".$_POST["frmGender"]."', '".$_POST["frmMother"]."', '".$_POST["frmFather"]."', '".$_POST["frmNarrative"]."', NOW())";
				$iresult = mysql_query($iquery) or die("Detail nsert query failed");
				$person = mysql_insert_id();
				break;
			
			case "marriage":
				$tquery = "UPDATE people SET updated = NOW() WHERE person_id = '".$_REQUEST["person"]."'";
				$tresult = mysql_query($tquery) or die("Timestamp update failed");

				if ($_REQUEST["gender"] == "M")
					$iquery = "INSERT INTO spouses (groom_id, bride_id, marriage_date, marriage_place) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmSpouse"]."', '".$_POST["frmDate"]."', '".$_POST["frmPlace"]."')";
				else
					$iquery = "INSERT INTO spouses (groom_id, bride_id, marriage_date, marriage_place) VALUES ('".$_POST["frmSpouse"]."', '".$_REQUEST["person"]."', '".$_POST["frmDate"]."', '".$_POST["frmPlace"]."')";
				$iresult = mysql_query($iquery);
				break;

			case "census":
				$tquery = "UPDATE people SET updated = NOW() WHERE person_id = '".$_REQUEST["person"]."'";
				$tresult = mysql_query($tquery) or die("Timestamp update failed");

				$iquery = "INSERT INTO census (person_id, year, schedule, address, condition, age, profession, where_born) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmYear"]."', '".$_POST["frmSchedule"]."', '".$_POST["frmAddress"]."', '".$_POST["frmCondition"]."', '".$_POST["frmAge"]."', '".$_POST["frmProfession"]."', '".$_POST["frmWhereBorn"]."')";
				$iresult = mysql_query($iquery) or die("Census insert query failed");
				break;

			default:
				break;
		}
	}

	// check we have a person
	if(!isset($_REQUEST["person"])) $person=1;

	// the query for the database
	$pquery = "SELECT * FROM people WHERE person_id = '".$_REQUEST["person"]."'";
	$presult = mysql_query($pquery) or die("Person query failed");

	while ($prow = mysql_fetch_array($presult)) {

		// pickout father and mother for use in queries
		// set to -1 to avoid too many siblings!!! :-)
		$father = $prow["father_id"];
		if ($father == 0) $father = -1;
		$mother = $prow["mother_id"];
		if ($mother == 0) $mother = -1;
		
		// fill out the header
		echo "<HTML>";
		echo "<HEAD>";
		css_site();
		echo "<title>".$prow["name"]."</title>";
		echo "</HEAD>";
		echo "<BODY>\n";

		echo "<table>";
			echo "<tr width=100%>";
				echo "<td width=80% align=center>";
					echo "<h2>".$prow["name"]."</h2>";
					echo "<h3>(".formatdbdate($prow["date_of_birth"])." - ".formatdbdate($prow["date_of_death"]).")</h3>";
				echo "</td>";
				echo "<td width=20%>";
					echo "<form method=post action=people.php?person=&func=>";
					// query for everybody
					$allquery = "SELECT person_id, SUBSTRING_INDEX(name, ' ', -1) AS surname, name FROM people WHERE person_id <> '".$_REQUEST["person"]."' ORDER BY surname, name";
					$allresult = mysql_query($allquery) or die("All people query failed");
						
					echo (mysql_num_rows($allresult) + 1)." people on file<br>";
						echo "<select name=person size=1>";
						echo "<option value=0 selected=selected>Jump to person</option>";
						while ($allrow = mysql_fetch_array($allresult)) {
							echo "<option value=".$allrow["person_id"].">".$allrow["surname"].", ".substr($allrow["name"], 0, strlen($allrow["name"]) - strlen($allrow["surname"]))."</option>";
						}
						echo "</select>";
						mysql_free_result($allresult);
						echo "<INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Go>";
					echo "</form>";
				echo "</td>";
			echo "</tr>";
		echo "</table>";

		echo "<HR>";
		 
		echo "<table width=100%>";
			echo "<tr width=100%>";
				echo "<td width=95%><h4>Details</h4></td>";
				echo "<td width=5%><a href=edit.php?func=edit&person=".$prow["person_id"]."&area=detail>edit</a></td>";
			echo "</tr>";
		echo "</table>";
		echo "<table>";
			echo "<tr>";
				echo "<td width=5%>Born:</td>";
				echo "<td width=40%>";
					echo formatdbdate($prow["date_of_birth"])." at ".$prow["birth_place"];
				echo "</td>";
				echo "<td width=5%>Father:</td>";
				echo "<td width=40%>";
					// the query for father
					$fquery = "SELECT * FROM people WHERE person_id = '".$father."'";
					$fresult = mysql_query($fquery) or die("Father query failed");
					while ($frow = mysql_fetch_array($fresult)) {
						echo "<a href=people.php?person=".$frow["person_id"]."&func=>".$frow["name"]." </a>(".formatdbdate($frow["date_of_birth"])." - ".formatdbdate($frow["date_of_death"]).")";
					}
					mysql_free_result($fresult);
				echo "</td>";
				
			echo "</tr>";
			echo "<tr>";
				echo "<td width=5%>Died:</td>";
				echo "<td width=20%>";
					echo formatdbdate($prow["date_of_death"])." of ".$prow["death_reason"];
				echo "</td>";
				echo "<td>Mother:</td>";
				echo "<td>";
					// the query for mother
					$mquery = "SELECT * FROM people WHERE person_id = '".$mother."'";
					$mresult = mysql_query($mquery) or die("Mother query failed");
					while ($mrow = mysql_fetch_array($mresult)) {
						echo "<a href=people.php?person=".$mrow["person_id"]."&func=>".$mrow["name"]."</a> (".formatdbdate($mrow["date_of_birth"])." - ".formatdbdate($mrow["date_of_death"]).")";
					}
					mysql_free_result($mresult);
				echo "</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td valign=top>Children:</td>";
				echo "<td valign=top>";
					// query for children
					$cquery = "SELECT * FROM people WHERE (father_id = '".$_REQUEST["person"]."' OR mother_id = '".$_REQUEST["person"]."') ORDER BY date_of_birth";
					$cresult = mysql_query($cquery) or die("Children query failed");
					while ($crow = mysql_fetch_array($cresult)) {
						echo "<a href=people.php?person=".$crow["person_id"]."&func=>".$crow["name"]."</a> (".formatdbdate($crow["date_of_birth"])." - ".formatdbdate($crow["date_of_death"]).")<br>";
					}
					mysql_free_result($cresult);
				echo "</td>";
				echo "<td valign=top>Siblings:</td>";
				echo "<td valign=top>";
					// the query for siblings
					$squery = "SELECT * FROM people WHERE (father_id = '".$father."' OR mother_id = '".$mother."') AND person_id <> '".$_REQUEST["person"]."' ORDER BY date_of_birth";
					$sresult = mysql_query($squery) or die("Siblings query failed");
					while ($srow = mysql_fetch_array($sresult)) {
						echo "<a href=people.php?person=".$srow["person_id"]."&func=>".$srow["name"]."</a> (".formatdbdate($srow["date_of_birth"])." - ".formatdbdate($srow["date_of_death"]).")<br>";
					}
					mysql_free_result($sresult);
				echo "</td>";
			echo "</tr>";
		echo "</table>";
		echo "<hr>";
		echo "<table>";
			echo "<tr>";
				echo "<td valign=top width=5%>Married:</td>";
				echo "<td valign=top width=80%>";
					// query for weddings
					$wquery = "SELECT * FROM people, spouses WHERE (bride_id = person_id OR groom_id = person_id) AND (groom_id = '".$_REQUEST["person"]."' OR bride_id = '".$_REQUEST["person"]."') AND person_id <> '".$_REQUEST["person"]."' ORDER BY marriage_date";
					$wresult = mysql_query($wquery) or die("Marriage query failed");
					while ($wrow = mysql_fetch_array($wresult)) {
						echo "<a href=edit.php?func=edit&person=".$_REQUEST["person"]."&area=marriage&spouse=".$wrow["person_id"].">edit</a> <a href=people.php?person=".$wrow["person_id"]."&func=>".$wrow["name"]."</a> on  ".formatdbdate($wrow["marriage_date"])." at ".$wrow["marriage_place"]."<br>";
					}
					mysql_free_result($wresult);
				echo "</td>";
				echo "<td align=right><a href=edit.php?func=add&person=".$_REQUEST["person"]."&area=marriage>insert</a> new marriage</td>";
			echo "</tr>";
		echo "</table>";
		
		echo "<HR>";

		echo "<table width=100%>";
			echo "<tr width=100%>";
				echo "<td width=95%><h4>Notes</h4></td>";
				echo "<td width=5%>";
				//<a href=edit.php?func=edit&person=".$prow["person_id"]."&area=narrative>edit</a>
				echo "</td>";
			echo "</tr>";
		echo "</table>";

		echo $prow["narrative"];
		
		echo "<br><br>";
		echo "<HR>";
			echo "<table width=100%>";
				echo "<tr>";
					echo "<td width=80%><h4>Census Details</h4></td>";
					echo "<td width=20% valign=top align=right><a href=edit.php?func=add&person=".$_REQUEST["person"]."&area=census>Insert</a> new census</td>";
				echo "</tr>";
			echo "</table>";
			$cquery = "SELECT * FROM census WHERE person_id = '".$_REQUEST["person"]."' ORDER BY year";
			$cresult = mysql_query($cquery) or die("Census query failed");
			if (mysql_num_rows($cresult) == 0)
				echo "No information available";
			else {
				echo "<table width=100%>";
					echo "<tr>";
						echo "<th bgcolor=#D3DCE3></th>";
						echo "<th bgcolor=#D3DCE3>Year</th>";
						echo "<th bgcolor=#D3DCE3>Reference</th>";
						echo "<th bgcolor=#D3DCE3>Address</th>";
						echo "<th bgcolor=#D3DCE3>Condition</th>";
						echo "<th bgcolor=#D3DCE3>Age</th>";
						echo "<th bgcolor=#D3DCE3>Profession</th>";
						echo "<th bgcolor=#D3DCE3>Birth Place</th>";
					echo "</tr>";
					while ($crow = mysql_fetch_array($cresult)) {
						echo "<tr>";
							echo "<td><a href=edit.php?func=edit&person=".$_REQUEST["person"]."&area=census&year=".$crow["year"].">edit</a></td>";
							echo "<td bgcolor=#DDDDDD>".$crow["year"]."</td>";
							echo "<td bgcolor=#CCCCCC>".$crow["schedule"]."</td>";
							echo "<td bgcolor=#CCCCCC>".$crow["address"]."</td>";
							echo "<td bgcolor=#CCCCCC>".$crow["condition"]."</td>";
							echo "<td bgcolor=#CCCCCC>".$crow["age"]."</td>";
							echo "<td bgcolor=#CCCCCC>".$crow["profession"]."</td>";
							echo "<td bgcolor=#CCCCCC>".$crow["where_born"]."</td>";
						echo "</tr>";
					}
					mysql_free_result($cresult);
				echo "</table>";
			}
		echo "<HR>";
		echo "<center><h5>Last updated: ".date('H:i \o\n \t\h\e d/m/Y', convertstamp($prow["updated"]))."<br>Any information to add? <a href=edit.php?func=edit&person=".$prow["person_id"]."&area=detail>Edit</a> this person<br>Missing people? <a href=edit.php?func=add&area=detail>Add</a> a new person to the database<br>Problems? Let <a href=mailto:simon.booth@giric.com?subject=".$prow["person_id"].">me</a> know</h5></center>";
	}
	
	mysql_free_result($presult);
	
?>

<script language="JavaScript" type="text/javascript" src="pphlogger.js"></script>
<noscript><img alt="" src="http://logger.giric.com/pphlogger.php?id=giric&st=img"></noscript>

<?php
	
	echo "</body>";
	echo "</html>";

	// eof
?>