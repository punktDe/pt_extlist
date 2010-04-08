<?php

/**
 * This class implements a dummy data backend for generating
 * some output for testing and development.
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_DataBackend_DummyDataBackend_DummyDataBackend extends Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend {
	
	/**
	 * Generates dummy list data
	 *
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getListData() {
		$rowData = array(
		    'a' => 'aa',
		    'b' => 'bb',
		    'c' => 'cc',
		    'd' => 'dd', 
		    'e' => 'ee'
		);
		
		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		
		for ($i = 0; $i < 10; $i++) {
			$row = new Tx_PtExtlist_Domain_Model_List_Row();
			foreach($rowData as $fieldName => $fieldValue) {
				$row->addCell($fieldName, $fieldValue . $i);
			}
			$listData->addRow($row);
		}
		
		return $listData;
	}
	
}

?>