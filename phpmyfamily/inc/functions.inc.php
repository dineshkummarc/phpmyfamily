<?php
		
	// Copyright (c)2002 - 2003 Simon E Booth
	// Family Tree Software
	// functions.inc.php

	// some definitions
	$version = "1.1.1";

	// convert a timestamp into a proper date/time
	function convertstamp($origdate) {
		$year = substr($origdate, 0, 4);
		$day = substr($origdate, 4, 2);
		$month = substr($origdate, 6, 2);
		$hour = substr($origdate, 8, 2) -1;		// poor correction for server being 1hr 
		$minute = substr($origdate, 10, 2);
		$sec = substr($origdate, -2);
		$stamp = mktime($hour,$minute,$sec,$day,$month,$year);
		return $stamp;
	}	// end of convertstamp()

	// format a MySQL date as 
	function formatdbdate($origdate) {
		$year = substr($origdate, 0, 4);
		$month = substr($origdate, 5, 2);
		$day = substr($origdate, -2);
		$retval = $day."/".$month."/".$year;
		if ($year == 0)
			$retval = "";
		return $retval;
	}	// end of formatdbdate()

	function listpeeps($form, $omit = 0, $gender = "A", $default = 0) {
		// create the query based on the parameters
		$query = "SELECT person_id, SUBSTRING_INDEX(name, ' ', -1) AS surname, name FROM people WHERE person_id <> '".$omit."'";
		switch ($gender) {
			case "M":
				$query .= " AND gender = 'M'";
				break;
			case "F":
				$query .= " AND gender = 'F'";
				break;
			default:
				break;
		}		
		$query .= " ORDER BY surname, name";
		$result = mysql_query($query) or die(mysql_error($result));

		// create the select list
		if ($gender == "A" && $omit == 0)
			echo mysql_num_rows($result)." people on file<BR>\n";
		if ($gender == "A" && $omit <> 0)
			echo (mysql_num_rows($result) + 1)." people on file<BR>\n";
		echo "<SELECT NAME=".$form." SIZE=1>\n";
		if ($default == 0)
			echo "<OPTION VALUE=0>Jump to person</OPTION>\n";
		while ($row = mysql_fetch_array($result)) {
			echo "<OPTION VALUE=".$row["person_id"];
			if ($row["person_id"] == $default)
				echo " SELECTED=SELECTED";
			echo ">".$row["surname"].", ".substr($row["name"], 0, strlen($row["name"]) - strlen($row["surname"]))."</OPTION>\n";
		}
		echo "</SELECT>";

		// clean up after self
		mysql_free_result($result);
	}	// end of listpeeps()

	// timestamp a particular person for last updated
	function stamppeeps($person) {
		$query = "UPDATE people SET updated = NOW() WHERE person_id = '".$person."'";
		$result = mysql_query($query);
	}	// end of stamppeeps()

	// process an uploaded image
	function processimage($image) {
		$size = getimagesize($_FILES["userfile"]["tmp_name"]);
		
		// error with image creation so fail and back out
		switch ($size[2]) {
			case 2:
				// get the image resource from the uploaded file
				$incoming = imagecreatefromjpeg($_FILES["userfile"]["tmp_name"]);
				break;
			default:
				$query = "DELETE FROM images WHERE image_id = '".$image."'";
				$result = mysql_query($query);
				return false;
				break;
		}

		// work out the ratio of width to height
		$ratio = $size[0] / $size[1];

		// create the thumbnail
		$thumbw = 100;
		$thumbh = 100;
		$thumb = imagecreate($thumbw, $thumbh);
		$background = imagecolorallocate($thumb, 147, 150, 147);
		imagefill($thumb, 0, 0, $background);

		// basics for creating the main image
		$maxheight = 500;
		$maxwidth = 500;

		// do different things depending on orientation of image
		if ($ratio < 1) {		// higher than wide
			// create a file with maximum height
			$file = imagecreate($maxwidth * $ratio, $maxheight);
			imagecopyresized($file, $incoming, 0, 0, 0, 0, ($maxheight * $ratio), $maxheight, $size[0], $size[1]);

			// workout border for thumbnail
			$border = ($thumbw - $thumbw * $ratio) / 2;
			imagecopyresized($thumb, $incoming, $border, 0, 0, 0, ($thumbw * $ratio), $thumbh, $size[0], $size[1]);
		}
		else {					// wider than high
			// create a file with maximum width
			$file = imagecreate($maxwidth, $maxheight / $ratio);
			imagecopyresized($file, $incoming, 0, 0, 0, 0, $maxwidth, ($maxwidth / $ratio), $size[0], $size[1]);

			// workout border for thumbnail
			$border = ($thumbh - $thumbh / $ratio) / 2;
			imagecopyresized($thumb, $incoming, 0, $border, 0, 0, $thumbw, ($thumbh / $ratio), $size[0], $size[1]);
		}
	
		// set as interlaced and save to paths
		imageinterlace($thumb, 1);
		imagejpeg($thumb, "images/tn_".$image.".jpg", 100);
		imageinterlace($file, 1);
		imagejpeg($file, "images/".$image.".jpg", 95);

		return true;
	}	// end of processimage();

	// eof
?>