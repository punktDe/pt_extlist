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
 * Testcase for aggregate configuration
 *
 * @package pt_extlist
 * @subpackage Tests\Configuration\Data\Aggregates
 * @author Daniel Lienert <linert@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Data_Aggregates_AggregateConfig_testcase extends Tx_Extbase_BaseTestcase {

	/**
	 * Holds a dummy configuration for a aggregate config object
	 * @var array
	 */
	protected $aggregateSettings = array();
	
	
	/**
	 * Holds an instance of field configuration object
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig
	 */
	protected $aggregateConfig = null; 
	
	
	
	public function setup() {
		$this->aggregateSettings = array(
		    'fieldIdentifier' => 'fieldName',
		    'method' => 'avg',
		);
		$this->aggregateConfig = new Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig('agg1', $this->aggregateSettings);
	}
	
	
	public function testGetFieldIdentifier() {
		$this->assertEquals(array('fieldName'), $this->aggregateConfig->getFieldIdentifier());
	}
	
	public function testGetIndetifier() {
		$this->assertEquals('agg1', $this->aggregateConfig->getIdentifier());
	}
	
	public function testGetMethod() {
		$this->assertEquals('avg', $this->aggregateConfig->getMethod());
	}
}
?>