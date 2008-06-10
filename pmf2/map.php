<?php
	include_once "modules/db/DAOFactory.php";
	include_once "modules/location/show.php";
	$config = Config::getInstance();
	$ldao = getLocationDAO(); 
	$p = new Locations();
	$ldao->getPlaces($p);
	$places = $p->places;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title><?php echo $config->desc;?></title>
<?php    echo get_location_header($places);?>
  </head>

  <body onload="initialize()" onunload="GUnload()">
 <?php
     echo show_location($p);
  ?>  
  </body>
</html>


