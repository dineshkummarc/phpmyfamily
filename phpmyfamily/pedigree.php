<?php
	
	// family tree software
	// (c)2002 - 2003 Simon E Booth
	// All rights reserved
	// File to control editing and creation of new data.

	// include the database parameters
	include "inc/db.inc.php";

	// include the browser 
	include "inc/browser.inc.php";
	include "inc/css.inc.php";

	// check to see if we have a person
	if (!isset($_GET["person"])) $person = 1;

	// the query for the database
	$pquery = "SELECT * FROM people WHERE person_id = '".$_GET["person"]."'";
	$presult = mysql_query($pquery) or die("Person query failed");

	while ($prow = mysql_fetch_array($presult)) {

		// fill out the header
		echo "<html>";
		echo "<head>";
		css_site();
		echo "<title>Pedigree for: ".$prow["name"]."</title>";
		echo "</HEAD>";

		$father[0] = $prow["father_id"];
		$mother[0] = $prow["mother_id"];

		
	

		echo $prow["name"]."<br>\n";

		echo $father[0]."<br>\n";
		echo $mother[0]."<br>\n";




	}

	// close of the file
	mysql_free_result($presult);
	echo "</html>";

	// eof
?>