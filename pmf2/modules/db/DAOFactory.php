<?php
include_once("modules/db/MyFamilyDAO.php");
include_once "modules/census/CensusDAO.php";
include_once "modules/relations/RelationsDAO.php";
include_once "modules/transcript/TranscriptDAO.php";
include_once "modules/image/ImageDAO.php";
include_once "modules/admin/AdminDAO.php";
include_once "modules/location/LocationDAO.php";
include_once "modules/event/EventDAO.php";
include_once "modules/gedcom/GedcomDAO.php";
include_once "modules/source/SourceDAO.php";
include_once "modules/user/UserDAO.php";
include_once "modules/user/TrackingDAO.php";
include_once "inc/database.inc.php";
include_once "inc/class.phpmailer.php";

//=====================================================================================================================
// Database connection routines
//=====================================================================================================================
// connect to database

$pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpwd);
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

function getGedcomDAO() {
	return (new GedcomDAO());
}

function getSourceDAO() {
	return (new SourceDAO());
}

function getUserDAO() {
	return (new UserDAO());
}

function getTrackingDAO() {
	return (new TrackingDAO());
}

	// function: stamppeeps
	// timestamp a particular person for last updated
	function stamppeeps($person) {
		$dao = getTrackingDAO();
		$dao->stamppeeps($person);

	}	// end of stamppeeps()
	
// function: quote_smart
// Quote a variable to make is smart
function quote_smart($value) {
	global $pdo;
	if (!is_numeric($value)) {
		$ret = $pdo->quote($value);
	} else {
		$ret = $value;
	}
	return ($ret);

}	// end of quote_smart

function db_date_value($date) {
    $ret_date = '';
    if ($date == '0000-00-00' || $date == '') {
        $ret_date = 'NULL';
    } else {
        $ret_date = quote_smart($date);
    }

    return ($ret_date);
}

?>
