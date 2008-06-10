<?php
include_once("modules/event/show.php");
include_once("modules/location/show.php");

function setup_edit() {
	$l = new Location();
	$l->setFromRequest();
	$dao = getLocationDAO();
	$dao->getLocations($l, Q_MATCH);
	if ($l->numResults > 0) {
		$ret = $l->results[0];
	} else {
		$ret = $l;
	}
	return ($ret);
}

function get_edit_title($l) {
	global $strEditing;
	if ($l->location_id > 0) {
		return($strEditing.": ".$l->place);
	} else {
		return ("");
	}
}

function get_edit_header($l) {
	return (get_edit_title($l));
}

function get_edit_form($l) {
	global $strName, $strPlace, $strLatitude, $strLongitude, $strCentre, $strSubmit, $strReset;
	
?>
<!--Fill out form -->
<form method="post" action="passthru.php?area=location">
<input type="hidden" name="location_id" value="<?php echo $l->location_id; ?>" />

	<table>
		<tr>
		<td><?php echo $strName;?></td>
		<td><input type="text" name="name" value="<?php echo $l->name; ?>" size="30" maxlength="60" /></td>
		<td></td>
		</tr>
		<tr>
		<td><?php echo $strPlace;?></td>
		<td><input type="text" name="place" value="<?php echo $l->place; ?>" size="30" maxlength="80" /></td>
		<td></td>
		</tr>
		<tr>
		<td><?php echo $strLatitude;?></td>
		<td><input type="text" name="lat" value="<?php echo $l->lat; ?>" size="15" maxlength="11" /></td>
		<td></td>
		</tr>
		<tr>
		<td><?php echo $strLongitude;?></td>
		<td><input type="text" name="lng" value="<?php echo $l->lng; ?>" size="15" maxlength="11" /></td>
		<td></td>
		</tr>
		<tr>
		<td><?php echo $strCentre;?></td>
		<td><input type="checkbox" name="centre" <?php if ($l->centre > 0) { echo 'checked="checked"';}?> value="1" /></td>
		<td></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="<?php echo $strSubmit; ?>" /></td>
			<td colspan="2" class="tbl_even"><input type="reset" name="Reset1" value="<?php echo $strReset; ?>" /></td>
		</tr>
	</table>
	<?php
	if ($l->location_id > 0) {
		$p = new Locations();
		$p->location_id = $l->location_id;
		$ldao = getLocationDAO();
		$ldao->getPlaces($p);
		foreach ($p->places as $loc) {
			echo $loc->text;
		}
		foreach ($p->notFound as $loc) {
			echo $loc->text;
		}
	}
	?>
</form>
<?php
}
?>
