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

	// Fill out the headers
	do_headers("phpmyfamily: ".$desc);
	
?>

<table width="100%" class="header">
	<tr>
		<td width="65%" align="center">
			<h1>phpmyfamily</h1>
			<h3><?php echo $desc; ?></h3>
		</td>
		<td width="35%" valign="top" align="right">
			<form method="get" action="people.php">
				<?php listpeeps("person"); ?>
			</form>
<?php if ($_SESSION["id"] <> 0) { ?>
			<?php echo $strLoggedIn; ?><?php echo $_SESSION["name"]; ?>: (<a href="passthru.php?func=logout" class="hd_link"><?php echo $strLogout; ?></a><?php if ($_SESSION["admin"] == 1) echo ", <a href=\"admin.php\" class=\"hd_link\">".$strAdmin."</a>"; ?>)<br /><a href="edit.php?func=add&amp;area=detail"><?php echo $strAdd; ?></a> <?php echo $strNewPerson; ?>
<?php } ?>
		</td>
	</tr>
</table>

<hr />

<table width="100%">
	<tr>
		<td width="33%" valign="top">
<?php
			// include login form if not logged in
			if ($_SESSION["id"] == 0)
				include "inc/loginform.inc.php";
			else
				include "inc/passwdform.inc.php";
?>

			<br /><br />
				<table>
					<tr>
						<th colspan="2"><?php echo $strStats; ?></th>
					</tr>
					<tr>
						<th width="200"><?php echo $strArea; ?></th>
						<th width="50"><?php echo $strNo; ?></th>
					</tr>
					<tr>
						<td class="tbl_odd"><a href="surnames.php"><?php echo ucwords($strOnFile); ?></a></td>
						<td class="tbl_odd" align="right">
<?php
					$query = "SELECT count(*) as number FROM ".$tblprefix."people";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
?>
						</td>
					</tr>
					<tr>
						<td class="tbl_even"><?php echo $strCensusRecs; ?></td>
						<td class="tbl_even" align="right">
<?php
					$query = "SELECT count(*) as number FROM ".$tblprefix."census";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
?>
						</td>
					</tr>
					<tr>
						<td class="tbl_odd"><a href="gallery.php"><?php echo $strImages; ?></a></td>
						<td class="tbl_odd" align="right">
<?php
					$query = "SELECT count(*) as number FROM ".$tblprefix."images WHERE image_id <> '10000'";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
?>
						</td>
					</tr>
					<tr>
						<td class="tbl_even"><?php echo $strDocTrans; ?></td>
						<td class="tbl_even" align="right">
<?php
					$query = "SELECT count(*) as number FROM ".$tblprefix."documents";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
?>
						</td>
					</tr>
				</table>

			</td>
			<td width="33%" valign="top">
				<table width="100%">
					<tr>
						<th><?php echo $strRandomImage; ?></th>
					</tr>
					<tr>
						<td	class="tbl_odd" align="center">
<?php
	$iquery = "SELECT ".$tblprefix."images.*, ".$tblprefix."people.name FROM ".$tblprefix."images, ".$tblprefix."people WHERE ".$tblprefix."images.person_id = ".$tblprefix."people.person_id	";
	if ($_SESSION["id"] == 0)
		$iquery .= " AND date_of_birth < '".$restrictdate."'";
	$iquery .= " ORDER BY RAND() LIMIT 0,1";
	$iresult = mysql_query($iquery) or die(mysql_error());
	while($irow = mysql_fetch_array($iresult)) {
		echo "\t\t\t\t\t\t\t<a href=\"people.php?person=".$irow["person_id"]."\"><img src=\"images/tn_".$irow["image_id"].".jpg\" width=\"100\" height=\"100\" border=\"0\" title=\"".$irow["description"]."\" alt=\"".$irow["description"]."\" /><br />".$irow["name"]."</a>";
	}
?>
						</td>
					</tr>
				</table>
				<table width="100%">
					<thead>
						<tr>
							<th scope="col" colspan="3"><?php echo $strUpcoming; ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?php echo $strBirths; ?></th>
							<th><?php echo $strDate; ?></th>
							<th><?php echo $strAnniversary; ?></th>
						</tr>
<?php
	// find out what the current day and month are.
	$nquery = "SELECT DAYOFMONTH(NOW()) AS day, MONTH(NOW()) AS month";
	$nresult = mysql_query($nquery) or die("Errrororororororo");
	while ($nrow = mysql_fetch_array($nresult)) {
		$day = $nrow["day"];
		$month = $nrow["month"];
	}
	mysql_free_result($nresult);

	// get the births in the next n days
	$bquery = "SELECT *, CONCAT_WS('-', YEAR(NOW()), LPAD(MONTH(date_of_birth), 2, '0'), LPAD(DAYOFMONTH(date_of_birth), 2, '0')) AS year_birth, DATE_FORMAT(date_of_birth, ".$datefmt.") AS DOB FROM ".$tblprefix."people";
	if ($_SESSION["id"] == 0)
		$bquery .= " WHERE date_of_birth < '".$restrictdate."'";
	$bquery .= " HAVING year_birth >= NOW() AND year_birth <= date_add(NOW(), INTERVAL 21 DAY) ORDER BY year_birth LIMIT 0,6";
	$bresult = mysql_query($bquery) or die($err_person);
	$i = 0;
	while ($brow = mysql_fetch_array($bresult)) {
		if ($i == 0 || fmod($i, 2) == 0)
			$class = "tbl_odd";
		else
			$class = "tbl_even";
?>
						<tr>
							<td class="<?php echo $class; ?>"><a href="people.php?person=<?php echo $brow["person_id"]; ?>"><?php echo $brow["name"]; ?></a></td>
							<td class="<?php echo $class; ?>"><?php echo $brow["DOB"]; ?></td>
							<td class="<?php echo $class; ?>"><?php echo $brow["year_birth"] - $brow["date_of_birth"]; ?> years</td>
						</tr>
<?php
	$i++;
	}
	mysql_free_result($bresult);
?>
						<tr>
							<th><?php echo $strMarriages; ?></th>
						</tr>
<?php
	// get the marriages in the next n days
	$mquery = "SELECT CONCAT_WS('-', YEAR(NOW()), LPAD(MONTH(marriage_date), 2, '0'), LPAD(DAYOFMONTH(marriage_date), 2, '0')) AS year_marriage, DATE_FORMAT(marriage_date, ".$datefmt.") AS DOM, marriage_date, tbl_male.name AS male, tbl_female.name AS female, tbl_male.person_id AS groom, tbl_female.person_id AS bride FROM ".$tblprefix."spouses, ".$tblprefix."people AS tbl_male, ".$tblprefix."people AS tbl_female";
	if ($_SESSION["id"] == 0)
		$mquery .= " WHERE (tbl_male.date_of_birth < '".$restrictdate."' AND tbl_female.date_of_birth < '".$restrictdate."') AND";
	else
		$mquery .= " WHERE";
	$mquery .= " groom_id = tbl_male.person_id AND bride_id = tbl_female.person_id HAVING year_marriage >= now() AND year_marriage <= DATE_ADD(NOW(), INTERVAL 21 DAY) ORDER BY year_marriage LIMIT 0,6";
	$mresult = mysql_query($mquery) or die($err_marriage);
	$i = 0;
	while ($mrow = mysql_fetch_array($mresult)) {
		if ($i == 0 || fmod($i, 2) == 0)
			$class = "tbl_odd";
		else
			$class = "tbl_even";
?>
						<tr>
							<td class="<?php echo $class; ?>"><a href="people.php?person=<?php echo $mrow["groom"]; ?>"><?php echo $mrow["male"]; ?></a> &amp; <a href="people.php?person=<?php echo $mrow["bride"]; ?>"><?php echo $mrow["female"]; ?></a></td>
							<td class="<?php echo $class; ?>"><?php echo $mrow["DOM"]; ?></td>
							<td class="<?php echo $class; ?>"><?php echo $mrow["year_marriage"] - $mrow["marriage_date"]; ?> years</td>
						</tr>
<?php
	$i++;
	}
	mysql_free_result($mresult);
?>
						<tr>
							<th><?php echo $strDeaths; ?></th>
						</tr>
<?php
	// get the deaths in the next n days
	$dquery = "SELECT *, CONCAT_WS('-', YEAR(NOW()), LPAD(MONTH(date_of_death), 2, '0'), LPAD(DAYOFMONTH(date_of_death), 2, '0')) AS year_death, DATE_FORMAT(date_of_death, ".$datefmt.") AS DOD FROM ".$tblprefix."people";
	if ($_SESSION["id"] == 0)
		$dquery .= " WHERE date_of_birth < '".$restrictdate."'";
	$dquery .= " HAVING year_death >= NOW() AND year_death <= DATE_ADD(NOW(), INTERVAL 21 DAY) ORDER BY year_death LIMIT 0,6";
	$dresult = mysql_query($dquery) or die($err_person);
	$i = 0;
	while ($drow = mysql_fetch_array($dresult)) {
		if ($i == 0 || fmod($i, 2) == 0)
			$class = "tbl_odd";
		else
			$class = "tbl_even";
?>
						<tr>
							<td class="<?php echo $class; ?>"><a href="people.php?person=<?php echo $drow["person_id"]; ?>"><?php echo $drow["name"]; ?></a></td>
							<td class="<?php echo $class; ?>"><?php echo $drow["DOD"]; ?></td>
							<td class="<?php echo $class; ?>"><?php echo $drow["year_death"] - $drow["date_of_death"]; ?> years</td>
						</tr>
<?php
	$i++;
	}
	mysql_free_result($dresult);
?>
					</tbody>
				</table>

			</td>
			<td width="33%" align="right" valign="top">
				<!--list of last 20 updated people-->
				<table width="80%">
					<tr>
						<th colspan="2"><?php echo $strLast20; ?></th>
					</tr>
					<tr>
						<th><?php echo $strPerson; ?></th>
						<th><?php echo $strUpdated; ?></th>
					</tr>
<?php
					$query = "SELECT person_id, name, DATE_FORMAT(updated, ".$datefmt.") AS ddate FROM ".$tblprefix."people";
					if ($_SESSION["id"] == 0)
						$query .= " WHERE date_of_birth < '".$restrictdate."'";
					$query .= " ORDER BY updated DESC LIMIT 0,20";
					$result = mysql_query($query) or die($err_changed);
					$i = 0;
					while ($row = mysql_fetch_array($result)) {
						if ($i == 0 || fmod($i, 2) == 0)
							$class = "tbl_odd";
						else
							$class = "tbl_even";
?>
					<tr>
						<td class="<?php echo $class; ?>" align="left"><a href="people.php?person=<?php echo $row["person_id"]; ?>"><?echo $row["name"]; ?></a></td>
						<td class="<?php echo $class; ?>"><?php echo $row["ddate"]; ?></td>
					</tr>
<?php
						$i++;
					}
					mysql_free_result($result);

?>
				</table>

			</td>
		</tr>
	</table>

<?php

	include "inc/footer.inc.php";

	//eof
?>
