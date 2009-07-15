<?php

class AdminDAO extends MyFamilyDAO {
	function getConfig(&$c) {
		global $tblprefix;
		$query = "SELECT ";
		$query .= "email,mailto,`desc`,styledir,imagedir,filedir,defaultstyle,lang,timing,gedcom,restricttype,".
			"restrictyears,restrictdate,tracking,trackemail,absurl,bbtracking,".
			"layout, gmapskey, gmapshost, img_max,img_min";
		$query .= " FROM ".$tblprefix."config ";
		//TODO - add a proper error message
		$result = $this->runQuery($query, "Error retrieving config");
		
		while($row = $this->getNextRow($result)) {
			$c->loadFields($row);
		}
	}
	
	function updateConfig($c) {
		global $tblprefix;
		
		$q = "UPDATE ".$tblprefix."config SET `email` = ".quote_smart($c->email).",".
		"`mailto` = ".($c->mailto?1:0).",".
			"`desc` = ".quote_smart($c->desc).",".
			"`styledir` = ".quote_smart($c->styledir).",".
			"`imagedir` = ".quote_smart($c->imagedir).",".
			"`filedir` = ".quote_smart($c->filedir).",".
			"`defaultstyle` = ".quote_smart($c->defaultstyle).",".
			"`lang` = ".quote_smart($c->lang).",".
			"`timing` = ".($c->timing?1:0).",".
			"`gedcom` = ".($c->gedcom?1:0).",".
			"`restricttype` = ".$c->restricttype.",".
			"`restrictyears` = ".$c->restrictyears.",".
			"`restrictdate` = ".quote_smart($c->restrictdate).",".
			"`tracking` = ".($c->tracking?1:0).",".
			"`trackemail` = ".quote_smart($c->trackemail).",".
			"`absurl` = ".quote_smart($c->absurl).",".
			"`bbtracking` = ".($c->bbtracking?1:0).",".
			"`img_max` = ".$c->img_max.",".
			"`img_min` = ".$c->img_min.",".
			"`layout` = ".$c->layout.",".
			"`gmapshost` = ".quote_smart($c->gmapshost).",".
			"`gmapskey` = ".quote_smart($c->gmapskey);
			//TODO - add a proper error message
		$ret = $this->runQuery($q, "Error updating config");
		
		return ($ret);
	}
}
?>