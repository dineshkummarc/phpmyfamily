<?php  
//phpmyfamily - opensource genealogy webbuilder
//Copyright (C) 2002 - 2004  Simon E Booth (simon.booth@giric.com)
//GedcomExport (C) 2004 Geltmar von Buxhoeveden (geltmar@gmx.net)
//Modified 2009 Ian Wright idwright@users.sourceforge.net
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
include_once "modules/db/DAOFactory.php";

ini_set("auto_detect_line_endings", TRUE);

function full_ged_date1($event) {
	$date = "";
	if ($event->date1 <> "0000-00-00") {
		$date = "2 DATE ";
		$mod = "";
		switch($event->date1_modifier) {
        	case 9:		//	"In"
        	case 1:		//  "About"
				$mod = "ABT ";
            break;
        	case 2:		// "Circa"
        	case 3:		// "Estimated
        	case 4:		// "Roughly"
				$mod = "EST ";
            break;
            case 5:		// Calculated
                $mod = "CAL ";
             break;
             case 6:		// "Before"
                $mod = "BEF ";
             break;
             case 7:		// "After"
                $mod = "AFT ";
             break;
		}
		$date .= $mod;
		$date .= ged_date($event->date1);
		$date .= "\n";
	}
	return($date);
}
//convert date from yyyy-mm-dd database format to dd MMM yyyy gedcom format
function ged_date($incoming) {
	// define the months
	$months = array ("00" => "00", "01" => "JAN", "02" => "FEB", "03" => "MAR", "04" => "APR", "05" => "MAY", "06" => "JUN", "07" => "JUL", "08" => "AUG", "09" => "SEP", "10" => "OCT", "11" => "NOV", "12" => "DEC");
	// explode date
	$work = explode("-", $incoming);
	$retval = "";
	if (count($work) > 1) {
		// if  month or day unknown, just return year
		if ($work[1] == "00" OR $work[2] == "00") {
			$retval = "$work[0]";
		} elseif ($work[1] != "00" AND $work[2] == "00") {
            // year and month are known
			$replacemonth = strtr($work[1], $months);
			$retval = "$replacemonth $work[0]";
		} else {
			// reformat whole date to dd MMM yyyy
			$replacemonth = strtr($work[1], $months);
			$retval = "$work[2] $replacemonth $work[0]";
		}
	}
	// return the string for gedcom DATE
	return $retval;
}

$config = Config::getInstance();

$filename="$config->desc.ged";
	header("Content-Location: ".$filename);
	header("Content-Type: application/unknown");
	header("Content-Disposition: attachment; filename=\"".$filename."\"");
	header("Content-Transfer-Encoding: binary");
// write gedcom header
$today = date("d M Y");
echo "0 HEAD
1 SOUR phpmyfamily
2 VERS $version
2 CORP phpmyfamily.sourceforge.net
3 ADDR http://www.phpmyfamily.net
1 DEST any		
1 DATE $today
1 SUBM @phpmyfamily@
1 GEDC
2 VERS 5.5
2 FORM Lineage-Linked
1 CHAR ASCII
1 FILE $config->desc.ged\n";//end of header

// deal with gedcom INDIviduals	 

$famarray = array();

$peep = new PersonDetail();
$peep->setFromRequest();
if(isset($peep->person_id)) {
	$peep->queryType = Q_IND;
} else {
	$peep->queryType = Q_TYPE;
}
$dao = getPeopleDAO();
$dao->getPersonDetails($peep, "print_person");

function output_ged($level, $tag, $data, $suffix = "") {
	echo $level." ".$tag.html_entity_decode($data, ENT_QUOTES).$suffix."\n";
}

#while ($row = mysql_fetch_array($result)) {
function print_person($search, $per) { 
	global $famarray;
	
	// if trying to access a restriced person
	if (!$per->isExportable()) {
		return;
	}
	$config = Config::getInstance();
	
	//get surename right for gedcom NAME field
	//if there is just one name filled in name and surname
	$surname = "";
	if ($per->name->link != "") {
		$surname = $per->name->link;
	}
	$surname .= $per->name->surname;
	if ($per->name->suffix != "") {
		$surname .= $per->name->suffix;
	}
	if ($per->name->forenames != ""){
		$ged_name = $per->name->forenames." /".$surname."/";
	} else {
		$ged_name = $surname;
	}
	//If this person was previously imported from a gedcom file then use that id
	$id = $per->person_id;
	$gdao = getGedcomDAO();
	$gedids = $gdao->getReferences($id);
	if (count($gedids) === 1) {
		$id = $gedids[0]["gedrefid"];
	}
	output_ged(0, "@",$id,"@ INDI");
	output_ged(1, "NAME ",$ged_name);
	output_ged(2, "GIVN ",$per->name->forenames);
	if ($per->name->link != "") { output_ged(2, "SPFX ",$per->name->link); }
	output_ged(2, "SURN ",$surname);
	if ($per->name->title != "") { output_ged(2, "NPFX ",$per->name->title); }
	if ($per->name->suffix != "") { output_ged(2, "NSFX ",$per->name->suffix); }
	if ($per->name->knownas != "") { output_ged(2, "NICK ",$per->name->knownas); }
	output_ged(1, "SEX ",$per->gender);

	
	$edao = getEventDAO();
	$e = new Event();
	$e->person->person_id = $per->person_id;
	$edao->getEvents($e, Q_BD, true);
	
	
	
	$sdao = getSourceDAO();
	$classes = array("1 BIRT","1 BAPM","1 DEAT","1 BURI");
	foreach ($e->results AS $e) {
		if ($e->hasData()) {
			echo $classes[$e->type]."\n";
			$events[$e->type] = $e;
#			$sdao->getEventSources($e);
			echo full_ged_date1($e);
			if ($e->location->place != "") {
				output_ged(2, "PLAC ",$e->location->place);
			}
			if ($e->type == DEATH_EVENT && $per->death_reason != "") {
				 output_ged(2, "CAUS ",$per->death_reason);
			}
			if ($e->notes != "") {
				echo "2 NOTE\n" ;
				output_ged(3, "CONT ",$e->notes);
    			if ($e->source != "") {
    				output_ged(2, "SOUR ",$e->source->title);
    			} else {
    				output_ged(3, "SOUR ","phpmyfamily");
                }
			}
		}
	}
	
	if ($per->mother->person_id <> "00000" and $per->father->person_id <> "00000") {
		$famc = $per->father->person_id.$per->mother->person_id;
		output_ged(1, "FAMC @",$famc."@");
		if (!array_key_exists($famc, $famarray)) {
			$famarray[$famc] = array();
		}
		$famarray[$famc][] = $per;
	}
	
	if ($per->narrative <> "") {
		//Textfield could have Returns, separate them in gedcom CONT lines
		$narrative = ereg_replace("\n","\n2 CONT ",$per->narrative);
		output_ged(1, "NOTE ",$narrative);
	}
	$exploded = explode(" ", $per->updated);
	$updated = ged_date($exploded[0]);
	echo "1 CHAN\n";
	output_ged(2, "DATE ",$updated);
	//find photos
	$eid = -1; 
	$sid = -1;
	$images = new Image();
	$images->person = $per;
	$idao = getImageDAO();
	$idao->getImages($images);
	
	for ($current = 0; $current < $images->numResults; $current++) {
			$img = $images->results[$current];
			output_ged(1, "OBJE @img",$img->image_id,"@");
			output_ged(2, "FILE ",$config->absurl.$img->getImageFile());
			output_ged(2, "FORM ","jpg");
			output_ged(2, "TITL ",$img->title);
	}
	
	$trans = new Transcript();
	$trans->person = $per;
	$tdao = getTranscriptDAO();
	$tdao->getTranscripts($trans);
	for ($i=0; $i < $trans->numResults; $i++) {
		$doc = $trans->results[$i];
		output_ged(1, "SOUR @doc",$doc->transcript_id,"@");
		//TITL not allowed here
		output_ged(2, "TEXT ",$doc->description);
		//FILE not allowed here
	}
	echo "1 SUBM @phpmyfamily@\n";
	
} //end of INDIvidual

// Gedcom Families
foreach ($famarray as $famc => $fam) {
	output_ged(0, "@",$famc,"@ FAM");
	output_ged(1, "HUSB @",$fam[0]->father->person_id,"@");
	output_ged(1, "WIFE @",$fam[0]->mother->person_id,"@");
	foreach ($fam as $child) {
		output_ged(1, "CHIL @",$child->person_id,"@");
	}
	
	$search = new Relationship();
	$search->person->person_id = $fam[0]->father->person_id;
	$dao = getRelationsDAO();
	$dao->getRelationshipDetails($search);
	for($i=0; $i < $search->numResults; $i++) {
		$rel = $search->results[$i];
		if ($rel->relation->person_id == $fam[0]->mother->person_id) {
			//write MARR line if an (even otherwise empty) record is found
				echo "1 MARR\n";
				//write DATE only when present
				echo full_ged_date1($rel->event);
				//write place only when present
				if ($rel->marriage_place <> "") {
					output_ged(2, "PLAC ",$rel->marriage_place->place);
				}
				$div = "";
				if ($rel->dissolve_date <> "0000-00-00") {
					output_ged(2, "DATE ",ged_date($rel->dissolve_date));
				}
				if ($rel->dissolve_reason <> "") {
					output_ged(2, "CAUS ",$rel->dissolve_reason);
				}
				if ($div != "") {
					output_ged(1, "DIV","");
				}
		}
	}
}

//insert gedcom multimedia objects (this is deprecated since gedcom 5.5.1 to reduce file size)
//just the empty objects and references are exported to make some older applications happy
//photos are now just FILE entries in an INDI record and point to a web resource

/*
//find photos
$pquery = "SELECT * FROM ".$tblprefix."images";
$presult = mysql_query($pquery) or die(mysql_error());
$pnumber = mysql_num_rows($presult);
$p = 0;
while ($p < $pnumber) {
	$prow = mysql_fetch_array($presult);
	output_ged(0, "@img",$prow["image_id"]."@ OBJE\n";
	//insertion of binary data would be here in gedcom < 5.5.1
	//output_ged(1, "FILE $absurl","images/".$prow["image_id"].".jpg\n";
	//echo "2 FORM jpg\n";
	//output_ged(2, "TITL ",$prow["title"]);
	//the above three lines are for 5.5.1, for now just fill the title
	output_ged(1, "TITL ",$prow["title"]);
	$p ++;
}

//find documents
$pquery = "SELECT * FROM ".$tblprefix."documents";
$presult = mysql_query($pquery) or die(mysql_error());
$pnumber = mysql_num_rows($presult);
$p = 0;
while ($p < $pnumber) {
	$prow = mysql_fetch_array($presult);
	output_ged(0, "@doc",$prow["id"]."@ SOUR\n";
	output_ged(1, "TITL ",$prow["doc_title"]);
	output_ged(1, "TEXT ",$prow["doc_description"]);
	echo "1 OBJE\n";
	output_ged(2, "FILE $absurl","documents/".$prow["file_name"]);
	$p ++;
}
*/
//find documents
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
?>