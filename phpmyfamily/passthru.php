<?php

	// family tree software
	// (c)2002 - 2003 Simon E Booth
	// All rights reserved
	// passthru.php

	// include the database parameters
	include "inc/session.inc.php";
	include "inc/db.inc.php";
	include "inc/functions.inc.php";

	if ($_SESSION["id"] == 0 && ($_REQUEST["func"] != "jump" && $_REQUEST["func"] != "login"))
		die("Security Breach");

	echo "<HEAD>\n";
	switch ($_REQUEST["func"]) {
		case "update":
			switch ($_REQUEST["area"]) {
				case "marriage":
					stamppeeps($_REQUEST["person"]);
					stamppeeps($_POST["frmSpouse"]);
					stamppeeps($_REQUEST["oldspouse"]);

					if ($_REQUEST["gender"] == "M")
						$query = "UPDATE family_spouses SET bride_id = '".$_POST["frmSpouse"]."', marriage_date = '".$_POST["frmDate"]."', marriage_place = '".$_POST["frmPlace"]."' WHERE groom_id = '".$_REQUEST["person"]."' AND bride_id = '".$_REQUEST["oldspouse"]."'";
					else
						$query = "UPDATE family_spouses SET groom_id = '".$_POST["frmSpouse"]."', marriage_date = '".$_POST["frmDate"]."', marriage_place = '".$_POST["frmPlace"]."' WHERE bride_id = '".$_REQUEST["person"]."' AND groom_id = '".$_REQUEST["oldspouse"]."'";
					break;
				case "detail":
					echo $_POST["frmBirthCert"];
					$query = "UPDATE family_people SET name = '".$_POST["frmName"]."', date_of_birth = '".$_POST["frmDOB"]."', birth_place = '".$_POST["frmBirthPlace"]."', date_of_death = '".$_POST["frmDOD"]."', death_reason = '".$_POST["frmDeathReason"]."', mother_id = '".$_POST["frmMother"]."', father_id = '".$_POST["frmFather"]."', narrative = '".$_POST["frmNarrative"]."' WHERE person_id = '".$_REQUEST["person"]."'";
					break;
				case "census":
					stamppeeps($_REQUEST["person"]);

					$query = "UPDATE family_census SET schedule = '".$_POST["frmSchedule"]."', address = '".$_POST["frmAddress"]."', condition = '".$_POST["frmCondition"]."', age = '".$_POST["frmAge"]."', profession = '".$_POST["frmProfession"]."', where_born = '".$_POST["frmBirthPlace"]."' WHERE person_id = '".$_REQUEST["person"]."' AND year = '".$_REQUEST["year"]."'";
					break;
				default:
					break;
			}
			$result = mysql_query($query);
			echo "<META HTTP-EQUIV=Refresh CONTENT='0; URL=people.php?person=".$_REQUEST["person"]."'>\n";
			break;
		case "insert";
			switch ($_REQUEST["area"]) {
				case "marriage":
					stamppeeps($_REQUEST["person"]);
					stamppeeps($_POST["frmSpouse"]);

					if ($_REQUEST["gender"] == "M")
						$iquery = "INSERT INTO family_spouses (groom_id, bride_id, marriage_date, marriage_place) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmSpouse"]."', '".$_POST["frmDate"]."', '".$_POST["frmPlace"]."')";
					else
						$iquery = "INSERT INTO family_spouses (groom_id, bride_id, marriage_date, marriage_place) VALUES ('".$_POST["frmSpouse"]."', '".$_REQUEST["person"]."', '".$_POST["frmDate"]."', '".$_POST["frmPlace"]."')";
					$iresult = mysql_query($iquery);
					$person = $_REQUEST["person"];
					break;
				case "transcript":
					$iquery = "INSERT INTO family_documents (person_id, doc_date, doc_title, doc_description, file_name) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmDate"]."', '".$_POST["frmTitle"]."', '".$_POST["frmDesc"]."', 'docs/".$_FILES["userfile"]["name"]."')";
					$iresult = mysql_query($iquery) or die("Transcript insertion failed");
					move_uploaded_file($_FILES["userfile"]["tmp_name"], "docs/".$_FILES["userfile"]["name"]);
					$person = $_REQUEST["person"];
					stamppeeps($person);
					break;
				case "image":
					$iquery = "INSERT INTO family_images (person_id, title, date, description) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmTitle"]."', '".$_POST["frmDate"]."', '".$_POST["frmDesc"]."')";;
					$iresult = mysql_query($iquery) or die("Image insert failed");
					$image = mysql_insert_id();
					$person = $_REQUEST["person"];
					if (processimage($image)) 
						stamppeeps($person);
					break;
				case "detail":
					$iquery = "INSERT INTO family_people (person_id, name, date_of_birth, birth_place, date_of_death, death_reason, gender, mother_id, father_id, narrative, updated) VALUES ('', '".$_POST["frmName"]."', '".$_POST["frmDOB"]."', '".$_POST["frmBirthPlace"]."', '".$_POST["frmDOD"]."', '".$_POST["frmDeathReason"]."', '".$_POST["frmGender"]."', '".$_POST["frmMother"]."', '".$_POST["frmFather"]."', '".$_POST["frmNarrative"]."', NOW())";
					$iresult = mysql_query($iquery) or die("Detail nsert query failed");
					$person = mysql_insert_id();
					break;
				case "census":
					stamppeeps($_REQUEST["person"]);

					$iquery = "INSERT INTO family_census (person_id, year, schedule, address, condition, age, profession, where_born) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmYear"]."', '".$_POST["frmSchedule"]."', '".$_POST["frmAddress"]."', '".$_POST["frmCondition"]."', '".$_POST["frmAge"]."', '".$_POST["frmProfession"]."', '".$_POST["frmWhereBorn"]."')";
					$iresult = mysql_query($iquery) or die("Census insert query failed");

					$person = $_REQUEST["person"];
					break;
				default:
					break;
			}
			echo "<META HTTP-EQUIV=Refresh CONTENT='0; URL=people.php?person=".$person."'>\n";
			break;
		case "login":
			@$query = "SELECT * FROM family_users WHERE username = '".$_POST["pwdUser"]."' AND password = '".md5($_POST["pwdPassword"])."'";
			$result = mysql_query($query) or die("error logging on");
			if (mysql_num_rows($result) == 1) {	
				while ($row = mysql_fetch_array($result))
					$_SESSION["id"] = $row["id"];
			}
			mysql_free_result($result);
			echo "<META HTTP-EQUIV=Refresh CONTENT='0; URL=index.php'>\n";
			break;
		case "logout":
			$_SESSION["id"] = 0;
			echo "<META HTTP-EQUIV=Refresh CONTENT='0; URL=index.php'>\n";
			break;
		default:
			echo "<META HTTP-EQUIV=Refresh CONTENT='0; URL=people.php?person=".$_POST["person"]."'>\n";
			break;
	}
	echo "</HEAD>\n";

	// eof
?>