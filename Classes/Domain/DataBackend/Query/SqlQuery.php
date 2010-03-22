<?php

class Tx_PtExtlist_Domain_DataBackend_Query_SqlQuery implements Tx_PtExtlist_Domain_DataBackend_Query_QueryInterface {
	
	protected $select;
	protected $from;
	
	/**
	 * 
	 * @var Array.
	 */
	protected $where;
	protected $join;
	
	
	public function __construct($select,$from) {
		$this->select = $select;
		$this->from = $from;
	}
	
	public function setWhere($where) {
		$this->where = $where;
	}
	
	public function setJoin($join) {
		$this->join = $join;
	}
	
	public function getSelect() {
		return $this->select;
	}
	
	public function getFrom() {
		return $this->from;
	}
	
	public function getWhere() {
		return $this->where;
	}
	
	public function getJoin() {
		return $this->join;
	}
	
}

?>