<?php
		// download.giric.com
		// Copyright(c)2004 Simon E Booth
		// All Rights Reserved

		// page title
		$title = "downloads";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>phpmyfamily::<?php echo $title; ?></title>
<link rel="stylesheet" href="inc/default.css.php" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta name="Author" content="Simon E Booth">
<meta name="copyright" content="2004 Simon E Booth">
</head>
<body>

<table class="body" width="800" align="center" cellspacing="0">
  <tbody>
    <tr class="header" valign="top">
		<?php include "inc/header.inc.php"; ?>
    </tr>
	<tr>
		<td colspan="2">
<img src="images/filler.png" width="10" height="600" border="0" align="left" alt="" />

<!--start of main-->
<br />
<h3>downloads</h3>
<p><em>phpmyfamily</em> is available in either gzip or zip format.  Please select the archive you require from the table below.  Installation instructions are included in the packages, or are available <a href="install.php">here</a>.  Please make sure that you understand the GPL software LICENSE and it's implications before continuing.</p>
<table>
  <thead>
    <tr>
      <th scope="col">Filename  </th>
      <th scope="col">Size  </th>
      <th scope="col">md5 Sum  </th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="tbl_odd"><a href="http://prdownloads.sourceforge.net/phpmyfamily/phpmyfamily-1.2.5.tar.gz?download">phpmyfamily-1.2.5.tar.gz</a>  </td>
      <td class="tbl_odd">48,701 bytes  </td>
      <td class="tbl_odd">0ec85e17cdeea04961fe1c89de1cd27c</td>
    </tr>
    <tr>
      <td class="tbl_even"><a href="http://prdownloads.sourceforge.net/phpmyfamily/phpmyfamily-1.2.5.zip?download">phpmyfamily-1.2.5.zip</a>  </td>
      <td class="tbl_even">75,273 bytes  </td>
      <td class="tbl_even">4bad0cf107dc44ada4a1f1167af55714</td>
    </tr>
	<tr>
		<td class="tbl_odd"><a href="http://prdownloads.sourceforge.net/phpmyfamily/phpmyfamily-1.2.5.tar.bz2?download">phpmyfamily-1.2.5.bz2</a></td>
		<td class="tbl_odd">42,713 bytes</td>
		<td class="tbl_odd">93d2c63f4962cd32eb1fce6f4e8c16a4</td>
	</tr>
  </tbody>
</table>
<p>You may need to shift and click to download the files.</p>
<!--end of main-->

<h3>changelog</h3>
<strong><em>phpmyfamily</em> v1.2.5 (Released 09/04/2004)</strong>
<p>The latest release of <em>phpmyfamily</em> has many feature enhancements.  New to this version are;</p>

<ul>
  <li>Pedigree Page - allow users to see an individuals pedigree</li>
  <li>GEDCOM import - import data from exisiting GEDCOM files</li>
  <li>Census details can now be stored for any country</li>
  <li>Birth, Marriage and Death anniversaries are now listed</li>
  <li>All fields can now be edited (certificates and gender)</li>
  <li>Now with internationalisation (translations welcome)</li>
</ul>

<strong><em>phpmyfamily</em> v1.2.4 (Released 30/11/2003)</strong>
<p>This release of phpmyfamily fixes a number of small bugs and includes a general clean up of the code.  The major changes are listed below.</p>

<ul>
  <li>Forbidden page added - a more gracefull way for the script to die if it detects a possible hacking attempt.</li>
  <li>User ammendable table prefixes (defaults to "family_").  </li>
  <li>Clean up code throughout the application.  </li>
  <li>install.php ammended to correct issue with image table and auto-increment column.  </li>
  <li>Better error checking and control over uploaded images.</li>
  <li>CSS cleaned up and better applied.</li>
  <li>Changes to session use and meta-data to make pages more "search engine friendly"</li>
</ul>

		 </td>
	</tr>
  </tbody>
</table>

<?php
	include "inc/footer.inc.php";
	// eof
?>
