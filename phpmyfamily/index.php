<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2003  Simon E Booth (simon.booth@giric.com)

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
	header('content-Type: text/html; charset=ISO-8859-1');
	header('content-Language: en');

	// include the configuration parameters and functions
	include "inc/config.inc.php";
	include "inc/functions.inc.php";

	// include the browser 
	include "inc/browser.inc.php";
	include "inc/css.inc.php";

	// fill out the header
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta name="author" content="Simon E Booth">
<meta name="publisher" content="Giric">
<meta name="copyright" content="2002-2003 Simon E Booth">
<meta name="keywords" content="Genealogy<?php
	$fname = "SELECT SUBSTRING_INDEX(name, ' ', -1) AS surname FROM family_people GROUP BY surname";
	$rname = mysql_query($fname) or die("Error getting names!");
	if (mysql_num_rows($rname) <> 0) {
		while ($row = mysql_fetch_array($rname))
			echo " ".$row["surname"];
	}
?>">
<meta name="description" content="<?php echo $desc; ?>">
<meta name="page-topic" content="Genealogy">
<meta name="audience" content="All">
<meta name="expires" content="0">
<meta name="page-type" content="Private homepage">
<meta name="robots" content="INDEX,FOLLOW">

<?php css_site(); ?>
<title>phpmyfamily: <?php echo $desc; ?></title>
</head>
<body>

<table>
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
			<br>You are logged in as <?php echo $_SESSION["name"]; ?>: (<a href="passthru.php?func=logout">logout</a><?php if ($_SESSION["admin"] == 1) echo ", <a href=\"admin.php\">admin</a>"; ?>)
<?php } ?>
		</td>
	</tr>
</table>

<hr>

<table width="100%">
	<tr>
		<td width="70%" valign="top">
<?php
			// include login form if not logged in
			if ($_SESSION["id"] == 0)
				include "inc/loginform.inc.php";
			else
				include "inc/passwdform.inc.php";

			echo "<br><br>\n";
			echo "<table>\n";
				echo "<tr>\n";
					echo "<th colspan=\"2\">Site Statistics</th>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<th width=\"200\">Area</th>\n";
					echo "<th width=\"50\">No</th>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td bgcolor=\"#CCCCCC\">People on file</td>\n";
					echo "<td bgcolor=\"#CCCCCC\" align=\"right\">";
					$query = "SELECT count(*) as number FROM family_people";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td bgcolor=\"#DDDDDD\">Census Records</td>\n";
					echo "<td bgcolor=\"#DDDDDD\" align=\"right\">";
					$query = "SELECT count(*) as number FROM family_census";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td bgcolor=\"#CCCCCC\">Images</td>\n";
					echo "<td bgcolor=\"#CCCCCC\" align=right>";
					$query = "SELECT count(*) as number FROM family_images";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td bgcolor=\"#DDDDDD\">Document Transcripts</td>\n";
					echo "<td bgcolor=\"#DDDDDD\" align=\"right\">";
					$query = "SELECT count(*) as number FROM family_documents";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
					echo "</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
					echo "<td bgcolor=\"#CCCCCC\">Page Requests</td>\n";
					echo "<td bgcolor=\"#CCCCCC\" align=\"right\">";
					$query = "SELECT count(*) as number FROM pphl_97075_mpdl";
					$result = mysql_query($query);
					while ($row = mysql_fetch_array($result))
						echo $row["number"];
					mysql_free_result($result);
					echo "</td>\n";
				echo "</tr>\n";
			echo "</table>\n";

			echo "</td>\n";
			echo "<td width=\"30%\" align=\"right\">";
				// list of last updated people
				echo "<table>\n";
					echo "<tr>\n";
						echo "<th colspan=\"2\">Last 20 People Updated</th>\n";
					echo "</tr>\n";
					echo "<tr>\n";
						echo "<th>Person</th>\n";
						echo "<th>Updated</th>\n";
					echo "</tr>\n";
					$query = "SELECT person_id, name, updated FROM family_people"; 
					if ($_SESSION["id"] == 0)
						$query .= " WHERE date_of_birth < '".$restrictdate."'";
					$query .= " ORDER BY updated DESC LIMIT 0,20";
					$result = mysql_query($query) or die(mysql_error($result));
					$i = 0;
					while ($row = mysql_fetch_array($result)) {
						if ($i == 0 || fmod($i, 2) == 0)
							$bgcolor = "#CCCCCC";
						else
							$bgcolor = "#DDDDDD";
						echo "<tr>\n";
							echo "<td bgcolor=\"".$bgcolor."\"><a ";
							echo htmlspecialchars("href='people.php?person=".$row["person_id"]."'");
							echo ">".$row["name"]."</a>";
							echo "</td>\n";
							echo "<td bgcolor=\"".$bgcolor."\">".date('H:i d/m/Y', convertstamp($row["updated"]))."</td>\n";
						echo "</tr>\n";
						$i++;
					}
					mysql_free_result($result);
				echo "</table>\n";
				
			echo "</td>\n";
		echo "</tr>\n";
	echo "</table>\n";

	// insert footer and copyright here
	echo "<hr>\n";
	echo "<table width=\"100%\">\n";
		echo "<tr>\n";
			echo "<td width=\"15%\" align=\"center\" valign=\"middle\">";
				echo "<a href=\"http://validator.w3.org/check/referer\"><img border=\"0\" src=\"images/valid-html401.png\" alt=\"Valid HTML 4.01!\" height=\"31\" width=\"88\"></a>";
			echo "</td>\n";
			echo "<td width=\"70%\" align=\"center\" valign=\"middle\">";
				echo "<h5><a href=\"http://www.giric.com/phpmyfamily\">phpmyfamily v".$version."</a><br>Copyright 2002-2003 Simon E Booth<br>\n";
				echo "Email <a href=mailto:".$email.">me</a> with any problems</h5>\n";
			echo "</td>\n";
			echo "<td width=\"15%\" align=\"center\" valign=\"middle\">";
				echo "<a href=\"http://jigsaw.w3.org/css-validator/\"><img style=\"border:0;width:88px;height:31px\" src=\"images/vcss.png\" alt=\"Valid CSS!\"></a>";
			echo "</td>\n";
		echo "</tr>\n";
	echo "</table>\n";
?>

<script language="JavaScript" type="text/javascript" src="pphlogger.js"></script>
<noscript><img alt="" src="http://logger.giric.com/pphlogger.php?id=family&amp;st=img"></noscript>

<?php

	echo "</body>\n";
	echo "</html>\n";

	//eof
?>
