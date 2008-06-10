<?php

include_once "classes/Image.php";

class ImageDAO extends MyFamilyDAO {
	
	function updateImage($img) {
		global $tblprefix, $err_image_insert;
		
		$iquery = "UPDATE ".$tblprefix."images SET title=".quote_smart($img->title).
			", date = ".quote_smart($img->date).
			", description = ".quote_smart($img->description).
			"WHERE image_id = ".quote_smart($img->image_id);
		$iresult = $this->runQuery($iquery, $err_image_insert);
		return ($iresult);
	}
	
	function createImage(&$img) {
		global $tblprefix, $err_image_insert;
		$iquery = "INSERT INTO ".$tblprefix."images (person_id, title, date, description) VALUES ".
			"('".$img->person->person_id."', ".quote_smart($img->title).", '".$img->date."', ".quote_smart($img->description).")";;
		$iresult = $this->runQuery($iquery, $err_image_insert);
		$img->image_id = str_pad($this->getInsertId(), 5, 0, STR_PAD_LEFT);
	}
	
	function deleteImage($img) {
		global $tblprefix, $err_image_delete, $err_image_file;
		$dresult = false;
		
		$imgFile = $img->getImageFile();		
		$tnFile = $img->getThumbnailFile();

		if ((@unlink($tnFile) && @unlink($imgFile)) || 
			!file_exists($tnFile) || 
			!file_exists($imgFile)) {
			$dquery = "DELETE FROM ".$tblprefix."images WHERE image_id = ".quote_smart($img->image_id);
			$dresult = $this->runQuery($dquery, $err_image_delete);
		} else {
			die ($err_image_file);
		}
		return ($dresult);
	}
	
	
	function getImages(&$img) {
		global $tblprefix, $err_images;
		
		$iquery = "SELECT image_id, i.title, description, date, p.person_id as p_person_id, ".PersonDetail::getFields().
			" FROM ".$tblprefix."images i ".
			" LEFT JOIN ".$tblprefix."people p ON p.person_id = i.person_id ".
			PersonDetail::getJoins();
			
			switch ($img->queryType) {
			case Q_RANDOM:
				$iquery .= $this->addPersonRestriction(" WHERE ").
					$this->addRandom();
				
				break;
			default:
				if (isset($img->person->person_id)) {
					$iquery .= " WHERE ";
					$iquery .= "p.person_id = ".quote_smart($img->person->person_id);
					$iquery .= $this->addPersonRestriction(" AND ");
					if (isset($img->image_id)) {
						$iquery .= " AND image_id=".$img->image_id;
					}
					$iquery .= " ORDER BY date";
				} else {
					$bool = " WHERE ";
					if (isset($img->image_id)) {
						$iquery .= " WHERE image_id=".$img->image_id;
						$bool = " AND ";
					}
					$iquery .= $this->addPersonRestriction($bool).
					" ORDER BY b.date1";
				}
				break;
			}
		$this->addLimit($img, $query);
		$iresult = $this->runQuery($iquery, $err_images);
		$res = array();
		
		$img->numResults = 0;
		while($row = $this->getNextRow($iresult)) {
			$image = new Image();
			$image->person = new PersonDetail();
			$image->person->loadFields($row, L_HEADER, "p_");
			$image->person->name->loadFields($row, "n_");
			$image->image_id = $row["image_id"];
			$image->title = $row["title"];
			$image->description = $row["description"];
			$image->date = $row["date"];
			$res[] = $image;
			$img->numResults++;
		}
		$this->freeResultSet($iresult);
		
		$img->results = $res;
		
	}
}
?>
