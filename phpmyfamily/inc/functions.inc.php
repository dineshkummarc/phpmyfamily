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

	// function: convertstamp
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

	// function formatdate
	// allows unknown to be displayed when no details kknown
	function formatdate($origdate) {
		global $strUnknown;
		global $nulldate;

		// if there are any non-zero numbers, then display as is
		if ($origdate == $nulldate)
			return $strUnknown;
		// else return unknown
		else
			return $origdate;
	} // end of formatdate()

	// function: listpeeps
	// list all people in database that current request has access to
	function listpeeps($form, $omit = 0, $gender = "A", $default = 0, $auto = 1) {

		// declare global variables used within
		global $restrictdate;
		global $tblprefix;
		global $err_listpeeps;
		global $strOnFile;
		global $strSelect;
		global $strUnknown;

		// create the query based on the parameters
		$query = "SELECT person_id, SUBSTRING_INDEX(name, ' ', -1) AS surname, name, YEAR(date_of_birth) AS year FROM ".$tblprefix."people WHERE person_id <> '".$omit."'";

		// if the user is not logged in, only show people pre $restrictdate
		if ($_SESSION["id"] == 0)
			$query .= " AND date_of_birth < '".$restrictdate."'";

		// need the gender if listing for mother or father selection
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

		// and sort the query
		$query .= " ORDER BY surname, name";
		$result = mysql_query($query) or die($err_listpeeps);

		// show the number of people in the list
		if ($gender == "A" && $omit == 0)
			echo mysql_num_rows($result)." ".$strOnFile."<br />\n";
		if ($gender == "A" && $omit <> 0)
			echo (mysql_num_rows($result) + 1)." ".$strOnFile."<br />\n";

		// start building the select list
		echo "<select name=\"".$form."\" size=\"1\"";
		//if needed, set form to auto submit
		if ($auto == 1)
			echo " onchange=\"this.form.submit()\"";
		echo ">\n";
		// if no person selected, show generic at top of list
		if ($default == 0)
			echo "<option value=\"0\">".$strSelect."</option>\n";
		//
		while ($row = mysql_fetch_array($result)) {
			$year = $row["year"];
			if ($year == 0)
				$year = $strUnknown;
			echo "<option value=\"".$row["person_id"]."\"";
			if ($row["person_id"] == $default)
				echo " selected=\"selected\"";
				echo ">".$row["surname"].", ".substr($row["name"], 0, strlen($row["name"]) - strlen($row["surname"]))."(b. ".$year.")</option>\n";
		}
		echo "</select>";

		// clean up after self
		mysql_free_result($result);
	}	// end of listpeeps()

	// function: stamppeeps
	// timestamp a particular person for last updated
	function stamppeeps($person) {
		// declare globals used within
		global $tblprefix;

		$query = "UPDATE ".$tblprefix."people SET updated = NOW() WHERE person_id = '".$person."'";
		$result = mysql_query($query);
	}	// end of stamppeeps()

	// function: processimage
	// process an uploaded image
	function processimage() {
		// define globals used within
		global $tblprefix;
		global $err_image_insert;

		// image creation needs masses of memory
		// this is set too large! but needs to be to process a 1MB jpg!
		// if left as standard 8M, image creation fails and you get error messages and blank thumbnails
		ini_set("memory_limit", "32M");

		$size = getimagesize($_FILES["userfile"]["tmp_name"]);

		// get the image resource from the uploaded file
		switch ($size[2]) {
			case 1:
				// it's a gif
				$incoming = @imagecreatefromgif($_FILES["userfile"]["tmp_name"]);
				break;
			case 2:
				// it's a jpeg
				$incoming = @imagecreatefromjpeg($_FILES["userfile"]["tmp_name"]);
				break;
			case 3:
				// it's a png
				$incoming = @imagecreatefrompng($_FILES["userfile"]["tmp_name"]);
				break;
			default:
				// don't know what it is so just bail-out
				return false;
				break;
		}

		if (!$incoming) {
			return false;
		}

		$iquery = "INSERT INTO ".$tblprefix."images (person_id, title, date, description) VALUES ('".$_REQUEST["person"]."', '".$_POST["frmTitle"]."', '".$_POST["frmDate"]."', '".$_POST["frmDesc"]."')";;
		$iresult = mysql_query($iquery) or die($err_image_insert);
		$image = mysql_insert_id();

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

	// function: list_enums
	// Produce a select list of an enum column
	function list_enums($table, $col, $name, $value = 0) {
		global $err_list_enums;

		// get an array of the values in the column
		$query = "SHOW COLUMNS FROM ".$table." LIKE '".$col."'";
		$result = mysql_query($query) or die($err_list_enums);

		// do some processing ?
		while ($row = mysql_fetch_array($result)) {
			$enum        = str_replace('enum(', '', $row['Type']);
			$enum        = ereg_replace('\\)$', '', $enum);
			$enum        = explode('\',\'', substr($enum, 1, -1));
			$enum_cnt    = count($enum);
			$default	 = $row["Default"];
		}

		// decide if we want column default, or a value passed as arg
		if (func_num_args() == 4)
			$select = $value;			// we've been given a value to select
		else
			$select = $default;			// just select the column default value

		// do the output
		echo "<select name=".$name." size=1>";
		for ($j = 0; $j < $enum_cnt; $j++) {
			$enum_atom = str_replace('\'\'', '\'', str_replace('\\\\', '\\', $enum[$j]));
			echo '<option value="' . urlencode($enum_atom) . '"';
			if ($enum_atom == $select)
					echo ' selected="selected"';
			echo '>' . htmlspecialchars($enum_atom) . '</option>' . "\n";
		}
		echo "</select>";

		// clean up
		mysql_free_result($result);
	}	// end of list_enums()

	// eof
?>
