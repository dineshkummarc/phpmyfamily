<?php
set_include_path("..");
include_once("modules/db/DAOFactory.php");

//header("Content-Type", "application/json");

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
	
	$search->count = 10;
	if (isset($_POST["count"])) {
		if ($_POST["count"] == "Infinity") {
			$search->count = 10;
		} else {
			$search->count = $_POST["count"];
		}
	}
	$pos = false;
	if (isset($_POST["name"])) { 
		$names = str_replace("*", "%", $_POST["name"],$count);
		if ($count == 0) {
			$names .= "%";
		}
		$search->parseSelectName($names);
		$pos = strpos($names, '(');
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