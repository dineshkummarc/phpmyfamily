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

	// die if user not autorized
	if ($_SESSION["id"] == 0 || $_SESSION["admin"] == 0)
		die(include "inc/forbidden.inc.php");

	// get the request without generating error message
	@$func = $_REQUEST["func"];
	// error message to be passed
	$err = "";

	// we need a long execution time here
	if ($func == "ged")
		ini_set("max_execution_time", 360);

	// process the request
	switch ($func) {
		// add a new user
		case "add":
			// carry out some simple checks to see if user already exists and if passwords match
			$check1 = "SELECT * FROM ".$tblprefix."users WHERE username = '".$_POST["pwdUser"]."'";
			$result1 = mysql_query($check1) or die($err_pwd);
			if (mysql_num_rows($result1) == 0) {
				if ($_POST["pwdPwd1"] == $_POST["pwdPwd2"]) {
					$query = "INSERT INTO ".$tblprefix."users (username, password) VALUES ('".$_POST["pwdUser"]."', '".md5($_POST["pwdPwd1"])."')";
					$result = mysql_query($query) or die($err_new_user);
				}
				else
					$err = $err_pwd_match;
			}
			else
				$err = $err_user_exist;
			break;
		// delete an existing user
		case "delete":
			$query = "DELETE FROM ".$tblprefix."users WHERE id = '".$_REQUEST["id"]."'";
			$result = mysql_query($query) or die($err_delete_user);
			break;
		// bail out if we don't know what else to do
		default:
			break;
	}

	// fill out the header
	do_headers("phpmyfamily Admin");

	if ($func <> "ged") {
?>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h3><?php echo $strAdminFuncs; ?></h3>  </td>
    </tr>
  </tbody>
</table>

<hr />
<table width="100%">
  <tbody>
    <tr>
      <td width="50%" valign="top">
	  	<table width="80%">
			<tr>
				<th><?php echo $strAction; ?></th>
				<th><?php echo $strUsername; ?></th>
				<th><?php echo ucwords($strAdmin); ?></th>
			</tr>
<?php
		$query = "SELECT * FROM ".$tblprefix."users WHERE id <> '".$_SESSION["id"]."' ORDER BY username";
		$result = mysql_query($query) or die($err_users);
		$i = 0;
		while ($row = mysql_fetch_array($result)) {
			if ($i == 0 || fmod($i, 2) == 0)
				$class = "tbl_odd";
			else
				$class = "tbl_even";
?>
			<tr>
				<td class="<?php echo $class; ?>"><a href="admin.php?func=delete&amp;id=<?echo $row["id"]; ?>"><?php echo $strDelete; ?></a></td>
				<td class="<?php echo $class; ?>"><?php echo $row["username"]; ?></td>
				<td class="<?php echo $class; ?>"><?php echo $row["admin"]; ?></td>
			</tr>
<?php
		$i++;
		}
?>
		</table>
	  </td>
      <td width="50%" valign="top">
			<table>
				<tr><h4><?php echo $strUserCreate; ?></h4></tr>
				<form method="POST" action="admin.php?func=add">
					<tr><td><?php echo $strUsername; ?></td><td><input type="text" name="pwdUser" size="20" maxlength="20" /></td></tr>
					<tr><td><?php echo $strPassword; ?></td><td><input type="password" name="pwdPwd1" size="20" maxlength="30" /></td></tr>
					<tr><td><?php echo $strRePassword; ?></td><td><input type="password" name="pwdPwd2" size="20" maxlength="30" /></td></tr>
					<tr><td></td><td><input type="submit" name="<?php echo $strCreate; ?>" /></td></tr>
					<tr><td><font color="red"><?php echo $err; ?></font></td></tr>
				</form>
			</table>
	  </td>
    </tr>
	<!--Gedcom table-->
	<tr>
		<td>
			<form action="admin.php?func=ged" method="POST" enctype="multipart/form-data" >
				<table>
					<tbody>
						<tr>
							<th colspan="2"> Upload Gedcom File</th>
						</tr>
						<tr>
							<td class="<?php echo $tbl_odd; ?>">File to upload  </td>
							<td class="<?php echo $tbl_even; ?>"><input type="file" name="gedfile" size="30" />  </td>
						</tr>
						<tr>
							<td class="<?php echo $tbl_even; ?>"><input type="submit" name="Submit1" value="<?php echo $strSubmit; ?>" /></td>
							<td>  </td>
						</tr>
						<tr>
							<td colspan="2" class="<?php echo $tbl_even; ?>">Warning - Highly experimental - Only tested on Version 5.5</td>
						</tr>
					</tbody>
				</table>
			</form>
		</td>
	</tr>
  </tbody>
</table>

<hr />
<a href="index.php"><?php echo $strBack; ?></a> <?php echo $strToHome; ?>

<?php
	} else {
		include "inc/gedcom.inc.php";
	}
?>
</body>
</html>

<?php
	// eof
?>
