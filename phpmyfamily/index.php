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

	// send the headers
	ini_set("arg_separator.output", "&amp;");
	ini_set('session.use_trans_sid', false);

	// include the configuration parameters and functions
	include "inc/config.inc.php";
	include "inc/functions.inc.php";
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
<meta name="keywords" content="Genealogy phpmyfamily<?php
	$fname = "SELECT SUBSTRING_INDEX(name, ' ', -1) AS surname FROM ".$tblprefix."people";
	if ($_SESSION["id"] == 0)
		$fname .= " WHERE date_of_birth < '".$restrictdate."'";
	$fname .= " GROUP BY surname LIMIT 0,18";
	$rname = mysql_query($fname) or die($err_keywords);
	if (mysql_num_rows($rname) <> 0) {
		while ($row = mysql_fetch_array($rname))
			echo " ".$row["surname"];
	}
?>" />
<meta name="description" content="<?php echo $desc; ?>" />
<meta name="page-topic" content="Genealogy" />
<meta name="audience" content="All" />
<meta name="expires" content="0" />
<meta name="page-type" content="Private homepage" />
<meta name="robots" content="INDEX,FOLLOW" />
<title>phpmyfamily: <?php echo $desc; ?></title>
</head>
<body>

<table class="header">
	<tr>
		<td width="80%" align="center">
			<h1>phpmyfamily</h1>
			<h3><?php echo $desc; ?></h3>
		</td>
		<td width="20%" valign="top">
			<form method="get" action="people.php">
				<?php listpeeps("person"); ?>
			</form>
<?php if ($_SESSION["id"] <> 0) { ?>
			<br /><?php echo $strLoggedIn; ?><?php echo $_SESSION["name"]; ?>: (<a href="passthru.php?func=logout" class="hd_link"><?php echo $strLogout; ?></a><?php if ($_SESSION["admin"] == 1) echo ", <a href=\"admin.php\" class=\"hd_link\">".$strAdmin."</a>"; ?>)
<?php } ?>
		</td>
	</tr>
</table>

<hr />

<table width="100%">
	<tr>
		<td width="50%" valign="top">
<?php
			// include login form if not logged in
			if ($_SESSION["id"] == 0)
				include "inc/loginform.inc.php";
			else
				include "inc/passwdform.inc.php";
?>

			<br /><br />
				<table>
					<tr>
						<th colspan="2"><?php echo $strStats; ?></th>
					</tr>
					<tr>
						<th width="200"><?php echo $strArea; ?></th>
						<th width="50"><?php echo $strNo; ?></th>
					</tr>
					<tr>
						<td class="tbl_odd"><?php echo ucwords($strOnFile); ?></td>
						<td class="tbl_odd" align="right">
<?php
					$query = "SELECT count(*) as number FROM ".$tblprefix."people";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
?>
						</td>
					</tr>
					<tr>
						<td class="tbl_even"><?php echo $strCensusRecs; ?></td>
						<td class="tbl_even" align="right">
<?php
					$query = "SELECT count(*) as number FROM ".$tblprefix."census";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
?>
						</td>
					</tr>
					<tr>
						<td class="tbl_odd"><?php echo $strImages; ?></td>
						<td class="tbl_odd" align="right">
<?php
					$query = "SELECT count(*) as number FROM ".$tblprefix."images WHERE image_id <> '10000'";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
?>
						</td>
					</tr>
					<tr>
						<td class="tbl_even"><?php echo $strDocTrans; ?></td>
						<td class="tbl_even" align="right">
<?php
					$query = "SELECT count(*) as number FROM ".$tblprefix."documents";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
?>
						</td>
					</tr>
				</table>

			</td>
			<td width="50%" align="right">
				<!--list of last 20 updated people-->
				<table width="80%">
					<tr>
						<th colspan="2"><?php echo $strLast20; ?></th></th>
					</tr>
					<tr>
						<th><?php echo $strPerson; ?></th>
						<th><?php echo $strUpdated; ?></th>
					</tr>
<?php
					$query = "SELECT person_id, name, DATE_FORMAT(updated, ".$datefmt.") AS ddate FROM ".$tblprefix."people";
					if ($_SESSION["id"] == 0)
						$query .= " WHERE date_of_birth < '".$restrictdate."'";
					$query .= " ORDER BY updated DESC LIMIT 0,20";
					$result = mysql_query($query) or die($err_changed);
					$i = 0;
					while ($row = mysql_fetch_array($result)) {
						if ($i == 0 || fmod($i, 2) == 0)
							$class = "tbl_odd";
						else
							$class = "tbl_even";
?>
					<tr>
						<td class="<?php echo $class; ?>"><a href="people.php?person=<?php echo $row["person_id"]; ?>"><?echo $row["name"]; ?></a></td>
						<td class="<?php echo $class; ?>"><?php echo $row["ddate"]; ?></td>
					</tr>
<?php
						$i++;
					}
					mysql_free_result($result);

					if ($_SESSION["id"] <> 0) {
?>
					<tr><td colspan="2"><a href="edit.php?func=add&amp;area=detail">Add</a> a new person</td></tr>
					<?php } ?>
				</table>

			</td>
		</tr>
	</table>

<?php

	include "inc/footer.inc.php";
	
	//eof
?>
