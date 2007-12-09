<?php
		// download.giric.com
		// Copyright(c)2004-2007 Simon E Booth
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
<p>The current release can be downloaded from the <a href="https://sourceforge.net/project/showfiles.php?group_id=110402&package_id=119221&release_id=560275">SourceForge download page</a>.</p>
<!--end of main-->

<h3>changelog</h3>
<strong><em>phpmyfamily</em> v1.4.2 (Released 9/11/2007)</strong>
<p>This is a minor security release and all users are urged to upgrade when possible.</p>

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
