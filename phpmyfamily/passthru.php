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

	// include the configuration parameters and functions
	include "inc/config.inc.php";
	include "inc/functions.inc.php";
	ini_set("max_execution_time", 180);

	// if you are not logged in then you shouldn't be here!
	if ($_SESSION["id"] == 0 && $_REQUEST["func"] != "login")
		die(include "inc/forbidden.inc.php");

	// build the page to be sent
	// it's really only html headers
	echo "<head>\n";
	switch ($_REQUEST["func"]) {
		case "update":
			switch ($_REQUEST["area"]) {
				case "marriage":
					stamppeeps($_REQUEST["person"]);
					stamppeeps($_POST["frmSpouse"]);
					stamppeeps($_REQUEST["oldspouse"]);

					if ($_REQUEST["gender"] == "M")
						$query = "UPDATE ".$tblprefix."spouses SET bride_id = '".$_POST["frmSpouse"]."', marriage_date = '".$_POST["frmDate"]."', marriage_place = '".$_POST["frmPlace"]."' WHERE groom_id = '".$_REQUEST["person"]."' AND bride_id = '".$_REQUEST["oldspouse"]."'";
					else
						$query = "UPDATE ".$tblprefix."spouses SET groom_id = '".$_POST["frmSpouse"]."', marriage_date = '".$_POST["frmDate"]."', marriage_place = '".$_POST["frmPlace"]."' WHERE bride_id = '".$_REQUEST["person"]."' AND groom_id = '".$_REQUEST["oldspouse"]."'";
					break;
				case "detail":
					$query = "UPDATE ".$tblprefix."people SET name = '".$_POST["frmName"]."', date_of_birth = '".$_POST["frmDOB"]."', birth_place = '".$_POST["frmBirthPlace"]."', date_of_death = '".$_POST["frmDOD"]."', death_reason = '".$_POST["frmDeathReason"]."', mother_id = '".$_POST["frmMother"]."', father_id = '".$_POST["frmFather"]."', narrative = '".$_POST["frmNarrative"]."' WHERE person_id = '".$_REQUEST["person"]."'";
					break;
				case "census":
					stamppeeps($_REQUEST["person"]);

					$query = "UPDATE ".$tblprefix."census SET schedule = '".$_POST["frmSchedule"]."', address = '".$_POST["frmAddress"]."', condition = '".$_POST["frmCondition"]."', age = '".$_POST["frmAge"]."', profession = '".$_POST["frmProfession"]."', where_born = '".$_POST["frmBirthPlace"]."' WHERE person_id = '".$_REQUEST["person"]."' AND year = '".$_REQUEST["year"]."'";
					break;
				default:
					break;
			}
			$result = mysql_query($query);
			echo "<meta http-equiv=refresh content='0; url=people.php?person=".$_REQUEST["person"]."' />\n";
			break;
		case "insert";
			switch ($_REQUEST["area"]) {
				case "marriage":
					stamppeeps($_REQUEST["person"]);
					stamppeeps($_POST["frmSpouse"]);

					if ($_REQUEST["gender"] == "M")
						$iquery = "INSERT INTO ".$tblprefix."spouses (groom_id, bride_id, marriage_date, marriage_place) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmSpouse"]."', '".$_POST["frmDate"]."', '".$_POST["frmPlace"]."')";
					else
						$iquery = "INSERT INTO ".$tblprefix."spouses (groom_id, bride_id, marriage_date, marriage_place) VALUES ('".$_POST["frmSpouse"]."', '".$_REQUEST["person"]."', '".$_POST["frmDate"]."', '".$_POST["frmPlace"]."')";
					$iresult = mysql_query($iquery);
					$person = $_REQUEST["person"];
					break;
				case "transcript":
					$iquery = "INSERT INTO ".$tblprefix."documents (person_id, doc_date, doc_title, doc_description, file_name) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmDate"]."', '".$_POST["frmTitle"]."', '".$_POST["frmDesc"]."', 'docs/".$_FILES["userfile"]["name"]."')";
					$iresult = mysql_query($iquery) or die($err_transcript);
					move_uploaded_file($_FILES["userfile"]["tmp_name"], "docs/".$_FILES["userfile"]["name"]);
					$person = $_REQUEST["person"];
					stamppeeps($person);
					break;
				case "image":
					$person = $_REQUEST["person"];
					if (processimage())
						stamppeeps($person);
					break;
				case "detail":
					$iquery = "INSERT INTO ".$tblprefix."people (person_id, name, date_of_birth, birth_place, date_of_death, death_reason, gender, mother_id, father_id, narrative, updated) VALUES ('', '".$_POST["frmName"]."', '".$_POST["frmDOB"]."', '".$_POST["frmBirthPlace"]."', '".$_POST["frmDOD"]."', '".$_POST["frmDeathReason"]."', '".$_POST["frmGender"]."', '".$_POST["frmMother"]."', '".$_POST["frmFather"]."', '".$_POST["frmNarrative"]."', NOW())";
					$iresult = mysql_query($iquery) or die($err_detail);
					$person = mysql_insert_id();
					break;
				case "census":
					stamppeeps($_REQUEST["person"]);

					$iquery = "INSERT INTO ".$tblprefix."census (person_id, year, schedule, address, condition, age, profession, where_born) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmYear"]."', '".$_POST["frmSchedule"]."', '".$_POST["frmAddress"]."', '".$_POST["frmCondition"]."', '".$_POST["frmAge"]."', '".$_POST["frmProfession"]."', '".$_POST["frmWhereBorn"]."')";
					$iresult = mysql_query($iquery) or die($err_census);

					$person = $_REQUEST["person"];
					break;
				default:
					break;
			}
			echo "<meta http-equiv=refresh content='0; url=people.php?person=".$person."' />\n";
			break;
		case "login":
			@$query = "SELECT * FROM ".$tblprefix."users WHERE username = '".$_POST["pwdUser"]."' AND password = '".md5($_POST["pwdPassword"])."'";
			$result = mysql_query($query) or die($err_logon);
			if (mysql_num_rows($result) == 1) {
				while ($row = mysql_fetch_array($result)) {
					$_SESSION["id"] = $row["id"];
					$_SESSION["name"] = $row["username"];
					if ($row["admin"] == "Y")
						$_SESSION["admin"] = 1;
					else
						$_SESSION["admin"] = 0;
				}
			}
			mysql_free_result($result);
			echo "<meta http-equiv=refresh content='0; url=index.php' />\n";
			break;
		case "logout":
			$_SESSION["id"] = 0;
			$_SESSION["name"] = "nobody";
			$_SESSION["admin"] = 0;
			echo "<meta http-equiv=refresh content='0; url=index.php' />\n";
			break;
		case "change":
			$fcheck1 = "SELECT * FROM ".$tblprefix."users WHERE id = '".$_SESSION["id"]."' AND password = '".md5($_POST["pwdOld"])."'";
			$rcheck1 = mysql_query($fcheck1) or die($err_change);
			if (mysql_num_rows($rcheck1) == 0)
				echo "<meta http-equiv=refresh content='0; url=index.php?reason=".$err_pwd_incorrect."' />\n";
			elseif ($_POST["pwdPwd1"] <> $_POST["pwdPwd2"])
				echo "<meta http-equiv=refresh content='0; url=index.php?reason=".$err_pwd_match."' />\n";
			else {
				$fchange = "UPDATE ".$tblprefix."users SET password = '".md5($_POST["pwdPwd1"])."' WHERE id = '".$_SESSION["id"]."'";
				$rchange = mysql_query($fchange) or die($err_update);
				echo "<meta http-equiv=refresh content='0; url=index.php?reason=".$err_pwd_success."' />\n";
			}
			break;
		default:
			echo "<meta http-equiv=refresh content='0; url=people.php?person=".$_POST["person"]."' />\n";
			break;
	}
	echo "</head>\n";

	// eof
?>
