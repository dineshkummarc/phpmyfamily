<?php

	// family tree software
	// (c)2002 - 2003 Simon E Booth
	// All rights reserved
	// file to control display of personal information

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

	//get the details for the image
	$iquery = "SELECT * FROM family_images WHERE image_id = '".$_REQUEST["image"]."'";
	$iresult = mysql_query($iquery) or die("Image retreival query failed");

	while ($irow = mysql_fetch_array($iresult)) {
		$pquery = "SELECT name, date_of_birth FROM family_people WHERE person_id = '".$irow["person_id"]."'";
		$presult = mysql_query($pquery) or die("Person fetch failed");
		while ($prow = mysql_fetch_array($presult)) {

			// check security
			if ($_SESSION["id"] == 0 && $prow["date_of_birth"] > $restrictdate)
				$restricted = true;
			else
				$restricted = false;

			// fill out the header
			echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n\t\"http://www.w3.org/TR/html4/loose.dtd\">\n";
			echo "<HTML>\n";
			echo "<HEAD>\n";
			css_site();
			echo "<title>".$irow["title"]." (".$prow["name"].")</title>\n";
			echo "</HEAD>\n";
			echo "<BODY>\n";
	
			echo "<h2>".$irow["title"]."</h2>\n";
			echo "<hr>\n";
		}
		mysql_free_result($presult);
	
	echo "<center><img src=\"images/".$irow["image_id"].".jpg\" alt=\"".$irow["description"]."\"></center>\n";
	echo "<center><p>";
	if ($restricted)
		echo $restrictmsg;
	else
		echo formatdbdate($irow["date"]);
	echo "</p>\n";
	echo "<p>".$irow["description"]."</p></center>\n";
 	echo "<br><br>back\n";
	
	}
	mysql_free_result($iresult);

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
	
	echo "</body>";
	echo "</html>";

	// eof
?>