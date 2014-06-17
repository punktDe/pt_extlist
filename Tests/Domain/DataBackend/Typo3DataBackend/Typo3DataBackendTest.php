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
 * Testcase for pt_list typo3 data backend object. 
 * 
 * @author Michael Knoll 
 * @author Daniel Lienert
 * @package Test
 * @subpackage Domain\Databackend
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_Typo3DataBackend_Typo3DataBackendTest extends Tx_PtExtlist_Tests_Domain_DataBackend_AbstractDataBackendBaseTest {


	public function testSetUp() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend'));
	}
	
	

	/** @test */
	public function createDataSourceCreatesObjectOfExpectedType() {
		$dataSource = Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend::createDataSource($this->configurationBuilder);
		$this->assertTrue($dataSource instanceof Tx_PtExtlist_Domain_DataBackend_DataSource_Typo3DataSource, 'Class is ' . get_class($dataSource));
	}


	
	/** @test */
	public function buildWherePartWithEnableFields() {
			
		$backendMock = $this->getAccessibleMock('Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend', array('getWhereClauseFromFilterboxes','getTypo3SpecialFieldsWhereClause'), array($this->configurationBuilder));

		$backendMock->_set('backendConfiguration', $this->configurationBuilder->buildDataBackendConfiguration());
		
		$backendMock->expects($this->once())
		    ->method('getWhereClauseFromFilterboxes')
		    ->will($this->returnValue(''));
		$backendMock->expects($this->once())
		    ->method('getTypo3SpecialFieldsWhereClause');
		    
		$wherePart = $backendMock->buildWherePart();
	}
	
}
?>