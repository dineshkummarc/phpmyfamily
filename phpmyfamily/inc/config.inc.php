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

//=====================================================================================================================
// Database Connection details
	$dbhost 	= "localhost"; 	// host the database is running on
	$dbname 	= "family";		// Database name
	$dbuser 	= "root";		// Database user name
	$dbpwd  	= "dave27";		// Database password

//=====================================================================================================================
// General details
	$email 			= "simesb@giric.com";					// Contact email address
	$mailto			= true;									// bool for use of mailto form
	$desc  			= "My Family";
	$tblprefix 		= "family_";
	$styledir		= "styles/";							// See styles directory for options
	$defaultstyle	= "default.css.php"	;					// default style sheet
	$lang			= "lang/en-uk.inc.php";					// Translations welcome!
	$timing			= true;									// Wether we want execution time

//=====================================================================================================================
// Change tracking details
	$tracking	= true;									// Allow email monitoring of people
	$trackemail	= "no-reply@giric.com";					// Emails will come from this address
	$absurl		= "http://www.phpmyfamily.net/";		// Where phpmyfamily is installed (remember trailing slash)
	$bbtracking	= false;								// Big brother tracking of all changes. (Sent to $email)

//=====================================================================================================================
// Image max and minimum sizes
	$img_max		= 700;
	$img_min		= 300;

//=====================================================================================================================
// Nothing should need changing beneath this line
//=====================================================================================================================

//=====================================================================================================================
// Get the current time for tracking execution
//=====================================================================================================================

	$starttime = array_sum(explode(' ',microtime()));

//=====================================================================================================================
// Send a few headers
//=====================================================================================================================
	ini_set("arg_separator.output", "&amp;");				// keep w3c compiance
	ini_set("session.use_trans_sid", false);				// be nice to search engines

//=====================================================================================================================
// include required headers
//=====================================================================================================================
	include "functions.inc.php";

//=====================================================================================================================
// Session routines
//=====================================================================================================================
	// call to start a new session or resume if exists
	session_start();

	// set default variables
	if (!isset($_SESSION["id"])) $_SESSION["id"] = 0;					// non zero if logged in
	if (!isset($_SESSION["name"])) $_SESSION["name"] = "nobody";		// actual login name
	if (!isset($_SESSION["admin"])) $_SESSION["admin"] = 0;				// admin flag
	if (!isset($_SESSION["lang"])) $_SESSION["lang"] = $lang;			// default language file
	if (!isset($_SESSION["editable"])) $_SESSION["editable"] = "N";		// Edit permission flag
	if (!isset($_SESSION["style"])) $_SESSION["style"] = $defaultstyle;	// set the default style sheet
	if (!isset($_SESSION["email"])) $_SESSION["email"] = "";			// set default email for user

//=====================================================================================================================
// Database connection routines
//=====================================================================================================================
	// connect to database
	$mysql_connect = mysql_connect_wrapper($dbhost, $dbuser, $dbpwd) or die("phpmyfamily cannot access the database server (".$dbhost.")");
	$database_select = mysql_select_db($dbname) or die("phpmyfamily cannot access the database (".$dbname.")");

//=====================================================================================================================
// Some general variables
//=====================================================================================================================
	// some definitions
	$version = "1.3.1";											// version string
	$restrictdate = "1910-01-01";								// date for restricting people

//=====================================================================================================================
// Language routines
//=====================================================================================================================
	// include the language file
	@include $_SESSION["lang"];

	// eof
?>
