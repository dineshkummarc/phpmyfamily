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

	// do different things for those not logged in
	if ($_SESSION["id"] != 0) {
		do_headers("Logged in to phpmyfamily");
?>
<table width="100%" class="header">
	<tr>
		<td width="65%" align="center">
			<h1>phpmyfamily</h1>
			<h3><?php echo $desc; ?></h3>
		</td>
		<td width="35%" valign="top" align="right">
			<form method="get" action="people.php">
				<?php listpeeps("person"); ?>
			</form>
<?php user_opts(); ?>
		</td>
	</tr>
</table>
<?php
	} else {
		do_headers("Login to phpmyfamily");
?>
<table width="100%" class="header">
	<tr>
		<td width="65%" align="center">
			<h1>phpmyfamily</h1>
			<h3><?php echo $desc; ?></h3>
		</td>
		<td width="35%" valign="top" align="right">
			<form method="get" action="people.php">
				<?php listpeeps("person"); ?>
			</form>
<?php user_opts(); ?>
		</td>
	</tr>
</table>
<?php
		include "inc/loginform.inc.php";
	}

	include "inc/footer.inc.php";

	// eof
?>
