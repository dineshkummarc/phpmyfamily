<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2005  Simon E Booth (simon.booth@giric.com)
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
	include "modules/db/DAOFactory.php";

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
1 FILE $filename
";//end of header

	}	// end of ged_header()

	function add_row($level, $tag, $data, $date = false) {
		$output = false; 	
		if ($date) {
			if($data <> "0000-00-00") {
				$output = true;
				$data = ged_date($data);
			}
		} 
		if ($data <> "") {
			$output = true;
		}
		if ($output) {
			echo $level." ".$tag." ".$data."\r\n";
		}
	}
	
	function ged_indi($gedperson) {
		global $datefmt, $tblprefix;

		$peep = new PersonDetail();
		$peep->person_id = $gedperson;
		$peep->queryType = Q_IND;
		$dao = getPeopleDAO();
		$dao->getPersonDetails($peep);
		if ($peep->numResults != 1) {
			die("error");
		}
		$per = $peep->results[0];
	
		// if trying to access a restriced person
		if (!$per->isExportable()) {
			die(include "inc/forbidden.inc.php");
		}
		
		// deal with gedcom INDIviduals	 
		$i = 0;

		//reformat updated database field to gedcom dd MMM yyyy
		$exploded = explode("/", $per->updated);
		$updated = "$exploded[2]-$exploded[1]-$exploded[0]";

		echo "0 @".$per->person_id."@ INDI\r";
		add_row("1", "NAME", $per->name->forenames. "/".$per->name->surname."/");
		add_row("2", "GIVN", $per->name->forenames);
		add_row("2", "SURN", $per->name->surname);
		add_row("1", "SEX", $per->gender);
		if ($per->date_of_birth <> "0000-00-00" or $per->birth_place->place <> "") {
			echo "1 BIRT\n";
		}
		add_row("2", "DATE", $per->date_of_birth, true);
		add_row("2", "PLAC", $per->birth_place->place);
		if ($per->date_of_death <> "0000-00-00" or $per->death_reason <> "") {
			echo "1 DEAT\n";
		}
		add_row("2", "DATE", $per->date_of_death, true);
		add_row("2", "CAUS", $per->death_reason);
	
		if ($per->mother_id <> "00000" and $per->father_id <> "00000") {
			echo "1 FAMC @".$per->father->person_id.$per->mother->person_id."@\n";
		}
		if ($per->narrative <> "") {
			//Textfield could have Returns, separate them in gedcom CONT lines
			$narrative = ereg_replace("\n","2 CONT ",$per->narrative);
			add_row("1", "NOTE", $narrative);
		}
		echo "1 CHAN\n";
		add_row("2", "DATE", $updated, true);
		$i ++;

		ged_photos($gedperson);

		ged_docs($gedperson);
		
		echo "1 SUBM @phpmyfamily@\n";

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
						//write DATE only when present
						add_row("2", "DATE", $srow["marriage_date"], true);
						add_row("2", "PLAC", $srow["marriage_place"]);
						
						//write DIVorce data only if not empty
						if ($srow["divorce_date"] <> "" or $srow["divorce_reason"] <> "") {
							echo "1 DIV\n";
							echo $srow["divorce_date"];
							add_row("2", "DATE", $srow["divorce_date"], true);
							add_row("2", "CAUS", $srow["divorce_reason"]);
						}
					}
					$l ++;
				}
			}
			$j ++;
		}

	}	// end of ged_fam()

	// function: ged_photos
	function ged_photos($gedperson) {
		//find photos
		$img = new Image();
		if (isset($gedperson)) {
			$startlevel = 1;
			$img->person->person_id = $gedperson;
		} else {
			$startlevel = 0;
		}
		$dao = getImageDAO();
		$dao->getImages($img);
		
		foreach($img->results AS $image) {
			echo $startlevel." OBJE @img".$image->image_id."@\n";
			echo $startlevel+1;
			echo " FILE $absurl".$image->getImageFile();
			echo $startlevel+1;
			echo " FORM jpg\n";
			add_row($startlevel+1, "TITL", $image->title);
		}
	}	// end of ged_photos()

	// function: ged_docs
	function ged_docs($gedcom) {
		//find documents
		$trans = new Transcript();
		if (isset($gedperson)) {
			$startlevel = 1;
			$trans->person->person_id = $gedperson;
		} else {
			$startlevel = 0;
		}
		$trans->person->person_id = $gedperson;
		$dao = getTranscriptDAO();
		$dao->getTranscripts($trans);
		
		foreach ($trans->results AS $tran) {
			echo $startlevel." SOUR @doc".$tran->transcript_id."@\n";
			//TITL not allowed here
			add_row($startlevel + 1, "TEXT", $tran->description);
			//FILE not allowed here
			/*TODO This was in the original code need to check which is correct
			echo "0 @doc".$prow["id"]."@ SOUR\n";
			add_row("1", "TITL", $prow["doc_title"]);
			add_row("1", "TEXT", $prow["doc_description"]);
			echo "1 OBJE\n";
			echo "2 FILE $absurl"."documents/".$prow["file_name"]);
			*/
		}
		//find documents
	}	// end of ged_docs()

	// function: ged_trailer
	function ged_trailer() {
		$config = Config::getInstance();
		//Standard submitter, as the data is stored in the database an comes from one source
		//EMAIL and WWW are new in gedcom 5.5.1, for now disabled
		echo "0 @phpmyfamily@ SUBM\n";
		echo "1 NAME phpmyfamily /$config->desc/\n";
		echo "1 ADDR phpmyfamily\n";//required by specification to have a valid ADDR
		echo "2 CONT phpmyfamily\n"; //required by specification to have a valid ADDR
		//echo "1 EMAIL $email\n";
		//echo "1 WWW $absurl\n";

		// End of Gedcom File	
		echo "0 TRLR\n";
	}	// end of ged_trailer()

	$person = mysql_real_escape_string($_REQUEST["person"]);
	$filename = "gedcom.ged";

	header("Content-Location: ".$filename);
	header("Content-Type: application/unknown");
	header("Content-Disposition: attachment; filename=\"".$filename."\"");
	header("Content-Transfer-Encoding: binary");
	ged_header($filename);
	ged_indi($person);
	ged_trailer();
?>
