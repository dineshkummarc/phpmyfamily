<?php

function displayPersonOption($param, $per) {
	echo "<option value=\"".$per->person_id."\"";
		if ($per->person_id == $param->person_id)
			echo " selected=\"selected\"";
		echo ">".$per->getSelectName()."</option>\n";
}

// function: listpeeps
// list all people in database that current request has access to
function selectPeople($form, $omit = 0, $gender = "A", $default = 0, $auto = 1, $date = 0, $type = 0) {
	global $strOnFile, $strSelect, $strInvalidPerson;
/**
<!-- assumes
    <style type="text/css">
        @import "http://o.aolcdn.com/dojo/1.0.2/dijit/themes/tundra/tundra.css";
        @import "http://o.aolcdn.com/dojo/1.0.2/dojo/dojo.css"
    </style>
    <script type="text/javascript" src="http://o.aolcdn.com/dojo/1.0.2/dojo/dojo.xd.js"
            djConfig="parseOnLoad: true"></script>
    <body class="tundra">
-->*/

	$search = new PersonDetail();
	$search->queryType = Q_COUNT;
	$search->person_id = $omit;
	$search->gender = $gender;
	if ($date <> 0) { $search->date_of_birth = $date; }
	
	$dao = getPeopleDAO();
	
	$callback = '';
	
	$config = Config::getInstance();
	$dojo = false;
	
	if ($config->dojo) {
		$dojo = true;
	}
		
	if (!$dojo) {
		$callback = "displayPersonOption";
		echo "<select name=\"".$form."\" size=\"1\"";
		//if needed, set form to auto submit
		if ($auto == 1) {
			echo " onchange=\"this.form.submit()\"";
		}
		echo ">\n";
		
		if ($default <= 0) {
			echo "<option value=\"0\">".$strSelect."</option>\n";
		}

	}
	
	$search->queryType = Q_TYPE;
	$search->person_id = $default;
	$dao->getPersonDetails($search, $callback);
	
	if (!$dojo) {	
		echo "</select>\n";
	} 
	
	if ($dojo) {
		$store = $form."_peopleStore";
?>
    <script type="text/javascript">
      dojo.require("dijit.form.FilteringSelect");
		dojo.require("dojox.data.QueryReadStore");


		dojo.provide("<?php echo $store;?>");
		dojo.declare("<?php echo $store;?>", dojox.data.QueryReadStore, {
			fetch:function(request) {
				request.serverQuery = {q:request.query.name, start:request.start, count:request.count, gender:'<?php echo $gender;?>', 
				date:'<?php echo $date;?>', omit:'<?php echo $omit;?>'};
				return this.inherited("fetch", arguments);
			}
		});
		
    </script>
	<div dojoType="dojox.data.QueryReadStore" jsId="<?php echo $store;?>"
			url="services/PeopleQueryReadStore.php" requestMethod="post"></div>

<input searchAttr="name" id="<?php echo $form;?>"
	dojoType="dijit.form.FilteringSelect" style="width: 300px;"
	<?php
	if ($auto == 1) { echo " onChange=\"dojo.byId('".$form."').form.submit();\" "; }
	if ($default > 0) { echo " value=\"".$default."\""; }
	$query = "";
	if ($gender != "A") { $query = "gender:'$gender'";
	if ($date <> 0) { $query .= ","; }
	}
	if ($date <> 0) { $query .= "date: $date"; }
	if ($query != "") {echo 'query="{'.$query.'}"';}
	?>
	store="<?php echo $store;?>" name="<?php echo $form;?>"
	autoComplete="false" pageSize="10" invalidMessage="<?php echo $strInvalidPerson;?>"></input>
<?php
	}
	echo "<br/>";
	// show the number of people in the list
	if ($gender == "A" && $omit == 0) {
		echo $search->numResults." ".$strOnFile."<br />\n";
	}
	if ($gender == "A" && $omit <> 0) {
		echo ($search->numResults + 1)." ".$strOnFile."<br />\n";
	}
}	// end of selectPeople()
?>