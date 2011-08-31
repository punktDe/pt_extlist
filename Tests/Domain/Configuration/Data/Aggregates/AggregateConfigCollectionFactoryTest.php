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
 * Testcase for aggregate config collection factory
 *
 * @package Tests
 * @subpackage Configuration\Data\Aggregates
 * @author Daniel Lienert <linert@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Data_Aggregates_AggregateConfigCollectionFactory_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * Holds a dummy configuration for a aggregate config collection object
	 * @var array
	 */
	protected $aggregateSettings = array();
	
	
	
	public function setup() {
		$this->aggregateSettings = array(
		    'agg1' => array( 
		    	'fieldIdentifier' => 'field1',
		    	'method' => 'avg',
		    ),
		    'agg2' => array( 
		   		'fieldIdentifier' => 'field1',
		   		'method' => 'max',
            )
		);
		
		$settingsTree['listConfig']['test']['aggregateData'] = $this->aggregateSettings;
		$this->initDefaultConfigurationBuilderMock($settingsTree);
	}
	
	
	
	public function testGetAggregateConfigCollection() {
		$aggregateConfigCollection = Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollectionFactory::getInstance($this->configurationBuilderMock);
		$this->assertTrue(is_a($aggregateConfigCollection, 'Tx_PtExtbase_Collection_ObjectCollection'));
		$aggregateConfig1 = $aggregateConfigCollection->getAggregateConfigByIdentifier('agg1');
		$this->assertEquals($aggregateConfig1->getMethod(), 'avg');
	}
			
}

?>