<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2003  name of author

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
	ini_set('session.use_trans_sid', false);
	// include the configuration parameters and functions
	include "inc/config.inc.php";
	include "inc/functions.inc.php";

	// include the browser 
	include "inc/browser.inc.php";
	include "inc/css.inc.php";

	// check to see if we have a person
	if (!isset($_GET["person"])) $person = 1;

	// the query for the database
	$pquery = "SELECT * FROM ".$tblprefix."people WHERE person_id = '".$_GET["person"]."'";
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

		$query = "SELECT * FROM ".$tblprefix."people WHERE person_id = '".$prow["father_id"]."'";
		$row = 



	}

	// close of the file
	mysql_free_result($presult);
	echo "</html>";

	// eof
?>
