<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2004  Simon E Booth (simon.booth@giric.com)

	//This program is free software; you can redistribute it and/or
	//modify it under the terms of the GNU General Public License
	//as published by the Free Software Foundation; either version 2
	//of the License, or (at your option) any later version.

	//This program is distributed in the hope that it will be useful,
	//but WITHOUT ANY WARRANTY; without even the implied warranty of
	//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	//GNU General Public License for more details.

	//You should have received a copy of the GNU General Public License
	//along with this program; if not, write to the Free Software
	//Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

	include "../inc/config.inc.php";

	// Check and duck out if tables already exist
	$dbcheck = mysql_list_tables($dbname);
	if (mysql_num_rows($dbcheck) <> 0) {
		while ($row = mysql_fetch_array($dbcheck)) {
			if ($row["0"] == "".$tblprefix."users" || $row["0"] == "".$tblprefix."people" || $row["0"] == "".$tblprefix."census" || $row["0"] == "".$tblprefix."spouses" || $row["0"] == "".$tblprefix."documents" || $row["0"] == "".$tblprefix."images" || $row["0"] == "".$tblprefix."tracking")
				die("phpmyfamily: Tables appear to already exist - please check you installation");
		}
	}

	echo "<html>\n";
	echo "<head>\n";
	echo "<title>Installing phpmyfamily</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<h2>Installing phpmyfamily database</h2>\n";

	// install ".$tblprefix."users
	$fusers = "CREATE TABLE `".$tblprefix."users` (
  `id` smallint(6) NOT NULL auto_increment,
  `username` varchar(10) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `admin` enum('Y','N') NOT NULL default 'N',
  `edit` enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (`id`)
)";
	$rusers = mysql_query($fusers) or die("phpmyfamily: Error creating user table!!!");
	echo "User table created<br>\n";
	$fadmin = "INSERT INTO ".$tblprefix."users VALUES('43', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Y')";
	$radmin = mysql_query($fadmin) or die("phpmyfamily: Error creating default user!!!");
	echo "Default user created<br>\n";

	// install ".$tblprefix."people
	$fpeople = "CREATE TABLE `".$tblprefix."people` (
  `person_id` smallint(5) unsigned zerofill NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `surname` varchar(20) NOT NULL default '',
  `date_of_birth` date NOT NULL default '0000-00-00',
  `birth_cert` enum('Y','N') NOT NULL default 'N',
  `birth_place` varchar(50) NOT NULL default '',
  `date_of_death` date NOT NULL default '0000-00-00',
  `death_cert` enum('Y','N') NOT NULL default 'N',
  `death_reason` varchar(50) NOT NULL default '',
  `gender` enum('M','F') NOT NULL default 'M',
  `mother_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `father_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `narrative` longtext NOT NULL,
  `updated` timestamp(14) NOT NULL,
  PRIMARY KEY  (`person_id`),
  KEY `gender` (`gender`),
  KEY `surname` (`surname`),
  KEY `idx_list_peeps1` (`surname`,`name`,`date_of_birth`,`person_id`),
  KEY `idx_list_peeps2` (`gender`,`surname`,`name`,`date_of_birth`,`person_id`),
  KEY `idx_children` (`date_of_birth`,`mother_id`,`father_id`,`person_id`,`name`,`date_of_death`)
)";
	$rpeople = mysql_query($fpeople) or die("phpmyfamily: Error creating people table!!!");
	echo "People table created<br>\n";
	$fpeeps = "INSERT INTO ".$tblprefix."people(person_id, name) VALUES ('1', 'Alpha Male')";
	$rpeeps = mysql_query($fpeeps) or die("phpmyfamily: Error creating alpha male!!!");
	echo "Alpha Male created<br>\n";

	// install ".$tblprefix."spouses
	$fspouses = "CREATE TABLE `".$tblprefix."spouses` (
  `groom_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `bride_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `marriage_date` date NOT NULL default '0000-00-00',
  `marriage_cert` enum('Y','N') NOT NULL default 'N',
  `marriage_place` varchar(50) NOT NULL default '0',
  `dissolve_date` date NOT NULL default '0000-00-00',
  `dissolve_reason` char(1) NOT NULL default '',
  PRIMARY KEY  (`marriage_date`,`groom_id`,`bride_id`)
)";
	$rspouses = mysql_query($fspouses) or die("phpmyfamily: Error creating spouses table!!!");
	echo "Spouses table created<br>\n";

	// install ".$tblprefix."census
	$fcensus = "CREATE TABLE `".$tblprefix."census` (
  `person_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `census` mediumint(4) NOT NULL default '0',
  `schedule` varchar(20) NOT NULL default '',
  `address` varchar(70) NOT NULL default '',
  `condition` enum('','married','unmarried','widowed') NOT NULL default 'unmarried',
  `age` tinyint(4) unsigned NOT NULL default '0',
  `profession` varchar(45) NOT NULL default '',
  `where_born` varchar(40) NOT NULL default '',
  `other_details` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`person_id`,`census`)
)";
	$rcensus = mysql_query($fcensus) or die("phpmyfamily: Error creating census table!!!");
	echo "Census table created<br>\n";

	// install ",$tblprefix."census_years
	$cyquery = "CREATE TABLE `".$tblprefix."census_years` (
  `census_id` mediumint(9) NOT NULL auto_increment,
  `country` varchar(20) NOT NULL default '',
  `year` smallint(4) NOT NULL default '0',
  `available` enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (`census_id`),
  KEY `available` (`available`)
)";
	$cyresult = mysql_query($cyquery) or die("phpmyfamily: Error creating census years table!!!");
	echo "Census years table created<br>\n";

	// install ".$tblprefix."census_years values
	$cyvquery = "INSERT INTO ".$tblprefix."census_years (country, year) VALUES ('British Isles', '1841'), ('British Isles', '1851'), ('British Isles', '1861'), ('British Isles', '1871'), ('British Isles', '1881'), ('British Isles', '1891'), ('British Isles', '1901'), ('USA', '1790'), ('USA', '1800'), ('USA', '1810'), ('USA', '1820'), ('USA', '1830'), ('USA', '1840'), ('USA', '1850'), ('USA', '1860'), ('USA', '1870'), ('USA', '1880'), ('USA', '1890'), ('USA', '1900'), ('USA', '1910'), ('USA', '1920'), ('USA', '1930'), ('Canada', '1842'), ('Canada', '1848'), ('Canada', '1851'), ('Canada', '1861'), ('Canada', '1871'), ('Canada', '1881'), ('Canada', '1891'), ('Canada', '1901')";
	$cyvresult = mysql_query($cyvquery) or die("phpmyfamily: Error creating census years values!!!");
	echo "Census years values created<br>\n";

	// install ".$tblprefix."images
	$fimages = "CREATE TABLE `".$tblprefix."images` (
  `image_id` smallint(5) unsigned zerofill NOT NULL auto_increment,
  `person_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `title` varchar(30) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`image_id`),
  KEY `idx_show_gallery` (`person_id`,`date`)
)";
	$rimages = mysql_query($fimages) or die("phpmyfamily: Error creating images table!!!");
	echo "Images table created<br>\n";
	$fimg = "INSERT INTO ".$tblprefix."images (image_id) VALUES ('10000')";
	$rimg = mysql_query($fimg) or die("phpmyfamily: Error creating default image");
	echo "Default image created<br>";

	// install ".$tblprefix."documents
	$fdocs = "CREATE TABLE `".$tblprefix."documents` (
  `id` smallint(5) unsigned zerofill NOT NULL auto_increment,
  `person_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `doc_date` date NOT NULL default '0000-00-00',
  `doc_title` varchar(30) NOT NULL default '',
  `doc_description` varchar(60) NOT NULL default '',
  `file_name` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `person_id` (`person_id`)
)";
	$rdocs = mysql_query($fdocs) or die("phpmyfamily: Error creating documents table!!!");
	echo "Documents table created<br>\n";

	// install tracking
	$tquery = "CREATE TABLE `".$tblprefix."tracking` (
  `person_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `email` varchar(128) NOT NULL default '',
  `key` varchar(32) NOT NULL default '',
  `action` enum('sub','unsub') NOT NULL default 'sub',
  `expires` datetime default NULL,
  UNIQUE KEY `person_id` (`person_id`,`email`)
)";
	$tresult = mysql_query($tquery) or die("phpmyfamily: Error creating tracking table!!!");
	echo "tracking table created<br>\n";

	// give a link to continue
	echo "<h3>Finished!!</h3>\n";
	echo "Click <a href=\"../index.php\">here</a> to continue.  Login as (admin/admin)\n";
	echo "</body>\n";
	echo "</html>\n";
	// eof
?>
