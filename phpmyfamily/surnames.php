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

	// fill out the headers
	do_headers($strSurnameIndex);

?>

<table class="header" width="100%">
	<tbody>
		<tr>
			<td align="center" width="65%"><h2><?php echo $strSurnameIndex; ?></h2></td>
			<td width="35%" valign="top" align="right">
<?php
			if ($_SESSION["id"] <> 0) { ?>
				<?php echo $strLoggedIn; ?><a href="index.php" class="hd_link"><?php echo $_SESSION["name"]; ?></a>: (<a href="passthru.php?func=logout" class="hd_link"><?php echo $strLogout; ?></a><?php if ($_SESSION["admin"] == 1) echo ", <a href=\"admin.php\" class=\"hd_link\">".$strAdmin."</a>"; ?>)
<?php 		}
			else {
?>
				<?php echo $strLoggedOut; ?><a href="index.php" class="hd_link"><?php echo $strHome; ?></a>
<?php
			} ?>
			</td>
    </tr>
  </tbody>
</table>

<?php
	// provide a list of surnames
	$nquery = "SELECT surname FROM ".$tblprefix."people";
	if ($_SESSION["id"] == 0)
		$nquery .= " WHERE ".$tblprefix."people.date_of_birth < '".$restrictdate."'";
	$nquery .= " GROUP BY surname";
	$nresult = mysql_query($nquery) or die($err_person);
	echo "<hr />\n<h4 align=\"center\">";
	while ($nrow = mysql_fetch_array($nresult)) {
		echo "<a href=\"surnames.php#".$nrow["surname"]."\">".$nrow["surname"]."</a> ";
	}
	mysql_free_result($nresult);
	echo "</h4>\n";

	// make a list of people
	$squery = "SELECT person_id, name, surname, DATE_FORMAT(date_of_birth, ".$datefmt.") AS DOB, DATE_FORMAT(date_of_death, ".$datefmt.") AS DOD FROM ".$tblprefix."people";
	if ($_SESSION["id"] == 0)
		$squery .= " WHERE date_of_birth < '".$restrictdate."'";
	$squery .= " ORDER BY surname, name, date_of_birth";
	$sresult = mysql_query($squery) or die($err_person);
	$surname = "";
	echo "<hr />\n";
	while ($srow = mysql_fetch_array($sresult)) {
		if ($surname != $srow["surname"]) {
			$surname = $srow["surname"];
			$familyname = " name=\"".$surname."\"";
		} else {
			$familyname = "";
		}
		echo "<a href=\"people.php?person=".$srow["person_id"]."\"".$familyname.">".$srow["name"]."</a> (".formatdate($srow["DOB"])." - ".formatdate($srow["DOD"])."):<br />\n";
	}
	mysql_free_result($sresult);

	include "inc/footer.inc.php";

	// eof
?>
