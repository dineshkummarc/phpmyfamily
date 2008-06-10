<?php
include_once("modules/db/MyFamilyDAO.php");
include_once "modules/census/CensusDAO.php";
include_once "modules/relations/RelationsDAO.php";
include_once "modules/transcript/TranscriptDAO.php";
include_once "modules/image/ImageDAO.php";
include_once "modules/admin/AdminDAO.php";
include_once "modules/location/LocationDAO.php";
include_once "modules/event/EventDAO.php";
include_once "inc/database.inc.php";

//=====================================================================================================================
// Database connection routines
//=====================================================================================================================
// connect to database
if ($usepconnect) {
	$mysql_connect = mysql_pconnect($dbhost, $dbuser, $dbpwd) or die("phpmyfamily cannot access the database server (".$dbhost.")");
} else {
	$mysql_connect = mysql_connect($dbhost, $dbuser, $dbpwd) or die("phpmyfamily cannot access the database server (".$dbhost.")");
}

$database_select = mysql_select_db($dbname) or die("phpmyfamily cannot access the database (".$dbname.")");

//Has to be after the database setup as it reads stuff from the db
include_once "classes/Config.php";

function getCensusDAO() {
	return (new CensusDAO());
}

function getPeopleDAO() {
	return (new PeopleDAO());
}

function getRelationsDAO() {
	return (new RelationsDAO());
}

function getTranscriptDAO() {
	return (new TranscriptDAO());
}

function getImageDAO() {
	return (new ImageDAO());
}

function getAdminDAO() {
	return (new AdminDAO());
}

function getLocationDAO() {
	return (new LocationDAO());
}

function getEventDAO() {
	return (new EventDAO());
}

	// function: stamppeeps
	// timestamp a particular person for last updated
	function stamppeeps($person) {
		// declare globals used within
		global $tblprefix;
		$config = Config::getInstance();
		

		// update the updated column
		$query = "UPDATE ".$tblprefix."people SET updated = NOW() WHERE person_id = '".$person->person_id."'";
		$result = mysql_query($query);

		// If we allow tracking by email
		if ($config->tracking)
			track_person($person);

		// If Big Brother is watching
		if ($config->bbtracking)
			bb_person($person);

	}	// end of stamppeeps()
	
	// function: track_person
	// send an email to everybody tracking an individual
	function track_person($person) {
		global $tblprefix;
		global $err_person;
		global $eTrackSubject;
		global $eTrackBodyTop;
		global $eTrackBodyBottom;
		global $currentRequest;
		
		$config = Config::getInstance();

		$tquery = "SELECT ".$tblprefix."people.person_id, email FROM ".$tblprefix."people, ".$tblprefix."tracking WHERE ".
		$tblprefix."people.person_id = ".$tblprefix."tracking.person_id AND ".$tblprefix."people.person_id = ".
		quote_smart($person->person_id).
		" AND `key` = '' AND expires IS NULL";
		
		$tresult = mysql_query($tquery) or die($err_person);
		while ($trow = mysql_fetch_array($tresult)) {
			$headers = "Content-type: text/plain; charset=iso-8859-1\r\n";
			$headers .= "From: <".$config->trackemail.">\r\n";
			$headers .= "X-Mailer: PHP/" . phpversion();
			$subject = str_replace("$1", $person->getDisplayName(), $eTrackSubject);
			$body = str_replace("$1", $person->getDisplayName(), $eTrackBodyTop);
			$body = str_replace("$2", $config->absurl, $body);
			$body = str_replace("$3", $currentRequest->name, $body);
			$body .= $config->absurl."people.php?person=".$person->person_id."\n\n";
			$body .= $eTrackBodyBottom;
			$body .= $config->absurl."track.php?person=".$person->person_id."&action=unsub&email=".$trow["email"]."&name=".urlencode($person->name->getDisplayName())."\n";

			mail($trow["email"], $subject, $body, $headers);
		}
		mysql_free_result($tresult);
	}	// eod of track_person()

	// function: bb_person($person)
	// send a big brother email on all changes
	function bb_person($person, $action = "updated") {
		global $tblprefix;
		global $err_person;
		global $eBBSubject;
		global $eTrackBodyTop;
		global $eBBBottom;
		global $currentRequest;
		
		$config = Config::getInstance();
		
		// Get the details of the person changed from the db
		// Set up the headers to be meaningful
			$headers = "Content-type: text/plain; charset=iso-8859-1\r\n";
			$headers .= "From: <".$config->trackemail.">\r\n";
			$headers .= "X-Mailer: PHP/" . phpversion();

			// Give a subject line
			$subject = str_replace("$1", $person->getDisplayName(), $eBBSubject);

			// Flesh out the body
			$body = str_replace("$1", $person->getDisplayName(), $eTrackBodyTop);
			$body = str_replace("$2", $config->absurl, $body);
			$body = str_replace("$3", $currentRequest->name, $body);
			$body .= $config->absurl."people.php?person=".$person->person_id."\n\n";
			$body .= $eBBBottom;

		// Fire of the Big Brother email
		mail($config->email, $subject, $body, $headers);
	}	// end of bb_person()

// function: quote_smart
// Quote a variable to make is smart
function quote_smart($value) {

    // Stripslashes
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    // Quote if not integer
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value) . "'";
    }
    return $value;
}	// end of quote_smart

?>
