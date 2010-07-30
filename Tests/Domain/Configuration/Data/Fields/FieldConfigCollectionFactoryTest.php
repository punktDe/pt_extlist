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
 * Testcase for field config collection factory
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Daniel Lienert <linert@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Configuration_Data_Fields_FieldConfigCollectionFactory_testcase extends Tx_Extbase_BaseTestcase {

	/**
	 * Holds a dummy configuration for a field config collection object
	 * @var array
	 */
	protected $fieldSettings = array();
	
	
	
	public function setup() {
		$this->fieldSettings = array(
		    'field1' => array( 
		        'table' => 'tableName1',
		        'field' => 'fieldName1',
		        'isSortable' => '0',
		        'access' => '1,2,3,4'
		    ),
		    'field2' => array( 
                'table' => 'tableName2',
                'field' => 'fieldName2',
                'isSortable' => '0',
                'access' => '1,2,3,4'
            )
		);
	}
	
	
	
	public function testGetFieldConfigCollection() {
		$fieldConfigCollection = Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollectionFactory::getFieldConfigCollection($this->fieldSettings);
		$this->assertTrue(is_a($fieldConfigCollection, 'tx_pttools_objectCollection'));
		$fieldConfig1 = $fieldConfigCollection->getFieldConfigByIdentifier('field1');
		$this->assertEquals($fieldConfig1->getTable(), 'tableName1');
	}
			
}

?>