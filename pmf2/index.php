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
	include_once "modules/db/DAOFactory.php";
	include_once "inc/header.inc.php";
	include_once "modules/people/show.php";
	
	$config = Config::getInstance();
	$rss = "<link rel=\"alternate\" type=\"application/rss+xml\" href=\"".$config->absurl."rss.php\" title=\"".$config->desc."\" />";
	// Fill out the headers
	do_headers_dojo($config->desc." :phpmyfamily", $rss);
?>
<body class="tundra">
<table width="100%" class="header">
	<tr>
		<td width="65%" align="center">
			<h1><?php 
			echo $strPhpMyFamily; // MODIFICA 20120506 
			?></h1>
			<h3><?php echo $config->desc; ?></h3>
		</td>
		<td width="35%" valign="top" align="right">
			<form method="get" action="people.php">
				<?php selectPeople("person", 0, "A", 0, 1, 0, 1); ?>
			</form>

		</td>

	</tr>
	<tr>
	<td colspan="2" valign="top" align="right">
	<?php 
	user_opts(); // MODIFICA 20120508  
	?>
	</td>
	</tr>	
</table>

<hr />

<table width="100%">
	<tr>
		<td width="33%" valign="top">
<?php
			// include login form if not logged in
			if ($_SESSION["id"] == 0) {
				if($config->mailto) echo "<p>".str_replace("$1", "mail.php?subject=".$title, $strIndex)." <a href='schedafam.zip'>$strScheda</a></p>"; else echo "<p>".str_replace("$1", "mailto:".$config->email."?subject=".$title, $strIndex)." <a href='schedafam.zip'>$strScheda</a></p><br /><br />"; // MODIFICA 20120508
			} 
?>
				<table>
					<tr>
						<th colspan="2"><?php echo $strStats; ?></th>
					</tr>
					<tr>
						<th width="200"><?php echo $strArea; ?></th>
						<th width="50"><?php echo $strNumber; ?></th>
					</tr>
					<tr>
						<td class="tbl_odd"><a href="surnames.php"><?php echo ucwords($strOnFile); ?></a></td>
						<td class="tbl_odd" align="right">
<?php
$search = new PersonDetail();
	$search->queryType = Q_COUNT;
	$search->person_id = -1;
	$dao = getPeopleDAO();
	$dao->getPersonDetails($search);
	echo $search->numResults;
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
					<tr>
						<td class="tbl_odd">
						<a href="map.php"><?php echo $strLocations;?></a></td>
						<td class="tbl_odd" align="right">
						<?php $dao = getLocationDAO(); echo $dao->getLocationCount();?>
						</td>
					</tr>
				</table>
<?php
	$search = new PersonDetail();
	$search->person_id = 0;
	$search->queryType = Q_TYPE;
	
	$dao = getPeopleDAO();
	
	$surnames = $dao->getSurnames(1);
	echo "<hr />\n<h4>$strTopSurnames</h4>\n<ul>";
	foreach($surnames AS $per) {
		echo "<li>".$per->name->surname." (".$per->count.")</li> ";
	}
	?>
				</ul>

			</td>
			<td width="33%" valign="top">
				<table width="100%">
					<tr>
						<th><?php echo $strRandomImage; ?></th>
					</tr>
					<tr>
						<td	class="tbl_odd" align="center">
<?php
$img = new Image();
$img->queryType = Q_RANDOM;
$img->start = 0;
$img->count = 1;
$idao = getImageDAO();
$idao->getImages($img);

if ($img->numResults > 0) {
	$randImage = $img->results[0];
	echo "\t\t\t\t\t\t\t<a href=\"people.php?person=".$randImage->person->person_id."\"><img src=\"".$randImage->getThumbnailFile()."\" width=\"100\" height=\"100\" border=\"0\" title=\"".$randImage->description."\" alt=\"".$randImage->description."\" /><br />".$randImage->person->name->getDisplayName()."</a>";
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
	$today = getdate();
	$year = $today["year"];
	$search = new PersonDetail();
	$search->count = 6;
	$search->order = "birth";
	$dao = getPeopleDAO();
	$dao->getNearest($search);
	$i = 0;
	foreach ($search->results AS $per) {
		if ($i == 0 || fmod($i, 2) == 0)
			$class = "tbl_odd";
		else
			$class = "tbl_even";
?>
		<tr>
		<td class="<?php echo $class; ?>" align="left"><?php echo $per->getLink(); ?></td>
		<td class="<?php echo $class; ?>"><?php echo $per->dob; ?></td>
		<?php // MODIFICA 20120506 
		$age=$year - $per->year_of_birth;
		if (stripos($per->dob, "0000")>0){
			$age="????";
		}
		?>
		<td class="<?php echo $class; ?>"><?php echo $age." ".$strYears; ?></td>
		</tr>
<?php
		$i++;
	}
?>

						<tr>
							<th><?php echo $strMarriages; ?></th>
							<th><?php echo $strDate; ?></th>
							<th><?php echo $strAnniversary; ?></th>
						</tr>
						<?php
	$search = new Relationship();
	$search->count = 6;
	$dao = getRelationsDAO();
	$dao->getNearest($search);
	$i = 0;
	foreach ($search->results AS $rel) {
		if ($i == 0 || fmod($i, 2) == 0)
			$class = "tbl_odd";
		else
			$class = "tbl_even";
		list ($yom, $mom, $dom) = preg_split("/-/",$rel->marriage_date);
?>
		<tr>
		<td class="<?php echo $class; ?>" align="left"><?php echo $rel->person->getLink()." &amp; ".$rel->relation->getLink() ?></td>
		<td class="<?php echo $class; ?>"><?php echo $rel->dom; ?></td>
		<?php // MODIFICA 20120508 
		$marr=$year - $yom;
		if (stripos($marr, "0000")>0){
			$marr="????";
		}
		?>
		<td class="<?php echo $class; ?>"><?php echo $marr." ".$strYears; ?></td>
		</tr>
<?php
		$i++;
	}
?>
						<tr>
							<th><?php echo $strDeaths; ?></th>
							<th><?php echo $strDate; ?></th>
							<th><?php echo $strAnniversary; ?></th>
						</tr>
<?php
	$search = new PersonDetail();
	$search->count = 6;
	$search->order = "death";
	$dao = getPeopleDAO();
	$dao->getNearest($search);
	$i = 0;
	foreach ($search->results AS $per) {
		if ($i == 0 || fmod($i, 2) == 0)
			$class = "tbl_odd";
		else
			$class = "tbl_even";
?>
		<tr>
		<td class="<?php echo $class; ?>" align="left"><?php echo $per->getLink(); ?></td>
		<td class="<?php echo $class; ?>"><?php echo $per->dod; ?></td>
		<?php // MODIFICA 20120508 
		$memoriam=$year - $per->year_of_death;
		if (stripos($memoriam, "0000")>0){
			$memoriam="????";
		}
		?>
		<td class="<?php echo $class; ?>"><?php echo $memoriam." ".$strYears; ?></td>
		</tr>
<?php
		$i++;
	}
?>
					</tbody>
				</table>

			</td>
			<td width="33%" align="right" valign="top">
				<!--list of last 20 updated people-->
				<table width="80%">
					<tr>
						<th colspan="2"><?php echo $strLast20; ?> <a href="rss.php">RSS</a></th>
					</tr>
					<tr>
						<th><?php echo $strPerson; ?></th>
						<th><?php echo $strUpdated; ?></th>
					</tr>
<?php
$search = new PersonDetail();
	$search->queryType = Q_TYPE;
	$search->person_id = 0;
	$search->count = 20;
	$search->order = " updated DESC";
	$dao = getPeopleDAO();
	$dao->getPersonDetails($search);
	$i = 0;
	foreach ($search->results AS $per) {
		if ($i == 0 || fmod($i, 2) == 0)
			$class = "tbl_odd";
		else
			$class = "tbl_even";
?>
		<tr>
		<td class="<?php echo $class; ?>" align="left"><?php echo $per->getLink(); ?></td>
		<td class="<?php echo $class; ?>"><?php echo $per->dupdated; ?></td>
		</tr>
<?php
		$i++;
	}
?>
				</table>

			</td>
		</tr>
	</table><?php

	include "inc/footer.inc.php";

	//eof
?>