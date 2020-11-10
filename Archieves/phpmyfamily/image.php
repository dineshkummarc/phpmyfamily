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

	//get the details for the image
	$iquery = "SELECT *, DATE_FORMAT(date, ".$datefmt.") AS ddate FROM ".$tblprefix."images WHERE image_id = ".quote_smart($_REQUEST["image"]);
	$iresult = mysql_query($iquery) or die($err_image);

	// when we have an image, get the associated person details
	while ($irow = mysql_fetch_array($iresult)) {
		$pquery = "SELECT name, suffix, date_of_birth FROM ".$tblprefix."people WHERE person_id = '".$irow["person_id"]."'";
		$presult = mysql_query($pquery) or die($err_person);
		while ($prow = mysql_fetch_array($presult)) {

			// check security
			if ($_SESSION["id"] == 0 && $prow["date_of_birth"] > $restrictdate)
				$restricted = true;
			else
				$restricted = false;

			// if we don't have a suffix
			// don't want brackets messed up
			if ($prow["suffix"] != "")
				$suffix = " ".$prow["suffix"];
			else
				$suffix = "";

			// fill out the header
			do_headers($irow["title"]." (".$prow["name"].$suffix.")");
?>

<table class="header" width="100%">
  <tbody>
    <tr>
      <td align="center"><h2><?php echo $irow["title"]; ?></h2></td>
    </tr>
  </tbody>
</table>


<hr />

<a href="people.php?person=<?php echo $irow["person_id"]; ?>">Return to <?php echo $prow["name"]." ".$suffix; ?></a>

<?php
		}
		mysql_free_result($presult);
		$size = getimagesize("images/".$irow["image_id"].".jpg");
?>
<div align="center"><img src="<?php echo "images/".$irow["image_id"].".jpg"; ?>" alt="<?php echo $irow["description"]; ?>" <?php echo $size[3]; ?> /></div>

<div align="center">
	<p><?php if ($restricted)
		echo $restrictmsg;
	else
		echo formatdate($irow["ddate"]); ?></p>
	<p><?php echo $irow["description"]; ?></p>
</div>

<br /><br />
<?php
	}
	mysql_free_result($iresult);

	include "inc/footer.inc.php";
	
	// eof
?>
