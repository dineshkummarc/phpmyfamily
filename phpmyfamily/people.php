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

	// include the configuration parameters and function
	include "inc/config.inc.php";

	// check we have a person
	if(!isset($_REQUEST["person"])) $person = 1;
	@$person = $_REQUEST["person"];

	// the query for the database
	$pquery = "SELECT *, DATE_FORMAT(date_of_birth, ".$datefmt.") AS DOB, DATE_FORMAT(date_of_death, ".$datefmt.") AS DOD FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["person"]."'";
	$presult = mysql_query($pquery) or die($err_person);
	while ($prow = mysql_fetch_array($presult)) {

		// set security for living people (born after 01/01/1910)
		if ($_SESSION["id"] == 0 && $prow["date_of_birth"] > $restrictdate)
			$restricted = true;
		else
			$restricted = false;

		// if trying to access a restriced person
		if ($restricted)
			die(include "inc/forbidden.inc.php");

		// pickout father and mother for use in queries
		// set to -1 to avoid too many siblings!!! :-)
		$father = $prow["father_id"];
		if ($father == 0) $father = -1;
		$mother = $prow["mother_id"];
		if ($mother == 0) $mother = -1;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $clang; ?>" lang="<?php echo $clang; ?>" dir="<?php echo $dir; ?>">
<head>
<link rel="stylesheet" href="<?php echo $style; ?>" type="text/css" />
<link rel="shortcut icon" href="images/favicon.ico" />
<meta name="author" content="Simon E Booth" />
<meta name="publisher" content="Giric" />
<meta name="copyright" content="2002-2003 Simon E Booth" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
<meta name="keywords" content="Genealogy <?php echo $prow["name"]; ?><?php
	$fname = "SELECT SUBSTRING_INDEX(name, ' ', -1) AS surname FROM ".$tblprefix."people";
	if ($_SESSION["id"] == 0)
		$fname .= " WHERE date_of_birth < '".$restrictdate."' AND person_id <> '".$_REQUEST["person"]."'";
	else
		$fname .= " WHERE person_id <> '".$_REQUEST["person"]."'";
	$fname .= " GROUP BY surname LIMIT 0,16";
	$rname = mysql_query($fname) or die($err_keywords);
	if (mysql_num_rows($rname) <> 0) {
		while ($row = mysql_fetch_array($rname))
			echo " ".$row["surname"];
	}
?>" />
<meta name="description" content="<?php echo $desc; ?>" />
<meta name="page-topic" content="Genealogy" />
<meta name="audience" content="All" />
<meta name="expires" content="0" />
<meta name="page-type" content="Private homepage" />
<meta name="robots" content="INDEX,FOLLOW" />
<title><?php echo $prow["name"] ?></title>

<script language="JavaScript" type="text/javascript">
 <!--
 function confirm_delete(year, section, url) {
 	input_box = confirm(<?php echo $strConfirmDelete; ?>);
 	if (input_box == true) {
		if (section == "person") {
			// make double sure to delete a person
			person_box = confirm(<?php echo $strDoubleDelete; ?>);
			if (person_box == true)
				window.location = url;
		}
		else
 			window.location = url;
 	}
 }
 -->
</script>


</head>
<body>

<!--titles-->
	<table width="100%" class="header">
		<tr>
			<td width="65%" align="center" valign="top">
				<h2><?php echo $prow["name"] ?></h2>
				<h3><?php
					if ($restricted)
						echo "(".$restrictmsg." - ".$restrictmsg.")";
					else
						echo "(".formatdate($prow["DOB"])." - ".formatdate($prow["DOD"]).")";?></h3>
			</td>
			<td width="35%" valign="top" align="right">
				<form method="get" action="people.php">
				<?php listpeeps("person", 0, "A", $_REQUEST["person"]); ?>
				</form>
<?php
			if ($_SESSION["id"] <> 0) { ?>
				<?php echo $strLoggedIn; ?><a href="index.php" class="hd_link"><?php echo $_SESSION["name"]; ?></a>: (<a href="passthru.php?func=logout" class="hd_link"><?php echo $strLogout; ?></a><?php if ($_SESSION["admin"] == 1) echo ", <a href=\"admin.php\" class=\"hd_link\">".$strAdmin."</a>"; ?>)<br /><a href="edit.php?func=add&amp;area=detail"><?php echo $strAdd; ?></a> <?php echo $strNewPerson; ?>
<?php 		}
			else {
?>
				<?php echo $strLoggedOut; ?><a href="index.php" class="hd_link"><?php echo $strHome; ?></a>
<?php
			} ?>
			</td>
		</tr>
	</table>

<hr />

<!--links to relations table-->
	<table width="100%">
		<tr>
			<th width="85%"><h4><?php echo $strDetails; ?></h4></th>
			<td width="15%" class="tbl_odd" align="center"><?php
				if ($_SESSION["id"] <> 0) {
?><a href="pedigree.php?person=<?php echo $prow["person_id"]; ?>"><?php echo $strPedigree; ?></a>::<a href="edit.php?func=edit&amp;area=detail&amp;person=<?php echo $prow["person_id"]; ?>"><?php echo $strEdit; ?></a>::<a href="JavaScript:confirm_delete('<?php echo $prow["name"]; ?>', '<?php echo strtolower($strPerson); ?>', 'passthru.php?func=delete&amp;area=person&amp;person=<?php echo $_REQUEST["person"]; ?>')" class="delete"><?php echo $strDelete; ?></a></td>
<?php } else echo "<a href=\"pedigree.php?person=".$prow["person_id"]."\">".$strPedigree."</a></td>"; ?>
		</tr>
	</table>

<!--BDM-->
	<table>
		<tr>
			<th width="5%" valign="top"><?php echo $strBorn; ?>:</th>
			<td width="38%" class="tbl_odd" valign="top"><?php
				if ($restricted)
					echo $restrictmsg;
				else
					echo formatdate($prow["DOB"])." ".$strAt." ".$prow["birth_place"]; ?></td>
			<td class="tbl_odd" valign="top"><?php echo $strCertified; ?><input type="checkbox" name="birthcert" disabled="disabled"<?php if ($prow["birth_cert"] == "Y") echo " checked=\"checked\"" ?> /></td>
			<th width="5%" valign="top"><?php echo $strFather; ?>:</th>
			<td width="40%" class="tbl_odd" valign="top"><?php
		// the query for father
		$fquery = "SELECT *, DATE_FORMAT(date_of_birth, ".$datefmt.") AS DOB, DATE_FORMAT(date_of_death, ".$datefmt.") AS DOD FROM ".$tblprefix."people WHERE person_id = '".$father."'";
		$fresult = mysql_query($fquery) or die($err_father);
		while ($frow = mysql_fetch_array($fresult)) {
			if ($frow["date_of_birth"] > $restrictdate && $_SESSION["id"] == 0)
				// if anybody gets here they are hacking
				// or someones made a mistake with peoples parents
				echo $frow["name"]." (<font class=\"restrict\">".$strRestricted."</font>)<br />\n";
			else
				echo "<a href=\"people.php?person=".$frow["person_id"]."\">".$frow["name"]."</a>(".formatdate($frow["DOB"])." - ".formatdate($frow["DOD"]).")";
		}
		mysql_free_result($fresult);
?></td>
		</tr>
		<tr>
			<th width="5%" valign="top"><?php echo $strDied; ?>:</th>
			<td width="20%" class="tbl_odd" valign="top"><?php
			if ($restricted)
				echo $restrictmsg;
			else
				echo formatdate($prow["DOD"])." ".$strOf." ".$prow["death_reason"]; ?></td>
			<td class="tbl_odd" valign="top"><?php echo $strCertified; ?><input type="checkbox" name="deathcert" disabled="disabled"<?php if ($prow["death_cert"] == "Y") echo " checked=\"checked\""; ?> /></td>
			<th valign="top"><?php echo $strMother; ?>:</th>
			<td class="tbl_odd" valign="top"><?php
		// the query for mother
		$mquery = "SELECT *, DATE_FORMAT(date_of_birth, ".$datefmt.") AS DOB, DATE_FORMAT(date_of_death, ".$datefmt.") AS DOD FROM ".$tblprefix."people WHERE person_id = '".$mother."'";
		$mresult = mysql_query($mquery) or die($err_mother);
		while ($mrow = mysql_fetch_array($mresult)) {
			if ($mrow["date_of_birth"] > $restrictdate && $_SESSION["id"] == 0)
			// if anybody gets here they are hacking
			// or someones made a mistake with peoples parents
			echo $mrow["name"]." (<font class=\"restrict\">".$strRestricted."</font>)<br />\n";
		else
			echo "<a href=\"people.php?person=".$mrow["person_id"]."\">".$mrow["name"]."</a>(".formatdate($mrow["DOB"])." - ".formatdate($mrow["DOD"]).")";
		}
		mysql_free_result($mresult);
?></td>
		</tr>
		<tr>
			<!--Children-->
			<th valign="top"><?php echo $strChildren; ?>:</th>
			<td valign="top" class="tbl_even" colspan="2">
<?php
		// query for children
		$cquery = "SELECT *, DATE_FORMAT(date_of_birth, ".$datefmt.") AS DOB, DATE_FORMAT(date_of_death, ".$datefmt.") AS DOD FROM ".$tblprefix."people WHERE (father_id = '".$_REQUEST["person"]."' OR mother_id = '".$_REQUEST["person"]."') ORDER BY date_of_birth";
		$cresult = mysql_query($cquery) or die($err_children);
		while ($crow = mysql_fetch_array($cresult)) {
			if ($crow["date_of_birth"] > $restrictdate && $_SESSION["id"] == 0) {
?>
				<?php echo $crow["name"]; ?>(<font class="restrict"><?php echo $strRestricted; ?></font>)<br />
<?php
			}
			else {
?>
				<a href="people.php?person=<?php echo $crow["person_id"]; ?>"><?php echo $crow["name"]; ?> </a><?php echo "(".formatdate($crow["DOB"])." - ".formatdate($crow["DOD"]).")"; ?><br />
<?php
			}
		}
		mysql_free_result($cresult);
?>
			</td>
			<!--Siblings-->
			<th valign="top"><?php echo $strSiblings; ?>:</th>
			<td valign="top" class="tbl_even">
<?php
		// the query for siblings
		$squery = "SELECT *, DATE_FORMAT(date_of_birth, ".$datefmt.") AS DOB, DATE_FORMAT(date_of_death, ".$datefmt.") AS DOD FROM ".$tblprefix."people WHERE (father_id = '".$father."' OR mother_id = '".$mother."') AND person_id <> '".$_REQUEST["person"]."' ORDER BY date_of_birth";
		$sresult = mysql_query($squery) or die($err_siblings);
		while ($srow = mysql_fetch_array($sresult)) {
			if ($srow["date_of_birth"] > $restrictdate && $_SESSION["id"] == 0) {
?>
				<?php echo $srow["name"]; ?>(<font class="restrict"><?php echo $strRestricted; ?></font>)<br />
<?php
			}
			else {
?>
				<a href="people.php?person=<?php echo $srow["person_id"]; ?>"><?php echo $srow["name"]; ?></a><?php echo "(".formatdate($srow["DOB"])." - ".formatdate($srow["DOD"]).")"; ?><br />
<?php
			}
		}
		mysql_free_result($sresult);
?>
			</td>
		</tr>
	</table>

<!--marriages-->
<hr />
	<table>
		<tr>
			<th valign="top" width="5%"><?php echo $strMarried; ?>:</th>
			<td valign="top" width="80%" class="tbl_even">
<?php
		// query for weddings
		$wquery = "SELECT *, DATE_FORMAT(marriage_date, ".$datefmt.") AS DOM FROM ".$tblprefix."people, ".$tblprefix."spouses WHERE (bride_id = person_id OR groom_id = person_id) AND (groom_id = '".$_REQUEST["person"]."' OR bride_id = '".$_REQUEST["person"]."') AND person_id <> '".$_REQUEST["person"]."' ORDER BY marriage_date";
		$wresult = mysql_query($wquery) or die($err_marriage);
?>
				<table width="100%" cellspacing="0">
<?php
		while ($wrow = mysql_fetch_array($wresult)) {
?>
					<tr>
						<td width="85%" class="tbl_even"><?php
			if ($_SESSION["id"] <> 0)
				echo "<a href=\"edit.php?func=edit&amp;area=marriage&amp;person=".$_REQUEST["person"]."&amp;spouse=".$wrow["person_id"]."\">".$strEdit."</a>::<a href=\"JavaScript:confirm_delete('".$wrow["name"]."', '".strtolower($strMarriage)."', 'passthru.php?func=delete&amp;area=marriage&amp;person=".$_REQUEST["person"]."&amp;spouse=".$wrow["person_id"]."')\" class=\"delete\">".$strDelete."</a>";
			if ($wrow["date_of_birth"] > $restrictdate && $_SESSION["id"] == 0)
				echo $wrow["name"]." (<font class=\"restrict\">".$strRestricted."</font>)</td>\n";
			else
				echo " <a href=\"people.php?person=".$wrow["person_id"]."\">".$wrow["name"]."</a> ".$strOn." ".formatdate($wrow["DOM"])." ".$strAt." ".$wrow["marriage_place"]."</td>\n";
?>
						<td valign="top" class="tbl_even" width="15%" align="right"><?php echo $strCertified; ?><input type="checkbox" name="marriagecert" disabled="disabled"<?php
			if ($wrow["marriage_cert"] == "Y")
				echo " checked=\"checked\"";
?> /></td>
					</tr>
<?php
		}
		mysql_free_result($wresult);
?>
 				</table>
			</td>
			<td align="right" class="tbl_odd" valign="top"><?php
		if ($_SESSION["id"] <> 0)
			echo "<a href=\"edit.php?func=add&amp;person=".$_REQUEST["person"]."&amp;area=marriage\">".$strInsert."</a> ".$strNewMarriage; ?></td>
		</tr>
	</table>

<!--narrative-->
<hr />
	<table width="100%">
		<tr>
			<td width="95%"><h4><?php echo $strNotes; ?></h4></td>
			<td width="5%"></td>
		</tr>
	</table>
<?php
		if ($restricted)
			echo $restrictmsg."\n";
		else
			echo $prow["narrative"]."\n";
?>
<br /><br />

<!--images-->
<hr />
	<table width="100%">
		<tr>
			<td width="80%"><h4><?php echo $strGallery; ?></h4></td>
			<td align="right"><?php
		if ($_SESSION["id"] <> 0)
			echo "<a href=\"edit.php?func=add&amp;area=image&amp;person=".$_REQUEST["person"]."\">".$strUpload."</a> ".$strNewImage;
?></td>
		</tr>
	</table>
<?php show_gallery($_REQUEST["person"]); ?>

<!--census-->
<hr />
	<table width="100%">
		<tr>
			<td width="80%"><h4><?php echo $strCensusDetails; ?></h4></td>
			<td width="20%" valign="top" align="right"><?php
				if ($_SESSION["id"] <> 0)
					echo "<a href=\"edit.php?func=add&amp;area=census&amp;person=".$_REQUEST["person"]."\">".$strInsert."</a> ".$strNewCensus; ?></td>
		</tr>
	</table>

<?php
		if ($restricted)
			echo $restrictmsg."\n";
		else {
			$cquery = "SELECT * FROM ".$tblprefix."census, ".$tblprefix."census_years WHERE person_id = '".$_REQUEST["person"]."' AND census = census_id ORDER BY year";
			$cresult = mysql_query($cquery) or die($err_census_ret);
			if (mysql_num_rows($cresult) == 0)
				echo $strNoInfo."\n";
			else {
?>
	<table width="100%">
		<tr>
			<td></td>
			<th><?php echo $strYear; ?></th>
			<th><?php echo $strSchedule; ?></th>
			<th><?php echo $strAddress; ?></th>
			<th><?php echo $strCondition; ?></th>
			<th><?php echo $strAge; ?></th>
			<th><?php echo $strProfession; ?></th>
			<th><?php echo $strBirthPlace; ?></th>
			<th><?php echo $strDetails; ?></th>
		</tr>
<?php
		$i = 0;
		while ($crow = mysql_fetch_array($cresult)) {
			if ($i == 0 || fmod($i, 2) == 0)
				$class = "tbl_odd";
			else
				$class = "tbl_even";
?>
		<tr>
			<td class="<?php echo $class; ?>"><?php
							if ($_SESSION["id"] <> 0)
								echo "<a href=\"edit.php?func=edit&amp;area=census&amp;person=".$_REQUEST["person"]."&amp;census=".$crow["census_id"]."\">".$strEdit."</a>::<a href=\"JavaScript:confirm_delete('".$crow["year"]." (".$crow["country"].")', '".strtolower($strCensus)."', 'passthru.php?func=delete&amp;area=census&amp;person=".$_REQUEST["person"]."&amp;census=".$crow["census"]."')\" class=\"delete\">".$strDelete."</a>";
?></td>
			<td class="<?php echo $class; ?>"><?php echo $crow["year"]; ?> (<?php echo $crow["country"]; ?>)</td>
			<td class="<?php echo $class; ?>"><?php echo $crow["schedule"]; ?></td>
			<td class="<?php echo $class; ?>"><?php echo $crow["address"]; ?></td>
			<td class="<?php echo $class; ?>"><?php echo $crow["condition"]; ?></td>
			<td class="<?php echo $class; ?>"><?php echo $crow["age"]; ?></td>
			<td class="<?php echo $class; ?>"><?php echo $crow["profession"]; ?></td>
			<td class="<?php echo $class; ?>"><?php echo $crow["where_born"]; ?></td>
			<td class="<?php echo $class; ?>"><?php echo $crow["other_details"]; ?></td>
		</tr>
<?php
			$i++;
		}
		mysql_free_result($cresult);
?>
	</table>
<?php
			}
		}
?>

<!--document transcripts-->
<hr />
	<table width="100%">
		<tr>
			<td width="80%"><h4><?php echo $strDocTrans; ?></h4></td>
			<td width="20%" valign="top" align="right"><?php
				if ($_SESSION["id"] <> 0)
					echo "<a href=\"edit.php?func=add&amp;area=transcript&amp;person=".$_REQUEST["person"]."\">".$strUpload."</a> ".$strNewTrans; ?></td>
		</tr>
	</table>
<?php
		if ($restricted)
			echo $restrictmsg."\n";
		else {
			$dquery = "SELECT *, DATE_FORMAT(doc_date, $datefmt) AS ddate FROM ".$tblprefix."documents WHERE person_id = '".$_REQUEST["person"]."'";
			$dresult = mysql_query($dquery) or die($err_trans);
			if (mysql_num_rows($dresult) == 0) {
?>
	<?php echo $strNoInfo; ?><br />
<?php
			}
			else {
?>
	<table>
		<tr>
			<td></td>
			<th width="30%"><?php echo $strTitle; ?></th>
			<th width="50%"><?php echo $strDesc; ?></th>
			<th width="10%"><?php echo $strDate; ?></th>
		</tr>
<?php
					$i = 0;
					while ($drow = mysql_fetch_array($dresult)) {
						if ($i == 0 || fmod($i, 2) == 0)
							$class = "tbl_odd";
						else
							$class = "tbl_even";
?>
		<tr>
<?php
	if ($_SESSION["id"] <> 0) {
?>
			<td class="<?php echo $class; ?>"><a href="JavaScript:confirm_delete('<?php echo $drow["doc_title"]; ?>', '<?php echo strtolower($strTranscript); ?>', 'passthru.php?func=delete&amp;area=transcript&amp;person=<?php echo $_REQUEST["person"]; ?>&amp;transcript=<?php echo $drow["file_name"]; ?>')" class="delete"><?php echo $strDelete; ?></a></td>
<?php
	} else {
?>
			<td class="<?php echo $class; ?>"></td>
<?php
	}
?>
			<td class="<?php echo $class; ?>"><a href="<?php echo $drow["file_name"]; ?>"><?php echo $drow["doc_title"]; ?></a></td>
			<td class="<?php echo $class; ?>"><?php echo $drow["doc_description"]; ?></td>
			<td class="<?php echo $class; ?>"><?php echo formatdate($drow["ddate"]); ?></td>
		</tr>
<?php
					$i++;
					}
?>
	</table>
<br /><?php echo $strRightClick; ?><br />
<?php
			}
			mysql_free_result($dresult);
		}
	}

	mysql_free_result($presult);

	include "inc/footer.inc.php";
	
	// eof
?>
