<?php

	// family tree software
	// (c)2002 - 2003 Simon E Booth
	// All rights reserved
	// loginform.inc.php

	// Warning messages
	echo "<h4>Notice</h4>\n";
	echo "<p>All details for people born before ".formatdbdate($restrictdate)." are restricted to protect their identities.  If you are a registered user you can view these details and edit record.  Everybody is free to browse the unrestricted records.  If you think anybody here matches into your family tree, please <a href=mailto:simon.booth@giric.com>let me know</a></p>\n";
	// form proper
	echo "<FORM METHOD=POST ACTION=passthru.php?func=login>\n";
		echo "<TABLE WIDTH=\"20%\">\n";
			echo "<TR>\n";
				echo "<TD WIDTH=\"102\">Username</TD>\n";
				echo "<TD WIDTH=\"145\"><INPUT TYPE=\"TEXT\" NAME=\"pwdUser\"></TD>\n";
			echo "</TR>\n";
			echo "<TR>\n";
				echo "<TD WIDTH=\"102\">Password</TD>\n";
				echo "<TD WIDTH=145><INPUT TYPE=\"PASSWORD\" NAME=\"pwdPassword\"></TD>\n";
			echo "</TR>\n";
			echo "<TR>\n";
				echo "<TD WIDTH=\"102\"></TD>\n";
				echo "<TD WIDTH=145><INPUT TYPE=\"SUBMIT\" NAME=\"Submit1\" VALUE=\"Login\"></TD>\n";
			echo "</TR>\n";
		echo "</TABLE>\n";
	echo "</FORM>\n";

	//eof
?>