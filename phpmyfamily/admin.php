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

	// die if user not autorized
	if ($_SESSION["id"] == 0 || $_SESSION["admin"] == 0)
		die(include "inc/forbidden.inc.php");

	// get the request without generating error message
	@$func = $_REQUEST["func"];
	// error message to be passed
	$err = "";

	// process the request
	switch ($func) {
		// add a new user
		case "add":
			// carry out some simple checks to see if user already exists and if passwords match
			$check1 = "SELECT * FROM ".$tblprefix."users WHERE username = '".$_POST["pwdUser"]."'";
			$result1 = mysql_query($check1) or die("Error running new user check 1");
			if (mysql_num_rows($result1) == 0) {
				if ($_POST["pwdPwd1"] == $_POST["pwdPwd2"]) {
					$query = "INSERT INTO ".$tblprefix."users (username, password) VALUES ('".$_POST["pwdUser"]."', '".md5($_POST["pwdPwd1"])."')";
					$result = mysql_query($query) or die("Error adding new user");
				}
				else
					$err = "Passwords do not match";
			}
			else
				$err = "User already exists";
			break;
		// delete an existing user
		case "delete":
			$query = "DELETE FROM ".$tblprefix."users WHERE id = '".$_REQUEST["id"]."'";
			$result = mysql_query($query) or die("Error deleting user");
			break;
		// bail out if we don't know what else to do
		default:
			break;
	}

	// fill out the header
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo $style; ?>" type="text/css">
<link rel="SHORTCUT ICON" href="images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="content-language" content="en">
<title>phpmyfamily Admin</title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h3>Admin Functions</h3>  </td>
    </tr>
  </tbody>
</table>

<hr>
<table width="100%">
  <tbody>
    <tr>
      <td width="50%" valign="top">
	  	<table width="80%">
			<tr>
				<th>action</th>
				<th>Username</th>
				<th>Admin</th>
			</tr>
<?php
		$query = "SELECT * FROM ".$tblprefix."users WHERE id <> '".$_SESSION["id"]."' ORDER BY username";
		$result = mysql_query($query) or die("Error running list users query");
		$i = 0;
		while ($row = mysql_fetch_array($result)) {
			if ($i == 0 || fmod($i, 2) == 0)
				$class = "tbl_odd";
			else
				$class = "tbl_even";
?>
			<tr>
				<td class="<?php echo $class; ?>"><a href="admin.php?func=delete&amp;id=<?echo $row["id"]; ?>">delete</a></td>
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
				<tr><h4>Create new user</h4></tr>
				<form method="POST" action="admin.php?func=add">
					<tr><td>Username</td><td><input type="text" name="pwdUser" size=20 maxlength=20></td></tr>
					<tr><td>Password</td><td><input type="password" name="pwdPwd1" size=20 maxlength=30></td></tr>
					<tr><td>Re-enter Password</td><td><input type="password" name="pwdPwd2" size=20 maxlength=30></td></tr>
					<tr><td></td><td><input type="submit" name="Create"></td></tr>
					<tr><td><font color="red"><?php echo $err; ?></font></td></tr>
				</form>
			</table>
	  </td>
    </tr>
  </tbody>
</table>

<hr>
<a href="index.php">Back</a> to the homepage.
</body>
</html>

<?php
	// eof
?>
