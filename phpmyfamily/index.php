<?php
	
	// index.php
	// family tree software
	// (c)2002 - 2003 Simon E Booth
	// All rights reserved
	// Welcome page

	// include the database parameters
	include "inc/session.inc.php";
	include "inc/db.inc.php";
	include "inc/functions.inc.php";

	// include the browser 
	include "inc/browser.inc.php";
	include "inc/css.inc.php";

	// fill out the header
	echo "<HTML>\n";
	echo "<HEAD>\n";
	css_site();
	echo "<title>Family Tree</title>\n";
	echo "</HEAD>\n";
	echo "<BODY>\n";
	
	echo "<table>\n";
		echo "<tr width=100%>\n";
			echo "<td width=80% align=center>\n";
				echo "<h2>Family Tree</h2>\n";
			echo "</td>\n";
			echo "<td width=20% align=top>\n";
				echo "<form method=post action=passthru.php?func=jump>";
					listpeeps("person");
					echo "<INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Go>\n";
				echo "</form>\n";
			echo "</td>\n";
		echo "</tr>\n";
	echo "</table>\n";

	echo "<p>For everybody in the family.  Please feel free to update any records with any missing information.  Contact <a href=mailto:simon.booth@giric.com>me</a> if you have any problems or if you have any pictures you would like to include.</p>\n";

	echo "<TABLE>\n";
		echo "<TR>\n";
			echo "<TH COLSPAN=2>Last 20 People Updated</TH>\n";
		echo "</TR>\n";
		echo "<TR>\n";
			echo "<TH>Person</TH>\n";
			echo "<TH>Updated</TH>\n";
		echo "</TR>\n";
		$query = "SELECT person_id, name, updated FROM people ORDER BY updated DESC LIMIT 0,20";
		$result = mysql_query($query) or die(mysql_error($result));
		$i = 0;
		while ($row = mysql_fetch_array($result)) {
			if ($i == 0 || $i == 2 || $i == 4 || $i == 6 || $i == 8 || $i == 10 || $i == 12 || $i == 14 || $i == 16 || $i == 18 || $i == 20)
				$bgcolor = "#CCCCCC";
			else
				$bgcolor = "#DDDDDD";
			echo "<TR>\n";
				echo "<TD bgcolor=".$bgcolor."><A HREF=people.php?person=".$row["person_id"].">".$row["name"]."</A></TD>\n";
				echo "<TD bgcolor=".$bgcolor.">".date('H:i d/m/Y', convertstamp($row["updated"]))."</TD>\n";
			echo "</TR>\n";
			$i++;
		}
		mysql_free_result($result);
	echo "</TABLE>\n";
?>

<script language="JavaScript" type="text/javascript" src="pphlogger.js"></script>
<noscript><img alt="" src="http://logger.giric.com/pphlogger.php?id=family&st=img"></noscript>

<?php

	echo "</body>\n";
	echo "</html>\n";

	//eof
?>