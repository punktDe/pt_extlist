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
 * Testcase for mysql query interpreter criterias
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreterCriteria_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	protected $simpleCriteria1;
	
	
	protected $simpleCriteria2;
	
	
	protected $simpleCriteria3;
	
	
	
	public function setup() {
		$this->initDefaultConfigurationBuilderMock();

		$this->simpleCriteria1 = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field1','value1','=');
		$this->simpleCriteria2 = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field2','value2','=');
		$this->simpleCriteria3 = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field3','value3','=');
	}
	
	
	
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_AndCriteriaTranslator'));
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_NotCriteriaTranslator'));
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_OrCriteriaTranslator'));
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator'));
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_FullTextCriteriaTranslator'));
	}
	
	
	
	/**
	 * Test the translation of a simple criteria
	 */
	public function testSimpleCriteriaTranslator() {
		$criteria = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field','value','=');
		$criteriaString = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator::translateCriteria($criteria);
		$this->assertTrue($criteriaString == 'field = "value"');
	}
	
	
	
	/**
	 * Test the AND translation of two simple criterias
	 */
	public function testAndCriteriaTranslatorSimpleSimple() {	
		$andCriteriaSimple = new Tx_PtExtlist_Domain_QueryObject_AndCriteria($this->simpleCriteria1, $this->simpleCriteria2);
		
		$andCriteriaSimpleString = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_AndCriteriaTranslator::translateCriteria($andCriteriaSimple);
		$andCriteriaSimpleTestString = '(' . Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator::translateCriteria($this->simpleCriteria1)
													 . ') AND (' . Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator::translateCriteria($this->simpleCriteria2) . ')';
		$this->assertTrue($andCriteriaSimpleString == $andCriteriaSimpleTestString, 'Test failed with SimpleCriteria AND SimpleCriteria.(' . $andCriteriaSimpleString . '!=' . $andCriteriaSimpleTestString);
	}
	
	
	
	/**
	 * Test the AND translation of simple and complex criterias
	 */
	public function testAndCriteriaTranslatorSimpleComplex() {
		$andCriteriaSimple = new Tx_PtExtlist_Domain_QueryObject_AndCriteria($this->simpleCriteria1, $this->simpleCriteria2);
		$andCriteriaSimpleComplex = new Tx_PtExtlist_Domain_QueryObject_AndCriteria($this->simpleCriteria3, $andCriteriaSimple);
		
		$andCriteriaSimpleComplexString = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_AndCriteriaTranslator::translateCriteria($andCriteriaSimpleComplex);
		$andCriteriaSimpleComplexTestString = '(field3 = "value3") AND ((field1 = "value1") AND (field2 = "value2"))';
		$this->assertTrue($andCriteriaSimpleComplexString == $andCriteriaSimpleComplexTestString, 'Test failed with SimpleCriteria AND ComplexCriteria. :: ' . $andCriteriaSimpleComplexString . ' != ' . $andCriteriaSimpleComplexTestString);
	}
	
	
	
	/**
	 * Test the OR translation of two simple criterias
	 */
	public function testOrCriteriaTranslatorSimpleSimple() {	
		$orCriteriaSimple = new Tx_PtExtlist_Domain_QueryObject_OrCriteria($this->simpleCriteria1, $this->simpleCriteria2);
		
		$orCriteriaSimpleString = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_OrCriteriaTranslator::translateCriteria($orCriteriaSimple);
		$orCriteriaSimpleTestString = '(' . Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator::translateCriteria($this->simpleCriteria1)
													 . ') OR (' . Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator::translateCriteria($this->simpleCriteria2) . ')';
		$this->assertTrue($andCriteriaSimpleString == $andCriteriaSimpleTestString, 'Test failed with SimpleCriteria OR SimpleCriteria.(' . $orCriteriaSimpleString . '!=' . $orCriteriaSimpleTestString);
		
	}
	
	
	
	/**
	 * Test the NOT criteria translator
	 */
	public function testNOTCriteriaTranslator() {
		$notCriteria = new Tx_PtExtlist_Domain_QueryObject_NotCriteria($this->simpleCriteria1);
		$notCriteriaString = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_NotCriteriaTranslator::translateCriteria($notCriteria);
		$notCriteriaTestString = 'NOT (' . Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator::translateCriteria($this->simpleCriteria1).')';
		$this->assertTrue($notCriteriaString == $notCriteriaTestString, 'Test failed with SimpleCriteria. :: ' . $notCriteriaString . '!=' . $notCriteriaTestString);
	}
	
	
	
	public function testWrapArrayInBracketsInSimpleCriteriaTranslator() {
		$wrappedString = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator::wrapArrayInBrackets(array('tes"t1','test2','test3'));
		$this->assertEquals($wrappedString, '("tes\"t1", "test2", "test3")');
		
		$wrappedString = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator::wrapArrayInBrackets('tes"t1');
		$this->assertEquals($wrappedString, '"tes\"t1"');
	}
	
	
	
	public function testTranslateInCriteria() {
		$inCriteria = new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('test', array('test1', 'test2'), 'IN');
		$translatedCriteria = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_SimpleCriteriaTranslator::translateCriteria($inCriteria);
		$this->assertEquals($translatedCriteria, 'test IN ("test1", "test2")');
	}


	/**  @test */
	public function translateFullTextCriteria() {
		$fieldConfig1 = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test1', array('field' => 'field', 'table' => 'table'));
		$fieldConfig2 = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test2', array('field' => 'field', 'table' => 'table', 'special' => 'special'));

		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
		$fieldConfigCollection->addFieldConfig($fieldConfig1);
		$fieldConfigCollection->addFieldConfig($fieldConfig2);

		$fullTextCriteria = new Tx_PtExtlist_Domain_QueryObject_FullTextCriteria($fieldConfigCollection, 'searchString');
		$translatedCriteria = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_FullTextCriteriaTranslator::translateCriteria($fullTextCriteria);

		$this->assertEquals('MATCH (table.field, (special)) AGAINST ("searchString")', $translatedCriteria);
	}



	/** @test */
	public function translateFullTextCriteriaInBooleanMode() {
		$fieldConfig1 = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test1', array('field' => 'field', 'table' => 'table'));

		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
		$fieldConfigCollection->addFieldConfig($fieldConfig1);

		$searchParameter['booleanMode'] = true;

		$fullTextCriteria = new Tx_PtExtlist_Domain_QueryObject_FullTextCriteria($fieldConfigCollection, 'searchString', $searchParameter);
		$translatedCriteria = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_FullTextCriteriaTranslator::translateCriteria($fullTextCriteria);

		$this->assertEquals('MATCH (table.field) AGAINST ("searchString" IN BOOLEAN MODE)', $translatedCriteria);
	}



	/** @test */
	public function translateFullTextCriteriaInBooleanModeWrappedWithStars() {
		$fieldConfig1 = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig($this->configurationBuilderMock,'test1', array('field' => 'field', 'table' => 'table'));

		$fieldConfigCollection = new Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection();
		$fieldConfigCollection->addFieldConfig($fieldConfig1);

		$searchParameter['booleanMode'] = true;
		$searchParameter['booleanModeWrapWithStars'] = true;

		$fullTextCriteria = new Tx_PtExtlist_Domain_QueryObject_FullTextCriteria($fieldConfigCollection, 'searchString', $searchParameter);
		$translatedCriteria = Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_FullTextCriteriaTranslator::translateCriteria($fullTextCriteria);

		$this->assertEquals('MATCH (table.field) AGAINST ("*searchString*" IN BOOLEAN MODE)', $translatedCriteria);
	}



    /** @test */
    public function translateRawSqlQueryReturnsGivenRawSqlQueryString() {
        $fakeRawSqlQuery = 'THIS IS A FAKE QUERY';
        $rawSqlCriteriaMock = $this->getMock('Tx_PtExtlist_Domain_QueryObject_RawSqlCriteria', array(), array(), '', FALSE);
        $rawSqlCriteriaMock->expects($this->once())->method('getRawSqlString')->will($this->returnValue($fakeRawSqlQuery));
        $this->assertEquals(Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_RawSqlCriteriaTranslator::translateCriteria($rawSqlCriteriaMock), $fakeRawSqlQuery);
    }

}
?>