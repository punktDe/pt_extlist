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
 * Testcase for flexform dataprovider
 *
 * @package Tests
 * @subpackage Utility
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Utility_FlexformDataProvider_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	 
	public function setUp() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
	
	public function testSetup() {
		$flexformDataProvider = new user_Tx_PtExtlist_Utility_FlexformDataProvider();
	}
	
	
	
	public function testGetDefinedExportConfigs() {
		$flexFormDataProvider = $this->getAccessibleFlexFormDataProvider();
		
		$config = array('items' => array(array('default', 'default')));
		$selectArray = $flexFormDataProvider->getDefinedExportConfigs($config);

		$this->assertEquals(current(current($selectArray['items'])), 'default', 'The default key does not exist.');
		$this->assertEquals($selectArray['items'][1][0], 'export1');
		$this->assertEquals($selectArray['items'][2][0], 'export2');
	}
	
	
	
	public function testGetDefinedListConfigs() {
		$flexFormDataProvider = $this->getAccessibleFlexFormDataProvider();
		
		$config = array('items' => array(array('default', 'default')));
		
		$selectArray = $flexFormDataProvider->getDefinedListConfigs($config);
		
		$this->assertEquals(current(current($selectArray['items'])), 'default', 'The default key does not exist.');
		$this->assertEquals($selectArray['items'][1][0], 'testList');
	}
	
	
	
	protected function getAccessibleFlexFormDataProvider() {

		$tsArray['settings']['listConfig']['testList'] = $this->configurationBuilderMock->getSettings(); 
		
		$tsArray['settings']['export']['exportConfigs'] = array ('export1' => array('viewClassName' => 'x')
																, 'export2' => array('viewClassName' => 'y'));
		
		
		$flexformDataProviderMock = $this->getAccessibleMock('user_Tx_PtExtlist_Utility_FlexformDataProvider', array('loadExtListTyposcriptArray'));
		$flexformDataProviderMock->expects($this->once())
			->method('loadExtListTyposcriptArray');
		
		$flexformDataProviderMock->_set('extListTypoScript', $tsArray);	
			
		return $flexformDataProviderMock;
	}
	
}

?>