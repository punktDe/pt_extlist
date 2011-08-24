<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Testcase for fullText criteria
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Tests_Domain_QueryObject_FullTextCriteria_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * @var Tx_PtExtlist_Domain_QueryObject_FullTextCriteria
	 */
	protected $fullTextCriteria = null;

	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection
	 */
	protected $fieldConfigCollection;


	public function setup() {
		$this->initDefaultConfigurationBuilderMock();

		$fieldConfig1 = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test1', array('field' => 'field', 'table' => 'table'));
		$fieldConfig2 = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test2', array('field' => 'field', 'table' => 'table', 'special' => 'special'));

		$this->fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
		$this->fieldConfigCollection->addFieldConfig($fieldConfig1);
		$this->fieldConfigCollection->addFieldConfig($fieldConfig2);

		$this->fullTextCriteria = new Tx_PtExtlist_Domain_QueryObject_FullTextCriteria($this->fieldConfigCollection, 'searchString');
	}
	

	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_QueryObject_FullTextCriteria'));
	}
	
	

	public function testConstruct() {
		$fullTextCriteria = new Tx_PtExtlist_Domain_QueryObject_FullTextCriteria($this->fieldConfigCollection, 'searchString');
		$this->assertTrue(is_a($fullTextCriteria, 'Tx_PtExtlist_Domain_QueryObject_FullTextCriteria'));
	}
	
	

	/**
	 * @test
	 */
	public function getFields() {
		$this->assertTrue(is_a($this->fullTextCriteria->getFields(), 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection'));
	}
	
	
	/**
	 * @test
	 */
	public function getSearchString() {
		$this->assertTrue($this->fullTextCriteria->getSearchString() == 'searchString');
	}

	
	/**
	 * @test
	 */
	public function isEqualTo() {
		$criteriaReference = new Tx_PtExtlist_Domain_QueryObject_FullTextCriteria($this->fieldConfigCollection, 'searchString');
		$criteriaEqual = new Tx_PtExtlist_Domain_QueryObject_FullTextCriteria($this->fieldConfigCollection, 'searchString');
		$criteriaInEqual = new Tx_PtExtlist_Domain_QueryObject_FullTextCriteria($this->fieldConfigCollection, 'anOtherSearchString');
		$this->assertTrue($criteriaReference->isEqualTo($criteriaEqual), 'Equal Criteria is not equal! ;)');
		$this->assertTrue(!($criteriaReference->isEqualTo($criteriaInEqual, 'Not Equal Criteria is equal! ;)')));
	}
	
}
 
?>