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

	if ($_SESSION["id"] == 0)
		die(include "inc/forbidden.inc.php");

	// fill out the header
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="<?php echo $dir; ?>">
<head>
<link rel="stylesheet" href="<?php echo $style; ?>" type="text/css" />
<link rel="shortcut icon" href="images/favicon.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
<meta http-equiv="content-language" content="<?php echo $clang; ?>" />
<?php

	switch ($_REQUEST["func"]) {

		// user wants to edit a record
		case "edit":
			switch ($_REQUEST["area"]) {
				case "detail":
					// get the person to edit
					$edquery = "SELECT * FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die($err_person);

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {
?>
<title><?php echo $strEditing.": ".$edrow["name"]; ?></title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2><?php echo $strEditing.": ".$edrow["name"]; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr />

<!--Form populated with details-->
<form method="post" action="passthru.php?func=update&amp;area=detail&amp;person=<?php echo $_REQUEST["person"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd"><?php echo $strName; ?></td>
			<td class="tbl_even"><input type="text" name="frmName" value="<?php echo $edrow["name"]; ?>" size="30" maxlength="50" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strDOB; ?></td>
			<td class="tbl_even"><input type="text" name="frmDOB" value="<?php echo $edrow["date_of_birth"]; ?>" size="30" /></td>
			<td><?php echo $strDateFmt; ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strBirthPlace; ?></td>
			<td class="tbl_even"><input type="text" name="frmBirthPlace" value="<?php echo $edrow["birth_place"]; ?>" size="30" maxlength="50" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strDOD; ?></td>
			<td class="tbl_even"><input type="text" name="frmDOD" value="<?php echo $edrow["date_of_death"]; ?>" size="30" /></td>
			<td><?php echo $strDateFmt; ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strCauseDeath; ?></td>
			<td class="tbl_even"><input type="text" name="frmDeathReason" value="<?php echo $edrow["death_reason"]; ?>" size="30" maxlength="50" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strMother; ?></td>
			<td class="tbl_even"><?php listpeeps("frmMother", $_REQUEST["person"], "F", $edrow["mother_id"], 0); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strFather; ?></td>
			<td class="tbl_even"><?php listpeeps("frmFather", $_REQUEST["person"], "M", $edrow["father_id"], 0); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd" valign="top"><?php echo $strNotes; ?></td>
			<td colspan="2" class="tbl_even"><textarea name="frmNarrative" rows="10" cols="80"><?php echo $edrow["narrative"]; ?></textarea></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="<?php echo $strSubmit; ?>" /></td>
			<td class="tbl_even"><input type="RESET" name="Reset1" value="<?php echo $strReset; ?>" /></td>
		</tr>
	</table>
</form>
<?php
					}
					break;

				case "marriage":
					// get the person to edit
					$pquery = "SELECT * FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["person"]."'";
					$presult = mysql_query($pquery) or die($err_person);

					$squery = "SELECT name FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["spouse"]."'";
					$sresult = mysql_query($squery) or die($err_spouse);
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

						$edresult = mysql_query($edquery) or die($err_marriage);

						while ($edrow = mysql_fetch_array($edresult)) {
?>

<title><?php echo $strEditing." ".$strMarriage.": ".$prow["name"]; ?> & <?php echo $spousename; ?></title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2><?php echo $strMarriage.": ".$prow["name"]; ?> & <?php echo $spousename; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr />

<!--Fill out form -->
<form method="post" action="passthru.php?func=update&amp;area=marriage&amp;person=<?php echo $_REQUEST["person"]; ?>&amp;oldspouse=<?php echo $_REQUEST["spouse"]; ?>&amp;gender=<?php echo $prow["gender"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd"><?php echo $strSpouse; ?></td>
			<td class="tbl_even"><?php
				if ($prow["gender"] == "M")
					listpeeps("frmSpouse", $_REQUEST["person"], "F", $_REQUEST["spouse"], 0);
				else
					listpeeps("frmSpouse", $_REQUEST["person"], "M", $_REQUEST["spouse"], 0);
?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strDOM; ?></td>
			<td class="tbl_even"><input type="text" name="frmDate" value="<?php echo $edrow["marriage_date"]; ?>" size="15" maxlength="10" /></td>
			<td><?php echo $strDateFmt; ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strMarriagePlace; ?></td>
			<td class="tbl_even"><input type="text" name="frmPlace" value="<?php echo $edrow["marriage_place"]; ?>" size="30" maxlength="50" /></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="<?php echo $strSubmit; ?>" /></td>
			<td class="tbl_even"><input type="reset" name="Reset1" value="<?php echo $strReset; ?>" /></td>
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
					$edquery = "SELECT * FROM ".$tblprefix."people, ".$tblprefix."census, ".$tblprefix."census_years WHERE ".$tblprefix."people.person_id = '".$_REQUEST["person"]."' AND ".$tblprefix."census.census = '".$_REQUEST["census"]."' AND ".$tblprefix."people.person_id = ".$tblprefix."census.person_id AND ".$tblprefix."census.census = ".$tblprefix."census_years.census_id";
					$edresult = mysql_query($edquery) or die($err_census_ret);

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {
?>

<title><?php echo $strEditing." ".$strCensus.": ".$edrow["name"]; ?> (<?php echo $edrow["year"]; ?>)</title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2><?php echo $strCensus.": ".$edrow["name"]; ?> (<?php echo $edrow["year"]; ?>)</h2>  </td>
    </tr>
  </tbody>
</table>

<hr />

<!--Fill out the form-->
<form method="post" action="passthru.php?func=update&amp;area=census&amp;person=<?php echo $_REQUEST["person"]; ?>&amp;census=<?php echo $_REQUEST["census"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd"><?php echo $strSchedule; ?></td>
			<td class="tbl_even"><input type="text" name="frmSchedule" value="<?php echo $edrow["schedule"]; ?>" size="30" maxlength="20" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strAddress; ?></td>
			<td class="tbl_even"><input type="text" name="frmAddress" value="<?php echo $edrow["address"]; ?>" size="50" maxlength="70" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strCondition; ?></td>
			<td class="tbl_even"><?php list_enums("".$tblprefix."census", "condition", "frmCondition", $edrow["condition"]); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strAge; ?></td>
			<td class="tbl_even"><input type="text" name="frmAge" value="<?php echo $edrow["age"]; ?>" size="30" maxlength="3" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strProfession; ?></td>
			<td class="tbl_even"><input type="text" name="frmProfession" value="<?php echo $edrow["profession"]; ?>" size="30" maxlength="40" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strBirthPlace; ?></td>
			<td class="tbl_even"><input type="text" name="frmBirthPlace" value="<?php echo $edrow["where_born"]; ?>" size="30" maxlength="40" /></td>
		</tr>
		<tr>
			<td class="tbl_odd" valign="top"><?php echo $strDetails; ?></td>
			<td class="tbl_even"><textarea type="text" name="frmDetails" rows="4" cols="40"><?php echo $edrow["other_details"]; ?></textarea></td>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="<?php echo $strSubmit; ?>" /></td>
			<td class="tbl_even"><input type="reset" name="Reset1" value="<?php echo $strReset; ?>" /></td>
		</tr>
	</table>
</form>
<?php
					}
					break;

				default:
					echo $strDragons;
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

<hr />
<b><?php echo $strNewMsg; ?></b><br />

<form method="post" action="passthru.php?func=insert&amp;area=detail">
	<table>
		<tr>
			<td class="tbl_odd"><?php echo $strName; ?></td>
			<td class="tbl_even"><input type="text" name="frmName" size="30" maxlength="50" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strDOB; ?></td>
			<td class="tbl_even"><input type="text" name="frmDOB" size="30" maxlength="10" value="0000-00-00" /></td>
			<td><?php echo $strDateFmt; ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strBirthPlace; ?></td>
			<td class="tbl_even"><input type="text" name="frmBirthPlace" size="30" maxlength="50" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strDOD; ?></td>
			<td class="tbl_even"><input type="text" name="frmDOD" size="30" maxlength="10" value="0000-00-00" /></td>
			<td><?php echo $strDateFmt; ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strCauseDeath; ?></td>
			<td class="tbl_even"><input type="text" name="frmDeathReason" size="30" maxlength="50" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strGender; ?></td>
			<td class="tbl_even"><input type="radio" name="frmGender" value="M" checked="checked" /><?php echo $strMale; ?><input type="radio" name="frmGender" value="F" /><?php echo $strFemale; ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strMother; ?></td>
			<td class="tbl_even"><?php listpeeps("frmMother", 0, "F", 0, 0); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strFather; ?></td>
			<td class="tbl_even"><?php listpeeps("frmFather", 0, "M", 0, 0); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strNotes; ?></td>
			<td colspan="2" class="tbl_even"><textarea name="frmNarrative" rows="10" cols="80"></textarea></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="<?php echo $strSubmit; ?>" /></td>
			<td class="tbl_even"><input type="reset" name="Reset1" value="<?php echo $strReset; ?>" /></td>
		</tr>
	</table>
</form>
<?php
					break;

				case "transcript":
					// get the person to insert marriage for
					$edquery = "SELECT * FROM ".$tblprefix."people WHERE person_id = '".$_REQUEST["person"]."'";
					$edresult = mysql_query($edquery) or die($err_person);

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {
?>
<title><?php echo ucwords($strNewTrans).": ".$edrow["name"]; ?></title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2><?php echo ucwords($strNewTrans).": ".$edrow["name"]; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr />

<!--Fill out the form-->
<form enctype="multipart/form-data" method="post" action="passthru.php?func=insert&amp;area=transcript&amp;person=<?php echo $_REQUEST["person"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd"><?php echo $strFUpload; ?></td>
			<td class="tbl_even"><input type="file" name="userfile" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strFTitle; ?></td>
			<td class="tbl_even"><input type="text" name="frmTitle" size="30" maxlength="30" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strFDesc; ?></td>
			<td class="tbl_even"><input type="text" name="frmDesc" size="60" maxlength="60" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strFDate; ?></td>
			<td class="tbl_even"><input type="text" name="frmDate" maxlength="10" value="0000-00-00" /></td>
			<td><?php echo $strDateFmt; ?></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="<?php echo $strSubmit; ?>" /></td>
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
					$edresult = mysql_query($edquery) or die($err_person);

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {
?>
<title><?php echo ucwords($strNewImage).": ".$edrow["name"]; ?></title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2><?php echo ucwords($strNewImage).": ".$edrow["name"]; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr />

<!--Fill out the form-->
<form enctype="multipart/form-data" method="post" action="passthru.php?func=insert&amp;area=image&amp;person=<?php echo $_REQUEST["person"]; ?>">
<input type="hidden" name="MAX_FILE_SIZE" value="1048576">
	<table>
		<tr>
			<td class="tbl_odd"><?php echo $strIUpload; ?></td>
			<td class="tbl_even"><input type="file" name="userfile" /></td>
			<td><?php echo $strISize; ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strITitle; ?></td>
			<td class="tbl_even"><input type="text" name="frmTitle" size="30" maxlength="30" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strIDesc; ?></td>
			<td class="tbl_even"><input type="text" name="frmDesc" size="60" maxlength="60" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strIDate; ?></td>
			<td class="tbl_even"><input type="text" name="frmDate" maxlength="10" value="0000-00-00" /></td>
			<td><?php echo $strDateFmt; ?></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="<?php echo $strSubmit; ?>" /></td>
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
					$edresult = mysql_query($edquery) or die($err_person);

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {
?>
<title><?php echo ucwords($strNewMarriage).": ".$edrow["name"]; ?></title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2><?php echo ucwords($strNewMarriage).": ".$edrow["name"]; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr />

<!--fill out the form-->
<form method="post" action="passthru.php?func=insert&amp;area=marriage&amp;person=<?php echo $_REQUEST["person"]; ?>&amp;gender=<?php echo $edrow["gender"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd"><?php echo $strSpouse; ?></td>
			<td class="tbl_even"><?php
				if ($edrow["gender"] == "M")
					listpeeps("frmSpouse", 0, "F", 0, 0);
				else
					listpeeps("frmSpouse", 0, "M", 0, 0);
?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strDOM; ?></td>
			<td class="tbl_even"><input type="text" name="frmDate" size="15" maxlength="10" value="0000-00-00" /></td>
			<td><?php echo $strDateFmt; ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strMarriagePlace; ?></td>
			<td class="tbl_even"><input type="text" name="frmPlace" size="30" maxlength="50" /></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="<?php echo $strSubmit; ?>" /></td>
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
					$edresult = mysql_query($edquery) or die($err_person);

					// fill out the form with retrieved data
					while ($edrow = mysql_fetch_array($edresult)) {
?>
<title><?php echo ucwords($strNewCensus).": ".$edrow["name"]; ?></title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2><?php echo ucwords($strNewCensus).": ".$edrow["name"]; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr />

<!--Fil out the form-->
<form method="post" action="passthru.php?func=insert&amp;area=census&amp;person=<?php echo $_REQUEST["person"]; ?>">
	<table>
		<tr>
			<td class="tbl_odd"><?php echo $strYear; ?></td>
			<td class="tbl_even"><?php list_censuses("frmYear"); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strSchedule; ?></td>
			<td class="tbl_even"><input type="text" name="frmSchedule" size="30" maxlength="20" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strAddress; ?></td>
			<td class="tbl_even"><input type="text2"name="frmAddress" size="50" maxlength="70" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strCondition; ?></td>
			<td class="tbl_even"><?php list_enums($tblprefix."census", "condition", "frmCondition"); ?></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strAge; ?></td>
			<td class="tbl_even"><input type="text" name="frmAge" size="10" maxlength="3" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strProfession; ?></td>
			<td class="tbl_even"><input type="text" name="frmProfession" size="30" maxlength="40" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strBirthPlace; ?></td>
			<td class="tbl_even"><input type="text" name="frmWhereBorn" size="30" maxlength="40" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strDetails; ?></td>
			<td class="tbl_even"><textarea name="frmDetails" cols="40" rows="4" type="text"></textarea></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="<?php echo $strSubmit; ?>" /></td>
			<td class="tbl_even"></td>
		</tr>
	</table>
</form>
<?php
					}
					mysql_free_result($edresult);
					break;

				default:
					echo $strDragons;
					break;
			}
		break;

		// don't know what else to do
		default:
			echo $strDragons;
			break;
	}

	include "inc/footer.inc.php";

	// eof
?>
