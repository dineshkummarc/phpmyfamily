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

	$_SESSION["id"] = 0;
	$_SESSION["name"] = "nobody";
	$_SESSION["admin"] = 0;

?>

<html>
<head>
<link rel="stylesheet" href="styles/metal.css" type="text/css">
<title>phpmyfamily: Forbidden</title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2>Forbidden</h2>  </td>
    </tr>
  </tbody>
</table>

<hr>
<p>The page that you have requested has reported that you do not have sufficient rights to view it.  Do not repeat this request.  Please click <a href="index.php">here</a> to continue.  </p>
</body>
</html>
