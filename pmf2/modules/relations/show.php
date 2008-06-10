<?php
include_once "modules/db/DAOFactory.php";

function insertChildrenLink($pid, $spid, $gender, $editable) {
		global $strInsert, $strChildren;		
		if ($gender == "M") {
			$mid = $spid;
			$fid = $pid;
		} else {
			$fid = $spid;
			$mid = $pid;
		}
		if ($editable) {
?>
<a href="edit.php?func=add&area=people&mid=<?php echo $mid;?>&fid=<?php echo $fid;?>"><?php echo $strInsert." ".$strChildren;?></a><?php 
		}
	}
	
	function show_relations_title() {
	}
	
function get_relations_create_string($per) {
	global $strInsert, $strNewMarriage;
	$ret = "";
	if ($per->isEditable()) {
		echo "<a href=\"edit.php?func=add&amp;person=".$per->person_id."&amp;area=marriage\">".
		$strInsert."</a> ".
		$strNewMarriage."</td>";
	} 
	return ($ret);
}

function get_relations_title() {
	global $strMarried;
	return $strMarried;
}

function show_relations($per) {
	global $strMarriage, $strRestricted, $strOn, $strAt, $strCertified, 
			$strEdit, $strDelete;
	$editable = $per->isEditable();
	?>
	<table width="100%">
		<tr>
			<th valign="top" width="5%"><?php echo get_relations_title(); ?>:</th>
			<td valign="top" width="80%" class="tbl_even">
<?php
	$search = new Relationship();
	$search->setFromRequest();
	$dao = getRelationsDAO();
	$dao->getRelationshipDetails($search);
?>
				<table width="100%" cellspacing="0">
<?php
		$count = 0;
		for($i=0; $i < $search->numResults; $i++) {
			$rel = $search->results[$i];
			if (!isset($rel->relation->person_id)) {
				continue;
			}
			$count++;
?>
					<tr>
						<td width="85%" class="tbl_even">
<?php
						if ($rel->isEditable()) {
				echo "<a href=\"edit.php?func=edit&amp;area=relations&amp;person=".$rel->person->person_id."&amp;event=".$rel->event->event_id."\">".$strEdit."</a>::<a href=\"JavaScript:confirm_delete('".$rel->relation->getDisplayName()."', '".strtolower($strMarriage)."', 'passthru.php?func=delete&amp;area=marriage&amp;person=".$rel->person->person_id."&amp;event=".$rel->event->event_id."')\" class=\"delete\">".$strDelete."</a>&nbsp;";
						}
						echo $rel->relation->getLink();
						if ($rel->isViewable()) {
							if ($rel->marriage_date != "0000-00-00") {
								echo " ".$strOn." ".$rel->dom;
							}
							echo $rel->marriage_place->getAtDisplayPlace();
						}
				echo "</td>\n";
?>
			
			<td valign="top" class="tbl_even" width="15%" align="right"><?php echo $strCertified; ?><input type="checkbox" name="marriagecert" disabled="disabled"<?php
						if ($rel->marriage_cert == "Y") {
							echo " checked=\"checked\"";
						}
?> /><br/><?php insertChildrenLink($rel->person->person_id, $rel->relation->person_id, $rel->person->gender, $rel->isEditable()); ?>
</td>
					</tr>
<?php
		}
?>
 				</table>
			</td>
			<td align="right" class="tbl_odd" valign="top"><?php
			echo get_relations_create_string($per);
			?>
		</tr>
	</table>
<?php
	return ($count);
}

?>
