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

	if ($_SESSION["id"] == 0)
		die(include "inc/forbidden.inc.php");

	// fill out the header
?>
<html>
<head>
<link rel="stylesheet" href="<?php echo $style; ?>" type="text/css">
<link rel="SHORTCUT ICON" href="images/favicon.ico">
<?php

	switch ($_REQUEST["func"]) {

		// user wants to edit a record
		case "edit":
			switch ($_REQUEST["area"]) {
				case "detail":
					// get the person to edit
					$edquery = "SELECT * FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die("Editing query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {
?>
<title>Editing: <?php echo $edrow["name"]; ?></title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2>Editing: <?php echo $edrow["name"]; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr>

<!--Form populated with details-->
<form method="post" action="passthru.php?func=update&amp;area=detail&amp;person=<?php echo $_REQUEST["person"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd">Name</td>
			<td class="tbl_even"><input type="text" name="frmName" value="<?php echo $edrow["name"]; ?>" size="30" maxlength="50"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Date of Birth</td>
			<td class="tbl_even"><input type="text" name="frmDOB" value="<?php echo $edrow["date_of_birth"]; ?>" size="30"></td>
			<td>Please use format YYYY-MM-DD</td>
		</tr>
		<tr>
			<td class="tbl_odd">Birth Place</td>
			<td class="tbl_even"><input type="text" name="frmBirthPlace" value="<?php echo $edrow["birth_place"]; ?>" size="30" maxlength="50"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Date of Death</td>
			<td class="tbl_even"><input type="text" name="frmDOD" value="<?php echo $edrow["date_of_death"]; ?>" size="30"></td>
			<td>Please use format YYYY-MM-DD</td>
		</tr>
		<tr>
			<td class="tbl_odd">Cause of Death</td>
			<td class="tbl_even"><input type="text" name="frmDeathReason" value="<?php echo $edrow["death_reason"]; ?>" size="30" maxlength="50"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Mother</td>
			<td class="tbl_even"><?php listpeeps("frmMother", $_REQUEST["person"], "F", $edrow["mother_id"], 0); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd">Father</td>
			<td class="tbl_even"><?php listpeeps("frmFather", $_REQUEST["person"], "M", $edrow["father_id"], 0); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd" valign="top">Narrative</td>
			<td colspan="2" class="tbl_even"><textarea name="frmNarrative" rows="10" cols="80"><?php echo $edrow["narrative"]; ?></textarea></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="Submit"></td>
			<td class="tbl_even"><input type="RESET" name="Reset1" value="Reset"></td>
		</tr>
	</table>
</form>
<?php
					}
					break;

				case "marriage":
					// get the person to edit
					$pquery = "SELECT * FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["person"]."'";
					$presult = mysql_query($pquery) or die("Editing query failed");

					$squery = "SELECT name FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["spouse"]."'";
					$sresult = mysql_query($squery) or die("Spouse name query failed");
					while ($srow = mysql_fetch_array($sresult)) {
						$spousename = $srow["name"];
					}
					mysql_free_result($sresult);

					// fill out the form with retrieved data
					while ($prow = mysql_fetch_array($presult)) {

						if ($prow["gender"] == "M")
							$edquery = "SELECT * FROM ".$tblprefix."spouses WHERE groom_id = '".$_REQUEST["person"]."' AND bride_id = '".$_REQUEST["spouse"]."'";
						else
							$edquery = "SELECT * FROM ".$tblprefix."spouses WHERE bride_id = '".$_REQUEST["person"]."' AND groom_id = '".$_REQUEST["spouse"]."'";

						$edresult = mysql_query($edquery) or die("Spouse query failed");

						while ($edrow = mysql_fetch_array($edresult)) {
?>

<title>Editing Marriage: <?php echo $prow["name"]; ?> & <?php echo $spousename; ?></title>
</head>
<BODY>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2>Marriage: <?php echo $prow["name"]; ?> & <?php echo $spousename; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr>

<!--Fill out form -->
<form method="post" action="passthru.php?func=update&amp;area=marriage&amp;person=<?php echo $_REQUEST["person"]; ?>&amp;oldspouse=<?php echo $_REQUEST["spouse"]; ?>&amp;gender=<?php echo $prow["gender"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd">Spouse</td>
			<td class="tbl_even"><?php
				if ($prow["gender"] == "M")
					listpeeps("frmSpouse", $_REQUEST["person"], "F", $_REQUEST["spouse"], 0);
				else
					listpeeps("frmSpouse", $_REQUEST["person"], "M", $_REQUEST["spouse"], 0);
?></td>
		</tr>
		<tr>
			<td class="tbl_odd">Date of marriage</td>
			<td class="tbl_even"><input type="text" name="frmDate" value="<?php echo $edrow["marriage_date"]; ?>" size="15" maxlength="10"></td>
			<td>Please use format YYYY-MM-DD</td>
		</tr>
		<tr>
			<td class="tbl_odd">Marriage Place</td>
			<td class="tbl_even"><input type="text" name="frmPlace" value="<?php echo $edrow["marriage_place"]; ?>" size="30" maxlength="50"></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="Submit"></td>
			<td class="tbl_even"><input type="reset" name="Reset1" value="Reset"></td>
		</tr>
	</table>
</form>
<?php
						}
						mysql_free_result($edresult);
					}
					mysql_free_result($presult);
					break;

				case "census":
					// get the person to edit
					$edquery = "SELECT * FROM ".$tblprefix."people, ".$tblprefix."census WHERE ".$tblprefix."people.person_id = '".$_REQUEST["person"]."' AND ".$tblprefix."census.year = '".$_REQUEST["year"]."' AND ".$tblprefix."people.person_id = ".$tblprefix."census.person_id";
					$edresult = mysql_query($edquery) or die("Editing query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {
?>

<title>Editing Census: <?php echo $edrow["name"]; ?> (<?php echo $edrow["year"]; ?>)</title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2> Census: <?php echo $edrow["name"]; ?> (<?php echo $edrow["year"]; ?>)</h2>  </td>
    </tr>
  </tbody>
</table>

<hr>

<!--Fill out the form-->
<form method="post" action="passthru.php?func=update&amp;area=census&amp;person=<?php echo $_REQUEST["person"]; ?>&amp;year=<?php echo $_REQUEST["year"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd">Schedule</td>
			<td class="tbl_even"><input type="text" name="frmSchedule" value="<?php echo $edrow["schedule"]; ?>" size="30" maxlength="20"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Address</td>
			<td class="tbl_even"><input type="text" name="frmAddress" value="<?php echo $edrow["address"]; ?>" size="50" maxlength="70"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Condition</td>
			<td class="tbl_even"><?php list_enums("".$tblprefix."census", "condition", "frmCondition", $edrow["condition"]); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd">Age</td>
			<td class="tbl_even"><input type="text" name="frmAge" value="<?php echo $edrow["age"]; ?>" size="30" maxlength="3"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Profession</td>
			<td class="tbl_even"><input type="text" name="frmProfession" value="<?php echo $edrow["profession"]; ?>" size="30" maxlength="40"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Place of Birth</td>
			<td class="tbl_even"><input type="text" name="frmBirthPlace" value="<?php echo $edrow["where_born"]; ?>" size="30" maxlength="40"></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="Submit"></td>
			<td class="tbl_even"><input type="reset" name="Reset1" value="Reset"></td>
		</tr>
	</table>
</form>
<?php
					}
					break;

				default:
					echo "Here be editing area dragons";
					break;
			}

			break;

		// user wants to create a new person
		case "add":
			switch($_REQUEST["area"]) {
				case "detail":
?>

<title>Creating new family member</title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2>Create new person</h2>  </td>
    </tr>
  </tbody>
</table>
<!--Create a blank form-->

<hr>
<b>Please make sure person doesn't exist before creating!!!</b><br>

<form method="post" action="passthru.php?func=insert&amp;area=detail">
	<table>
		<tr>
			<td class="tbl_odd">Name</td>
			<td class="tbl_even"><input type="text" name="frmName" size="30" maxlength="50"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Date of Birth</td>
			<td class="tbl_even"><input type="text" name="frmDOB" size="30" maxlength="10" value="0000-00-00"></td>
			<td>Please use format YYYY-MM-DD</td>
		</tr>
		<tr>
			<td class="tbl_odd">Birth Place</td>
			<td class="tbl_even"><input type="text" name="frmBirthPlace" size="30" maxlength="50"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Date of Death</td>
			<td class="tbl_even"><input type="text" name="frmDOD" size="30" maxlength="10" value="0000-00-00"></td>
			<td>Please use format YYYY-MM-DD</td>
		</tr>
		<tr>
			<td class="tbl_odd">Cause of Death</td>
			<td class="tbl_even"><input type="text" name="frmDeathReason" size="30" maxlength="50"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Gender</td>
			<td class="tbl_even"><input type="radio" name="frmGender" value="M" checked="checked">M<input type="radio" name="frmGender" value="F">F</td>
		</tr>
		<tr>
			<td class="tbl_odd">Mother</td>
			<td class="tbl_even"><?php listpeeps("frmMother", 0, "F", 0, 0); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd">Father</td>
			<td class="tbl_even"><?php listpeeps("frmFather", 0, "M", 0, 0); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd">Narrative</td>
			<td colspan="2" class="tbl_even"><textarea name="frmNarrative" rows="10" cols="80"></textarea></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="Submit"></td>
			<td class="tbl_even"><input type="reset" name="Reset1" value="Reset"></td>
		</tr>
	</table>
</form>
<?php
					break;

				case "transcript":
					// get the person to insert marriage for
					$edquery = "SELECT * FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die("New marriage person query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {
?>
<title>New Transcript: <?php echo $edrow["name"]; ?></title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2> New Transcript: <?php echo $edrow["name"]; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr>

<!--Fill out the form-->
<form enctype="multipart/form-data" method="post" action="passthru.php?func=insert&amp;area=transcript&amp;person=<?php echo $_REQUEST["person"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd">File to Upload</td>
			<td class="tbl_even"><input type="file" name="userfile"></td>
		</tr>
		<tr>
			<td class="tbl_odd">File Title</td>
			<td class="tbl_even"><input type="text" name="frmTitle" size="30" maxlength="30"></td>
		</tr>
		<tr>
			<td class="tbl_odd">File Description</td>
			<td class="tbl_even"><input type="text" name="frmDesc" size="60" maxlength="60"></td>
		</tr>
		<tr>
			<td class="tbl_odd">File Date</td>
			<td class="tbl_even"><input type="text" name="frmDate" maxlength="10" value="0000-00-00"></td>
			<td>Please use format YYYY-MM-DD</td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="Submit"></td>
			<td class="tbl_even"></td>
		</tr>
	</table>
</form>
<?php
					}
					break;

				case "image":
					// get the person to insert marriage for
					$edquery = "SELECT * FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die("New marriage person query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {
?>
<title>New Image: <?php echo $edrow["name"]; ?></title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2> New Image: <?php echo $edrow["name"]; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr>

<!--Fill out the form-->
<form enctype="multipart/form-data" method="post" action="passthru.php?func=insert&amp;area=image&amp;person=<?php echo $_REQUEST["person"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd">Image to Upload</td>
			<td class="tbl_even"><input type="file" name="userfile"></td>
			<td>JPEG only.</td>
		</tr>
		<tr>
			<td class="tbl_odd">Image Title</td>
			<td class="tbl_even"><input type="text" name="frmTitle" size="30" maxlength="30"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Image Description</td>
			<td class="tbl_even"><input type="text" name="frmDesc" size="60" maxlength="60"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Image Date</td>
			<td class="tbl_even"><input type="text" name="frmDate" maxlength="10" value="0000-00-00"></td>
			<td>Please use format YYYY-MM-DD</td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="Submit"></td>
			<td class="tbl_even"></td>
		<tr>
	</table>
</form>
<?php
					}
					break;

				case "marriage":
					// get the person to insert marriage for
					$edquery = "SELECT * FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die("New marriage person query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {
?>
<title>New Marriage: <?php echo $edrow["name"]; ?></title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2> New Marriage: <?php echo $edrow["name"]; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr>

<!--fill out the form-->
<form method="post" action="passthru.php?func=insert&amp;area=marriage&amp;person=<?php echo $_REQUEST["person"]; ?>&amp;gender=<?php echo $edrow["gender"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd">Spouse</td>
			<td class="tbl_even"><?php
				if ($edrow["gender"] == "M")
					listpeeps("frmSpouse", 0, "F", 0, 0);
				else
					listpeeps("frmSpouse", 0, "M", 0, 0);
?></td>
		</tr>
		<tr>
			<td class="tbl_odd">Date of marriage</td>
			<td class="tbl_even"><input type="text" name="frmDate" size="15" maxlength="10" value="0000-00-00"></td>
			<td>Please use format YYYY-MM-DD</td>
		</tr>
		<tr>
			<td class="tbl_odd">Marriage Place</td>
			<td class="tbl_even"><input type="text" name="frmPlace" size="30" maxlength="50"></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="Submit"></td>
			<td class="tbl_even"></td>
		</tr>
	</table>
</form>
<?php
					}
					mysql_free_result($edresult);
					break;

				case "census":
					// get the person to insert census for
					$edquery = "SELECT * FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die("New census person query failed");

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {
?>
<title>New Census: <?php echo $edrow["name"]; ?></title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2> New Census: <?php echo $edrow["name"]; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr>

<!--Fil out the form-->
<form method="post" action="passthru.php?func=insert&amp;area=census&amp;person=<?php echo $_REQUEST["person"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd">Year</td>
			<td class="tbl_even"><?php list_enums("".$tblprefix."census", "year", "frmYear"); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd">Schedule</td>
			<td class="tbl_even"><input type="text" name="frmSchedule" size="30" maxlength="20"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Address</td>
			<td class="tbl_even"><input type="text2"name="frmAddress" size="50" maxlength="70"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Condition</td>
			<td class="tbl_even"><?php list_enums("".$tblprefix."census", "condition", "frmCondition"); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd">Age</td>
			<td class="tbl_even"><input type="text" name="frmAge" size="10" maxlength="3"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Profession</td>
			<td class="tbl_even"><input type="text" name="frmProfession" size="30" maxlength="40"></td>
		</tr>
		<tr>
			<td class="tbl_odd">Place of Birth</td>
			<td class="tbl_even"><input type="text" name="frmWhereBorn" size="30" maxlength="40"></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="Submit"></td>
			<td class="tbl_even"></td>
		</tr>
	</table>
</form>
<?php
					}
					mysql_free_result($edresult);
					break;

				default:
					echo "Here be adding area dragons";
					break;
			}
		break;

		// don't know what else to do
		default:
			echo "Here be function dragons";
			break;
	}

?>

<hr>
	<table width="100%">
		<tr>
			<td width="15%" align="center" valign="middle"><a href="http://validator.w3.org/check/referer"><img border="0" src="images/valid-html401.png" alt="Valid HTML 4.01!" height="31" width="88"></a></td>
			<td width="70%" align="center" valign="middle"><h5><a href="http://www.giric.com/phpmyfamily">phpmyfamily v<?php echo $version; ?></a><br>Copyright 2002-2003 Simon E Booth<br>Email <a href="mailto:<?php echo $email; ?>">me</a> with any problems</h5></td>
			<td width="15%" align="center" valign="middle"><a href="http://jigsaw.w3.org/css-validator/"><img style="border:0;width:88px;height:31px" src="images/vcss.png" alt="Valid CSS!"></a></td>
		</tr>
	</table>

<!--pphlogger code-->
<script language="JavaScript" type="text/javascript" src="pphlogger.js"></script>
<noscript><img alt="" src="http://logger.giric.com/pphlogger.php?id=family&st=img"></noscript>
<!--end of pphlogger code-->

</body>
</html>

<?php
	// eof
?>
