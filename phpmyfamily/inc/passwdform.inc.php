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
?>
	<!-- Warning Messages -->
	<h4>Password Change</h4>
	<p>Please use this form if you wish to change your password.</p>

	<!-- Form proper -->
	<form method="post" action="passthru.php?func=change">
		<table width="100%">
			<tr>
				<td>Old Password</td>
				<td><input type="password" name="pwdOld" size="20" maxlength="30" /></td>
			</tr>
			<tr>
				<td>New Password</td>
				<td><input type="password" name="pwdPwd1" size="20" maxlength="30" /></td>
			</tr>
			<tr>
				<td>Re-enter New Password</td>
				<td><input type="password" name="pwdPwd2" size="20" maxlength="30" /></td>
			</tr>
			<tr>
				<td width="182"><?php
					@$reason = $_REQUEST["reason"];
					echo "<font color=\"red\">".$reason."</font>";
				?></td>
				<td width="145"><input type="submit" name="Submit1" value="Change" /></td>
			</tr>
		</table>
	</form>

<?php
	//eof
?>
