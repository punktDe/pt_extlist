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
 * Testcase for database utility class
 *
 * @package Tests
 * @subpackage Utility
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Utility_DbUtils_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}
    


	public function testGetAliasedSelectPartByFieldConfigTableAndField() {
        $fieldConfig = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test', array('field' => 'field', 'table' => 'table'));
    	$this->assertEquals('table.field AS test', Tx_PtExtlist_Utility_DbUtils::getAliasedSelectPartByFieldConfig($fieldConfig));
   }
    


	public function testGetAliasedSelectPartByFieldConfigSpecialString() {
        $fieldConfig = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test', array('field' => 'field', 'table' => 'table', 'special' => 'special'));
    	$this->assertEquals('(special) AS test', Tx_PtExtlist_Utility_DbUtils::getAliasedSelectPartByFieldConfig($fieldConfig));
    }


	
	/**
	 * @test
	 */
	public function getAliasedSelectPartByFieldConfigCollection() {
		$fieldConfig1 = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test1', array('field' => 'field', 'table' => 'table'));
		$fieldConfig2 = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test2', array('field' => 'field', 'table' => 'table', 'special' => 'special'));

		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
		$fieldConfigCollection->addFieldConfig($fieldConfig1);
		$fieldConfigCollection->addFieldConfig($fieldConfig2);

		$return = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfigCollection($fieldConfigCollection);
		$this->assertEquals('table.field, (special)', $return);
	}
}

?>