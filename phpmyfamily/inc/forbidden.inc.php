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

	$_SESSION["id"] = 0;
	$_SESSION["name"] = "nobody";
	$_SESSION["admin"] = 0;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="<?php echo $dir; ?>">
<head>
<link rel="stylesheet" href="<?php echo $style; ?>" type="text/css" />
<link rel="SHORTCUT ICON" href="images/favicon.ico" />
<meta name="author" content="Simon E Booth" />
<meta name="publisher" content="Giric" />
<meta name="copyright" content="2002-2003 Simon E Booth" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
<meta http-equiv="content-language" content="<?php echo $clang; ?>" />
<title>phpmyfamily: <?php echo $strForbidden; ?></title>
</head>
<body>
<table class="header" width="100%">
  <tbody>
    <tr>
      <td><h2><?php echo $strForbidden; ?></h2>  </td>
    </tr>
  </tbody>
</table>

<hr />
<p><?php echo $strForbiddenMsg; ?></p>
</body>
</html>
