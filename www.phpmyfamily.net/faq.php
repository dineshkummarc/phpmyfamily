<?php
		// download.giric.com
		// Copyright(c)2004 Simon E Booth
		// All Rights Reserved

		// page title
		$title = "faq";

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
<h3>faq</h3>
<p>These are some of the more common problems encountered whilst using <em>phpmyfamily</em>.</p>
<h4>installation</h4>
<ol>
  <li><a href="faq.php#install1">How do I change the date format?</a>  </li>
  <li><a href="faq.php#install2">Why does the image upload not work?</a></li>
  <li><a href="faq.php#install3">Why do I keep getting a message about fmod()?</a></li>
</ol>
<h4>use</h4>
<ol>
  <li><a href="faq.php#use1">How do I hide details of living people for whom I have no date of birth?</a></li>
</ol>
<h3>answers</h3>

<h4>installation</h4>
<ol>
  <li><a name="install1"></a>How do I change the date format?<p>It is now easy to change the format of the displayed dates.  Line 28 of your chosen language file can be altered to any POSIX date string.  Look <a href="http://dev.mysql.com/doc/mysql/en/Date_and_time_functions.html#IDX1364" target="_blank">here</a> for details of valid characters.</p>  </li>
  <li><a name="install2"></a>Why does the image upload not work?<p>For image uploading to work, the web-server must have write permissions to the image directory.  For instance, if you are running Apache and it runs as 'nobody' you could issue the commands 'chgrp nobody images' and 'chmod g+w images.'</p></li>
  <li><a name="install3"></a>Why do I keep getting a message about fmod()?<p>You are seeing this message because you are running a version of PHP prior to 4.2.0.  Although the recommended option is to upgrade, this is not always possible so a replacement function has been included.  Un-comment the lines for the fmod() function in the inc/functions.inc.php file.</p></li>
</ol>
<h4>use</h4>
<ol>
  <li><a name="use1"></a>How do I hide details of living people for whom I have no date of birth?<p>You can enter a date as any combination of year, month or day.  If you enter a date of birth as '9999-00-00' then that person will only be visible to registered users.  Alternatively, use '1909-00-00' etc.</p></li>
</ol>

<br /><br />
<!--end of main-->
		</td>
	</tr>
  </tbody>
</table>

<?php
	include "inc/footer.inc.php";
	// eof
?>
