<?php

class Tx_PtExtlist_Domain_DataBackend_Query_MySqlQueryBuilder implements Tx_PtExtlist_Domain_DataBackend_Query_QueryBuilderInterface {
	
	public function buildQuery(Tx_PtExtlist_Domain_Configuration_QueryConfiguration $configuration) {
		
		$config = $configuration->getConfiguration();
		
		$select = $this->extractSelect( $config['mapping'] );
		$from = $this->extractFrom( $config['from'] );
		
		$query = new Tx_PtExtlist_Domain_DataBackend_Query_SqlQuery($select, $from);
		
		$join = $this->extractJoin( $config['join'] );
		$query->setJoin($join);
		
		$where = $this->extractWhere( $config['where'] );
		$query->setWhere($where);
		
		return $query;
		
	}
	
	protected function extractSelect($mapping) {
		if(!is_array($mapping)) return '*';
		
		return implode(',', $mapping);
	}
	
	protected function extractFrom($from) {
		if(array_key_exists('_typoScriptNodeValue',$from)) return $from['_typoScriptNodeValue'];
		
		return implode(',',$from);
	}
	
	protected function extractJoin($join) {
		if(array_key_exists('_typoScriptNodeValue',$join)) return $join['_typoScriptNodeValue'];
		
		return '';
		
	}
	
	protected function extractWhere($where) {
		//TODO: parse the where array and create a structure which is usable in a prepared statement...
		
		print_r($where);
		return '';
	}
	
	
	
}

?>