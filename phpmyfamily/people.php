<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2003  Simon E Booth (simon.booth@giric.com)

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

	// send the headers
	ini_set("arg_separator.output", "&amp;");


	// include the configuration parameters and function
	include "inc/config.inc.php";
	include "inc/functions.inc.php";

	// include the browser
	include "inc/browser.inc.php";
	include "inc/css.inc.php";

	// check we have a person
	if(!isset($_REQUEST["person"])) $person = 1;
	@$person = $_REQUEST["person"];

	// the query for the database
	$pquery = "SELECT * FROM family_people WHERE person_id = '".$_REQUEST["person"]."'";
	$presult = mysql_query($pquery) or die("Person query failed");
	while ($prow = mysql_fetch_array($presult)) {

		// pickout father and mother for use in queries
		// set to -1 to avoid too many siblings!!! :-)
		$father = $prow["father_id"];
		if ($father == 0) $father = -1;
		$mother = $prow["mother_id"];
		if ($mother == 0) $mother = -1;

		// set security for living people (born after 01/01/1910)
		if ($_SESSION["id"] == 0 && $prow["date_of_birth"] > $restrictdate)
			$restricted = true;
		else
			$restricted = false;

		// if trying to access a restriced person
		if ($restricted)
			die(header("Status: 403 Forbidden"));

		// fill out the header
		header('Content-Type: text/html; charset=ISO-8859-1');
		header('Content-Language: en');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<meta name="author" content="Simon E Booth">
<meta name="publisher" content="Giric">
<meta name="copyright" content="2002-2003 Simon E Booth">
<meta name="keywords" content="Genealogy<?php
	$fname = "SELECT SUBSTRING_INDEX(name, ' ', -1) AS surname FROM family_people GROUP BY surname";
	$rname = mysql_query($fname) or die("Error getting names!");
	if (mysql_num_rows($rname) <> 0) {
		while ($row = mysql_fetch_array($rname))
			echo " ".$row["surname"];
	}
?>">
<meta name="description" content="<?php echo $desc; ?>">
<meta name="page-topic" content="Genealogy">
<meta name="audience" content="All">
<meta name="expires" content="0">
<meta name="page-type" content="Private homepage">
<meta name="robots" content="INDEX,FOLLOW">
<?php
	css_site();
?>
<title><?php echo $prow["name"] ?></title>
</HEAD>
<BODY>

<table>
	<tr>
		<td width="80%" align="center">
			<h2><?php echo $prow["name"] ?></h2>
			<h3><?php
				if ($restricted)
					echo "(".$restrictmsg." - ".$restrictmsg.")";
				else
					echo "(".formatdbdate($prow["date_of_birth"])." - ".formatdbdate($prow["date_of_death"]).")";
			?></h3>
		</td>
		<td width="20%">
			<form method="get" action="people.php">
			<?php listpeeps("person", 0, "A", $_REQUEST["person"]); ?>
			</form>
<?php if ($_SESSION["id"] <> 0) { ?>
			<br>You are logged in as <a href="index.php"><?php echo $_SESSION["name"]; ?></a>: (<a href="passthru.php?func=logout">logout</a><?php if ($_SESSION["admin"] == 1) echo ", <a href=\"admin.php\">admin</a>"; ?>)
<?php } ?>
		</td>
	</tr>
</table>

<HR>

<table width="100%">
	<tr>
		<th width="95%"><h4>Details</h4></th>
		<td width="5%" bgcolor="#CCCCCC">
<?php
		if ($_SESSION["id"] <> 0)
			echo "<a href=\"edit.php?func=edit&amp;area=detail&amp;person=".$prow["person_id"]."\">edit</a>";
?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<th width="5%" valign="top">Born:</th>
		<td width="38%" bgcolor="#CCCCCC" valign="top">
<?php
		if ($restricted)
			echo $restrictmsg;
		else
			echo formatdbdate($prow["date_of_birth"])." at ".$prow["birth_place"];
?>
		</td>
		<td bgcolor="#CCCCCC" valign="top">Certified <input type="checkbox" name="birthcert" disabled<?php if ($prow["birth_cert"] == "Y") echo " checked" ?>></td>
		<th width="5%" valign="top">Father:</th>
		<td width="40%" bgcolor="#CCCCCC" valign="top">

<?php
		// the query for father
		$fquery = "SELECT * FROM family_people WHERE person_id = '".$father."'";
		$fresult = mysql_query($fquery) or die("Father query failed");
		while ($frow = mysql_fetch_array($fresult)) {
			if ($frow["date_of_birth"] > $restrictdate && $_SESSION["id"] == 0)
				// if anybody gets here they are hacking
				// or someones made a mistake with peoples parents
				echo $frow["name"]." (".$restrictmsg.")<br>\n";
			else
				echo "<a href=\"people.php?person=".$frow["person_id"]."\">".$frow["name"]."</a>(".formatdbdate($frow["date_of_birth"])." - ".formatdbdate($frow["date_of_death"]).")<br>\n";
		}
		mysql_free_result($fresult);
?>
		</td>
	</tr>
	<tr>
		<th width="5%" valign="top">Died:</th>
		<td width="20%" bgcolor="#CCCCCC" valign="top">
<?php
		if ($restricted)
			echo $restrictmsg;
		else
			echo formatdbdate($prow["date_of_death"])." of ".$prow["death_reason"];
?>
		</td>
		<td bgcolor="#CCCCCC" valign="top">Certified <input type="checkbox" name="deathcert" disabled<?php if ($prow["death_cert"] == "Y") echo " checked"; ?>></td>
		<th valign="top">Mother:</th>
		<td bgcolor="#CCCCCC" valign="top">
<?php
		// the query for mother
		$mquery = "SELECT * FROM family_people WHERE person_id = '".$mother."'";
		$mresult = mysql_query($mquery) or die("Mother query failed");
		while ($mrow = mysql_fetch_array($mresult)) {
			if ($mrow["date_of_birth"] > $restrictdate && $_SESSION["id"] == 0)
			// if anybody gets here they are hacking
			// or someones made a mistake with peoples parents
			echo $mrow["name"]." (".$restrictmsg.")<br>\n";
		else
			echo "<a href=\"people.php?person=".$mrow["person_id"]."\">".$mrow["name"]."</a>(".formatdbdate($mrow["date_of_birth"])." - ".formatdbdate($mrow["date_of_death"]).")<br>\n";
		}
		mysql_free_result($mresult);
?>
		</td>
	</tr>
	<tr>

	<th valign="top">Children:</th>
	<td valign="top" bgcolor="#DDDDDD" colspan="2">
<?php
		// query for children
		$cquery = "SELECT * FROM family_people WHERE (father_id = '".$_REQUEST["person"]."' OR mother_id = '".$_REQUEST["person"]."') ORDER BY date_of_birth";
		$cresult = mysql_query($cquery) or die("Children query failed");
		while ($crow = mysql_fetch_array($cresult)) {
			if ($crow["date_of_birth"] > $restrictdate && $_SESSION["id"] == 0)
				echo $crow["name"]." (".$restrictmsg.")<br>\n";
			else
				echo "<a href=\"people.php?person=".$crow["person_id"]."\">".$crow["name"]."</a>(".formatdbdate($crow["date_of_birth"])." - ".formatdbdate($crow["date_of_death"]).")<br>\n";
		}
		mysql_free_result($cresult);				echo "</td>\n";
?>
		<!--Siblings-->
		<th valign="top">Siblings:</th>
		<td valign="top" bgcolor="#DDDDDD">
<?php
		// the query for siblings
		$squery = "SELECT * FROM family_people WHERE (father_id = '".$father."' OR mother_id = '".$mother."') AND person_id <> '".$_REQUEST["person"]."' ORDER BY date_of_birth";
		$sresult = mysql_query($squery) or die("Siblings query failed");
		while ($srow = mysql_fetch_array($sresult)) {
			if ($srow["date_of_birth"] > $restrictdate && $_SESSION["id"] == 0)
				echo $srow["name"]." (".$restrictmsg.")<br>\n";
			else
				echo "<a href=\"people.php?person=".$srow["person_id"]."\">".$srow["name"]."</a>(".formatdbdate($srow["date_of_birth"])." - ".formatdbdate($srow["date_of_death"]).")<br>\n";
		}
		mysql_free_result($sresult);
?>
		</td>
	</tr>
</table>

<!--marriages-->
<hr>
<table>
	<tr>
		<th valign="top" width="5%">Married:</th>
		<td valign="top" width="71%" bgcolor="#DDDDDD">
<?php
		// query for weddings
		$wquery = "SELECT * FROM family_people, family_spouses WHERE (bride_id = person_id OR groom_id = person_id) AND (groom_id = '".$_REQUEST["person"]."' OR bride_id = '".$_REQUEST["person"]."') AND person_id <> '".$_REQUEST["person"]."' ORDER BY marriage_date";
		$wresult = mysql_query($wquery) or die("Marriage query failed");
		echo "<table width=\"100%\">";
		while ($wrow = mysql_fetch_array($wresult)) {
			echo "<tr>\n";
			echo "<td width=\"90%\">\n";
			if ($_SESSION["id"] <> 0)
				echo "<a href=\"edit.php?func=edit&amp;area=marriage&amp;person=".$_REQUEST["person"]."&amp;spouse=".$wrow["person_id"]."\">edit</a>";
			if ($wrow["date_of_birth"] > $restrictdate && $_SESSION["id"] == 0)
				echo $wrow["name"]." on ".$restrictmsg;
			else
				echo " <a href=\"people.php?person=".$wrow["person_id"]."\">".$wrow["name"]."</a> on ".formatdbdate($wrow["marriage_date"])." at ".$wrow["marriage_place"]."<br>";
			echo "<td valign=\"top\" bgcolor=\"#DDDDDD\" width=\"9%\">Certified <input type=\"checkbox\" name=\"marriagecert\" disabled";
			if ($wrow["marriage_cert"] == "Y")
				echo " checked";
			echo "></td>\n";
			echo "</tr>\n";
		}
 		echo "</table>\n";
		mysql_free_result($wresult);
		echo "</td>\n";
		echo "<td align=\"right\" bgcolor=\"#CCCCCC\" valign=\"top\">";
		if ($_SESSION["id"] <> 0)
			echo "<a href=\"edit.php?func=add&amp;person=".$_REQUEST["person"]."&amp;area=marriage\">insert</a> new marriage";
		echo "</td>\n";
	echo "</tr>\n";
echo "</table>\n";

		// notes
		echo "<HR>\n";
		echo "<table width=\"100%\">\n";
			echo "<tr>\n";
				echo "<td width=\"95%\"><h4>Notes</h4></td>\n";
				echo "<td width=\"5%\">";
				//<a href=edit.php?func=edit&person=".$prow["person_id"]."&area=narrative>edit</a>
echo "</td>\n";
	echo "</tr>\n";
		echo "</table>\n";
		if ($restricted)
			echo $restrictmsg."\n";
		else
			echo $prow["narrative"]."\n";
		echo "<br><br>\n";

		// images
		echo "<hr>\n";
		echo "<table width=\"100%\">\n";
			echo "<tr>\n";
				echo "<td width=\"80%\"><h4>Image Gallery</h4></td>\n";
				echo "<td align=\"right\">";
					if ($_SESSION["id"] <> 0)
						echo "<a href=\"edit.php?func=add&amp;area=image&amp;person=".$_REQUEST["person"]."\">upload</a> new image";
				echo "</td>\n";			echo "</tr>\n";
		echo "</table>\n";

		if ($restricted)
			echo $restrictmsg."\n";
		else {
			$iquery = "SELECT * FROM family_images WHERE person_id = '".$_REQUEST["person"]."' ORDER BY date";
			$iresult = mysql_query($iquery) or die("image fetch failed");
			if (mysql_num_rows($iresult) == 0)
				echo "No images available\n";
			else {
				echo "<table width=\"100%\">\n";
				$rows = ceil(mysql_num_rows($iresult) / 5);
				$current = 0;
				$currentrow = 1;
					while ($irow = mysql_fetch_array($iresult)) {
						// start a new row every 5 images
						if ($current == 0 || fmod($current, 5) == 0)
							echo "<tr>\n";
						// alternate background colours
						if ($current == 0 || fmod($current, 2) == 0)
							$bgcolour = "#CCCCCC";
						else
							$bgcolour = "#DDDDDD";
						// display image thumbnail
						echo "<td width=\"20%\" bgcolor=\"".$bgcolour."\" align=\"center\" valign=\"top\"><a href=\"image.php?image=".$irow["image_id"]."\"><IMG SRC=\"images/tn_".$irow["image_id"].".jpg\" WIDTH=\"100\" HEIGHT=\"100\" BORDER=\"0\" title=\"".$irow["description"]."\"alt=\"".$irow["description"]."\"></a><br><a href=\"image.php?image=".$irow["image_id"]."\">".$irow["title"]."</a></td>\n";
						// close each row every 5 images
						if ($current <> 0 && fmod($current + 1, 5) == 0) {
							$currentrow++;
							echo "</tr>\n";
						}
						$current++;
					}
				mysql_free_result($iresult);
					// make sure that rows and tables are padded and closed properly
					while ($currentrow <= $rows) {
						echo "<td width=\"20%\"></td>\n";
						if ($current <> 0 && fmod($current + 1, 5) == 0) {
							$currentrow++;
							echo "</tr>\n";
						}
						$current++;
					}
				echo "</table>\n";
			}
		}

		// census details
		echo "<HR>\n";
		echo "<table width=\"100%\">\n";
			echo "<tr>\n";
				echo "<td width=\"80%\"><h4>Census Details</h4></td>\n";
				echo "<td width=\"20%\" valign=\"top\" align=\"right\">";
					if ($_SESSION["id"] <> 0)
						echo "<a href=\"edit.php?func=add&amp;area=census&amp;person=".$_REQUEST["person"]."\">insert</a> new census";
					echo "</td>\n";
				echo "</tr>\n";
		echo "</table>\n";

		if ($restricted)
			echo $restrictmsg."\n";
		else {
			$cquery = "SELECT * FROM family_census WHERE person_id = '".$_REQUEST["person"]."' ORDER BY year";					$cresult = mysql_query($cquery) or die("Census query failed");
				if (mysql_num_rows($cresult) == 0)
					echo "No information available\n";
				else {
					echo "<table width=\"100%\">";
					echo "<tr>";
						echo "<th></th>";
						echo "<th>Year</th>";
						echo "<th>Reference</th>";
						echo "<th>Address</th>";
						echo "<th>Condition</th>";
						echo "<th>Age</th>";
						echo "<th>Profession</th>";
						echo "<th>Birth Place</th>";
					echo "</tr>";
					$i = 0;
					while ($crow = mysql_fetch_array($cresult)) {
						if ($i == 0 || fmod($i, 2) == 0)
							$bgcolor = "#CCCCCC";
						else
							$bgcolor = "#DDDDDD";
						echo "<tr>\n";
							echo "<td bgcolor=\"".$bgcolor."\">";
							if ($_SESSION["id"] <> 0)
								echo "<a href=\"edit.php?func=edit&amp;area=census&amp;person=".$_REQUEST["person"]."&amp;year=".$crow["year"]."\">edit</a>";
							echo "</td>\n";
							echo "<td bgcolor=\"".$bgcolor."\">".$crow["year"]."</td>\n";
							echo "<td bgcolor=\"".$bgcolor."\">".$crow["schedule"]."</td>\n";
							echo "<td bgcolor=\"".$bgcolor."\">".$crow["address"]."</td>\n";
							echo "<td bgcolor=\"".$bgcolor."\">".$crow["condition"]."</td>\n";
							echo "<td bgcolor=\"".$bgcolor."\">".$crow["age"]."</td>\n";
							echo "<td bgcolor=\"".$bgcolor."\">".$crow["profession"]."</td>\n";
							echo "<td bgcolor=\"".$bgcolor."\">".$crow["where_born"]."</td>\n";
						echo "</tr>\n";
						$i++;
					}
					mysql_free_result($cresult);
				echo "</table>\n";
			}
		}

		// document transcripts
		echo "<HR>\n";
		echo "<table width=\"100%\">\n";
			echo "<tr>\n";
				echo "<td width=\"80%\"><h4>Document Transcripts</h4></td>\n";
				echo "<td width=\"20%\" valign=\"top\" align=\"right\">";
				if ($_SESSION["id"] <> 0)
					echo "<a href=\"edit.php?func=add&amp;area=transcript&amp;person=".$_REQUEST["person"]."\">upload</a> new transcript";
				echo "</td>\n";
			echo "</tr>\n";
		echo "</table>\n";

		if ($restricted)
			echo $restrictmsg."\n";
		else {
			$dquery = "SELECT * FROM family_documents WHERE person_id = '".$_REQUEST["person"]."'";
			$dresult = mysql_query($dquery) or die("Document query failed");
			if (mysql_num_rows($dresult) == 0) {
				echo "No documents available<BR>\n";
			}
			else {
				echo "<TABLE>\n";
					echo "<TR>\n";
						echo "<TH WIDTH=\"30%\">Title</TH>\n";
						echo "<TH WIDTH=\"50%\">Description</TH>\n";
						echo "<TH WIDTH=\"10%\">Date</TH>\n";
					echo "</TR>\n";
					$i = 0;
					while ($drow = mysql_fetch_array($dresult)) {
						if ($i == 0 || fmod($i, 2) == 0)
							$bgcolor = "#CCCCCC";
						else
							$bgcolor = "#DDDDDD";
					echo "<TR>\n";
						echo "<TD bgcolor=\"".$bgcolor."\"><A HREF=\"http://logger.giric.com/dlcount.php?id=family&amp;url=/".$drow["file_name"]."\">".$drow["doc_title"]."</A></TD>\n";
						echo "<TD bgcolor=\"".$bgcolor."\">".$drow["doc_description"]."</TD>\n";
						echo "<TD bgcolor=\"".$bgcolor."\">".formatdbdate($drow["doc_date"])."</TD>\n";
					echo "</TR>\n";
					$i++;
					}
				echo "</TABLE>\n";

				echo "<BR>Click the document title to download. (Might need to right click&amp; Save Target As.. in Internet Explorer)<BR>\n";
			}
			mysql_free_result($dresult);
		}

		// page footer
		echo "<HR>\n";
		echo "<table width=\"100%\">\n";
		echo "<tr>\n";
			echo "<td width=\"15%\" align=\"center\" valign=\"middle\">";
				echo "<a href=\"http://validator.w3.org/check/referer\"><img border=\"0\" src=\"images/valid-html401.png\" alt=\"Valid HTML 4.01!\" height=\"31\" width=\"88\"></a>";
			echo "</td>\n";
			echo "<td width=\"70%\" align=\"center\" valign=\"middle\">";
			echo "<h5><a href=\"http://www.giric.com/phpmyfamily\">phpmyfamily v".$version."</a><br>Copyright 2002-2003 Simon E Booth<br>\n";
			echo "Last updated: ".date('H:i \o\n \t\h\e d/m/Y', convertstamp($prow["updated"]))."<br>\n";
			if ($_SESSION["id"] <> 0) {
				echo "Missing people? <a href=\"edit.php?func=add&amp;area=detail\">Add</a> a new person to the database<br>\n";
			}
			echo "Problems";
			if ($_SESSION["id"] == 0)
				echo " or anything to add";
			echo "? Let <a href=\"mailto:".$email."?subject=".$prow["name"]."\">me</a> know</h5>\n";
			echo "</td>\n";
			echo "<td width=\"15%\" align=\"center\" valign=\"middle\">";
				echo "<a href=\"http://jigsaw.w3.org/css-validator/\"><img style=\"border:0;width:88px;height:31px\" src=\"images/vcss.png\" alt=\"Valid CSS!\"></a>";
			echo "</td>\n";
		echo "</tr>\n";
	echo "</table>\n";
	}

	mysql_free_result($presult);

?>

<script language="JavaScript" type="text/javascript" src="pphlogger.js"></script><noscript>
<img alt="" src="http://logger.giric.com/pphlogger.php?id=family&amp;st=img"></noscript>
<?php

	echo "</body>\n";
	echo "</html>";

	// eof
?>
