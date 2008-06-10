<?php

class Name extends Base {
	
	var $type;
	var $title;
	var $forenames;
	var $link;
	var $suffix;
	var $surname;
	var $knownas;

	function setFromPost() {
		if (isset($_POST["person"])) { $this->person_id = quote_smart($_POST["person"]);}		
		
		$this->title = htmlspecialchars($_POST["frmTitle"], ENT_QUOTES);
		$this->forenames = htmlspecialchars($_POST["frmForenames"], ENT_QUOTES);
		$this->link = htmlspecialchars($_POST["frmLink"], ENT_QUOTES);
		$this->surname = htmlspecialchars($_POST["frmSurname"], ENT_QUOTES);
		$this->knownas = htmlspecialchars($_POST["frmAKA"], ENT_QUOTES);
		$this->suffix = $_POST["frmSuffix"];
	}

	
	function loadFields($edrow, $prefix = '') {
		$this->title = $edrow[$prefix."title"];
		$this->forenames = $edrow[$prefix."forenames"];
		$this->link = $edrow[$prefix."link"];
		$this->surname = $edrow[$prefix."surname"];
		$this->suffix = $edrow[$prefix."suffix"];
		$this->knownas = $edrow[$prefix."knownas"];
	}
	
	//Minimum fields to display a person, and work out whether they should be 
	//shown
	function getFields($tbl) {
		global $currentRequest;
		
		$fields = array("title", "forenames", "link", "surname", "suffix", "knownas");
			
		return (Base::addFields($tbl, $fields));
	}
	
	function getDisplayName() {
		$ret = $this->title;
		if (strlen($ret) > 0) {
			$ret .= " ";
		}
		$ret .= $this->forenames." ";
		if (strlen($this->link)) {
			$ret .= $this->link." ";
		}
		$ret .= $this->surname;
		if (strlen($this->suffix)) {
			$ret .= " ".$this->suffix;
		}
		return($ret);
	}
	
	function getReverseName() {
		$ret = $this->surname;
		if (strlen($this->suffix)) {
			$ret .= " ".$this->suffix;
		}
		$ret .=", ";
		if (strlen($this->title)) {
			$ret .= $this->title." ";
		}
		$ret .= $this->forenames;
		if (strlen($this->link)) {
			$ret .= " ".$this->link;
		}
		return ($ret);
	}
}

?>
