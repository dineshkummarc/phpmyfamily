<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2005  Simon E Booth (simon.booth@giric.com)

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
	do_headers($strGallery);

?>

<script language="JavaScript" type="text/javascript">
 <!--
 function confirm_delete(year, section, url) {
 	input_box = confirm(<?php echo $strConfirmDelete; ?>);
 	if (input_box == true) {
		window.location = url;
 	}
 }
 -->
</script>

<table class="header" width="100%">
	<tbody>
		<tr>
			<td align="center" width="65%"><h2><?php echo $strGallery; ?></h2></td>
			<td width="35%" valign="top" align="right"><?php user_opts(); ?></td>
    </tr>
  </tbody>
</table>

<?php
	$query = "SELECT ".$tblprefix."people.*, DATE_FORMAT(date_of_birth, ".$datefmt.") AS DOB, DATE_FORMAT(date_of_death, ".$datefmt.") AS DOD FROM ".$tblprefix."people, ".$tblprefix."images WHERE ".$tblprefix."people.person_id = ".$tblprefix."images.person_id";
	if ($_SESSION["id"] == 0)
		$query .= " AND ".$tblprefix."people.date_of_birth < '".$restrictdate."'";
	$query .= " GROUP BY ".$tblprefix."people.person_id ORDER BY ".$tblprefix."people.date_of_birth";
	$result = mysql_query($query) or die(mysql_error());
	//die($err_person);
	while ($row = mysql_fetch_array($result)) {
		echo "<hr />\n";
		echo "<h4><a href=\"people.php?person=".$row["person_id"]."\">".$row["name"]." ".$row["suffix"]."</a> ";
		if ($row["date_of_birth"] != "0000-00-00" && $row["date_of_death"] != "0000-00-00") {
			echo "(".$row["DOB"]." - ".$row["DOD"].")";
		} elseif ($row["date_of_birth"] != "0000-00-00") {
			echo "(".$strBorn." ".$row["DOB"].")";
		} elseif ($row["date_of_death"] != "0000-00-00") {
			echo "(".$strDied." ".$row["DOD"].")";
		}
		echo "</h4>\n";
		show_gallery($row["person_id"], "gallery");
	}
	mysql_free_result($result);

	include "inc/footer.inc.php";

	// eof
?>
