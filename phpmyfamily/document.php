<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2005  Simon E Booth (simon.booth@giric.com)

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

	// include the configuration parameters and function
	include "inc/config.inc.php";

	// get the passed id and set it to 0 if nothing passed
	@$docid = $_REQUEST["docid"];
	if (!isset($docid))
		$docid = 0;

	$dquery = "SELECT * FROM ".$tblprefix."documents WHERE id = '".$docid."'";
	$dresult = mysql_query($dquery);

	while ($drow = mysql_fetch_array($dresult)) {
		// see if we have mime capabilities
		if (function_exists("mime_content_type"))
			$type = mime_content_type("docs/".$drow["id"]);
		else
			$type = "application/unknown";

		// fire off a few headers
		header("Content-Location: docs/".$drow["id"]);
		header("Content-Type: ".$type);
		header("Content-Length: ".filesize("docs/".$drow["id"]));
		header("Content-Disposition: attachment; filename=\"".$drow["file_name"]."\"");

		readfile("docs/".$drow["id"]);

	}

	// eof
?>
