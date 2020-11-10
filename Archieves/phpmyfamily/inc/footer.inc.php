<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2005  Simon E Booth (simon.booth@giric.com)

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



	// This is the place to change the page footers
	// It would be nice if you could retain my copyright
	// and link back to my site!

?>

<hr />
	<table width="100%">
		<tr>
			<td width="15%" align="center" valign="top"><a href="http://validator.w3.org/check/referer"><img border="0" src="images/valid-xhtml10.png" alt="Valid XHTML 1.0!" height="31" width="88" /></a></td>
			<td width="70%" align="center" valign="top">
<?php include "lang/lang.inc.php"; ?>
				<h5><?php echo $strPowered; ?> <a href="http://www.phpmyfamily.net/" target="_blank" class="copyright">phpmyfamily v<?php echo $version; ?></a> &copy;2002-2005 Simon E Booth<br /><?php if($mailto) echo str_replace("$1", "mail.php?subject=".$title, $strFooter); else echo str_replace("$1", "mailto:".$email."?subject=".$title, $strFooter); ?>
<?php
	if ($timing) {
		$endtime = array_sum(explode(' ',microtime()));
		echo "<br />".$strExecute.": ".bcsub($endtime,$starttime,6)." ".$strSeconds."</h5>\n";
	} else echo "</h5>\n";
?>
			</td>
			<td width="15%" align="center" valign="top"><a href="http://jigsaw.w3.org/css-validator/check/referer"><img style="border:0;width:88px;height:31px" src="images/vcss.png" alt="Valid CSS!" /></a></td>
		</tr>
	</table>

</body>
</html>

<?php
	// eof
?>
