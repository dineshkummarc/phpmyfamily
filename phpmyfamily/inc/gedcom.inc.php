<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2004  Simon E Booth (simon.booth@giric.com)

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

	$gedname = $_FILES["gedfile"]["name"];
	$gedfile = $_FILES["gedfile"]["tmp_name"];
	$gedarray = array();
	$people = array();
	$marriages = array();

	ini_Set("auto_detect_line_endings", TRUE);

?>

<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h3>Importing GedCom file: <?php echo $gedname; ?></h3>  </td>
    </tr>
  </tbody>
</table>

<br>
<!--Check we have a valid file-->
Checking uploaded file is valid:
<?php
	if (!empty($gedfile) && file_exists($gedfile)) {
		echo " OK<br>\n";
	} else {
		echo " NO!<br>\n";
		die("Error with gedcom file");
	}
?>
<!--Read the file into an array-->
Reading in file:
<?php
	$handle = fopen ($gedfile, "r");
	while (!feof ($handle)) {
    	$buffer = fgets($handle, 4096);
		// need to trim the annoying newline
		// from end of buffer
		$gedarray[] = rtrim($buffer);
	}
	fclose ($handle);
	echo " OK<br>\n";
?>
<!--parse the array-->
Verifying GedCom data:
<?php
	if (in_array("1 GEDC", $gedarray) && in_array("2 VERS 5.5", $gedarray))
		echo " OK<br>\n";
	else {
		die ("Doesn't seem to be a valid GedCom file!");
	}
?>
<!--Write out people from array-->
Analyzing people data:<br>
<?php
	$i = 0;
	$row = 2;
	$end = count($gedarray);

	while ($row < $end) {
		if (substr($gedarray[$row], 0, 4) == "0 @I") {
			$i++;
			$slice = $row + 1;
			while (substr($gedarray[$slice], 0, 1) != "0")
				$slice++;
			$slice = $slice - $row;
			$people[$i] = array_slice($gedarray, $row, $slice);
		}
		$row++;
	}

	$q = 1;
	while ($q <= $i) {
		echo "Record No:".$q."<br>\n";
		print_r($people[$q]);
		echo "<br>\n";
		$q++;
	}

	echo $i." records found<br>\n";
	die("Can't get beyond here yet!!!");
?>
<!--Write out marriages from array-->
Analyzing marriage data:
<?php
	echo " TODO<br>\n";
?>
<!--Insert everybody into database-->
Inserting people into database:
<?php
	echo " TODO<br>\n";
?>
<!--Insert all marriages into the database-->
Inserting marriages into database:
<?php
	echo " TODO<br>\n";
?>
<!--Phew - we've reached the end-->
Done!!!<br>
<?php

	// eof
?>
