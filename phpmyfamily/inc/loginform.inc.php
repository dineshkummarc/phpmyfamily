<?php

	// family tree software
	// (c)2002 - 2003 Simon E Booth
	// All rights reserved
	// loginform.inc.php
?>
	<!-- Warning Messages -->
	<h4>Notice</h4>
	<p>All details for people born after <?php echo formatdbdate($restrictdate)?> are restricted to protect their identities.  If you are a registered user you can view these details and edit record.  Everybody is free to browse the unrestricted records.  If you think anybody here matches into your family tree, please <a href="mailto:simon.booth@giric.com">let me know</a></p>

	<!-- Form proper -->
	<form method="post" action="passthru.php?func=login">
		<table width="20%">
			<tr>
				<td width="102">Username</td>
				<td width="145"><input type="text" name="pwdUser"></td>
			</tr>
			<tr>
				<td width="102">Password</td>
				<td width="145"><input type="password" name="pwdPassword"></td>
			</tr>
			<tr>
				<td width="102"></td>
				<td width="145"><input type="submit" name="Submit1" value="Login"></td>
			</tr>
		</table>
	</form>

<?php
	//eof
?>