<?php
	// index.php
	// family tree software
	// (c)2002 Simon E Booth
	// All rights reserved
	// Welcome page

	// include the database parameters
	include "db.inc.php";

	// include the browser 
	include "browser.inc.php";
	include "css.inc.php";

	// fill out the header
	echo "<HTML>\n";
	echo "<HEAD>\n";
	css_site();
	echo "<title>Family Tree</title>\n";
	echo "</HEAD>\n";
	
	// query for everybody
	$allquery = "SELECT person_id, SUBSTRING_INDEX(name, ' ', -1) AS surname, name FROM people ORDER BY surname, name";
	$allresult = mysql_query($allquery) or die("All people query failed");

	echo "<table>\n";
		echo "<tr width=100%>\n";
			echo "<td width=80% align=center>\n";
				echo "<h2>Family Tree</h2>\n";
			echo "</td>\n";
			echo "<td width=20% align=top>\n";
				echo "<form method=post action=people.php?person=>";
					echo mysql_num_rows($allresult)." people on file<br>\n";
					echo "<select name=person size=1>\n";
					echo "<option value=0 selected=selected>Jump to person</option>\n";
					while ($allrow = mysql_fetch_array($allresult)) {
						echo "<option value=".$allrow["person_id"].">".$allrow["surname"].", ".substr($allrow["name"], 0, strlen($allrow["name"]) - strlen($allrow["surname"]))."</option>\n";
					}
					echo "</select>";
					mysql_free_result($allresult);
					echo "<INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Go>\n";
				echo "</form>\n";
			echo "</td>\n";
		echo "</tr>\n";
	echo "</table>\n";

	echo "<p>For everybody in the family.  Please feel free to update any records with any missing information.  Contact <a href=mailto:simon.booth@giric.com>me</a> if you have any problems or if you have any pictures you would like to include.</p>\n";

	echo "</html>\n";

	//eof
?>