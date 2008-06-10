<?php
define('BIRTH_EVENT', 0);
define('BAPTISM_EVENT', 1);
define('DEATH_EVENT', 2);
define('BURIAL_EVENT', 3);
define('BANNS_EVENT', 4);
define('MARRIAGE_EVENT', 5);
define('CENSUS_EVENT', 6);
define('OTHER_EVENT', 7);

class Event extends Base {
	var $event_id;
	var $type;
	var $descrip; //To describe other events
	var $person;
	var $location;
	var $date1;
	var $date1_modifier = 0;
	var $fdate1;
	var $date2 = '0000-00-00';
	var $date2_modifier = 0;
	var $fdate2;
	var $source;
	var $reference;
	var $certified;
	var $notes;
	
	var $attendees;
	
	function Event() {
		$this->person = new PersonDetail();
		$this->location = new Location();
		$this->attendees = array();
	}
	
	function setFromRequest() {
		@$this->event_id = $_REQUEST["event"];
	}
	
	function getFields($prefix) {
		global $currentRequest;
		
		$fields = array("event_id", "etype", "descrip", "person_id", "location_id", "d1type", "date1", "d2type", "date2", 
					"source", "reference", "certified", "notes");
			
		$ret = Base::addFields($prefix, $fields);
		$ret .= ", DATE_FORMAT(".$prefix.".date1, ".$currentRequest->datefmt.") AS ".$prefix."_fdate1";
		//$ret .= ", DATE_FORMAT(".$prefix.".date2, ".$currentRequest->datefmt.") AS ".$prefix."_fdate2";
		return ($ret);
	}
	
	function setFromPost($prefix = '') {
		$this->loadFields($_POST, $prefix);
		$this->descrip = htmlspecialchars($this->descrip, ENT_QUOTES);
		$this->source = htmlspecialchars($this->source, ENT_QUOTES);
		$this->reference = htmlspecialchars($this->reference, ENT_QUOTES);
		$this->notes = htmlspecialchars($this->notes, ENT_QUOTES);
		//Required in addition to the processing in loadFields
		$this->location->setFromPost($prefix);
	}
	function loadFields($row, $prefix) {
		$this->event_id = $row[$prefix."event_id"];
		$this->type = $row[$prefix."etype"];
		@$this->descrip = $row[$prefix."descrip"];
		$this->person->person_id = $row[$prefix."person_id"];
		$this->date1 = $row[$prefix."date1"];
		$this->date1_modifier = $row[$prefix."d1type"];
		@$this->fdate1 =  $row[$prefix."fdate1"];
		//Not used at present
		//@$this->date2 = $row[$prefix."date2"];
		//@$this->date2_modifier = $row[$prefix."d2type"];
		//@$this->fdate2 =  $row[$prefix."fdate2"];
		@$this->source = $row[$prefix."source"];
		$this->reference = $row[$prefix."reference"];
		@$this->certified = $row[$prefix."certified"];
		if ($this->certified == "") {
			$this->certified = "N";
		}
		$this->notes = $row[$prefix."notes"];
		$this->location->loadFields($row, $prefix);
	}
	
	function hasData() {
		if ((isset($this->event_id) && $this->event_id > 0) || $this->location->hasData()) {
			return (true);
		}
		if ($this->location->place == '' && $this->date1 == '0000-00-00' && $this->notes == '' &&
			$this->source == '' && $this->reference == '') {
			return (false);
		}
		return (true);
	}
	
	function getDate1() {
		$ret = formatdate($this->fdate1);
		return ($ret);
	}
}
?>
