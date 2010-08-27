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

/**
 * Testcase for aggregate collection
 *
 * @package pt_extlist
 * @subpackage Tests\Configuration\Data\Aggregates
 * @author Daniel Lienert <linert@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Data_Aggregates_AggregateConfigCollection_testcase extends Tx_Extbase_BaseTestcase {

	/**
	 * Holds a dummy configuration for a aggregate config collection object
	 * @var array
	 */
	protected $aggregateSettings = array();
	
	
	
	public function setup() {
		$this->aggregateSettings = array(
		    'agg1' => array( 
		    	'fieldIdentifier' => 'fieldName1',
		    	'method' => 'avg',
		    ),
		    'agg2' => array( 
		   		'fieldIdentifier' => 'fieldName2',
		   		'method' => 'max',
            )
		);
	}
	
	
	
	public function testSetup() {
		$aggregateConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection();
	}
	
	
	
	public function testExceptionOnNonCorrectItemAdded() {
		$aggregateConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection();
		try {
		    $aggregateConfigCollection->addAggregateConfig('test');
		} catch(Exception $e) {
			return;
		}
		$this->fail();
	}
	
	
	
	public function testExceptionOnGettingNonAddedItem() {
		$aggregateConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection();
        try {
            $aggregateConfigCollection->getAggregateConfigByIdentifier('test');
        } catch(Exception $e) {
            return;
        }
        $this->fail();
	}
	
	
	
	public function testAddGetCorrectItems() {
		$aggregateConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection();
		$aggregateConfigCollection->addAggregateConfig(new Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig('agg1', $this->aggregateSettings['agg1']));
		$aggregateConfigCollection->addAggregateConfig(new Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig('agg2', $this->aggregateSettings['agg2']));
		$aggregateConfig1 = $aggregateConfigCollection->getAggregateConfigByIdentifier('agg1');
		$this->assertEquals($aggregateConfig1->getIdentifier(), 'agg1');
		$aggregateConfig2 = $aggregateConfigCollection->getAggregateConfigByIdentifier('agg2');
		$this->assertEquals($aggregateConfig2->getIdentifier(), 'agg2');
	}
	
}
?>