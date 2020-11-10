<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2007  Simon E Booth (simon.booth@giric.com)

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

	// pick up the passed variables
	@$email = $_REQUEST["email"];
	@$person = $_REQUEST["person"];
	@$action = $_REQUEST["action"];
	@$key = $_REQUEST["key"];

	// bail out if we don't allow tracking
	if (!$tracking)
		die(include "inc/forbidden.inc.php");

	// fnuction: delete_expired
	// deletes timedout requests from database
	function delete_expired() {
		global $tblprefix;

		// clear out subscription requests
		$dquery = "DELETE FROM ".$tblprefix."tracking WHERE expires < NOW() and expires != '0000-00-00 00:00:00' AND `action` = 'sub'";
		$dresult = mysql_query($dquery) or die(mysql_error());

		// clear out unsubscribe requests
		$dquery = "UPDATE ".$tblprefix."tracking SET `key` = '', `expires` = '' WHERE expires < NOW() and expires != '0000-00-00 00:00:00' AND `action` = 'unsub'";
		$dresult = mysql_query($dquery) or die(mysql_error());

	}	// end of delete_expired()

	// Fill out the headers
	do_headers($strTracking);

?>

<script language="JavaScript" type="text/javascript">
 <!--
 function check_email() {
 	if (document.trackform.email.value == '') {
		alert(<?php echo $strNoEmail; ?>);
		return false
 	}
 }
 -->
</script>

<table class="header" width="100%">
	<tbody>
		<tr>
			<td align="center" width="65%"><h2><?php echo $strTracking; ?></h2></td>
			<td width="35%" valign="top" align="right"><?php user_opts(); ?></td>
    </tr>
  </tbody>
</table>

<?php

	// if we have a person and no email
	// then show subscribe form
	if (isset($person) && !isset($email)) {
		// the query for the database
		$pquery = "SELECT * FROM ".$tblprefix."people WHERE person_id = ".quote_smart($person);
		$presult = mysql_query($pquery) or die($err_person);
		while ($prow = mysql_fetch_array($presult)) {

			// set security for living people (born after 01/01/1910)
			if ($_SESSION["id"] == 0 && $prow["date_of_birth"] > $restrictdate)
				die(include "inc/forbidden.inc.php");

			echo "<hr />\n";
			echo "<h3>".$prow["name"]." ".$prow["suffix"]."</h3>\n";
			echo $strTrackSpeel."<br /><br />\n";
?>
	<form action="track.php" method="post" name="trackform" onsubmit="return check_email();">
		<input type="hidden" name="person" value="<?php echo $person; ?>" />
		<input type="hidden" name="name" value="<?php echo $prow["name"]; ?>" />
		<table>
			<tbody>
				<tr>
					<td class="tbl_odd"><?php echo $strEmail; ?>  </td>
					<td class="tbl_even"><input type="text" name="email" size="30" maxlength="128" />  </td>
				</tr>
				<tr>
					<td class="tbl_odd">  </td>
					<td class="tbl_even"><input type="radio" name="action" value="sub" checked="checked"  /><?php echo $strSubscribe; ?><input type="radio" name="action" value="unsub" /><?php echo $strUnSubscribe; ?></td>
				</tr>
				<tr>
					<td class="tbl_odd"><input type="submit" name="submit" value="<?php echo $strSubmit; ?>" />  </td>
					<td class="tbl_odd">  </td>
				</tr>
			</tbody>
		</table>
	</form>
<?php
		}
		mysql_free_result($presult);
	}

	// we have a key then process
	elseif (isset($key)) {
		// Housekeeping
		delete_expired();
		echo "<hr />\n";

		// find out what we're supposed to do
		$kquery = "SELECT * FROM ".$tblprefix."tracking WHERE `key` = ".quote_smart($key);
		$kresult = mysql_query($kquery);
		while ($krow = mysql_fetch_array($kresult)) {
			$action = $krow["action"];
		}

		// if no rows are returned then probably a timedout request
		if (mysql_num_rows($kresult) == 0) {
			echo $strMonError."\n";
		}

		// sub or un?
		if ($action == "sub") {
			// check we have key and action it
			$pquery = "UPDATE ".$tblprefix."tracking SET `key` = '', expires = '' WHERE `key` = '".$key."'";
			$presult = mysql_query($pquery) or die(mysql_error());
			if (mysql_affected_rows() != 0) {
				// You are now monitoring this person
				echo $strMonAccept."\n";
			} else {
				// Theres been a problem
				echo $strMonError."\n";
			}
		} elseif ($action == "unsub") {
			$uquery = "DELETE FROM ".$tblprefix."tracking WHERE `key` = '".$key."' AND `action` = 'unsub'";
			$uresult = mysql_query($uquery) or die(mysql_error());
			if (mysql_affected_rows() != 0) {
				// You are now monitoring this person
				echo $strMonCease."\n";
			} else {
				// Theres been a problem
				echo $strMonError."\n";
			}
		}
	}

	// we have a person & email & action so send subscribe message
	elseif (isset($person) && isset($email) && isset($action)) {
		// we want to subscribe
		delete_expired();
		echo "<hr />\n";
		echo "<h3>".htmlspecialchars($_POST["name"])."</h3>\n";
		// produce a new key (md5 hash of email and person requested)
		$newkey = md5(str_rand(20));

		if ($action == "sub") {
			// insert into database
			$iquery = "INSERT INTO ".$tblprefix."tracking (person_id, email, `key`, `action`, expires) VALUES ('".$person."', '".$email."', '".$newkey."', 'sub', DATE_ADD(NOW(), INTERVAL 24 HOUR))";
			$iresult = mysql_query($iquery);

			// if we get this error then already tracking
			if (mysql_errno() == 1062) {
				echo $strAlreadyMon."\n";
			} else {
				// and email to the subscriber
				$headers = "Content-type: text/plain; charset=iso-8859-1\r\n";
				$headers .= "From: <".$trackemail.">\r\n";
				$headers .= "X-Mailer: PHP/" . phpversion();
				$body = str_replace("$1", $_REQUEST["name"], $eSubBody);
				$body .= $absurl."track.php?key=".$newkey."\n";
				mail($email, $eSubSubject, $body, $headers);
				echo $strMonRequest."\n";
			}
		}
		// we want to unsubscribe
		elseif ($action == "unsub") {
			$uquery = "UPDATE ".$tblprefix."tracking SET `key` = '".$newkey."', `expires` = DATE_ADD(NOW(), INTERVAL 24 HOUR), `action` = 'unsub' WHERE person_id = '".$person."' AND email = '".$email."'";
			$uresult = mysql_query($uquery);

			// see if we have a row...
			if (mysql_affected_rows() != 0) {
				// email the unsubscriber
				$headers = "Content-type: text/plain; charset=iso-8859-1\r\n";
				$headers .= "From: <".$trackemail.">\r\n";
				$headers .= "X-Mailer: PHP/" . phpversion();
				$body = str_replace("$1", $_REQUEST["name"], $eUnSubBody);
				$body .= $absurl."track.php?key=".$newkey."\n";
				mail($email, $eSubSubject, $body, $headers);
				echo $strCeaseRequest."\n";
			} else {
				// cos if not, we are not subscribed
				echo $strNotMon."\n";
			}
		}
	}

	// Otherwise, I don't really know what to do
	else {
		echo $strDragons;
	}

	if(isset($person))
		$link = "people.php?person=".$person;
	else
		$link = "index.php";

	echo "<p><a href=\"".$link."\">".$strBack."</a> ".$strToHome."</p>\n";
	include "inc/footer.inc.php";

	// eof
?>
