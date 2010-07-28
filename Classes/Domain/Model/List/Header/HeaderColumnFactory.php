<?php
class Tx_PtExtlist_Domain_Model_List_Header_HeaderColumnFactory {
	
	public static function createInstance($headerConfiguration) {
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		return $headerColumn;
	}

}
?>