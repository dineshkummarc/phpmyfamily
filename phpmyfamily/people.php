<?php

	// family tree software
	// (c)2002 - 2003 Simon E Booth
	// All rights reserved
	// file to control display of personal information

	// include the database parameters
	include "inc/db.inc.php";
	include "inc/functions.inc.php";

	// include the browser 
	include "inc/browser.inc.php";
	include "inc/css.inc.php";

	// check we have a person
	if(!isset($_REQUEST["person"])) $person = 1;

	// the query for the database
	$pquery = "SELECT * FROM people WHERE person_id = '".$_REQUEST["person"]."'";
	$presult = mysql_query($pquery) or die("Person query failed");

	while ($prow = mysql_fetch_array($presult)) {

		// pickout father and mother for use in queries
		// set to -1 to avoid too many siblings!!! :-)
		$father = $prow["father_id"];
		if ($father == 0) $father = -1;
		$mother = $prow["mother_id"];
		if ($mother == 0) $mother = -1;
		
		// fill out the header
		echo "<HTML>";
		echo "<HEAD>";
		css_site();
		echo "<title>".$prow["name"]."</title>";
		echo "</HEAD>";
		echo "<BODY>\n";

		echo "<table>";
			echo "<tr width=100%>";
				echo "<td width=80% align=center>";
					echo "<h2>".$prow["name"]."</h2>";
					echo "<h3>(".formatdbdate($prow["date_of_birth"])." - ".formatdbdate($prow["date_of_death"]).")</h3>";
				echo "</td>";
				echo "<td width=20%>";
					echo "<form method=post action=passthru.php?func=jump>";
						listpeeps("person", $_REQUEST["person"]);
						echo "<INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Go>";
					echo "</form>";
				echo "</td>";
			echo "</tr>";
		echo "</table>";

		echo "<HR>";
		 
		echo "<table width=100%>";
			echo "<tr width=100%>";
				echo "<th width=95%><h4>Details</h4></th>";
				echo "<td width=5% bgcolor=#CCCCCC><a href=edit.php?func=edit&area=detail&person=".$prow["person_id"].">edit</a></td>";
			echo "</tr>";
		echo "</table>";
		echo "<table>";
			echo "<tr>";
				echo "<th width=5% valign=top>Born:</th>";
				echo "<td width=38% bgcolor=#CCCCCC valign=top>";
					echo formatdbdate($prow["date_of_birth"])." at ".$prow["birth_place"];
				echo "</td>";
				echo "<td bgcolor=#CCCCCC valign=top>Certified <input type=checkbox name=birthcert disabled";
					if ($prow["birth_cert"] == "Y")
						echo " checked";
				echo "></td>";
				echo "<th width=5% valign=top>Father:</th>";
				echo "<td width=40% bgcolor=#CCCCCC valign=top>";
					// the query for father
					$fquery = "SELECT * FROM people WHERE person_id = '".$father."'";
					$fresult = mysql_query($fquery) or die("Father query failed");
					while ($frow = mysql_fetch_array($fresult)) {
						echo "<a href=people.php?person=".$frow["person_id"].">".$frow["name"]." </a>(".formatdbdate($frow["date_of_birth"])." - ".formatdbdate($frow["date_of_death"]).")";
					}
					mysql_free_result($fresult);
				echo "</td>";
				
			echo "</tr>";
			echo "<tr>";
				echo "<th width=5% valign=top>Died:</th>";
				echo "<td width=20% bgcolor=#CCCCCC valign=top>";
					echo formatdbdate($prow["date_of_death"])." of ".$prow["death_reason"];
				echo "</td>";
				echo "<td bgcolor=#CCCCCC valign=top>Certified <input type=checkbox name=deathcert disabled";
					if ($prow["death_cert"] == "Y")
						echo " checked";
				echo "></td>";
				echo "<th valign=top>Mother:</th>";
				echo "<td bgcolor=#CCCCCC valign=top>";
					// the query for mother
					$mquery = "SELECT * FROM people WHERE person_id = '".$mother."'";
					$mresult = mysql_query($mquery) or die("Mother query failed");
					while ($mrow = mysql_fetch_array($mresult)) {
						echo "<a href=people.php?person=".$mrow["person_id"].">".$mrow["name"]."</a> (".formatdbdate($mrow["date_of_birth"])." - ".formatdbdate($mrow["date_of_death"]).")";
					}
					mysql_free_result($mresult);
				echo "</td>";
			echo "</tr>";
			echo "<tr>";

				// Children
				echo "<th valign=top>Children:</th>";
				echo "<td valign=top bgcolor=#DDDDDD colspan=2>";
					// query for children
					$cquery = "SELECT * FROM people WHERE (father_id = '".$_REQUEST["person"]."' OR mother_id = '".$_REQUEST["person"]."') ORDER BY date_of_birth";
					$cresult = mysql_query($cquery) or die("Children query failed");
					while ($crow = mysql_fetch_array($cresult)) {
						echo "<a href=people.php?person=".$crow["person_id"].">".$crow["name"]."</a> (".formatdbdate($crow["date_of_birth"])." - ".formatdbdate($crow["date_of_death"]).")<br>";
					}
					mysql_free_result($cresult);
				echo "</td>";

				// Siblings
				echo "<th valign=top>Siblings:</th>";
				echo "<td valign=top bgcolor=#DDDDDD>";
					// the query for siblings
					$squery = "SELECT * FROM people WHERE (father_id = '".$father."' OR mother_id = '".$mother."') AND person_id <> '".$_REQUEST["person"]."' ORDER BY date_of_birth";
					$sresult = mysql_query($squery) or die("Siblings query failed");
					while ($srow = mysql_fetch_array($sresult)) {
						echo "<a href=people.php?person=".$srow["person_id"].">".$srow["name"]."</a> (".formatdbdate($srow["date_of_birth"])." - ".formatdbdate($srow["date_of_death"]).")<br>";
					}
					mysql_free_result($sresult);
				echo "</td>";
			echo "</tr>";
		echo "</table>";

		// marriages
		echo "<hr>";
		echo "<table>";
			echo "<tr>";
				echo "<th valign=top width=5%>Married:</th>";
				echo "<td valign=top width=71% bgcolor=#DDDDDD>";
					// query for weddings
					$wquery = "SELECT * FROM people, spouses WHERE (bride_id = person_id OR groom_id = person_id) AND (groom_id = '".$_REQUEST["person"]."' OR bride_id = '".$_REQUEST["person"]."') AND person_id <> '".$_REQUEST["person"]."' ORDER BY marriage_date";
					$wresult = mysql_query($wquery) or die("Marriage query failed");
					while ($wrow = mysql_fetch_array($wresult)) {
						echo "<a href=edit.php?func=edit&area=marriage&person=".$_REQUEST["person"]."&spouse=".$wrow["person_id"].">edit</a> <a href=people.php?person=".$wrow["person_id"].">".$wrow["name"]."</a> on  ".formatdbdate($wrow["marriage_date"])." at ".$wrow["marriage_place"]."<br>";
						echo "<td valign=top bgcolor=#DDDDDD width=9%>Certified <input type=checkbox name=marriagecert disabled";
						if ($wrow["marriage_cert"] == "Y")
							echo " checked";
						echo "></td>\n";
					}
					mysql_free_result($wresult);
				echo "</td>";
				echo "<td align=right bgcolor=#CCCCCC><a href=edit.php?func=add&person=".$_REQUEST["person"]."&area=marriage>insert</a> new marriage</td>";
			echo "</tr>";
		echo "</table>";

		// notes
		echo "<HR>";
		echo "<table width=100%>";
			echo "<tr width=100%>";
				echo "<td width=95%><h4>Notes</h4></td>";
				echo "<td width=5%>";
				//<a href=edit.php?func=edit&person=".$prow["person_id"]."&area=narrative>edit</a>
				echo "</td>";
			echo "</tr>";
		echo "</table>";
		echo $prow["narrative"];
		echo "<br><br>";
		
		// images
		echo "<hr>\n";
			echo "<table width=100%>\n";
				echo "<tr>\n";
					echo "<td width=80%><h4>Image Gallery</h4></td>\n";
					echo "<td align=right><a href=edit.php?func=add&area=image&person=".$_REQUEST["person"].">upload</a> new image</td>\n";
				echo "</tr>\n";
			echo "</table>\n";

					$iquery = "SELECT * FROM images WHERE person_id = '".$_REQUEST["person"]."' ORDER BY date";
					$iresult = mysql_query($iquery) or die("image fetch failed");

					if (mysql_num_rows($iresult) == 0)
						echo "No images available\n";
					else {
						echo "<table width=100%>\n";
						$rows = ceil(mysql_num_rows($iresult) / 4);
						$current = 0;
						$currentrow = 1;
							while ($irow = mysql_fetch_array($iresult)) {
								if ($current == 0 || $current == 5 || $current == 10 || $current == 15)
									echo "<tr>\n";
								echo "<td width=20% bgcolor=#CCCCCC align=center valign=top><a href=image.php?image=".$irow["image_id"]."><IMG SRC=images/tn_".$irow["image_id"].".jpg WIDTH=100 HEIGHT=100 BORDER=0 title='".$irow["description"]."'></a><br><a href=image.php?image=".$irow["image_id"].">".$irow["title"]."</a></td>\n";
								if ($current == 4 || $current == 9 || $current == 14 || $current == 19) {
									$currentrow++;
									echo "</tr>\n";
								}
								$current++;
							}
							mysql_free_result($iresult);
							while ($currentrow <= $rows) {
								if ($current == 0 || $current == 5 || $current == 10 || $current == 15)
									echo "<tr>\n";
								echo "<td width=20%></td>\n";
								if ($current == 4 || $current == 9 || $current == 14 || $current == 19) {
									$currentrow++;
									echo "</tr>\n";
								}
								$current++;
							}
						echo "</table>\n";
					}
		
		// census details
		echo "<HR>";
			echo "<table width=100%>";
				echo "<tr>";
					echo "<td width=80%><h4>Census Details</h4></td>";
					echo "<td width=20% valign=top align=right><a href=edit.php?func=add&area=census&person=".$_REQUEST["person"].">Insert</a> new census</td>";
				echo "</tr>";
			echo "</table>";
			$cquery = "SELECT * FROM census WHERE person_id = '".$_REQUEST["person"]."' ORDER BY year";
			$cresult = mysql_query($cquery) or die("Census query failed");
			if (mysql_num_rows($cresult) == 0)
				echo "No information available\n";
			else {
				echo "<table width=100%>";
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
						if ($i == 0 || $i == 2 || $i == 4 || $i == 6)
							$bgcolor = "#CCCCCC";
						else
							$bgcolor = "#DDDDDD";
						echo "<tr>";
							echo "<td bgcolor=".$bgcolor."><a href=edit.php?func=edit&area=census&person=".$_REQUEST["person"]."&year=".$crow["year"].">edit</a></td>";
							echo "<td bgcolor=".$bgcolor.">".$crow["year"]."</td>";
							echo "<td bgcolor=".$bgcolor.">".$crow["schedule"]."</td>";
							echo "<td bgcolor=".$bgcolor.">".$crow["address"]."</td>";
							echo "<td bgcolor=".$bgcolor.">".$crow["condition"]."</td>";
							echo "<td bgcolor=".$bgcolor.">".$crow["age"]."</td>";
							echo "<td bgcolor=".$bgcolor.">".$crow["profession"]."</td>";
							echo "<td bgcolor=".$bgcolor.">".$crow["where_born"]."</td>";
						echo "</tr>";
						$i++;
					}
					mysql_free_result($cresult);
				echo "</table>";
			}

		// document transcripts
		echo "<HR>";
			echo "<table width=100%>";
				echo "<tr>";
					echo "<td width=80%><h4>Document Transcripts</h4></td>";
					echo "<td width=20% valign=top align=right><a href=edit.php?func=add&area=transcript&person=".$_REQUEST["person"].">Upload</a> new transcript</td>";
				echo "</tr>";
			echo "</table>";
			$dquery = "SELECT * FROM documents WHERE person_id = '".$_REQUEST["person"]."'";
			$dresult = mysql_query($dquery) or die("Document query failed");
			if (mysql_num_rows($dresult) == 0) {
				echo "No documents available<BR>\n";
			} 
			else {
				echo "<TABLE>\n";
					echo "<TR WIDTH=100%>\n";
						echo "<TH WIDTH=30%>Title</TH>\n";
						echo "<TH WIDTH=50%>Description</TH>\n";
						echo "<TH WIDTH=10%>Date</TH>\n";
					echo "</TR>\n";
					$i = 0;
					while ($drow = mysql_fetch_array($dresult)) {
						if ($i == 0 || $i == 2 || $i == 4 || $i == 6)
							$bgcolor = "#CCCCCC";
						else
							$bgcolor = "#DDDDDD";
						echo "<TR>\n";
							echo "<TD bgcolor=".$bgcolor."><A HREF='http://logger.giric.com/dlcount.php?id=family&url=/".$drow["file_name"]."'>".$drow["doc_title"]."</A></TD>\n";
							echo "<TD bgcolor=".$bgcolor.">".$drow["doc_description"]."</TD>\n";
							echo "<TD bgcolor=".$bgcolor.">".formatdbdate($drow["doc_date"])."</TD>\n";
						echo "</TR>\n";
						$i++;
					}
				echo "</TABLE>\n";
			
				echo "<BR>Click the document title to download. (Might have to right click & Save Target As.. in Internet Explorer)<BR>\n";
			}
			mysql_free_result($dresult);
		echo "<HR>\n";
		echo "<center><h5>Version: ".$version."<br>Last updated: ".date('H:i \o\n \t\h\e d/m/Y', convertstamp($prow["updated"]))."<br>Any information to add? <a href=edit.php?func=edit&person=".$prow["person_id"]."&area=detail>Edit</a> this person<br>Missing people? <a href=edit.php?func=add&area=detail>Add</a> a new person to the database<br>Problems? Let <a href=mailto:simon.booth@giric.com?subject=".$prow["person_id"].">me</a> know</h5></center>";
	}
	
	mysql_free_result($presult);
	
?>

<script language="JavaScript" type="text/javascript" src="pphlogger.js"></script>
<noscript><img alt="" src="http://logger.giric.com/pphlogger.php?id=family&st=img"></noscript>

<?php
	
	echo "</body>";
	echo "</html>";

	// eof
?>