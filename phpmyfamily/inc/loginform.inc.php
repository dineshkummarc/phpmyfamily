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
	<h4>Notice</h4>
	<p>All details for people born after <?php echo formatdbdate($restrictdate)?> are restricted to protect their identities.  If you are a registered user you can view these details and edit record.  Everybody is free to browse the unrestricted records.  If you think anybody here matches into your family tree, please <a href="mailto:<?php echo $email; ?>">let me know</a></p>

	<!-- Form proper -->
	<form method="post" action="passthru.php?func=login">
		<table width="20%">
			<tr>
				<td width="102">Username</td>
				<td width="145"><input type="text" name="pwdUser" /></td>
			</tr>
			<tr>
				<td width="102">Password</td>
				<td width="145"><input type="password" name="pwdPassword" /></td>
			</tr>
			<tr>
				<td width="102"></td>
				<td width="145"><input type="submit" name="Submit1" value="Login" /></td>
			</tr>
		</table>
	</form>

<?php
	//eof
?>
