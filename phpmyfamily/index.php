<?php
	
	// index.php
	// family tree software
	// (c)2002 - 2003 Simon E Booth
	// All rights reserved
	// Welcome page

	// send the headers
	ini_set("arg_separator.output", "&amp;");
	header('Content-Type: text/html; charset=ISO-8859-1');
	header('Content-Language: en');

	// include the database parameters
	include "inc/session.inc.php";
	include "inc/db.inc.php";
	include "inc/functions.inc.php";

	// include the browser 
	include "inc/browser.inc.php";
	include "inc/css.inc.php";

	// fill out the header
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n\t\"http://www.w3.org/TR/html4/loose.dtd\">\n";
	echo "<HTML>\n";
	echo "<HEAD>\n";
?>

	<META NAME="author" CONTENT="Simon E Booth">
	<META NAME="publisher" CONTENT="Giric">
	<META NAME="copyright" CONTENT="2002-2003 Simon E Booth">
	<META NAME="keywords" CONTENT="Genealogy Ambler Bate Bickle Birtwistle Booth Bridgewater Chaffe Clegg Eveleigh Grainger Granger Jenkins Jones Kitching Kitchen Knight Parry Platt Raistrick Thorne Vann Verity Virr Willcocks">
	<META NAME="description" CONTENT="Family tree for me, starting with Booths and Ambler and going who knows where?">
	<META NAME="page-topic" CONTENT="Genealogy">
	<META NAME="audience" CONTENT="All">
	<META NAME="expires" CONTENT="0">
	<META NAME="page-type" CONTENT="Private homepage">
	<META NAME="robots" CONTENT="INDEX,FOLLOW">

<?php
	css_site();
	echo "<title>Family Tree</title>\n";
	echo "</HEAD>\n";
	echo "<BODY>\n";
	
	echo "<table>\n";
		echo "<tr>\n";
			echo "<td width=\"80%\" align=\"center\">\n";
				echo "<h2>Family Tree</h2>\n";
			echo "</td>\n";
			echo "<td width=\"20%\" valign=\"top\">\n";
				echo "<form method=post action=passthru.php?func=jump>";
					listpeeps("person");
					echo "<INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Go>\n";
				echo "</form>\n";
			echo "</td>\n";
		echo "</tr>\n";
	echo "</table>\n";
	
	echo "<hr>\n";

	echo "<table width=\"100%\">\n";
		echo "<tr>\n";
			echo "<td width=\"70%\" valign=\"top\">";

			// include login form if not logged in
			if ($_SESSION["id"] == 0)
				include "inc/loginform.inc.php";

			echo "<br><br>\n";
			echo "<table>\n";
				echo "<tr>\n";
					echo "<th colspan=\"2\">Site Statistics</th>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<th width=\"200\">Area</th>\n";
					echo "<th width=\"50\">No</th>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td bgcolor=\"#CCCCCC\">People on file</td>\n";
					echo "<td bgcolor=\"#CCCCCC\" align=\"right\">";
					$query = "SELECT count(*) as number FROM people";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td bgcolor=\"#DDDDDD\">Census Records</td>\n";
					echo "<td bgcolor=\"#DDDDDD\" align=\"right\">";
					$query = "SELECT count(*) as number FROM census";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td bgcolor=\"#CCCCCC\">Images</td>\n";
					echo "<td bgcolor=\"#CCCCCC\" align=right>";
					$query = "SELECT count(*) as number FROM images";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td bgcolor=\"#DDDDDD\">Document Transcripts</td>\n";
					echo "<td bgcolor=\"#DDDDDD\" align=\"right\">";
					$query = "SELECT count(*) as number FROM documents";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td bgcolor=\"#CCCCCC\">Page Requests</td>\n";
					echo "<td bgcolor=\"#CCCCCC\" align=\"right\">";
					$query = "SELECT count(*) as number FROM pphl_97075_mpdl";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
					echo "</td>\n";
				echo "</tr>\n";
			echo "</table>\n";

			echo "</td>\n";
			echo "<td width=\"30%\" align=\"right\">";
				// list of last updated people
				echo "<TABLE>\n";
					echo "<TR>\n";
						echo "<TH COLSPAN=\"2\">Last 20 People Updated</TH>\n";
					echo "</TR>\n";
					echo "<TR>\n";
						echo "<TH>Person</TH>\n";
						echo "<TH>Updated</TH>\n";
					echo "</TR>\n";
					$query = "SELECT person_id, name, updated FROM people"; 
					if ($_SESSION["id"] == 0)
						$query .= " WHERE date_of_birth < '".$restrictdate."'";
					$query .= " ORDER BY updated DESC LIMIT 0,20";
					$result = mysql_query($query) or die(mysql_error($result));
					$i = 0;
					while ($row = mysql_fetch_array($result)) {
						if ($i == 0 || $i == 2 || $i == 4 || $i == 6 || $i == 8 || $i == 10 || $i == 12 || $i == 14 || $i == 16 || $i == 18 || $i == 20)
							$bgcolor = "#CCCCCC";
						else
							$bgcolor = "#DDDDDD";
						echo "<TR>\n";
							echo "<TD bgcolor=\"".$bgcolor."\"><a ";
							echo htmlspecialchars("href='people.php?person=".$row["person_id"]."'");
							echo ">".$row["name"]."</a>";
							echo "</TD>\n";
							echo "<TD bgcolor=\"".$bgcolor."\">".date('H:i d/m/Y', convertstamp($row["updated"]))."</TD>\n";
						echo "</TR>\n";
						$i++;
					}
					mysql_free_result($result);
				echo "</TABLE>\n";
				
			echo "</td>\n";
		echo "</tr>\n";
	echo "</table>\n";

	// insert footer and copyright here
	echo "<HR>\n";
	echo "<table width=\"100%\">\n";
		echo "<tr>\n";
			echo "<td width=\"15%\" align=\"center\" valign=\"middle\">";
				echo "<a href=\"http://validator.w3.org/check/referer\"><img border=\"0\" src=\"images/valid-html401.png\" alt=\"Valid HTML 4.01!\" height=\"31\" width=\"88\"></a>";
			echo "</td>\n";
			echo "<td width=\"70%\" align=\"center\" valign=\"middle\">";
				echo "<h5>Version: ".$version." Copyright 2002-2003 Simon E Booth<br>\n";
				echo "Email <a href=mailto:simon.booth@giric.com>me</a> with any problems</h5>\n";
			echo "</td>\n";
			echo "<td width=\"15%\" align=\"center\" valign=\"middle\">";
				echo "<a href=\"http://jigsaw.w3.org/css-validator/\"><img style=\"border:0;width:88px;height:31px\" src=\"images/vcss.png\" alt=\"Valid CSS!\"></a>";
			echo "</td>\n";
		echo "</tr>\n";
	echo "</table>\n";
?>

<script language="JavaScript" type="text/javascript" src="pphlogger.js"></script>
<noscript><img alt="" src="http://logger.giric.com/pphlogger.php?id=family&amp;st=img"></noscript>

<?php

	echo "</body>\n";
	echo "</html>\n";

	//eof
?>