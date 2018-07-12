<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2007  Simon E Booth (simon.booth@giric.com)

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

	// include the configuration parameters and function
	
?>
<table width="100%">
		<tr>
			<td width="80%"><h4><?php echo $strGallery; ?></h4></td>
			<td align="right"><?php
		if ($_SESSION["editable"] == "Y")
			echo "<a href=\"edit.php?func=add&amp;area=image&amp;person=".$_REQUEST["person"]."\">".$strUpload."</a> ".$strNewImage;
?></td>
		</tr>
	</table>
<?php 
	show_gallery($_REQUEST["person"]); 
	
	// EOF
?>
