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

	@$census = $_REQUEST["census"];
	@$ref = $_REQUEST["ref"];

	// Fill out the headers
	do_headers($strCensusDetails.": ".$ref);

	if(isset($_SERVER["HTTP_REFERER"]))
		$referer = $_SERVER["HTTP_REFERER"];
	else
		$referer = "index.php";

	$caption = "";
	$tquery = "SELECT * FROM ".$tblprefix."census_years WHERE census_id = '".$census."'";
	$tresult = mysql_query($tquery);
	while ($trow = mysql_fetch_array($tresult)) {
		$caption = $trow["country"]." (".$trow["year"].")";
	}

?>

<table class="header" width="100%">
	<tbody>
		<tr>
			<td align="center" width="65%"><h2><?php echo $strCensusDetails.": ".$ref; ?></h2></td>
			<td width="35%" valign="top" align="right"><?php user_opts(); ?></td>
    </tr>
  </tbody>
</table>

<hr />

<table width="100%">
	<tbody>
		<tr>
			<th colspan="7"><?php echo $caption; ?></th>
		</tr>
		<tr>
			<th><?php echo $strName; ?></th>
			<th><?php echo $strAddress; ?></th>
			<th><?php echo $strCondition; ?></th>
			<th><?php echo $strAge; ?></th>
			<th><?php echo $strProfession; ?></th>
			<th><?php echo $strBirthPlace; ?></th>
			<th><?php echo $strDetails; ?></th>
		</tr>

<?php

	$cquery = "SELECT ".$tblprefix."census.*, ".$tblprefix."people.name, ".$tblprefix."people.suffix FROM ".$tblprefix."census, ".$tblprefix."people WHERE ".$tblprefix."census.person_id = ".$tblprefix."people.person_id AND ".$tblprefix."census.schedule = '".$ref."' AND ".$tblprefix."census.census = '".$census."'";
	if ($_SESSION["id"] == 0)
		$cquery .= " AND ".$tblprefix."people.date_of_birth < '".$restrictdate."'";
	$cquery .= " ORDER BY ".$tblprefix."census.address, ".$tblprefix."census.age DESC";
	$cresult = mysql_query($cquery) or die("OOOOOOOpppppppssss");
	$i = 0;
	while($crow = mysql_fetch_array($cresult)) {
		if ($i == 0 || fmod($i, 2) == 0)
			$class = "tbl_odd";
		else
			$class = "tbl_even";
?>
		<tr>
			<td class="<?php echo $class; ?>"><a href="people.php?person=<?php echo $crow["person_id"]; ?>"><?php echo $crow["name"]." ".$crow["suffix"]; ?></a></td>
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
?>

	</tbody>
</table>

<?php

	echo "<p><a href=\"".$referer."\">".$strBack."</a> ".$strToHome."</p>\n";

	include "inc/footer.inc.php";

	// eof
?>
