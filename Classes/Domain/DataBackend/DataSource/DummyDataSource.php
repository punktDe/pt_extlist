<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
 *  All rights reserved
 *
 *  For further information: http://extlist.punkt.de <extlist@punkt.de>
 *
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class implements a dummy data source that is mainly used for testing
 * 
 * @package Domain
 * @subpackage DataBackend\DataSource
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_DataBackend_DataSource_DummyDataSource extends Tx_PtExtlist_Domain_DataBackend_DataSource_AbstractDataSource{ 

	/**
	 * Some dummy data to be returned
	 *
	 * @var array
	 */
	private  $dummyArray = array(
		    array('t1.f1' => 'v1_1', 't1.f2' => 'v1_2', 't1.f3' => 'v1_3','t2.f1' => 'v1_4', 't2.f2' => 'v1_5'),
		    array('t1.f1' => 'v2_1', 't1.f2' => 'v2_2', 't1.f3' => 'v2_3','t2.f1' => 'v2_4', 't2.f2' => 'v2_5'),
		    array('t1.f1' => 'v3_1', 't1.f2' => 'v3_2', 't1.f3' => 'v3_3','t2.f1' => 'v3_4', 't2.f2' => 'v3_5'),
		    array('t1.f1' => 'v4_1', 't1.f2' => 'v4_2', 't1.f3' => 'v4_3','t2.f1' => 'v4_4', 't2.f2' => 'v4_5'),
		    array('t1.f1' => 'v5_1', 't1.f2' => 'v5_2', 't1.f3' => 'v5_3','t2.f1' => 'v5_4', 't2.f2' => 'v5_5'),
		    array('t1.f1' => 'v6_1', 't1.f2' => 'v6_2', 't1.f3' => 'v6_3','t2.f1' => 'v6_4', 't2.f2' => 'v6_5'),
		    array('t1.f1' => 'v7_1', 't1.f2' => 'v7_2', 't1.f3' => 'v7_3','t2.f1' => 'v7_4', 't2.f2' => 'v7_5'),
		    array('t1.f1' => 'v8_1', 't1.f2' => 'v8_2', 't1.f3' => 'v8_3','t2.f1' => 'v8_4', 't2.f2' => 'v8_5'),
		);
	
		
		
	/**
	 * Returns some dummy data on an executed query
	 *
	 * @param Tx_PtExtlist_Domain_Query_QueryInterface $query
	 * @return array
	 */
	public function execute(Tx_PtExtlist_Domain_Query_QueryInterface $query = null) {
		
		return $this->dummyArray;
	}
	
	
	/**
	 * TODO remove function!
	 *
	 * @return unknown
	 */
	public function countItems() {
		return count($this->dummyArray);
	}
	
	
	
	/**
	 * TODO remove function!
	 * 
	 * Returns some dummy data, requested between a range.
	 * @param integer $start The start index of the requested data.
	 * @param integer $end The end index of the requested data.
	 */
	public function executeWithLimit($start=1,$end=-1) {
		
		
		if($end ==-1) {
			$end = $this->countItems(); 
		}
		
		$retArray = array();
		for($i = $start; $i<=$end; $i++) {
			$retArray[] = $this->dummyArray[$i-1];
		}
		
		return $retArray;
	}
	
}

?>