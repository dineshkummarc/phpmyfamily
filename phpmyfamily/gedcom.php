<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2004  Simon E Booth (simon.booth@giric.com)
	//GedcomExport (C) 2004 Geltmar von Buxhoeveden (geltmar@gmx.net)

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

	// include the configuration parameters and function
	include "inc/config.inc.php";
	ini_set("auto_detect_line_endings", TRUE);

	//convert date from yyyy-mm-dd database format to dd MMM yyyy gedcom format
	function ged_date($incoming) {
		// define the months
		$months = array ("00" => "00", "01" => "JAN", "02" => "FEB", "03" => "MAR", "04" => "APR", "05" => "MAY", "06" => "JUN", "07" => "JUL", "08" => "AUG", "09" => "SEP", "10" => "OCT", "11" => "NOV", "12" => "DEC");
		// explode date
		$work = explode("-", $incoming);
		// if  month or day unknown, just return year
		if ($work[1] == "00" OR $work[2] == "00") {
			$retval = "$work[0]";
		} else {
			// reformat whole date to dd MMM yyyy
			$replacemonth = strtr($work[1], $months);
			$retval = "$work[2] $replacemonth $work[0]";
		}
		// return the string for gedcom DATE
		return $retval;
	}	// end of ged_date()

	//this one is from www.php.net
	function unique_multi_array($array, $sub_key) {
		$target = array ();
		$existing_sub_key_values = array ();
		foreach ($array as $key => $sub_array) {
			if (!in_array($sub_array[$sub_key], $existing_sub_key_values)) {
				$existing_sub_key_values[] = $sub_array[$sub_key];
				$target[$key] = $sub_array;
			}
		}
		return $target;
	}	// end of unique_multi_array()

	function ged_header($filename) {
		global $version;

		$today = date("d M Y");

		// output the header
		echo "0 HEAD
1 SOUR phpmyfamily
2 VERS $version
2 CORP phpmyfamily.sourceforge.net
3 ADDR http://www.phpmyfamily.net/
1 DEST any		
1 DATE $today
1 SUBM @phpmyfamily@
1 GEDC
2 VERS 5.5
2 FORM Lineage-Linked
1 CHAR ASCII
1 FILE $filename\n";//end of header

	}	// end of ged_header()

	function ged_indi($gedperson) {
		global $datefmt, $tblprefix;

		// deal with gedcom INDIviduals	 
		$query = "SELECT person_id, name, surname, gender, date_of_birth, birth_place, birth_cert, date_of_death, death_reason, death_cert, DATE_FORMAT(updated, ".$datefmt.") AS ddate, mother_id, father_id , narrative FROM ".$tblprefix."people WHERE person_id = '".$gedperson."'";
		$result = mysql_query($query) or die("Error, selecting people table from database!");
		$i = 0;
		while ($row = mysql_fetch_array($result)) {
			//reformat updated database field to gedcom dd MMM yyyy
			$exploded = explode("/", $row["ddate"]);
			$updated = "$exploded[2]-$exploded[1]-$exploded[0]";
			//get surename right for gedcom NAME field
			//if there is just one name filled in name and surname
			if ($row['surname'] == $row['name']){
				$ged_name = $row['name']." /".$row['surname']."/";
				$givn_name = $row["name"];
			}
			//this is to isolate the surname from the name, which might have more than a one word enty
			else{
				$repname = "/".$row['surname']."/";
				$ged_name = ereg_replace($row['surname'], $repname, $row['name']);
				$givn_name = ereg_replace($row['surname'], "", $row['name']);
			}
			echo "0 @".$row["person_id"]."@ INDI\n";
			echo "1 NAME ".$ged_name."\n";
			echo "2 GIVN ".$givn_name."\n";
			echo "2 SURN ".$row["surname"]."\n";
			echo "1 SEX ".$row["gender"]."\n";
			if ($row["date_of_birth"] <> "0000-00-00" or $row["birth_place"] <> "") {
				echo "1 BIRT\n";
			}
			if ($row["date_of_birth"] <> "0000-00-00"){
				echo "2 DATE ".ged_date($row["date_of_birth"])."\n";
			}
			if ($row["birth_place"] <> "") {
				echo "2 PLAC ".$row["birth_place"]."\n";
			}
			if ($row["date_of_death"] <> "0000-00-00" or $row["death_reason"] <> "") {
				echo "1 DEAT\n";
			}
			if ($row["date_of_death"] <> "0000-00-00"){	
				echo "2 DATE ".ged_date($row["date_of_death"])."\n";
			}
			if ($row["death_reason"] <> "") {
				echo "2 CAUS ".$row["death_reason"]."\n";
			}
			if ($row["mother_id"] <> "00000" and $row["father_id"] <> "00000") {
				echo "1 FAMC @".$row["father_id"].$row["mother_id"]."@\n";
			}
			if ($row["narrative"] <> "") {
				//Textfield could have Returns, separate them in gedcom CONT lines
				$narrative = ereg_replace("\n","2 CONT ",$row["narrative"]);
				echo "1 NOTE ".$narrative."\n";
			}
			echo "1 CHAN\n";
			echo "2 DATE ".ged_date($updated)."\n";
			$i ++;

			//find photos
			$pquery = "SELECT * FROM ".$tblprefix."images WHERE person_id = '".$gedperson."'";
			$presult = mysql_query($pquery) or die(mysql_error());
			$pnumber = mysql_num_rows($presult);
			$p = 0;
			while ($p < $pnumber) {
				$prow = mysql_fetch_array($presult);
				if ($row["person_id"] == $prow["person_id"]) {
					echo "1 OBJE @img".$prow["image_id"]."@\n";
					echo "2 FILE $absurl"."images/".$prow["image_id"].".jpg\n";
					echo "2 FORM jpg\n";
					echo "2 TITL ".$prow["title"]."\n";
				}
			$p ++;
			}

			//find documents
			$pquery = "SELECT * FROM ".$tblprefix."documents WHERE person_id = '".$gedperson."'";
			$presult = mysql_query($pquery) or die(mysql_error());
			$pnumber = mysql_num_rows($presult);
			$p = 0;
			while ($p < $pnumber) {
				$prow = mysql_fetch_array($presult);
				if ($row["person_id"] == $prow["person_id"]) {
					echo "1 SOUR @doc".$prow["id"]."@\n";
					//TITL not allowed here
					echo "2 TEXT ".$prow["doc_description"]."\n";
					//FILE not allowed here
				}
				$p ++;
			}
			echo "1 SUBM @phpmyfamily@\n";
		} //end of INDIvidual

	}	// end of ged_indi();

	function ged_fam() {
		global $tblprefix;

		// Gedcom Families
		$mquery = "SELECT * FROM ".$tblprefix."people";
		$mresult = mysql_query($mquery) or die(mysql_error());
		$number = mysql_num_rows($result);
		$i = 0;
		while ($i < $number) {
			$mrow = mysql_fetch_array($mresult);
			$person = $mrow["person_id"];
			$father = $mrow["father_id"];
			$mother = $mrow["mother_id"];
			$family = $mrow["father_id"].$mrow["mother_id"];
			$name = $mrow["name"];
			$famarr[$i] = array ($family, $person, $father, $mother, $name);
			$i ++;
		}
		$j = 0;
		//get uniq families from concatenated mother_id and father_id
		while ($j < $number) {
			$famuniq = unique_multi_array($famarr, 0);
			//empty entries are returned by unique_multi_array as well, filter them, leave out unknown father and mother
			if ($famuniq[$j] <> "" and $famuniq[$j][0] != "0000000000") {
				//write the gedcom FAM line
				echo "0 @".$famuniq[$j][0]."@ FAM\n";
				//get back father and mother from FAM to write HUSB and WIFE lines
				$splitfam = $famuniq[$j][0];
				$splitfather = substr($splitfam, 0, 5);
				$splitmother = substr($splitfam, 5, 9);
				if ($splitfather != "00000") {
					echo "1 HUSB @".$splitfather."@\n";
				}
				if ($splitmother != "00000") {
					echo "1 WIFE @".$splitmother."@\n";
				}
				$k = 0;
				//walk through whole array again to find children
				while ($k < $number) {
					//child found, write gedcom FAMC line, leave out children with unknown parents
					if ($famuniq[$j][0] == $famarr[$k][0] and $famuniq[$j][0] <> "0000000000") {
						echo "1 CHIL @".$famarr[$k][1]."@\n";
					}
				$k ++;
				}
				//find marriages
				$squery = "SELECT * FROM ".$tblprefix."spouses";
				$sresult = mysql_query($squery) or die(mysql_error());
				$snumber = mysql_num_rows($sresult);
				$l = 0;
				while ($l < $snumber) {
					$srow = mysql_fetch_array($sresult);
					//find father in spouses table
					if ($splitfather == $srow["groom_id"]) {
						//write MARR line if an (even otherwise empty) record is found
						echo "1 MARR\n";
						//write DATE only when predent
						if ($srow["marriage_date"] <> "0000-00-00") {
							echo "2 DATE ".ged_date($srow["marriage_date"])."\n";
						}
						//write place only when present
						if ($srow["marriage_place"] <> "") {
							echo "2 PLAC ".$srow["marriage_place"]."\n";
						}
						//write DIVorce data only if not empty
						if ($srow["divorce_date"] <> "" or $srow["divorce_reason"] <> "") {
							echo "1 DIV\n";
							echo $srow["divorce_date"];
							if ($srow["divorce_date"] <> "0000-00-00") {
								echo "2 DATE ".ged_date($srow["divorce_date"])."\n";
							}
							if ($srow["divorce_reason"] <> "") {
								echo "2 CAUS ".$srow["divorce_reason"]."\n";
							}
						}
					}
					$l ++;
				}
			}
			$j ++;
		}

	}	// end of ged_fam()

	// function: ged_photos
	function ged_photos() {
		$pquery = "SELECT * FROM ".$tblprefix."images";
		$presult = mysql_query($pquery) or die(mysql_error());
		$pnumber = mysql_num_rows($presult);
		$p = 0;
		while ($p < $pnumber) {
			$prow = mysql_fetch_array($presult);
			echo "0 @img".$prow["image_id"]."@ OBJE\n";
			//insertion of binary data would be here in gedcom < 5.5.1
			//echo "1 FILE $absurl"."images/".$prow["image_id"].".jpg\n";
			//echo "2 FORM jpg\n";
			//echo "2 TITL ".$prow["title"]."\n";
			//the above three lines are for 5.5.1, for now just fill the title
			echo "1 TITL ".$prow["title"]."\n";
			$p ++;
		}
	}	// end of ged_photos()

	// function: ged_docs
	function ged_docs() {
		//find documents
		$pquery = "SELECT * FROM ".$tblprefix."documents";
		$presult = mysql_query($pquery) or die(mysql_error());
		$pnumber = mysql_num_rows($presult);
		$p = 0;
		while ($p < $pnumber) {
			$prow = mysql_fetch_array($presult);
			echo "0 @doc".$prow["id"]."@ SOUR\n";
			echo "1 TITL ".$prow["doc_title"]."\n";
			echo "1 TEXT ".$prow["doc_description"]."\n";
			echo "1 OBJE\n";
			echo "2 FILE $absurl"."documents/".$prow["file_name"]."\n";
			$p ++;
		}
		//find documents
	}	// end of ged_docs()

	// function: ged_trailer
	function ged_trailer() {
		//Standard submitter, as the data is stored in the database an comes from one source
		//EMAIL and WWW are new in gedcom 5.5.1, for now disabled
		echo "0 @phpmyfamily@ SUBM\n";
		echo "1 NAME phpmyfamily /$desc/\n";
		echo "1 ADDR phpmyfamily\n";//required by specification to have a valid ADDR
		echo "2 CONT phpmyfamily\n"; //required by specification to have a valid ADDR
		//echo "1 EMAIL $email\n";
		//echo "1 WWW $absurl\n";

		// End of Gedcom File	
		echo "0 TRLR\n";
	}	// end of ged_trailer()

	$person = $_REQUEST["person"];
	$filename = "gedcom.ged";

	header("Content-Location: ".$filename);
	header("Content-Type: application/unknown");
	header("Content-Disposition: attachment; filename=\"".$filename."\"");
	header("Content-Transfer-Encoding: binary");
	ged_header($filename);
	ged_indi($person);
	ged_trailer();
?>
