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
 * Class implements factory the aggregate list builder
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\List\Aggregates
 */
class Tx_PtExtlist_Domain_Model_List_Aggregates_AggregateListFactory {
	
	/**
	 * Get defined aggregate rows as list data structure
	 * if no aggregate Rows are defined return an empty list structure
	 * 
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public static function getAggregateListData(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		
		$aggregateListBuilder = new Tx_PtExtlist_Domain_Model_List_Aggregates_AggregateListBuilder($configurationBuilder);
		$aggregateListBuilder->injectArrayAggregator(Tx_PtExtlist_Domain_Model_List_Aggregates_ArrayAggregatorFactory::createInstance($dataBackend));
		//$aggregateListBuilder->injectRenderer(Tx_PtExtlist_Domain_Renderer_RendererChainFactory::getRendererChain($configurationBuilder->buildRendererChainConfiguration()));
		$aggregateListBuilder->injectDataBackend($dataBackend);
		
		$aggregateListData = $aggregateListBuilder->buildAggregateListData();	

		return $aggregateListData;
	}
}
?>