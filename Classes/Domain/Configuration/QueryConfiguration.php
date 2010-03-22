<?php

class Tx_PtExtlist_Domain_Configuration_QueryConfiguration implements Tx_PtExtlist_Domain_Configuration_Query_ValidQueryInterface {
		
	protected $select;
	protected $from;
//	protected $join;
//	protected $where;
//	protected $groupBy;
//	protected $limit;
//	protected $orderBy;
	
	public function __construct($select, $from) {
		$this->select = $select;
		$this->from = $from;
	}
	
	public function getSelect() {
		return $this->select;
	}
	
	public function getFrom() {
		return $this->from;
	}
	
	public function isValid() {
		if(! $this->select->isValid ) return false;
		if(! $this->from->isValid ) return false;
	}
}

?>