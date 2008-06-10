<?php

function setup_edit() {
	$trans = new Transcript();
	$trans->setFromRequest();
	if (!isset($trans->transcript_id)) {
		$trans->person->queryType = Q_IND;
		$dao = getPeopleDAO();
		$dao->getPersonDetails($trans->person);
		$trans->person = $trans->person->results[0];
		$ret = $trans;
	} else {
		$dao = getTranscriptDAO();
		$dao->getTranscripts($trans);
		$ret = $trans->results[0];
	}
	return($ret);
}

function get_edit_title($trans) {
	global $strNewTrans;
	return ucwords($strNewTrans).": ".$trans->person->getDisplayName();
}
function get_edit_header($trans) {
	return (get_edit_title($trans));
}

function get_edit_form($trans) {
	global $strFUpload, $strFTitle, $strFDesc, $strFDate, $strSubmit, $strDateFmt;
?>
<!--Fill out the form-->
<form enctype="multipart/form-data" method="post" action="passthru.php?func=insert&amp;area=transcript">
<input type="hidden" name="person" value="<?php echo $trans->person->person_id;?>" />
<?php if (isset($trans->transcript_id)) { ?>
<input type="hidden" name="transcript_id" value="<?php echo $trans->transcript_id;?>" />
<?php } ?>
	<table>
	<?php if (!isset($trans->transcript_id)) { 
		$tdate = "0000-00-00";
		?>
		<tr>
			<td class="tbl_odd"><?php echo $strFUpload; ?></td>
			<td class="tbl_even"><input type="file" name="userfile" /></td>
		</tr>
	<?php } else {
		$tdate = $trans->date;
	}?>
		<tr>
			<td class="tbl_odd"><?php echo $strFTitle; ?></td>
			<td class="tbl_even"><input type="text" name="frmTitle" size="30" maxlength="30" value="<?php echo $trans->title;?>"/></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strFDesc; ?></td>
			<td class="tbl_even"><input type="text" name="frmDesc" size="60" maxlength="60" value="<?php echo $trans->description;?>"/></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strFDate; ?></td>
			<td class="tbl_even"><input type="text" name="frmDate" maxlength="10" value="<?php echo $tdate;?>" /></td>
			<td><?php echo $strDateFmt; ?></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="<?php echo $strSubmit; ?>" /></td>
			<td class="tbl_even"></td>
		</tr>
	</table>
</form>
<?php
}
?>
