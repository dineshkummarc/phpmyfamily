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

	// include the configuration parameters and functions
	include "inc/config.inc.php";

	// fill out the headers
	do_headers($strMailTo);

	@$action = $_REQUEST["action"];
	@$subject = $_REQUEST["subject"];
	if (!isset($subject))
		$subject = "";
	@$referer = $_REQUEST["referer"];
	if (!isset($referer) && isset($_SERVER["HTTP_REFERER"]))
		$referer = $_SERVER["HTTP_REFERER"];
	elseif (!isset($referer))
		$referer = "index.php";

?>

<script language="JavaScript" type="text/javascript">
 <!--
 function check_email() {
 	if (document.mailform.email.value == '') {
		alert(<?php echo $strNoEmail; ?>);
		return false
 	}
 }
 -->
</script>

<table class="header" width="100%">
	<tbody>
		<tr>
			<td align="center" width="65%"><h2><?php echo $strMailTo; ?></h2></td>
			<td width="35%" valign="top" align="right">
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
  </tbody>
</table>

<hr />

<?php

	if ($action == "send") {
		$headers = "Content-type: text/plain; charset=iso-8859-1\r\n";
		$headers .= "From: <".$_REQUEST["email"].">\r\n";
		$headers .= "X-Mailer: PHP/" . phpversion();
		mail($email, $_REQUEST["subject"], $_REQUEST["details"], $headers);
		echo $strEmailSent."<br /><br />\n";
	} else {
?>
<form action="mail.php" method="post" name="mailform" onsubmit="return check_email();">
	<input type="hidden" name="action" value="send" />
	<input type="hidden" name="referer" value="<?php echo $referer; ?>" />
	<table>
		<tbody>
			<tr>
				<td class="tbl_odd"><?php echo $strEmail; ?>  </td>
				<td class="tbl_even"><input type="text" name="email" size="30" maxlength="128" value="" />  </td>
			</tr>
			<tr>
				<td class="tbl_odd"><?php echo $strSubject; ?>  </td>
				<td class="tbl_even"><input type="text" name="subject" size="30" maxlength="128" value="<?php echo $subject; ?>" />  </td>
			</tr>
			<tr>
				<td class="tbl_odd"><?php echo $strDetails; ?>  </td>
				<td class="tbl_even"> <textarea name="details" cols="50" rows="15"></textarea> </td>
			</tr>
			<tr>
				<td class="tbl_odd"><input type="submit" value="<?php echo $strSubmit; ?>" />  </td>
				<td class="tbl_odd">  </td>
			</tr>
		</tbody>
	</table>
</form>
<?php
	}

	echo "<p><a href=\"".$referer."\">".$strBack."</a> ".$strToHome."</p>\n";

	include "inc/footer.inc.php";

	// eof
?>
