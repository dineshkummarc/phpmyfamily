<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2004  Simon E Booth

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

	// include the configuration parameters and functions
	include "inc/config.inc.php";

	// check to see if we have a person
	if (!isset($_GET["person"])) $person = 1;

	// the query for the database
	$pquery = "SELECT *, DATE_FORMAT(date_of_birth, ".$datefmt.") AS DOB, DATE_FORMAT(date_of_death, ".$datefmt.") AS DOD FROM ".$tblprefix."people WHERE person_id = '".$_GET["person"]."'";
	$presult = mysql_query($pquery) or die($err_person);

	while ($prow = mysql_fetch_array($presult)) {

		// set security for living people (born after 01/01/1910)
		if ($_SESSION["id"] == 0 && $prow["date_of_birth"] > $restrictdate)
			$restricted = true;
		else
			$restricted = false;

		// if trying to access a restriced person
		if ($restricted)
			die(include "inc/forbidden.inc.php");

		// Use an array to get people references
		$ids = array_fill(1, 15, 0);
		$ids[1] = $_GET["person"];
		$ids[2] = $prow["father_id"];
		$ids[3] = $prow["mother_id"];

		for ($i = 2; $i < 8; $i++) {
			$tquery = "SELECT * FROM ".$tblprefix."people WHERE person_id = '".$ids[$i]."'";
			$tresult = mysql_query($tquery) or die($err_person);
			while ($trow = mysql_fetch_array($tresult)) {
				$ids[($i * 2)] 		= $trow["father_id"];
				$ids[($i * 2 + 1)] 	= $trow["mother_id"];
			}
			mysql_free_result($tresult);
		}

	function person_disp($dip) {
		global $datefmt;
		global $tblprefix;
		global $err_person;
		global $strBorn;
		global $strDied;

		$dquery = "SELECT *, DATE_FORMAT(date_of_birth, ".$datefmt.") AS DOB, DATE_FORMAT(date_of_death, ".$datefmt.") AS DOD FROM ".$tblprefix."people WHERE person_id = '".$dip."'";
		$dresult = mysql_query($dquery) or die($err_person);

		while ($drow = mysql_fetch_array($dresult)) {
			if ($drow["gender"] == "M")
				$class = "tbl_odd";
			else
				$class = "tbl_even";

			echo "<td bgcolor=\"#FFFFFF\" width=\"22%\" class=\"".$class."\">";
			echo "<a href=\"pedigree.php?person=".$dip."\">".$drow["name"]."</a><br />";
			echo formatdate($drow["DOB"])." - ".formatdate($drow["DOD"])."<br />";
			echo $strBorn.": ".$drow["birth_place"]."<br />";
			echo $strDied.": ".$drow["death_reason"]."<br />";
			echo "</td>\n";
		}

		mysql_free_result($dresult);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $clang; ?>" lang="<?php echo $clang; ?>" dir="<?php echo $dir; ?>">
<head>
<link rel="stylesheet" href="<?php echo $style; ?>" type="text/css" />
<link rel="shortcut icon" href="images/favicon.ico" />
<meta name="author" content="Simon E Booth" />
<meta name="publisher" content="Giric" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
<meta name="copyright" content="2002-2003 Simon E Booth" />
<meta name="description" content="<?php echo $desc; ?>" />
<meta name="page-topic" content="Genealogy" />
<meta name="audience" content="All" />
<meta name="expires" content="0" />
<meta name="page-type" content="Private homepage" />
<meta name="robots" content="INDEX,FOLLOW" />
<title><?php echo $strPedigreeOf." ".$prow["name"]; ?></title>
</head>
<body>

<!--titles-->
	<table width="100%" class="header">
		<tr>
			<td width="65%" align="center" valign="top">
				<h2><?php echo $strPedigreeOf." ".$prow["name"] ?></h2>
				<h3><?php
					if ($restricted)
						echo "(".$restrictmsg." - ".$restrictmsg.")";
					else
						echo "(".formatdate($prow["DOB"])." - ".formatdate($prow["DOD"]).")";?></h3>
			</td>
			<td width="35%" valign="top" align="right">
				<form method="get" action="pedigree.php">
				<?php listpeeps("person", 0, "A", $_REQUEST["person"]); ?>
				</form>
<?php
			if ($_SESSION["id"] <> 0) { ?>
				<?php echo $strLoggedIn; ?><a href="index.php" class="hd_link"><?php echo $_SESSION["name"]; ?></a>: (<a href="passthru.php?func=logout" class="hd_link"><?php echo $strLogout; ?></a><?php if ($_SESSION["admin"] == 1) echo ", <a href=\"admin.php\" class=\"hd_link\">".$strAdmin."</a>"; ?>)<br /><?php echo "<a href=\"people.php?person=".$_REQUEST["person"]."\">".strtolower($strBack)."</a> ".$strToDetails; ?>
<?php 		}
			else {
?>
				<?php echo $strLoggedOut; ?><a href="index.php" class="hd_link"><?php echo $strHome; ?></a><br /><?php echo "<a href=\"people.php?person=".$_REQUEST["person"]."\">".strtolower($strBack)."</a> ".$strToDetails; ?>
<?php
			} ?>
			</td>
		</tr>
	</table>

<hr />

<!--Main body-->

<table width="100%" cellspacing="0">
  <tbody>
    <tr> <!--row 1-->
      <td width="22%">  </td>
      <td width="4%">  </td>
      <td width="22%">  </td>
      <td width="4%">  </td>
      <td width="22%">  </td>
      <td width="4%"<?php if($ids[8] != 0) echo " class=\"tr\""; ?>> </td>
<?php
	if ($ids[8] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[8]);
?>
    </tr>
    <tr> <!--row 2-->
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[4] != 0) echo " class=\"tr\""; ?>></td>
<?php
	if ($ids[4] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[4]);
?>
      <td<?php
		if ($ids[8] != 0 && $ids[9] != 0)
			echo " class=\"outer\"";
		elseif ($ids[8] != 0)
			echo " class=\"rt\"";
		elseif ($ids[9] != 0)
			echo " class=\"rb\"";
?>>  </td>
      <td>  </td>
    </tr>
    <tr> <!--row 3-->
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[4] != 0) echo " class=\"vert\""; ?>>  </td>
      <td>  </td>
      <td<?php if($ids[9] != 0) echo " class=\"br\""; ?>></td>
<?php
	if ($ids[9] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[9]);
?>
    </tr>
    <tr> <!--row 4-->
      <td>  </td>
      <td<?php if($ids[2] != 0) echo " class=\"tr\""; ?>></td>
<?php
	if ($ids[2] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[2]);
?>
      <td<?php
		if ($ids[4] != 0 && $ids[5] != 0)
			echo " class=\"outer\"";
		elseif ($ids[4] != 0)
			echo " class=\"rt\"";
		elseif ($ids[5] != 0)
			echo " class=\"rb\"";
?>> </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
    </tr>
    <tr> <!--row 5-->
      <td>  </td>
      <td<?php if($ids[2] != 0) echo " class=\"vert\""; ?>></td>
      <td>  </td>
      <td<?php if($ids[5] != 0) echo " class=\"vert\""; ?>>  </td>
      <td>  </td>
      <td<?php if($ids[10] != 0) echo " class=\"tr\""; ?>></td>
<?php
	if ($ids[10] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[10]);
?>
    </tr>
    <tr> <!--row 6-->
      <td>  </td>
      <td<?php if($ids[2] != 0) echo " class=\"vert\""; ?>></td>
      <td>  </td>
      <td<?php if($ids[5] != 0) echo " class=\"br\""; ?>></td>
<?php
	if ($ids[5] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[5]);
?>
      <td<?php
		if ($ids[10] != 0 && $ids[11] != 0)
			echo " class=\"outer\"";
		elseif ($ids[10] != 0)
			echo " class=\"rt\"";
		elseif ($ids[11] != 0)
			echo " class=\"rb\"";
?>>  </td>
      <td>  </td>
    </tr>
    <tr> <!--row 7-->
      <td>  </td>
      <td<?php if($ids[2] != 0) echo " class=\"vert\""; ?>></td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[11] != 0) echo " class=\"br\""; ?>> </td>
<?php
	if ($ids[11] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[11]);
?>
    </tr>
    <tr> <!--row 8-->
      <?php person_disp($ids[1]); ?>
      <td<?php
		if ($ids[2] != 0 && $ids[3] != 0)
			echo " class=\"outer\"";
		elseif ($ids[2] != 0)
			echo " class=\"rt\"";
		elseif ($ids[3] != 0)
			echo " class=\"rb\"";
?>> </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
    </tr>
    <tr> <!--row 9-->
      <td>  </td>
      <td<?php if($ids[3] != 0) echo " class=\"vert\""; ?>> </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[12] != 0) echo " class=\"tr\""; ?>></td>
<?php
	if ($ids[12] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[12]);
?>
    </tr>
    <tr> <!--row 10-->
      <td>  </td>
      <td<?php if($ids[3] != 0) echo " class=\"vert\""; ?>></td>
      <td>  </td>
      <td<?php if($ids[6] != 0) echo " class=\"tr\""; ?>></td>
<?php
	if ($ids[6] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[6]);
?>
      <td<?php
		if ($ids[12] != 0 && $ids[13] != 0)
			echo " class=\"outer\"";
		elseif ($ids[12] != 0)
			echo " class=\"rt\"";
		elseif ($ids[13] != 0)
			echo " class=\"rb\"";
?>></td>
      <td>  </td>
    </tr>
    <tr> <!--row 11-->
      <td>  </td>
      <td<?php if($ids[3] != 0) echo " class=\"vert\""; ?>></td>
      <td>  </td>
      <td<?php if($ids[6] != 0) echo " class=\"vert\""; ?>>  </td>
      <td>  </td>
      <td<?php if($ids[13] != 0) echo " class=\"br\""; ?>></td>
<?php
	if ($ids[13] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[13]);
?>
    </tr>
    <tr> <!--row 12-->
      <td>  </td>
      <td<?php if($ids[3] != 0) echo " class=\"br\""; ?>> </td>
<?php
	if ($ids[3] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[3]);
?>
      <td<?php
		if ($ids[6] != 0 && $ids[7] != 0)
			echo " class=\"outer\"";
		elseif ($ids[6] != 0)
			echo " class=\"rt\"";
		elseif ($ids[7] != 0)
			echo " class=\"rb\"";
?>></td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
    </tr>
    <tr> <!--row 13-->
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[7] != 0) echo " class=\"vert\""; ?>>  </td>
      <td>  </td>
      <td<?php if($ids[14] != 0) echo " class=\"tr\""; ?>></td>
<?php
	if ($ids[14] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[14]);
?>
    </tr>
    <tr> <!--row 14-->
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[7] != 0) echo " class=\"br\""; ?>></td>
<?php
	if ($ids[7] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[7]);
?>
      <td<?php
		if ($ids[14] != 0 && $ids[15] != 0)
			echo " class=\"outer\"";
		elseif ($ids[14] != 0)
			echo " class=\"rt\"";
		elseif ($ids[15] != 0)
			echo " class=\"rb\"";
?>> </td>
      <td>  </td>
    </tr>
    <tr> <!--row 15-->
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td>  </td>
      <td<?php if($ids[15] != 0) echo " class=\"br\""; ?>></td>
<?php
	if ($ids[15] == 0)
		echo "\t<td></td>\n";
	else
		person_disp($ids[15]);
?>
    </tr>
  </tbody>
</table>

<!--End of main body-->
<?php
	}

	// close of the file
	mysql_free_result($presult);

	include "inc/footer.inc.php";

	// eof
?>
