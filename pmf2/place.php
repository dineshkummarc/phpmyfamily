<?php

class Place {
	var $placeName;
	var $lat;
	var $lng;
	var $text;
	var $birth = false;
	var $marriage = false;
	var $census = false;

function lookupAddress($address) {
    global $tblprefix;

    $config = Config::getInstance();
// Initialize delay in geocode speed
$delay = 0;
$found = false;
$base_url = "http://" . $config->gmapshost . "/maps/geo?output=csv&key=" . $config->gmapskey;

// Iterate through the rows, geocoding each address

  $geocode_pending = true;

  while ($geocode_pending) {
    $request_url = $base_url . "&q=" . urlencode($address);
    $csv = file_get_contents($request_url) or die("url not loading");
    $csvSplit = split(",", $csv);
    $status = $csvSplit[0];
    $lat = $csvSplit[2];
    $lng = $csvSplit[3];
    if (strcmp($status, "200") == 0) {
      // successful geocode
      $geocode_pending = false;
	$found = true;
      $lat = $csvSplit[2];
      $lng = $csvSplit[3];

      $this->lat = $lat;
      $this->lng = $lng;

      $query = sprintf("INSERT INTO ".$tblprefix."markers " .
             " (address, lat, lng) VALUES ('%s','%s','%s') ;",
             mysql_real_escape_string($address),
             mysql_real_escape_string($lat),
             mysql_real_escape_string($lng));
      $update_result = mysql_query($query);
      if (!$update_result) {
        die("Invalid query: " . mysql_error());
      }
    } else if (strcmp($status, "620") == 0) {
      // sent geocodes too fast
      $delay += 100000;
    } else {
      // failure to geocode
      $geocode_pending = false;

    }
    usleep($delay);
  }
  return($found);
}

}