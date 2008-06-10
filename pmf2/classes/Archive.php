<?php

class Archive extends Base {

	var $title;
	var $date;
	var $fdate; //Formatted date since php doesn't handle dates well
	var $description;
	var $file_name;

	function setFromRequest() {
		$this->person = new PersonDetail();
		$this->person->setFromRequest();
	}
	
	function setFromPost() {
		$this->person = new PersonDetail();
		$this->person->setFromPost();
		$this->title = htmlspecialchars($_POST["frmTitle"], ENT_QUOTES);
		$this->date = $_POST["frmDate"];
		$this->description = htmlspecialchars($_POST["frmDesc"], ENT_QUOTES);
		if (isset($_REQUEST["image_id"])) { $this->image_id = $_REQUEST["image_id"]; }
		@$this->file_name = $_FILES["userfile"]["name"];
		@$this->tmp_file_name = $_FILES["userfile"]["tmp_name"];
	}
	
	function getTitle() {
		return (htmlentities($this->title,ENT_QUOTES));
	}
	
	function getDescription() {
		return (htmlentities($this->description,ENT_QUOTES));
	}
}
?>
