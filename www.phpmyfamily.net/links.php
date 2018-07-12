<?php
		// download.giric.com
		// Copyright(c)2004 Simon E Booth
		// All Rights Reserved

		// page title
		$title = "links";
		include "inc/db.inc.php";

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
<img src="images/filler.png" width="10" height="520" border="0" align="left" alt="" />

<!--start of main-->
<br />
<h3>links</h3>
<p>There are already many sites around the world powered by <em>phpmyfamily</em>.  If you wish your site to be added to the list below, please complete the form below.  Your site will be added as soon as it has been verified.</p>

<table>
  	<tbody>
    <tr>
      <th>Name  </th>
      <th>Description  </th>
    </tr>

<?php

	$query = "SELECT * FROM main_sites ORDER BY site_name";
	$result = mysql_query($query) or die("Error with database");

	$i = 0;
	while ($row = mysql_fetch_array($result)) {
		if ($i == 0 || fmod($i, 2) == 0)
			$class = "tbl_odd";
		else
			$class = "tbl_even";
		echo "<tr>\n";
			echo "<td class=\"".$class."\"><a href=\"".$row["site_url"]."\">".$row["site_name"]."</a></td>\n";
			echo "<td class=\"".$class."\">".$row["site_desc"]."</td>\n";
		echo "</tr>\n";
	}

?>
  </tbody>
</table>

<hr>
<!--mailto form-->
<?php
	@$post = $_POST["action"];
	if ($post == "submit"){
		mail("simon.booth@giric.com",
		     "New phpmyfamily listing",
			 $_POST["site_name"]."\n".$_POST["site_url"]."\n".$_POST["site_email"]."\n".$_POST["site_desc"]."\n",
			 "From: site@giric.com");
		echo "<font color=\"red\"><p>Thank you for submitting your details.  Your site will be listed here shortly.  A confirmation will be sent to you once you site has been included.</p></font>\n";
	}
	else {
?>
<h3>Submission Details</h3>
<form action="links.php" method="POST">
<input type="hidden" name="action" value="submit">
<table>
  <tbody>
    <tr>
      <td class="tbl_odd"> Site Name</td>
      <td class="tbl_even"> <input type="text" name="site_name" size="40" maxlength="40"> </td>
    </tr>
    <tr>
      <td class="tbl_odd">Site Url  </td>
      <td class="tbl_even"> <input type="text" name="site_url" value="http://" size="40" maxlength="60"> </td>
    </tr>
    <tr>
      <td class="tbl_odd">Your Email  </td>
      <td class="tbl_even"> <input type="text" name="site_email" size="40" maxlength="128"> </td>
    </tr>
    <tr>
      <td valign="top" class="tbl_odd">Site Description  </td>
      <td class="tbl_even"> <textarea name="site_desc" cols="40" rows="5"></textarea> </td>
    </tr>
	<tr>
	  <td><input type="submit" name="Submit"></td>
	</tr>
  </tbody>
</table>

</form>

<?php
	}
?>

		</td>
	</tr>
  </tbody>
</table>

<?php
	include "inc/footer.inc.php";
	// eof
?>
