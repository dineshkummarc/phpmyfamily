<?php
	//phpmyfamily - opensource genealogy webbuilder
	//Copyright (C) 2002 - 2003  Simon E Booth (simon.booth@giric.com)

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

	include "config.inc.php";

	// Check and duck out if tables already exist
	$dbcheck = mysql_list_tables($dbname);
	if (mysql_num_rows($dbcheck) <> 0) {
		while ($row = mysql_fetch_array($dbcheck)) {
			if ($row["0"] == "family_users" || $row["0"] == "family_people" || $row["0"] == "family_census" || $row["0"] == "family_spouses" || $row["0"] == "family_documents" || $row["0"] == "family_images")
				die("phpmyfamily: Tables appear to already exist - please check you installation");
		}
	}

	echo "<html>\n";
	echo "<head>\n";
	echo "<title>Installing phpmyfamily</title>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "<h2>Installing phpmyfamily database</h2>\n";

	// install family_users
	$fusers = "CREATE TABLE `family_users` (
  `id` smallint(6) NOT NULL auto_increment,
  `username` varchar(10) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `admin` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`id`)
)";
	$rusers = mysql_query($fusers) or die("phpmyfamily: Error creating user table!!!");
	echo "User table created<br>\n";
	$fadmin = "INSERT INTO family_users VALUES('43', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Y')";
	$radmin = mysql_query($fadmin) or die("phpmyfamily: Error creating default user!!!");
	echo "Default user created<br>\n";

	// install family_people
	$fpeople = "CREATE TABLE `family_people` (
  `person_id` smallint(5) unsigned zerofill NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
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
  KEY `name` (`name`),
  KEY `gender` (`gender`)
)";
	$rpeople = mysql_query($fpeople) or die("phpmyfamily: Error creating people table!!!");
	echo "People table created<br>\n";
	$fpeeps = "INSERT INTO family_people(person_id, name) VALUES ('1', 'Alpha Male')";
	$rpeeps = mysql_query($fpeeps) or die("phpmyfamily: Error creating alpha male!!!");
	echo "Alpha Male created<br>\n";

	// install family_spouses
	$fspouses = "CREATE TABLE `family_spouses` (
  `groom_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `bride_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `marriage_date` date NOT NULL default '0000-00-00',
  `marriage_cert` enum('Y','N') NOT NULL default 'N',
  `marriage_place` varchar(50) NOT NULL default '0',
  `dissolve_date` date NOT NULL default '0000-00-00',
  `dissolve_reason` char(1) NOT NULL default '',
  KEY `groom_id` (`groom_id`,`bride_id`)
)";
	$rspouses = mysql_query($fspouses) or die("phpmyfamily: Error creating spouses table!!!");
	echo "Spouses table created<br>\n";

	// install family_census
	$fcensus = "CREATE TABLE `family_census` (
  `person_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `year` enum('1841','1851','1861','1871','1881','1891','1901') NOT NULL default '1881',
  `schedule` varchar(20) NOT NULL default '',
  `address` varchar(70) NOT NULL default '',
  `condition` enum('','married','unmarried','widowed') NOT NULL default 'unmarried',
  `age` tinyint(4) unsigned NOT NULL default '0',
  `profession` varchar(45) NOT NULL default '',
  `where_born` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`person_id`,`year`)
)";
	$rcensus = mysql_query($fcensus) or die("phpmyfamily: Error creating census table!!!");
	echo "Census table created<br>\n";

	// install family_images
	$fimages = "CREATE TABLE `family_images` (
  `image_id` smallint(5) unsigned zerofill NOT NULL auto_increment,
  `person_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `title` varchar(30) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`image_id`),
  KEY `person_id` (`person_id`)
)";
	$rimages = mysql_query($fimages) or die("phpmyfamily: Error creating images table!!!");
	echo "Images table created<br>\n";

	// install family_documents
	$fdocs = "CREATE TABLE `family_documents` (
  `person_id` smallint(5) unsigned zerofill NOT NULL default '00000',
  `doc_date` date NOT NULL default '0000-00-00',
  `doc_title` varchar(30) NOT NULL default '',
  `doc_description` varchar(60) NOT NULL default '',
  `file_name` varchar(128) NOT NULL default '',
  KEY `person_id` (`person_id`)
)";
	$rdocs = mysql_query($fdocs) or die("phpmyfamily: Error creating documents table!!!");
	echo "Documents table created<br>\n";

	// give a link to continue
	echo "<h3>Finished!!</h3>\n";
	echo "Click <a href=\"../index.php\">here</a> to continue.  Login as (admin/admin)\n";
	echo "</body>\n";
	echo "</html>\n";
	// eof
?>
