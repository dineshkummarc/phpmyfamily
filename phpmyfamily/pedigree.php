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
			die(include "inc/forbidden.in.php");

		// pickout father and mother for use in queries
		// set to -1 to avoid too many siblings!!! :-)
		$father = $prow["father_id"];
		if ($father == 0) $father = -1;
		$mother = $prow["mother_id"];
		if ($mother == 0) $mother = -1;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="<?php echo $dir; ?>">
<head>
<link rel="stylesheet" href="<?php echo $style; ?>" type="text/css" />
<link rel="shortcut icon" href="images/favicon.ico" />
<meta name="author" content="Simon E Booth" />
<meta name="publisher" content="Giric" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
<meta http-equiv="content-language" content="<?php echo $clang; ?>" />
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
			<td width="35%" valign="top">
				<form method="get" action="pedigree.php">
				<?php listpeeps("person", 0, "A", $_REQUEST["person"]); ?>
				</form>
<?php
			if ($_SESSION["id"] <> 0) { ?>
				<?php echo $strLoggedIn; ?><a href="index.php" class="hd_link"><?php echo $_SESSION["name"]; ?></a>: (<a href="passthru.php?func=logout" class="hd_link"><?php echo $strLogout; ?></a><?php if ($_SESSION["admin"] == 1) echo ", <a href=\"admin.php\" class=\"hd_link\">".$strAdmin."</a>"; ?>)
<?php 		}
			else {
?>
				<?php echo $strLoggedOut; ?><a href="index.php" class="hd_link"><?php echo $strHome; ?></a>
<?php
			} ?>
			</td>
		</tr>
	</table>

<hr />

<!--Main body-->

<table width="100%">
  <tbody>
    <tr>
      <td width="50%" align="left" bgcolor="#FFFFFF"><?php echo $prow["name"]; ?><br />Born: <?php echo formatdate($prow["DOB"]); ?> </td>
    </tr>
  </tbody>
</table>

<?php
	}

	// close of the file
	mysql_free_result($presult);

	include "inc/footer.inc.php";

	// eof
?>
