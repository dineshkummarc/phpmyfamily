<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2003  Simon E Booth (simon.booth@giric.com)

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

//=====================================================================================================================
// Database Connection details
	$dbhost = "localhost"; 	// host the database is running on
	$dbname = "family";		// Database name
	$dbuser = "root";		// Database user name
	$dbpwd  = "dave27";		// Database password

//=====================================================================================================================
// General details
	$email = "simesb@giric.com";	// Contact email address
	$desc  = "My Family";

//=====================================================================================================================
// Nothing should need changing beneath this line
//=====================================================================================================================

//=====================================================================================================================
// Session routines
//=====================================================================================================================
	// call to start a new session or resume if exists
	session_start();

	// set default variables
	if (!isset($_SESSION["id"])) $_SESSION["id"] = 0;
	if (!isset($_SESSION["name"])) $_SESSION["name"] = "nobody";
	if (!isset($_SESSION["admin"])) $_SESSION["admin"] = 0;

//=====================================================================================================================
// Database connection routines
//=====================================================================================================================
	// connect to database
	$mysql_connect = mysql_pconnect($dbhost, $dbuser, $dbpwd) or die("phpmyfamily cannot access the database server (".$dbhost.")");
	$database_select = mysql_select_db($dbname) or die("phpmyfamily cannot access the database (".$dbname.")");

//=====================================================================================================================
// Some general variables
//=====================================================================================================================
	// some definitions
	$version = "1.2.2";
	$restrictdate = "1910-01-01";
	$restrictmsg = "<font color=\"red\">Restricted</font>";

	// eof
?>
