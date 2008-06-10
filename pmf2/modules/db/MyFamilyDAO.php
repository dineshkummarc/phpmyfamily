<?php

define ('L_ALL', 0);
define ('L_HEADER', 1);

class MyFamilyDAO {
	
	//Note that mysql does not support nested transactions
	var $inTrans = 0;
	
	function startTrans() {
		$this->runQuery("start transaction","");
		$this->inTrans++;
	}
	
	function commitTrans() {
		$this->inTrans--;
		if ($this->inTrans == 0) {
			//Because no nested transactions avoid premature commit
			$this->runQuery("commit","");
		}
	}
	
	function rollbackTrans() {
		$this->runQuery("rollback","");
		$this->inTrans = 0;
	}
	
	function rowsChanged() {
		return(mysql_affected_rows());
	}
	
	function lockTable($table) {
	}
	
	function unlockTable($table) {
	}
	
	function runQuery($query, $msg) {

		if (!($result = mysql_query($query))) {
			error_log($query);
			error_log(mysql_error());
			if ($this->inTrans > 0) {
				$this->rollbackTrans();
			}
			die($msg);
		}
		//error_log($query);
		//echo $query."<br/>";
		return ($result);
	}
	
	
	function addPersonRestriction($op, $birth = 'b', $death = 'd') {
		$config = Config::getInstance();
		$restrictdate = $config->restrictdate;
		
		$ret = "";
		if ($_SESSION["id"] == 0) {
			$ret = $op." ".$birth.".date1 < '".$restrictdate."'";
		}
		return ($ret);
	}
	
	function addRandom() {
		return(" ORDER BY RAND() ");
	}
	
	function addLimit($search, &$query) {
		if (isset($search->count)) {
			$query .= " LIMIT ";
			if (isset($search->start)) {
				$query .= $search->start.",";
			}
			$query .= $search->count;
		}
	}
	
	function getNextRow($result) {
		$row = mysql_fetch_array($result);
		return ($row);
	}
	
	function freeResultSet($result) {
		 mysql_free_result($result);
	}
	
	function getInsertId() {
		return (mysql_insert_id());
	}
	
}
?>
