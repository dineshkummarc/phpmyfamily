<?php
	// db.inc.php
	// Database parameters for Family

	// details for logging into the database
	$dbhost="localhost"; //"db27.oneandone.co.uk";
	$dbname="family"; //"db34941598";
	$dbuser="root"; //"dbo34941598";
	$dbpwd="haptick"; //"FerrC5QW";

	// connect to database
	$mysql_connect = mysql_pconnect($dbhost, $dbuser, $dbpwd) or die("Family cannot access the database server (".$dbhost.")");
	$database_select = mysql_select_db($dbname) or die("Family cannot access the database (".$dbname.")");

	// eof
?>