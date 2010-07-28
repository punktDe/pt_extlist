<?php


class Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory {
	
	public static function createInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {

		$columnConfigurationCollection = $configurationBuilder->buildColumnsConfiguration();
		$listHeader = new Tx_PtExtlist_Domain_Model_List_Header_ListHeader();
		
		foreach($columnConfigurationCollection as $columnIdentifier => $singleColumnConfiguration) {
			$headerColumn = Tx_PtExtlist_Domain_Model_List_Header_HeaderColumnFactory::createInstance($singleColumnConfiguration);
			$listHeader->addHeaderColumn($headerColumn, $columnIdentifier);
		}
		
		return $listHeader;
	}
}
?>