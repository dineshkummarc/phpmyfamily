<?php
include_once "classes/Transcript.php";

class TranscriptDAO extends MyFamilyDAO {
	
	function updateTranscript($trans) {
		global $tblprefix, $err_transcript;
		$iquery = "UPDATE ".$tblprefix."documents SET doc_title=".quote_smart($trans->title).
			", doc_date = '".$trans->date."', doc_description = ".quote_smart($trans->description).
			", file_name = ".quote_smart($trans->file_name).
			" WHERE id = ".quote_smart($trans->transcript_id);
		$iresult = $this->runQuery($iquery, $err_transcript);
		return ($iresult);
	}
	
	function createTranscript(&$trans) {
		global $tblprefix, $err_transcript;
		$iquery = "INSERT INTO ".$tblprefix."documents (person_id, doc_date, doc_title, doc_description, file_name) VALUES ".
				"('".$trans->person->person_id."', '".$trans->date."', ".quote_smart($trans->title).", ".
				quote_smart($trans->description).", ".quote_smart($trans->file_name).")";
		$iresult = $this->runQuery($iquery, $err_transcript);
		$trans->transcript_id = str_pad($this->getInsertId(), 5, 0, STR_PAD_LEFT);
		return ($iresult);
	}
		
	function getTranscripts(&$trans) {
		global $tblprefix, $err_trans, $currentRequest;
		$res = array();
		$squery = "SELECT doc.person_id, id, doc_date, doc_title, doc_description, file_name, ".
			"DATE_FORMAT(doc_date, ".$currentRequest->datefmt.") AS fdate,".
			PersonDetail::getFields().
			" FROM ".$tblprefix."documents doc".
			" LEFT JOIN ".$tblprefix."people p ON p.person_id = doc.person_id ".
			PersonDetail::getJoins();
		
		if (isset($trans->transcript_id)) {
			$squery .= "WHERE id = ".quote_smart($trans->transcript_id);
		} else {
			$squery .= "WHERE p.person_id = ".quote_smart($trans->person->person_id);
		}
		$result = $this->runQuery($squery, $err_trans);

		$trans->numResults = 0;
		while ($row = $this->getNextRow($result)) {
			$t = new Transcript();
			$t->person = new PersonDetail();
			$t->person->loadFields($row, L_HEADER, "p_");
			$t->person->name->loadFields($row, "n_");
			$t->person->person_id = $row["person_id"];
			$t->transcript_id = $row["id"];
			$t->date = $row["doc_date"];
			$t->fdate = $row["fdate"];
			$t->title = $row["doc_title"];
			$t->description = $row["doc_description"];
			$t->file_name = $row["file_name"];
			$trans->numResults++;
			$res[] = $t;
		}
		$this->freeResultSet($result);
		$trans->results = $res;
	}
	
	function deleteTranscript($trans) {
		global $tblprefix, $err_trans_delete, $err_trans_file;
		$dresult = false;
		if (@unlink($trans->getFileName()) || !file_exists($trans->getFileName())) {
			$dquery = "DELETE FROM ".$tblprefix."documents WHERE id = '".$trans->transcript_id."'";
			$dresult = $this->runQuery($dquery, $err_trans_delete);
		} else {
			die($err_trans_file);
		}
		return ($dresult);
	}
	
}
?>
