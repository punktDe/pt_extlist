<?php

class Tx_PtExtlist_Domain_Configuration_QueryConfiguration implements Tx_PtExtlist_Domain_Configuration_Query_ValidQueryInterface {
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Query_Select
	 */
	protected $select;
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Query_From
	 */
	protected $from;
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Query_Join
	 */
	protected $join;
	
//	protected $where;
//	protected $groupBy;
//	protected $limit;
//	protected $orderBy;
	
	/**
	 * Construct the query configuration.
	 * @param Tx_PtExtlist_Domain_Configuration_Query_Select $select
	 * @param Tx_PtExtlist_Domain_Configuration_Query_From $from
	 * @return void
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_Query_Select $select, Tx_PtExtlist_Domain_Configuration_Query_From $from) {
		$this->select = $select;
		$this->from = $from;
	}
	
	/**
	 * Returns the query select configuration part.
	 * @return Tx_PtExtlist_Domain_Configuration_Query_Select
	 */
	public function getSelect() {
		return $this->select;
	}
	
	/**
	 * Returns the query from configuration part.
	 * @return Tx_PtExtlist_Domain_Configuration_Query_From
	 */
	public function getFrom() {
		return $this->from;
	}
	
	/**
	 * Sets the query join configuration part.
	 * @param Tx_PtExtlist_Domain_Configuration_Query_Join $join
	 * @return void
	 */
	public function setJoin(Tx_PtExtlist_Domain_Configuration_Query_Join $join) {
		$this->join = $join;
	}
	
	/**
	 * Returns the query join configuration part.
	 * @return Tx_PtExtlist_Domain_Configuration_Query_Join
	 */
	public function getJoin() {
		return $this->join;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Query/Tx_PtExtlist_Domain_Configuration_Query_ValidQueryInterface#isValid()
	 */
	public function isValid() {
		
		// Mandatory parts
		if(! $this->select->isValid() ) return false;
		if(! $this->from->isValid() ) return false;
		
		// Non mandatory parts
		if($this->join != null && ! $this->join->isValid()) return false;
		
		return true;
	}
}

?>