<?php

	// family tree software
	// (c)2002 - 2003 Simon E Booth
	// All rights reserved
	// loginform.inc.php

	echo "<FORM METHOD=POST ACTION=passthru.php?func=login>\n";
		echo "<TABLE WIDTH=20%>\n";
			echo "<TR>\n";
				echo "<TD WIDTH=102>Username</TD>\n";
				echo "<TD WIDTH=145><INPUT TYPE=TEXT NAME=pwdUser></TD>\n";
			echo "</TR>\n";
			echo "<TR>\n";
				echo "<TD WIDTH=102>Password</TD>\n";
				echo "<TD WIDTH=145><INPUT TYPE=PASSWORD NAME=pwdPassword></TD>\n";
			echo "</TR>\n";
			echo "<TR>\n";
				echo "<TD WIDTH=102></TD>\n";
				echo "<TD WIDTH=145><INPUT TYPE=SUBMIT NAME=Submit1 VALUE=Login></TD>\n";
			echo "</TR>\n";
		echo "</TABLE>\n";
	echo "</FORM>\n";

	//eof
?>