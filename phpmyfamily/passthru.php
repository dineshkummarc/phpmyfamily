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

	// set-time out to be long for image upload
	ini_set("max_execution_time", 180);

	// if you are not logged in then you shouldn't be here!
	if ($_SESSION["id"] == 0 && ($_REQUEST["func"] != "login" && $_REQUEST["func"] != "lang"))
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
					@$frmMCert = $_POST["frmMCert"];
					if ($frmMCert == "")
						$frmMCert = "N";

					if ($_REQUEST["gender"] == "M")
						$query = "UPDATE ".$tblprefix."spouses SET bride_id = '".$_POST["frmSpouse"]."', marriage_date = '".$_POST["frmDate"]."', marriage_cert = '".$frmMCert."', marriage_place = '".htmlspecialchars($_POST["frmPlace"], ENT_QUOTES)."' WHERE groom_id = '".$_REQUEST["person"]."' AND bride_id = '".$_REQUEST["oldspouse"]."'";
					else
						$query = "UPDATE ".$tblprefix."spouses SET groom_id = '".$_POST["frmSpouse"]."', marriage_date = '".$_POST["frmDate"]."', marriage_cert = '".$frmMCert."', marriage_place = '".htmlspecialchars($_POST["frmPlace"], ENT_QUOTES)."' WHERE bride_id = '".$_REQUEST["person"]."' AND groom_id = '".$_REQUEST["oldspouse"]."'";
					break;
				case "detail":
					// get the certificate status
					@$frmBCert = $_POST["frmBCert"];
					if ($frmBCert == "")
						$frmBCert = "N";
					@$frmDCert = $_POST["frmDCert"];
					if ($frmDCert == "")
						$frmDCert = "N";
					$query = "UPDATE ".$tblprefix."people SET name = '".htmlspecialchars($_POST["frmName"], ENT_QUOTES)."', date_of_birth = '".$_POST["frmDOB"]."', birth_cert = '".$frmBCert."', birth_place = '".htmlspecialchars($_POST["frmBirthPlace"], ENT_QUOTES)."', date_of_death = '".$_POST["frmDOD"]."', death_cert = '".$frmDCert."', death_reason = '".htmlspecialchars($_POST["frmDeathReason"], ENT_QUOTES)."', gender = '".$_POST["frmGender"]."', mother_id = '".$_POST["frmMother"]."', father_id = '".$_POST["frmFather"]."', narrative = '".add_quotes($_POST["frmNarrative"])."' WHERE person_id = '".$_REQUEST["person"]."'";
					break;
				case "census":
					stamppeeps($_REQUEST["person"]);

					$query = "UPDATE ".$tblprefix."census SET schedule = '".htmlspecialchars($_POST["frmSchedule"], ENT_QUOTES)."', address = '".htmlspecialchars($_POST["frmAddress"], ENT_QUOTES)."', condition = '".$_POST["frmCondition"]."', age = '".$_POST["frmAge"]."', profession = '".htmlspecialchars($_POST["frmProfession"], ENT_QUOTES)."', where_born = '".htmlspecialchars($_POST["frmBirthPlace"], ENT_QUOTES)."', other_details = '".add_quotes($_POST["frmDetails"])."' WHERE person_id = '".$_REQUEST["person"]."' AND census = '".$_REQUEST["census"]."'";
					break;
				default:
					break;
			}
			$result = mysql_query($query) or die($err_person_update);
			echo "<meta http-equiv=refresh content='0; url=people.php?person=".$_REQUEST["person"]."' />\n";
			break;
		case "insert";
			switch ($_REQUEST["area"]) {
				case "marriage":
					stamppeeps($_REQUEST["person"]);
					stamppeeps($_POST["frmSpouse"]);
					@$frmMCert = $_POST["frmMCert"];
					if ($frmMCert == "")
						$frmMCert = "N";
					if ($_REQUEST["gender"] == "M")
						$iquery = "INSERT INTO ".$tblprefix."spouses (groom_id, bride_id, marriage_date, marriage_cert, marriage_place) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmSpouse"]."', '".$_POST["frmDate"]."', '".$frmMCert."', '".htmlspecialchars($_POST["frmPlace"], ENT_QUOTES)."')";
					else
						$iquery = "INSERT INTO ".$tblprefix."spouses (groom_id, bride_id, marriage_date, marriage_cert, marriage_place) VALUES ('".$_POST["frmSpouse"]."', '".$_REQUEST["person"]."', '".$_POST["frmDate"]."', '".$frmMCert."', '".htmlspecialchars($_POST["frmPlace"], ENT_QUOTES)."')";
					$iresult = mysql_query($iquery) or die($err_marriage_insert);
					$person = $_REQUEST["person"];
					break;
				case "transcript":
					$iquery = "INSERT INTO ".$tblprefix."documents (person_id, doc_date, doc_title, doc_description, file_name) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmDate"]."', '".htmlspecialchars($_POST["frmTitle"], ENT_QUOTES)."', '".htmlspecialchars($_POST["frmDesc"], ENT_QUOTES)."', 'docs/".$_FILES["userfile"]["name"]."')";
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
					// get the certificate status
					@$frmBCert = $_POST["frmBCert"];
					if ($frmBCert == "")
						$frmBCert = "N";
					@$frmDCert = $_POST["frmDCert"];
					if ($frmDCert == "")
						$frmDCert = "N";
					$iquery = "INSERT INTO ".$tblprefix."people (person_id, name, date_of_birth, birth_cert, birth_place, date_of_death, death_cert, death_reason, gender, mother_id, father_id, narrative, updated) VALUES ('', '".htmlspecialchars($_POST["frmName"], ENT_QUOTES)."', '".$_POST["frmDOB"]."', '".$frmBCert."', '".htmlspecialchars($_POST["frmBirthPlace"], ENT_QUOTES)."', '".$_POST["frmDOD"]."', '".$frmDCert."', '".htmlspecialchars($_POST["frmDeathReason"], ENT_QUOTES)."', '".$_POST["frmGender"]."', '".$_POST["frmMother"]."', '".$_POST["frmFather"]."', '".add_quotes($_POST["frmNarrative"])."', NOW())";
					$iresult = mysql_query($iquery) or die($err_detail);
					$person = mysql_insert_id();
					break;
				case "census":
					stamppeeps($_REQUEST["person"]);

					$iquery = "INSERT INTO ".$tblprefix."census (person_id, census, schedule, address, condition, age, profession, where_born, other_details) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmYear"]."', '".htmlspecialchars($_POST["frmSchedule"], ENT_QUOTES)."', '".htmlspecialchars($_POST["frmAddress"], ENT_QUOTES)."', '".$_POST["frmCondition"]."', '".$_POST["frmAge"]."', '".htmlspecialchars($_POST["frmProfession"], ENT_QUOTES)."', '".htmlspecialchars($_POST["frmWhereBorn"], ENT_QUOTES)."', '".add_quotes($_POST["frmDetails"])."')";
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
		case "delete":
			switch ($_REQUEST["area"]) {
				case "census":
					stamppeeps($_REQUEST["person"]);
					$dquery = "DELETE FROM ".$tblprefix."census WHERE person_id = '".$_REQUEST["person"]."' AND census = '".$_REQUEST["census"]."'";
					$dresult = mysql_query($dquery) or die($err_census_delete);
					echo "<meta http-equiv=refresh content='0; url=people.php?person=".$_REQUEST["person"]."' />\n";
					break;
				case "image":
					if (@unlink("images/tn_".$_REQUEST["image"].".jpg") && @unlink("images/".$_REQUEST["image"].".jpg")){
						$dquery = "DELETE FROM ".$tblprefix."images WHERE image_id = '".$_REQUEST["image"]."'";
						$dresult = mysql_query($dquery) or die($err_image_delete);
						stamppeeps($_REQUEST["person"]);
					}
					echo "<meta http-equiv=refresh content='0; url=people.php?person=".$_REQUEST["person"]."' />\n";
					break;
				case "marriage":
					stamppeeps($_REQUEST["person"]);
					stamppeeps($_REQUEST["spouse"]);
					$dquery = "DELETE FROM ".$tblprefix."spouses WHERE (groom_id = '".$_REQUEST["person"]."' AND bride_id = '".$_REQUEST["spouse"]."') OR (groom_id = '".$_REQUEST["spouse"]."' AND bride_id = '".$_REQUEST["person"]."')";
					$dresult = mysql_query($dquery) or die($err_marriage_delete);
					echo "<meta http-equiv=refresh content='0; url=people.php?person=".$_REQUEST["person"]."' />\n";
					break;
				case "transcript":
					if (@unlink($_REQUEST["transcript"])) {
						$dquery = "DELETE FROM ".$tblprefix."documents WHERE person_id = '".$_REQUEST["person"]."' AND file_name = '".$_REQUEST["transcript"]."'";
						$dresult = mysql_query($dquery) or die($err_trans_delete);
						stamppeeps($_REQUEST["person"]);
					}
					echo "<meta http-equiv=refresh content='0; url=people.php?person=".$_REQUEST["person"]."' />\n";
					break;
				case "person":
					// there's a lot to do here
					// delete transcripts
					$squery = "SELECT * FROM ".$tblprefix."documents WHERE person_id = '".$_REQUEST["person"]."'";
					$sresult = mysql_query($squery) or die($err_trans);
					while ($srow = mysql_fetch_array($sresult)) {
						if (@unlink($srow["file_name"])) {
							$dquery = "DELETE FROM ".$tblprefix."documents WHERE person_id = '".$srow["person_id"]."' AND file_name = '".$srow["file_name"]."'";
							$dresult = mysql_query($dquery) or die($err_trans_delete);
						} else die($err_trans_file);
					}
					mysql_free_result($sresult);

					// delete censuses
					$dcquery = "DELETE FROM ".$tblprefix."census WHERE person_id = '".$_REQUEST["person"]."'";
					$dcresult = mysql_query($dcquery) or die($err_census_delete);

					// delete images
					$squery = "SELECT * FROM ".$tblprefix."images WHERE person_id = '".$_REQUEST["person"]."'";
					$sresult = mysql_query($squery) or die($err_images);
					while ($srow = mysql_fetch_array($sresult)) {
						if (@unlink("images/tn_".$srow["image_id"].".jpg") && @unlink("images/".$srow["image_id"].".jpg")){
							$dquery = "DELETE FROM ".$tblprefix."images WHERE image_id = '".$srow["image_id"]."'";
							$dresult = mysql_query($dquery) or die($err_image_delete);
						} else die ($err_image_file);
					}
					mysql_free_result($sresult);

					// delete marriages
					$dmquery = "DELETE FROM ".$tblprefix."spouses WHERE groom_id = '".$_REQUEST["person"]."' OR bride_id = '".$_REQUEST["person"]."'";
					$dmresult = mysql_query($dmquery) or die($err_marriage_delete);

					// update children to point to the right person
					$ucquery = "UPDATE ".$tblprefix."people SET mother_id = '0' WHERE mother_id = '".$_REQUEST["person"]."'";
					$ucresult = mysql_query($ucquery) or die($err_child_update);
					$ucquery = "UPDATE ".$tblprefix."people SET father_id = '0' WHERE father_id = '".$_REQUEST["person"]."'";
					$ucresult = mysql_query($ucquery) or die($err_child_update);

					// finally, the person
					$dpquery = "DELETE FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["person"]."'";
					$dpresult = mysql_query($dpquery) or die($err_person_delete);

					// have to go to index, cos don't know where else to go
					echo "<meta http-equiv=refresh content='0; url=index.php' />\n";
					break;
				default:
					break;
			}
			break;
		case "lang":
			$_SESSION["lang"] = "lang/".$_REQUEST["trans"].".inc.php";
			@$page = $_SERVER["HTTP_REFERER"];
			if ($page == "")
				echo "<meta http-equiv=refresh content='0; url=index.php' />\n";
			else
				echo "<meta http-equiv=refresh content='0; url=".$page."' />\n";
			break;
		default:
			echo "<meta http-equiv=refresh content='0; url=people.php?person=".$_POST["person"]."' />\n";
			break;
	}
	echo "</head>\n";

	// eof
?>
