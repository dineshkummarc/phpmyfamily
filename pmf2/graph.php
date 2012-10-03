<?php

include_once "modules/db/DAOFactory.php";

include_once "modules/graph/PmfGraphViz.php";

$g = new PmfGraphViz();

$g->addRelationships();
$g->addPeople();

$g->display('svg');

?>