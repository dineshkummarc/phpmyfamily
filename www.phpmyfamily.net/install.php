<?php
		// download.giric.com
		// Copyright(c)2004 Simon E Booth
		// All Rights Reserved

		// page title
		$title = "installation";

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
<img src="images/filler.png" width="10" height="650" border="0" align="left" alt="" />

<!--start of main-->
<br />
<h3>installation</h3>
<p>It should be possible to run <em>phpmyfamily</em> on any webserver that supports PHP and can access a MySQL database.  It is currently being developed on PHP 4.3.3 but should be compatible with any version from 4.2.0 onward.  The use of the GD library is required if you are to take advantage of the image galleries (Bundled with PHP since 4.3.0).</p>

<p>The pre-requisites for installation are:</p>
<ul>
  <li><a href="http://www.php.net" target="_blank">PHP</a> >= 4.2.0  </li>
  <li><a href="http://www.mysql.com" target="_blank">MySQL</a>  </li>
</ul>

<p>To install:</p>
<ol>
  <li>Uncompress the archive to the directory of your choice.  </li>
  <li>Amend inc/config.inc.php to the correct values for your system and database. </li>
  <li>Point your browser at http://www.yoursite.com/phpmyfamily-1.2.5/inc/install.php.  This will populate your database with the needed tables and install the default user name and password combination (admin/admin).  It is highly recommended that you change these values as soon as possible.  Additionally, a blank record is created for the first male in your tree.</li>
  <li>The default Alpha Male is your starting point.  Edit this record to represent the point at which you wish to start recording your family tree.  At any point you can add a new person into the database by clicking the "Add a new person" link at the bottom of the page.  The tree structure is made by linking individuals to their parents and spouses - links to siblings and children are automatically maintained.</li>
</ol>

<p>The source archives are available from the <a href="downloads.php">downloads</a> page.</p>

<h4>upgrading from 1.2.4</h4>
<p>No database changes from have been made from 1.2.4 so upgrading is a very simple task.  The cleanest way to accomplish this is to delete the existing files and extract the new archive in their place.  Remember to change the database and system variables in inc/config.inc.php.  There is no need to run inc/install.php (it will fail if you try to).</p>

<br />
<!--end of main-->
		</td>
	</tr>
  </tbody>
</table>

<?php
	include "inc/footer.inc.php";
	// eof
?>
