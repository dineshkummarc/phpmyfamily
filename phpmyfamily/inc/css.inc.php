<?php 

function css_site() {

	//determine font for this platform
	if (browser_is_windows() && browser_is_ie()) {

		//ie needs smaller fonts
		$font_size='x-small';
		$font_smaller='xx-small';
		$font_smallest='7pt';

	} else if (browser_is_windows()) {

		//netscape on wintel
		$font_size='small';
       		$font_smaller='x-small';
		$font_smallest='x-small';

	} else if (browser_is_mac()){

		//mac users need bigger fonts
		$font_size='medium';
		$font_smaller='small';
		$font_smallest='x-small';

	} else {

		//linux and other users
		$font_size='small';
		$font_smaller='x-small';
		$font_smallest='x-small';

	}

	$site_fonts='verdana, arial, helvetica, sans-serif';

	?>
	<STYLE TYPE="text/css">
	<!--
	BODY, OL, UL, LI { font-family: verdana, arial, helvetica, sans-serif; font-size: <?php echo $font_size; ?>; background-color: #F5F5F5}
        H1 { font-size: 175%; font-family: <?php echo $site_fonts; ?>; }
        H2 { font-size: 150%; font-family: <?php echo $site_fonts; ?>; } 
        H3 { font-size: 125%; font-family: <?php echo $site_fonts; ?>; }
        H4 { font-size: 100%; font-family: <?php echo $site_fonts; ?>; } 
        H5 { font-size: 75%; font-family: <?php echo $site_fonts; ?>; }
        H6 { font-size: 50%; font-family: <?php echo $site_fonts; ?>; }
	TH { font-family: verdana, arial, helvetica, sans-serif; font-size: <?php echo $font_size; ?>; background-color: #D3DCE3}
	TD { font-family: verdana, arial, helvetica, sans-serif; font-size: <?php echo $font_size; ?>}
	PRE, TT, CODE { font-family: courier, sans-serif; font-size: <?php echo $font_size; ?>; }
	A:hover { text-decoration: none; color: #FF6666; font-size: <?php echo $font_size; ?>; }
	A.menus { color: #FF6666; text-decoration: none; font-size: <?php echo $font_smaller; ?>; }
	A.menus:visited { color: #FF6666; text-decoration: none; font-size: <?php echo $font_smaller; ?>; }
	A.menus:hover { text-decoration: none; color: #FF6666; background: #ffa; font-size: <?php echo $font_smaller; ?>; }
	A.menussel { color: #FF6666; text-decoration: none; background: #ffa; font-size: <?php echo $font_smaller; ?>; }
	A.menussel:visited { color: #FF6666; text-decoration: none; background: #ffa; font-size: <?php echo $font_smaller; ?>; }
	A.menussel:hover { text-decoration: none; color: #FF6666; background: #ffa; font-size: <?php echo $font_smaller; ?>; }
	A.menusxxs { color: #FF6666; text-decoration: none; font-size: <?php echo $font_smallest; ?>; }
	A.menusxxs:visited { color: #FF6666; text-decoration: none; font-size: <?php echo $font_smallest; ?>; }
	A.menusxxs:hover { text-decoration: none; color: #FF6666; background: #ffa; font-size: <?php echo $font_smallest; ?>; }
	-->
	</STYLE>
	<?php
}

?>