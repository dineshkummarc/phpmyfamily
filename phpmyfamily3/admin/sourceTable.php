<?php

$tconfig = "CREATE TABLE `".$tblprefix."source` (
  `source_id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NULL DEFAULT NULL,
  `reference` VARCHAR(255) NULL DEFAULT NULL,
  `url` VARCHAR(255) NULL DEFAULT NULL,
  `ref_date` date NULL,
  `notes` longtext NULL DEFAULT NULL,
  `certainty` INTEGER NULL DEFAULT NULL,
  PRIMARY KEY (`source_id`),
  INDEX source_1(`title`)
)
ENGINE = InnoDB;
";

try {
  $pdo->exec($tconfig);
	echo "Sources table created<br/>\n";
} catch (PDOException $e) { 
	die("phpmyfamily: Error creating Sources table!!!".$e);
}

$tconfig = "CREATE TABLE `".$tblprefix."source_event` (
  `source_id` INTEGER UNSIGNED NOT NULL,
  `event_id` INTEGER UNSIGNED NOT NULL
)
ENGINE = InnoDB;
";

try {
  $pdo->exec($tconfig);
	echo "Sources Event table created<br/>\n";
} catch (PDOException $e) { 
	die("phpmyfamily: Error creating Sources Event table!!!".$e);
}

$tconfig = "ALTER TABLE `".$tblprefix."source_event`
  ADD CONSTRAINT `".$tblprefix."FK_se_1` FOREIGN KEY (`source_id`) REFERENCES `".$tblprefix."source` (`source_id`),
  ADD CONSTRAINT `".$tblprefix."FK_se_2` FOREIGN KEY (`event_id`) REFERENCES `".$tblprefix."event` (`event_id`);";

try {
  $pdo->exec($tconfig);
	echo "sources event table foreign key<br>\n";
} catch (PDOException $e) { 
	die("phpmyfamily: Error creating sources event table foreign key!!!".$e);
}

?>
