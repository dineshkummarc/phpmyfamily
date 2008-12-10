<?php
set_include_path("..");
include_once("modules/db/DAOFactory.php");

//header("Content-Type", "text/json");

function printPeopleOption($search, $per) {

}
?>
{}&&{identifier:"personid",
items: [
<?php

	$search = new PersonDetail();
	$search->queryType = Q_TYPE;
	$search->person_id = 0;
	$search->gender = 'A';
	if (isset($_POST["id"])) { 
		$search->person_id = $_POST["id"]; 
		$search->queryType = Q_IND;
	}
	if (isset($_POST["gender"])) { $search->gender = $_POST["gender"]; }
	if (isset($_POST["start"])) { $search->start = $_POST["start"]; }
	if (isset($_POST["count"])) {
		if ($_POST["count"] == "Infinity") {
			$search->count = 10;
		} else {
			$search->count = $_POST["count"];
		}
		$search->limit = 10;	
	}
	
	if (isset($_POST["name"])) { 
		$names = explode(',', str_replace("*", "%", $_POST["name"]));
		$lastname = rtrim($names[0]);
		if (count($names) > 1) {
			$firstname = ltrim($names[1]);
			$search->name->forenames = $firstname;
		}	
		$search->name->surname = $lastname;
	}
		
	if (isset($_POST["date"])) { $search->date_of_birth = $_POST["date"]; }
	
	$dao = getPeopleDAO();
	$dao->getPersonDetails($search);
	
	for($i=0;$i<$search->numResults;$i++) {
		$per = $search->results[$i];
?>
		{name:"<?php echo $per->getSelectName();?>", label:"<?php echo $per->getSelectName();?>",personid:"<?php echo $per->person_id;?>"}
<?php
		if ($i + 1 < $search->numResults) { echo ","; }
	}

?>
]}