<?php
	// Copyright (c)2003 Simon E Booth

	$dbhost 	= "mysql.sourceforge.net"; 	// host the database is running on
	$dbname 	= "phpmyfamily";		// Database name
	$dbuser 	= "phpmyfamily";		// Database user name
	$dbpwd  	= "xxxxxxxxxxx";		// Database password

	// connect to database
	$mysql_connect = mysql_pconnect($dbhost, $dbuser, $dbpwd) or die("phpmyfamily cannot access the database server (".$dbhost.")");
	$database_select = mysql_select_db($dbname) or die("phpmyfamily cannot access the database (".$dbname.")");

	// eof
?>
