<?php
	
	// index.php
	// family tree software
	// (c)2002 - 2003 Simon E Booth
	// All rights reserved
	// Welcome page

	// send the headers
	ini_set("arg_separator.output", "&amp;");
	header('content-Type: text/html; charset=ISO-8859-1');
	header('content-Language: en');

	// include the database parameters
	include "inc/session.inc.php";
	include "inc/db.inc.php";
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
<meta name="keywords" content="Genealogy Ambler Bate Bickle Birtwistle Booth Bridgewater Chaffe Clegg Eveleigh Grainger Granger Jenkins Jones Kitching Kitchen Knight Parry Platt Raistrick Thorne Vann Verity Virr Willcocks">
<meta name="description" content="Family tree for me, starting with Booths and Ambler and going who knows where?">
<meta name="page-topic" content="Genealogy">
<meta name="audience" content="All">
<meta name="expires" content="0">
<meta name="page-type" content="Private homepage">
<meta name="robots" content="INDEX,FOLLOW">

<?php css_site(); ?>
<title>Family Tree</title>
</head>
<body>
	
<table>
	<tr>
		<td width="80%" align="center">
			<h2>Family Tree</h2>
		</td>
		<td width="20%" valign="top">
			<form method="post" action="passthru.php?func=jump">
				<?php listpeeps("person"); ?>
				<input type="submit" name="Submit1" value="Go">
			</form>
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
						if ($i == 0 || $i == 2 || $i == 4 || $i == 6 || $i == 8 || $i == 10 || $i == 12 || $i == 14 || $i == 16 || $i == 18 || $i == 20)
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
				echo "<h5>Version: ".$version." Copyright 2002-2003 Simon E Booth<br>\n";
				echo "Email <a href=mailto:simon.booth@giric.com>me</a> with any problems</h5>\n";
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