<?php
include_once '../inc/version.php';

$tconfig = "CREATE TABLE `".$tblprefix."config` (
	`email` varchar(128) NOT NULL default '',
	`mailto` smallint(1) NOT NULL default 1,
	`desc` varchar(40) NOT NULL default 'My Family',
	`styledir` varchar(40) NOT NULL default 'styles/',
	`imagedir` varchar(40) NOT NULL default 'images/',
	`filedir` varchar(40) NOT NULL default 'docs/',
	`layout` smallint(3) NOT NULL default 0,
	`defaultstyle` varchar(40) NOT NULL default 'default.css.php',
	`lang` varchar(40) NOT NULL default 'en-uk',
	`timing` smallint(1) NOT NULL default 1,
	`gedcom` smallint(1) NOT NULL default 1,
	`datefmt` varchar(40) NOT NULL default '',
	`restricttype` smallint(3) NOT NULL default 0,
	`restrictyears` smallint(6) NOT NULL default '100',
	`restrictdate` date NOT NULL default '1900-01-01',
	`tracking` smallint(1) NOT NULL default 1,
	`trackemail` varchar(128) NOT NULL default '',
	`absurl` varchar(128) NOT NULL default '',
	`bbtracking` smallint(1) NOT NULL default 0,
	`gmapskey` varchar(90) NOT NULL default '',
	`gmapshost` varchar(20) NOT NULL default 'maps.google.com',
	`img_max` int(8) NOT NULL default '700',
	`img_min` int(8) NOT NULL default '300',
	`smtp_host` VARCHAR( 32 ) NULL ,
 	`smtp_user` VARCHAR( 32 ) NULL ,
	`smtp_password` VARCHAR( 32 ) NULL ,
	`recaptcha_public` VARCHAR( 50 ) NULL ,
	`recaptcha_private` VARCHAR( 50 ) NULL ,
 	`version` VARCHAR( 15 ) NOT NULL ,
 	`analytics_key` VARCHAR( 45 ) NULL
	) ENGINE = InnoDB";
	
	try {
		$pdo->exec($tconfig);
		echo "User config created<br>\n";

		$fconfig = "INSERT INTO ".$tblprefix."config (version) VALUES ('".$version."')";
		$pdo->exec($fconfig);
		echo "Default config created<br>\n";
	} catch (PDOException $e) { 
		die("phpmyfamily: ".$e->getMessage()); 
	}

?>
