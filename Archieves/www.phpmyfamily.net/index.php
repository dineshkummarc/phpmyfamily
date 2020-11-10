<?php
		// download.giric.com
		// Copyright(c)2004-2007 Simon E Booth
		// All Rights Reserved

		// page title
		$title = "home";

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
<img src="images/filler.png" width="10" height="545" border="0" align="left" alt="" />

<!--start main-->
<br />
<h3>home</h3>
<p><em>phpmyfamily</em> is a dynamic genealogy website builder which allows geographically dispersed family members to maintain a central database of research which is readily accessable and editable.  By having a central repository, family members can contribute as and when information becomes available without requiring them to send it to a central 'custodian', or disseminate via email, and allows anecdotal information and possible leads to be shared.</p>
<p><em>phpmyfamily</em> is now hosted by <a href="http://sourceforge.net" target="_blank">sourceforge.net</a>.  Click <a href="http://sourceforge.net/projects/phpmyfamily/">here</a> to visit the project page and access bug trackers etc.</p>

<h3>news</h3>

<p><strong><em>phpmyfamily</em> 1.4.2 released (9 December 2007)</strong><br />The latest version of <em>phpmyfamily</em> is now available for <a href="downloads.php">download</a>.  This is a minor security release and it is suggested that users upgrade when possible.</p>

<p><strong><em>phpmyfamily</em> 1.4.1 released (24 March 2005)</strong><br />The latest version of <em>phpmyfamily</em> is now available for <a href="downloads.php">download</a>.  This is an important security release and all users are urged to upgrade as soon as possible.</p>

<p><strong><em>phpmyfamily</em> 1.4.0 released (12 December 2004)</strong><br />The latest version of <em>phpmyfamily</em> is now available for <a href="downloads.php">download</a>.  This release includes many new and enhanced features, including export to GEDCOM, better management of dates and users.  Additionally, there are now Italian, German, French, Norwegian, and Spanish translations.</p>

<p><strong><em>phpmyfamily</em> 1.3.1 released (19 June 2004)</strong><br />The latest version of <em>phpmyfamily</em> is now available for <a href="downloads.php">download</a>.This is a security release following the discovery of a bug which allowed a user to gain edit capability when the application was running on a server with the php ini setting of "register_globals = On"</p>

<p><strong><em>phpmyfamily</em> 1.3.0 released (27 May 2004)</strong><br />The latest version of <em>phpmyfamily</em> is now available for <a href="downloads.php">download</a>.  This release has many new features and enhancements over previous versions and it is recommended that all users upgrade as soon as possible.  The major new features include email monitoring of individuals, new surname listings, new image gallery and new census views.  All database queries have be revisited and optomized for performance.</p>

<p><strong><em>phpmyfamily</em> 1.2.5 released (9 April 2004)</strong><br />The latest version of <em>phpmyfamily</em> is now available for <a href="downloads.php">download</a>.  This release has many new features and enhancements over previous versions and it is recommended that all users upgrade as soon as possible.  The major new features are GEDCOM import routines to allow data already gathered to be transferred and a new pedigree view.  There are also many enhancements including language translations, improved image uploading, non-uk censuses and the ability to delete. </p>

<p><strong><em>phpmyfamily</em> 1.2.4 released (29 November 2003)</strong><br />This release fixes many small bugs and feature a general optimisation of code.  There is now an automated install routine to populate the database.</p>

<br /><br />
<!--end main-->

		</td>
	</tr>
  </tbody>
</table>

<?php
	include "inc/footer.inc.php";
	// eof
?>
