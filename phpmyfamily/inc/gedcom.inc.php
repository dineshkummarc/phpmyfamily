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

	$gedname = $_FILES["gedfile"]["name"];
	$gedfile = $_FILES["gedfile"]["tmp_name"];
	$gedarray = array();
	$people = array();
	$indi = array();
	$family = array();
	$marriage = array();
	$text = array();
	$desc = array();
	$pref = array();
	$blocks = 0;

	// pick up the next auto value from table
	$query = "SHOW TABLE STATUS LIKE '".$tblprefix."people'";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result))
		$autoval = $row["Auto_increment"];

	ini_set("auto_detect_line_endings", TRUE);

	function make_date($incoming) {
		// define the months
		$months = array(1 => "JAN",
						2 => "FEB",
						3 => "MAR",
						4 => "APR",
						5 => "MAY",
						6 => "JUN",
						7 => "JUL",
						8 => "AUG",
						9 => "SEP",
						10 => "OCT",
						11 => "NOV",
						12 => "DEC");
		// how long is the date string
		$length = strlen($incoming);
		$work = explode(" ", $incoming);

		// if the first part is not numeric, then we don't really know what to do!...
		if (!is_numeric($work[1])) {
			$retval = "0000-00-00";
				// ...if it isn't, check it's not a month
				if (in_array($work[1], $months))
					$retval = $work[2]."-".str_pad(array_search($work[1], $months), 2, "0", STR_PAD_LEFT)."-00";
		} else {
			// ...if it is, see if it's a year (anybody back to 31AD is a bit buggered)..
			if ($work[1] > 31) {
				$retval = $work[1]."-00-00";
			} else {
				// ...so it must be a day
				$retval = $work[3]."-".str_pad(array_search($work[2], $months), 2, "0", STR_PAD_LEFT)."-".str_pad($work[1], 2, "0", STR_PAD_LEFT);
			}
		}

		// check that the returning string isn't going to break the database
		// must be 0000-00-00
		if (!ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $retval))
			$retval = "0000-00-00";

		// return the string
		return $retval;
	}

?>

<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h3>Importing GedCom file: <?php echo $gedname; ?></h3>  </td>
    </tr>
  </tbody>
</table>

<br>

<!--Check we have a valid file-->
Checking uploaded file is valid:
<?php
	if (!empty($gedfile) && file_exists($gedfile)) {
		echo " OK<br>\n";
	} else {
		echo " NO!<br>\n";
		die("Error with gedcom file");
	}
?>

<!--Read the file into an array-->
Reading in file:<br>
<?php
	$handle = fopen ($gedfile, "r");
	while (!feof ($handle)) {
    	$buffer = fgets($handle, 4096);
		// need to trim the annoying newline
		// from end of buffer
		if (substr($buffer, 0, 1) == "0")
			$blocks++;
		$gedarray[$blocks][] = rtrim($buffer);
	}
	fclose ($handle);
	echo ".....read ".($blocks - 1)." blocks<br>\n";

	$heads = 0;
	$indis = 0;
	$notes = 0;
	$fams  = 0;
	$trlrs = 0;
	$subns = 0;
	$subms = 0;
	$sours = 0;
	$repos = 0;
	$objes = 0;
	$unkno = 0;
	for ($i = 1; $i < $blocks; $i++) {
		switch (substr($gedarray[$i][0], -4)) {
			case "HEAD":
				$heads++;
				break;
			case "INDI":
				$indis++;
				$people[$indis] = $gedarray[$i];
				break;
			case "NOTE":
				$notes++;
				$text[$notes] = $gedarray[$i];
				break;
			case " FAM":
				$fams++;
				$family[$fams] = $gedarray[$i];
				break;
			case "TRLR":
				$trlrs++;
				break;
			case "SUBN":
				$subns++;
				break;
			case "SUBM":
				$subms++;
				break;
			case "SOUR":
				$sours++;
				break;
			case "REPO":
				$repos++;
				break;
			case "OBJE":
				$objes++;
				break;
			default:
				$unkno++;
				break;
		}
	}

	echo ".....read ".$heads." HEADS<br>\n";
	echo ".....read ".$indis." INDIS<br>\n";
	echo ".....read ".$fams." FAMS<br>\n";
	echo ".....read ".$notes." NOTES<br>\n";
	echo ".....read ".$subns." SUBNS<br>\n";
	echo ".....read ".$subms." SUBMS<br>\n";
	echo ".....read ".$sours." SOURS<br>\n";
	echo ".....read ".$objes." OBJES<br>\n";
	echo ".....read ".$repos." REPOS<br>\n";
	echo ".....read ".$trlrs." TRLRS<br>\n";
	if ($unkno > 0) echo ".....read ".$unkno." UNKNOWS<br>\n";
?>

<!--parse the array-->
Verifying GedCom data:
<?php
	if (in_array("1 GEDC", $gedarray[1]) && in_array("2 VERS 5.5", $gedarray[1]))
		echo " OK - correct version header found<br>\n";
	else {
		print_r($gedarray[1]);
		die ("Doesn't seem to be a valid GedCom file!");
	}
?>

<!--parse the notes first-->
Parsing notes data:
<?php

	// process each notes block in turn
	for ($i = 1; $i < $notes; $i++) {
		$count = count($text[$i]);

		// process each line ine the block
		for ($c = 0; $c < $count; $c++) {

			if ($c == 0)
				$temp = substr($text[$i][$c], 3, strlen($text[$i][$c]) - 9);

			switch (substr($text[$i][$c], 0, 6)) {
				case "1 CONC":
				case "1 CONT":
					if (array_key_exists($temp, $desc))
						$desc[$temp] = $desc[$temp]."\n".substr($text[$i][$c], 6, strlen($text[$i][$c]) - 6);
					else
						$desc[$temp] = substr($text[$i][$c], 6, strlen($text[$i][$c]) - 6);
					break;
				default:
					break;
			}
		}
	}
	echo " OK<br>\n";
?>

<!--parse the individual arrays-->
Parsing individual data:
<?php

	// process each individual in turn
	for ($i = 1; $i <= $indis; $i++) {
		$count = count($people[$i]);

		$indi[$i]["person_id"] = ($autoval + $i - 1);
		$previous = "";
		// process each record in turn
		for ($c = 0; $c < $count; $c++) {

			if ($c == 0) {
				$temp = substr($people[$i][$c], 3, strlen($people[$i][$c]) - 9);
				$indi[$i]["ged_person_ref"] = $temp;
				$pref[$temp] = ($autoval + $i -1);
			}
			switch (substr($people[$i][$c], 0, 6)) {
				case "1 NAME":
					$indi[$i]["name"] = str_replace("/", "", substr($people[$i][$c], 6, strlen($people[$i][$c]) - 6));
					break;
				case "1 SEX ":
					$indi[$i]["gender"] = substr($people[$i][$c], 6, strlen($people[$i][$c]) - 6);
					break;
				case "2 DATE":
					if ($previous == "1 BIRT") {
						$indi[$i]["dob"] = make_date(substr($people[$i][$c], 6, strlen($people[$i][$c]) - 6));
					} elseif ($previous == "1 DEAT") {
						$indi[$i]["dod"] = make_date(substr($people[$i][$c], 6, strlen($people[$i][$c]) - 6));
					}
					break;
				case "2 PLAC":
					if ($previous == "1 BIRT") {
						$indi[$i]["birth_place"] = substr($people[$i][$c], 6, strlen($people[$i][$c]) - 6);
					}
					break;
				case "1 NOTE":
					if (substr($people[$i][$c], 7, 1) == "@") {
						$temp = substr($people[$i][$c], 8, strlen($people[$i][$c]) - 9);
						$indi[$i]["note"] = $desc[$temp];
					}
					else
						$indi[$i]["note"] = substr($people[$i][$c], 6, strlen($people[$i][$c]) - 6);
					break;
				case "1 FAMC":
					$indi[$i]["ged_famc"] = substr($people[$i][$c], 8, strlen($people[$i][$c]) - 9);
					break;
				case "1 FAMS":
					$indi[$i]["ged_fams"] = substr($people[$i][$c], 8, strlen($people[$i][$c]) - 9);
					break;
				default:
					break;
			}

			if (substr($people[$i][$c], 0, 1) == "1") $previous = substr($people[$i][$c], 0, 6);
		}
	}

	echo " OK<br>\n";
?>

<!--Parsing family data-->
Parsing marriage data:
<?php

	// process each family in turn
	for ($i = 1; $i <= $fams; $i++) {
		$count = count($family[$i]);

		// process each row in turn
		$previous = "";
		for ($c = 0; $c < $count; $c++) {
			if ($c == 0)
				$marriage[$i]["ged_fam_ref"] = substr($family[$i][$c], 3, strlen($family[$i][$c]) - 8);
			switch (substr($family[$i][$c], 0, 6)) {
				case "1 HUSB":
					$temp = substr($family[$i][$c], 8, strlen($family[$i][$c]) - 9);
					$marriage[$i]["groom_id"] = $pref[$temp];
					$marriage[$i]["ged_husb_ref"] = $temp;
					break;
				case "1 WIFE":
					$temp = substr($family[$i][$c], 8, strlen($family[$i][$c]) - 9);
					$marriage[$i]["bride_id"] = $pref[$temp];
					$marriage[$i]["ged_wife_ref"] = $temp;
					break;
				case "2 DATE":
					if ($previous == "1 MARR")
						$marriage[$i]["dom"] = make_date(substr($family[$i][$c], 6, strlen($family[$i][$c]) - 6));
					break;
				case "2 PLAC":
					if ($previous == "1 MARR")
						$marriage[$i]["place"] = substr($family[$i][$c], 6, strlen($family[$i][$c]) - 6);
					break;
				default:
					break;
			}
			if (substr($family[$i][$c], 0, 1) == "1") $previous = substr($family[$i][$c], 0, 6);
		}
	}

	echo " OK<br>\n";
?>

<!--parentage-->
Parsing parentage data:
<?php

	for ($i = 1; $i <= $fams; $i++) {
		$count = count($family[$i]);

		$father_ref = 0;
		$mother_ref = 0;
		$father_id = 0;
		$mother_id = 0;
		// process each row in turn
		for ($c = 0; $c < $count; $c++) {
			switch (substr($family[$i][$c], 0, 6)) {
				case "1 HUSB":
					$father_ref = substr($family[$i][$c], 8, strlen($family[$i][$c]) - 9);
					$father_id = $pref[$father_ref];
					break;
				case "1 WIFE":
					$mother_ref = substr($family[$i][$c], 8, strlen($family[$i][$c]) - 9);
					$mother_id = $pref[$mother_ref];
					break;
				case "1 CHIL":
					$temp = substr($family[$i][$c], 8, strlen($family[$i][$c]) - 9);
					for ($m = 1; $m < $indis; $m++) {
						if ($indi[$m]["ged_person_ref"] == $temp) {
							$indi[$m]["father_id"] = $father_id;
							$indi[$m]["mother_id"] = $mother_id;
						}
					}
					break;
				default:
					break;
			}
		}
	}
	echo " OK<br>\n";
?>
<!--Sort and insert person data-->
Inserting person data:
<?php

	for ($i = 1; $i <= $indis; $i++) {
		// do some sanity checking
		if (!array_key_exists("name", $indi[$i]))
			$indi[$i]["name"] = "";
		if (!array_key_exists("dob", $indi[$i]))
			$indi[$i]["dob"] = "0000-00-00";
		if (!array_key_exists("birth_place", $indi[$i]))
			$indi[$i]["birth_place"] = "";
		if (!array_key_exists("dod", $indi[$i]))
			$indi[$i]["dod"] = "0000-00-00";
		if (!array_key_exists("gender", $indi[$i]))
			$indi[$i]["gender"] = "";
		if (!array_key_exists("note", $indi[$i]))
			$indi[$i]["note"] = "";
		if (!array_key_exists("father_id", $indi[$i]))
			$indi[$i]["father_id"] = "0";
		if (!array_key_exists("mother_id", $indi[$i]))
			$indi[$i]["mother_id"] = "0";

		$query = "INSERT INTO ".$tblprefix."people (person_id, name, date_of_birth, birth_place, date_of_death, gender, mother_id, father_id, narrative) VALUES ('".$indi[$i]["person_id"]."', '".htmlspecialchars($indi[$i]["name"], ENT_QUOTES)."', '".$indi[$i]["dob"]."', '".htmlspecialchars($indi[$i]["birth_place"], ENT_QUOTES)."', '".$indi[$i]["dod"]."', '".$indi[$i]["gender"]."', '".$indi[$i]["mother_id"]."', '".$indi[$i]["father_id"]."', '".htmlspecialchars($indi[$i]["note"], ENT_QUOTES)."')";

		$result = mysql_query($query) or die("Error inserting person");
	}
	echo " OK<br>\n";
?>

<!--sort and insert marriages-->
Inserting marriage data:
<?php

	for ($i = 1; $i <= $fams; $i++) {
		if (!array_key_exists("groom_id", $marriage[$i]))
			break;
		if (!array_key_exists("bride_id", $marriage[$i]))
			break;
		if (!array_key_exists("dom", $marriage[$i]))
			$marriage[$i]["dom"] = "0000-00-00";
		if (!array_key_exists("place", $marriage[$i]))
			$marriage[$i]["place"] = "";

		$query = "INSERT INTO ".$tblprefix."spouses (groom_id, bride_id, marriage_date, marriage_place) VALUES ('".$marriage[$i]["groom_id"]."', '".$marriage[$i]["bride_id"]."', '".$marriage[$i]["dom"]."', '".htmlspecialchars($marriage[$i]["place"], ENT_QUOTES)."')";

		$result = mysql_query($query) or die("Error inserting marriage");
	}
	echo " OK<br>\n";
?>

Done!!!!!!(Phew)<br>
<br>
Click <a href="index.php">here</a> to return to the homepage.<br>
