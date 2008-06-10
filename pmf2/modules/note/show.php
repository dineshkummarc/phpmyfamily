<?php

function get_note_title() {
	global $strNotes;
	return $strNotes;
}

function show_note_title() {
	global $strNotes;
	?>
	<table width="100%">
		<tr>
			<td width="95%"><h4><?php echo $strNotes; ?></h4></td>
			<td width="5%"></td>
		</tr>
	</table>
	<?php
}

function get_note_create_string() {
	return ("");
}

function show_note($per) {
	global $restrictmsg;
	$ret = 0;
	if (!$per->isViewable()) {
		echo $restrictmsg."\n";
	} else {
		echo $per->narrative."\n";
		if (strlen($per->narrative) > 0) {
			$ret = 1;
		}
	}
	return ($ret);
}
?>
