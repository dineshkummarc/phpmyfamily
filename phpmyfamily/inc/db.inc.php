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

	function convertstamp($origdate ) {
		$year = substr($origdate, 0, 4);
		$day = substr($origdate, 4, 2);
		$month = substr($origdate, 6, 2);
		$hour = substr($origdate, 8, 2) - 1;
		$minute = substr($origdate, 10, 2);
		$sec = substr($origdate, -2);
		$retval = mktime($hour,$minute,$sec,$day,$month,$year);
		return $retval;
	}

	function formatdbdate($origdate) {
		$year = substr($origdate, 0, 4);
		$month = substr($origdate, 5, 2);
		$day = substr($origdate, -2);
		$retval = $day."/".$month."/".$year;
		if ($year == 0)
			$retval = "";
		return $retval;
	}

	// eof
?>