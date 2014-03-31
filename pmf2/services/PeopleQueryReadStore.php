<?php
set_include_path("..");
include_once("modules/db/DAOFactory.php");

header("Content-Type", "application/json");

foreach (getallheaders() as $header_name => $value) {
	//X-Range required for Opera
        if ($header_name == "Range" || $header_name == "X-Range") {
                list($n,$v) = explode("=",$value);
                list($start, $end) = explode("-",$v);
                $count = $end - $start;
        }
}

        $search = new PersonDetail();
        $search->queryType = Q_TYPE;
        $search->person_id = 0;
        $search->gender = 'A';
        $search->count = 10;
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
        case 'POST':
	echo 'identifier:"personid",';
	echo 'items: [';
        if (isset($_POST["id"])) { $id = $_POST["id"];}
        if (isset($_POST["gender"])) { $gender = $_POST["gender"];}
        if (isset($_POST["start"])) { $start = $_POST["start"];}
        if (isset($_POST["date"])) { $date = $_POST["date"];}
        if (isset($_POST["count"])) { $count = $_POST["count"];}
        if (isset($_POST["name"])) { $name = $_POST["name"];}
        break;
    case 'GET':
        if (isset($_GET["id"]) && $_GET["id"] != '') { 
		if ($_GET["id"] != '') {
			$id = $_GET["id"];
		}
	}
	else {
		echo '[';
        	if (isset($_GET["gender"])) { $gender = $_GET["gender"];}
        	//if (isset($_GET["start"])) { $start = $_GET["start"];}
        	if (isset($_GET["date"]) && $_GET["date"] != "0") { $date = $_GET["date"];}
        	//if (isset($_GET["count"])) { $count = $_GET["count"];}
        	if (isset($_GET["name"])) { $name = $_GET["name"];}
	}
    break;
}
    	$csearch = new PersonDetail();
    	$csearch->queryType = Q_COUNT;
    $csearch->gender = 'A';

    $search = new PersonDetail();
    $search->queryType = Q_TYPE;
    $search->person_id = 0;
    $search->gender = 'A';
    if (isset($id)) { 
        $search->person_id = $id; 
        $search->queryType = Q_IND;
    } else {
    ?>
    <?php
//{name:"", label:"",personid:"-1"},
    }
    if (isset($gender)) { 
	$search->gender = $gender; 
	$csearch->gender = $gender; 
    }
    if (!isset($start)) {
      $start = 0;
    }
    $search->start = $start;
    
    $search->count = 10;
    if (isset($count)) {
        if ($count == "Infinity") {
            $search->count = 10;
        } else {
            $search->count = $count;
        }
    }
    $pos = false;
    if (isset($name)) { 
        $names = str_replace("*", "%", $name,$count);
        if ($count == 0) {
            $names .= "%";
        }
        $search->parseSelectName($names);
        $csearch->parseSelectName($names);
        $pos = strpos($names, '(');
    }
		
    if (isset($date)) {
	$search->date_of_birth = $date;
	$csearch->date_of_birth = $date;
    }
	$dao = getPeopleDAO();
	$dao->getPersonDetails($search);

	$dao->getPersonDetails($csearch);
	$total = $csearch->numResults;

	$end = $start + $search->numResults;
	
        $resultsHeader = "Content-Range: items $start-$end/$total";
	header($resultsHeader);

	for($i=0;$i<$search->numResults;$i++) {
		$per = $search->results[$i];
?>
		{name:"<?php echo $per->getSelectName();?>", label:"<?php echo $per->getSelectName();?>",personid:"<?php echo $per->person_id;?>"}
<?php
		if ($i + 1 < $search->numResults) { echo ","; }
	}

        if (!isset($id)) {
		echo "]";
	}
?>
