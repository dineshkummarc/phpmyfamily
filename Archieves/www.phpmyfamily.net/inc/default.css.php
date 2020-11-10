<?php
	// download.giric.com
	// Copyright(c)2004 Simon E Booth
	// All Rights Reserved

	header("Content-type: text/css");
	$font_size = "small";
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
		$font_size = "x-small";
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera'))
		$font_size = "small";
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mac'))
		$font_size = "medium";
?>
td.tbl_even    {
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: <?php echo $font_size; ?>;
	background-color: #EEEEFF;
}
td.tbl_odd    {
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: <?php echo $font_size; ?>;
	background-color: #E0E0E0;
}
td.tower  {
	background-color: #ADB5BA;
}
tr.header {
	background-color: #FFFFFF;
}
table.body     {
	color: #000000;
	background-color: #D0D1D3;
}
table.header   {
	background-color: #7E868A;
}
a.hd_link  {
	color: #5f5f5f;
}
a.hd_link:hover  {
	color: #5f5f5f;
}
input,select,textarea      {
	background-color: #DDDDDD;
}
th    {
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: <?php echo $font_size; ?>;
	background-color: #a0a0a0;
}
body    {
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: <?php echo $font_size; ?>;
	background-color: #C0C6D3;
}
a:hover    {
	color: #0000a0;
	text-decoration: underline;
}
a    {
	color: #5f5f5f;
	text-decoration: none;
}
h5  {
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: 75%;
}
td  {
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: <?php echo $font_size; ?>;
}
h4  {
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: 100%;
}
h1  {
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: 175%;
}
h2  {
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: 150%;
}
h3  {
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: 125%;
}
h6  {
	font-family: verdana, arial, helvetica, sans-serif;
	font-size: 50%;
}
.restrict  {
	color: red;
}
