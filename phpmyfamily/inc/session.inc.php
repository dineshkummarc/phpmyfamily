<?php

	// family tree software
	// (c)2002 - 2003 Simon E Booth
	// All rights reserved
	// session.inc.php

	// call to start a new session or resume if exists
	session_start();

	// set default variables
	if (!isset($_SESSION["id"])) $_SESSION["id"] = 0;
	
	// eof
?>