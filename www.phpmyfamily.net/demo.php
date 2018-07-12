<?php
		// download.giric.com
		// Copyright(c)2004 Simon E Booth
		// All Rights Reserved

		// page title
		$title = "demo";

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
		<td colspan="2" valign="top">
<img src="images/filler.png" width="10" height="520" border="0" align="left" alt="" />

<!--start of main-->
<br />
<h3>demo</h3>
<p>Please feel free to browse the online demo.  A fictitious Smith family from Hammersmith, London has been provided so that you can see how the appliction works.  I suggest that you try browsing first to get an idea of how the security protects the identity of living persons, and then login so that you can see how easy it is to alter records and add people, marriages, census records etc.</p>
<p>You can access it by clicking <a href="demo/">here.</a></p>
<p>To login:</p>
<ol>
  <li>Username: demo  </li>
  <li>Password: demo  </li>
</ol>
<!--end of main-->

		</td>
	</tr>
  </tbody>
</table>

<?php
	include "inc/footer.inc.php";
	// eof
?>
