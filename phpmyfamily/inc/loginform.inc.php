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
	<h4><?php echo $strNote; ?></h4>
	<p><?php if($mailto) echo str_replace("$1", "mail.php?subject=".$title, $strIndex); else echo str_replace("$1", "mailto:".$email."?subject=".$title, $strIndex); ?></p>

	<!-- Form proper -->
	<form method="post" action="passthru.php?func=login">
		<table width="20%">
			<tr>
				<td width="102"><?php echo $strEmail; ?></td>
				<td width="145"><input type="text" name="pwdEmail" maxlength="128" /></td>
			</tr>
			<tr>
				<td width="102"><?php echo $strPassword; ?></td>
				<td width="145"><input type="password" name="pwdPassword" /></td>
			</tr>
			<tr>
				<td width="102"></td>
				<td width="145"><input type="submit" name="Submit1" value="<?php echo $strLogin; ?>" /></td>
			</tr>
		</table>
	</form>

<?php
	//eof
?>
