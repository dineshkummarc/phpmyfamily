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
<img src="images/filler.png" width="10" height="750" border="0" align="left" alt="" />

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
      <td class="tbl_odd"><a href="http://prdownloads.sourceforge.net/phpmyfamily/phpmyfamily-1.4.1.tar.gz?download">phpmyfamily-1.4.1.tar.gz</a>  </td>
      <td class="tbl_odd">83,355 bytes  </td>
      <td class="tbl_odd">e36e18a5f0018aa922f4adec85d23349</td>
    </tr>
    <tr>
      <td class="tbl_even"><a href="http://prdownloads.sourceforge.net/phpmyfamily/phpmyfamily-1.4.1.zip?download">phpmyfamily-1.4.1.zip</a>  </td>
      <td class="tbl_even">130,715 bytes  </td>
      <td class="tbl_even">2809890fa4d49340b92a6560280fe35e</td>
    </tr>
	<tr>
		<td class="tbl_odd"><a href="http://prdownloads.sourceforge.net/phpmyfamily/phpmyfamily-1.4.1.tar.bz2?download">phpmyfamily-1.4.1.bz2</a></td>
		<td class="tbl_odd">73,772 bytes</td>
		<td class="tbl_odd">dd51f71da8695f5a5c1a01b304c95b25</td>
	</tr>
  </tbody>
</table>
<p>You may need to shift and click to download the files.</p>
<!--end of main-->

<h3>changelog</h3>
<strong><em>phpmyfamily</em> v1.4.1 (Released 24/03/2005)</strong>
<p>This is an important security release and all users are urged to upgrade as soon as possible.</p>

<strong><em>phpmyfamily</em> v1.4.0 (Released 12/12/2004)</strong>
<p>The latest release of <em>phpmyfamily</em> has many new features and enhancements. Please see the CHANGELOG for full details.</p>

<strong><em>phpmyfamily</em> v1.3.1 (Released 19/06/2004)</strong>
<p>The latest release of <em>phpmyfamily</em> has a major security fix. </p>

<strong><em>phpmyfamily</em> v1.3.0 (Released 26/05/2004)</strong>
<p>This release of <em>phpmyfamily</em> has many feature enhancements.  New to this version are;</p>
<ul>
	<li>Surname List - View all names on one page; great for search engines</li>
	<li>Email monitoring - sign up and be informed of any changes to an individual</li>
	<li>Image gallery - view all images at once</li>
	<li>Census view - see other individuals captured by a census </li>
</ul>

<strong><em>phpmyfamily</em> v1.2.5 (Released 09/04/2004)</strong>
<p>This release of <em>phpmyfamily</em> has many feature enhancements.  New to this version are;</p>
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
