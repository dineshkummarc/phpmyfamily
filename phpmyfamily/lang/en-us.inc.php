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

//=====================================================================================================================
//  global definitions
//=====================================================================================================================

	$datefmt 			= "'%m/%d/%Y'";

//=====================================================================================================================
// some date stuff
// This is really out of place, but $restictdate is defined in config.inc.php and $datefmt here.
// Neither should be moved to the other == catch 22
// if anybody can think of a better way to set nulldate and dispdate - let me know!
//=====================================================================================================================
	$dquery = "SELECT DATE_FORMAT('0000-00-00', ".$datefmt." ) , DATE_FORMAT( '".$restrictdate."', ".$datefmt." )";
	$dresult = mysql_query($dquery) or die("OOOOOppppps");
	while ($row = mysql_fetch_array($dresult)) {
		$nulldate = $row[0];
		$dispdate = $row[1];
	}
	mysql_free_result($dresult);

//=====================================================================================================================
// strings for translation
//=====================================================================================================================

//=====================================================================================================================
//  email definitions
//=====================================================================================================================

//=====================================================================================================================
//  error definitions
//=====================================================================================================================

	// eof
?>
