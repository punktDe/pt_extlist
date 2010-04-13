<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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



// TODO think about useful interface for dataSource
// Query is not a useful interface, as it must be translated to 
// a proprietary query command depending on data source!
// ry21: I think there is no useful interface as 
// each backend has to know how to access its datasource


/**
 * Class implements a dummy data source that is mainly used for testing
 * 
 * @package Typo3
 * @subpackage pt_exlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_DataBackend_DataSource_DummyDataSource { 

	/**
	 * Returns some dummy data on an executed query
	 *
	 * @param Tx_PtExtlist_Domain_Query_QueryInterface $query
	 * @return array
	 */
	public function execute(Tx_PtExtlist_Domain_Query_QueryInterface $query = null) {
		$dummyArray = array(
		    array('t1.f1' => 'v1_1', 't1.f2' => 'v1_2', 't1.f3' => 'v1_3','t2.f1' => 'v1_4', 't2.f2' => 'v1_5'),
		    array('t1.f1' => 'v2_1', 't1.f2' => 'v2_2', 't1.f3' => 'v2_3','t2.f1' => 'v2_4', 't2.f2' => 'v2_5'),
		    array('t1.f1' => 'v3_1', 't1.f2' => 'v3_2', 't1.f3' => 'v3_3','t2.f1' => 'v3_4', 't2.f2' => 'v3_5'),
		    array('t1.f1' => 'v4_1', 't1.f2' => 'v4_2', 't1.f3' => 'v4_3','t2.f1' => 'v4_4', 't2.f2' => 'v4_5')
		);
		
		return $dummyArray;
	}
	
}

?>