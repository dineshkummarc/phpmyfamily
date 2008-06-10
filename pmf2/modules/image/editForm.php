<?php

function setup_edit() {
	$img = new Image();
	$img->image_id = -1;
	$img->setFromRequest();
	$dao = getImageDAO();
	$dao->getImages($img);
	if($img->numResults == 0) {
		$img->person->queryType = Q_IND;
		$dao = getPeopleDAO();
		$dao->getPersonDetails($img->person);
		$img->person = $img->person->results[0];
		$ret = $img;
	} else {
		$ret = $img->results[0];
	}
	
	return($ret);
}

function get_edit_title($img) {
	global $strNewImage;
	return ucwords($strNewImage).": ".$img->person->getDisplayName();
}
function get_edit_header($img) {
	return (get_edit_title($img));
}

function get_edit_form($img) {
	global $strIUpload, $strITitle, $strIDesc, $strIDate, $strISize, $strSubmit, $strDateFmt;
?>
<!--Fill out the form-->
<form enctype="multipart/form-data" method="post" action="passthru.php?func=insert&amp;area=image">
<input type="hidden" name="person" value="<?php echo $img->person->person_id;?>" />
<?php if (isset($img->image_id) && $img->image_id > 0) { 
	$existing = true;
} else {
	$existing = false;
}
if ($existing) {?>
<input type="hidden" name="image_id" value="<?php echo $img->image_id;?>" />
<?php } ?>
<input type="hidden" name="MAX_FILE_SIZE" value="1048576">
	<table>
	<?php if (!$existing) {
		$idate = "0000-00-00";
		?>
		<tr>
			<td class="tbl_odd"><?php echo $strIUpload; ?></td>
			<td class="tbl_even"><input type="file" name="userfile" /></td>
			<td><?php echo $strISize; ?></td>
		</tr>
	<?php } else {
		$idate = $img->date;
	}?>
		<tr>
			<td class="tbl_odd"><?php echo $strITitle; ?></td>
			<td class="tbl_even"><input type="text" name="frmTitle" size="30" maxlength="30" value="<?php echo $img->title;?>" /></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strIDesc; ?></td>
			<td class="tbl_even"><input type="text" name="frmDesc" size="60" maxlength="255" value="<?php echo $img->description;?>"/></td>
		</tr>
		<tr>
			<td class="tbl_odd"><?php echo $strIDate; ?></td>
			<td class="tbl_even"><input type="text" name="frmDate" maxlength="10" value="<?php echo $idate;?>" /></td>
			<td><?php echo $strDateFmt; ?></td>
		</tr>
		<tr>
			<td class="tbl_even"><input type="submit" name="Submit1" value="<?php echo $strSubmit; ?>" /></td>
			<td class="tbl_even"></td>
		<tr>
	</table>
</form>
<?php
}
?>
