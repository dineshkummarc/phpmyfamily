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

	//get the details for the image
	$iquery = "SELECT * FROM images WHERE image_id = '".$_REQUEST["image"]."'";
	$iresult = mysql_query($iquery) or die("Image retreival query failed");

	while ($irow = mysql_fetch_array($iresult)) {
		$pquery = "SELECT name FROM people WHERE person_id = '".$irow["person_id"]."'";
		$presult = mysql_query($pquery) or die("Person fetch failed");
		while ($prow = mysql_fetch_array($presult)) {
			// fill out the header
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
	
	echo "<center><img src=images/".$irow["image_id"].".jpg></center>\n";
	echo "<p><center>".formatdbdate($irow["date"])."</center></p>\n";
	echo "<p><center>".$irow["description"]."</center></p>\n";
// 	echo "<br><br><a type=javascript href=javascript.go(-1)>back</a>\n";
	
	}
	mysql_free_result($iresult);
?>

<script language="JavaScript" type="text/javascript" src="pphlogger.js"></script>
<noscript><img alt="" src="http://logger.giric.com/pphlogger.php?id=family&st=img"></noscript>

<?php
	
	echo "</body>";
	echo "</html>";

	// eof
?>