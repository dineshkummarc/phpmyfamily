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
	ini_set('session.use_trans_sid', false);

	// include the configuration parameters and functions
	include "inc/config.inc.php";
	include "inc/functions.inc.php";

	// include the browser
	include "inc/browser.inc.php";
	include "inc/css.inc.php";

	//get the details for the image
	$iquery = "SELECT * FROM ".$tblprefix."images WHERE image_id = '".$_REQUEST["image"]."'";
	$iresult = mysql_query($iquery) or die("Image retreival query failed");

	// when we have an image, get the associated person details
	while ($irow = mysql_fetch_array($iresult)) {
		$pquery = "SELECT name, date_of_birth FROM ".$tblprefix."people WHERE person_id = '".$irow["person_id"]."'";
		$presult = mysql_query($pquery) or die("Person fetch failed");
		while ($prow = mysql_fetch_array($presult)) {

			// check security
			if ($_SESSION["id"] == 0 && $prow["date_of_birth"] > $restrictdate)
				$restricted = true;
			else
				$restricted = false;

			// fill out the header
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php css_site(); ?>
<title><?php echo $irow["title"]." (".$prow["name"].")"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="content-language" content="en">
</head>
<body>

<h2><?php echo $irow["title"]; ?></h2>

<hr>

<?php
		}
		mysql_free_result($presult);
?>
<div align="center"><img src="<?php echo "images/".$irow["image_id"].".jpg"; ?>" alt="<?php echo $irow["description"]; ?>"></div>

<div align="center">
	<p><?php if ($restricted)
		echo $restrictmsg;
	else
		echo formatdbdate($irow["date"]); ?></p>
	<p><?php echo $irow["description"]; ?></p>
</div>

<br><br>
<?php
	}
	mysql_free_result($iresult);
?>

<hr>
	<table width="100%">
		<tr>
			<td width="15%" align="center" valign="middle"><a href="http://validator.w3.org/check/referer"><img border="0" src="images/valid-html401.png" alt="Valid HTML 4.01!" height="31" width="88"></a></td>
			<td width="70%" align="center" valign="middle"><h5>Version: v<?php echo $version; ?> Copyright 2002-2003 Simon E Booth<br>Email <a href="mailto:<?php echo $email; ?>">me</a> with any problems</h5></td>
			<td width="15%" align="center" valign="middle"><a href="http://jigsaw.w3.org/css-validator/"><img style="border:0;width:88px;height:31px" src="images/vcss.png" alt="Valid CSS!"></a></td>
		</tr>
	</table>

<!--pphlogger code-->
<script language="JavaScript" type="text/javascript" src="pphlogger.js"></script>
<noscript><img alt="" src="http://logger.giric.com/pphlogger.php?id=family&amp;st=img"></noscript>
<!--end of pphlogger code-->

</body>
</html>

<?php
	// eof
?>
