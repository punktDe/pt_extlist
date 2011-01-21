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
 * Class implementing tests for aggregate column config
 *
 * @package Tests
 * @subpackage Domain\Configuration\Aggregates
 * @author Daniel Lienert <linert@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Aggregates_AggregateColumnConfig_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * Holds a dummy configuration for a aggregate column config object
	 * @var array
	 */
	protected $aggregateColumnSettings = array();
	
	
	/**
	 * Holds an instance of aggregate column configuration object
	 * @var Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateColumnConfig
	 */
	protected $aggregateColumnConfig = null; 
	

	public function setup() {
		
		$this->initDefaultConfigurationBuilderMock();
		$aggregateRowSettings = $this->configurationBuilderMock->getSettingsForConfigObject('aggregateRows');
		$columnIdentifier = key(current($aggregateRowSettings));
		$this->aggregateColumnSettings = current(current($aggregateRowSettings));
		$this->aggregateColumnConfig = new Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateColumnConfig($this->configurationBuilderMock, $this->aggregateColumnSettings, $columnIdentifier);
	}
	
	
	
	public function testGetAggregateDataIdentifier() {
		$this->assertEquals($this->aggregateColumnConfig->getAggregateDataIdentifier(), array($this->aggregateColumnSettings['aggregateDataIdentifier']));
	}
	
	
	
	public function testGetColumnIdentifier() {
		$this->assertEquals($this->aggregateColumnConfig->getColumnIdentifier(), 'column2');
	}
	
	
	public function testGetRenderTemplate() {
		$this->markTestIncomplete();
	}
	
}
?>