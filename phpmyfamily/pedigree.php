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
	$pquery = "SELECT * FROM family_people WHERE person_id = '".$_GET["person"]."'";
	$presult = mysql_query($pquery) or die("Person query failed");

	while ($prow = mysql_fetch_array($presult)) {

		// set security for living people (born after 01/01/1910)
		if ($_SESSION["id"] == 0 && $prow["date_of_birth"] > $restrictdate)
			$restricted = true;
		else
			$restricted = false;

		// if trying to access a restriced person
		if ($restricted)
			die("Possible security breach");

		// fill out the header
		echo "<html>";
		echo "<head>";
		css_site();
		echo "<title>Pedigree for: ".$prow["name"]."</title>";
		echo "</HEAD>";

		echo $prow["name"];

		$query = "SELECT * FROM family_people WHERE person_id = '".$prow["father_id"]."'";
		$row = 



	}

	// close of the file
	mysql_free_result($presult);
	echo "</html>";

	// eof
?>