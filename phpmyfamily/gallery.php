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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $clang; ?>" lang="<?php echo $clang; ?>" dir="<?php echo $dir; ?>">
<head>
<link rel="stylesheet" href="<?php echo $style; ?>" type="text/css" />
<link rel="shortcut icon" href="images/favicon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
<title><?php echo $strGallery; ?></title>

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

</head>
<body>

<table class="header" width="100%">
  <tbody>
    <tr>
      <td align="center"><h2><?php echo $strGallery; ?></h2></td>
    </tr>
  </tbody>
</table>

<?php
	$query = "SELECT ".$tblprefix."people.* FROM ".$tblprefix."people, ".$tblprefix."images WHERE ".$tblprefix."people.person_id = ".$tblprefix."images.person_id";
	if ($_SESSION["id"] == 0)
		$query .= " AND ".$tblprefix."people.date_of_birth < '".$restrictdate."'";
	$query .= " GROUP BY ".$tblprefix."people.person_id ORDER BY ".$tblprefix."people.date_of_birth";
	$result = mysql_query($query) or die(mysql_error());
	//die($err_person);
	while ($row = mysql_fetch_array($result)) {
		echo "<h4><a href=\"people.php?person=".$row["person_id"]."\">".$row["name"]."</a></h4>\n";
		show_gallery($row["person_id"], "gallery");
	}
	mysql_free_result($result);

	include "inc/footer.inc.php";

	// eof
?>
