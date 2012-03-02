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
 * Testcase for object mapper
 * 
 * @author Daniel Lienert
 * @package Tests
 * @subpackage Domain\Renderer\Default
 */
class Tx_PtExtlist_Tests_Domain_Renderer_Default_ObjectMapperTest extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * @var Tx_PtExtlist_Domain_Renderer_Default_ObjectMapper
	 */
	protected $objectMapper;



	public function setUp() {
		$this->initDefaultConfigurationBuilderMock();

		$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
		$mapper = $objectManager->get('Tx_Extbase_Property_Mapper');

		$this->objectMapper = $objectManager->get('Tx_PtExtlist_Domain_Renderer_Default_ObjectMapper');
	}



	/**
	 * @test
	 */
	public function mapperReturnsCorrectObjectWithNoDataGiven() {
		$mapperConfig = $this->getMapperConfig(array('class' => 'Tx_PtExtlist_Tests_Domain_Renderer_Default_ObjectMapper_testObject'));

		$object = $this->objectMapper->convert(array(), $mapperConfig);
		$expectedObject = new Tx_PtExtlist_Tests_Domain_Renderer_Default_ObjectMapper_testObject();

		$this->assertEquals($expectedObject, $object);
	}



	/**
	 * @test
	 */
	public function mapperMapsDataToObjectWithoutConfiguration() {
		$mapperConfig = $this->getMapperConfig(array('class' => 'Tx_PtExtlist_Tests_Domain_Renderer_Default_ObjectMapper_testObject'));
		$mapperData = array(
			'value1' => 'test1',
			'value2' => 'test2'
		);

		$object = $this->objectMapper->convert($mapperData, $mapperConfig);

		$this->assertEquals('test1', $object->getValue1());
		$this->assertEquals('test2', $object->getValue2());
	}



	/**
	 * @test
	 */
	public function mapperMapsDataToObjectWithAdditionalMapperConfig() {
		$mapperConfig = $this->getMapperConfig(array(
			'class' => 'Tx_PtExtlist_Tests_Domain_Renderer_Default_ObjectMapper_testObject',
			'mapping' => array(
				'valueFromDatabase1' => 'value1',
				'someOtherValue' => 'value2'
			))
		);
		$mapperData = array(
			'valueFromDatabase1' => 'test1',
			'someOtherValue' => 'test2'
		);

		$object = $this->objectMapper->convert($mapperData, $mapperConfig);

		$this->assertEquals('test1', $object->getValue1());
		$this->assertEquals('test2', $object->getValue2());
	}


	/**
	 * @test
	 */
	public function mapperThrowsExceptionIfValueCouldNotBeSet() {
		$mapperConfig = $this->getMapperConfig(array('class' => 'Tx_PtExtlist_Tests_Domain_Renderer_Default_ObjectMapper_testObject'));
		$mapperData = array(
			'badKey' => 'test1',
		);

		try {
			$object = $this->objectMapper->convert($mapperData, $mapperConfig);
		} catch(Exception $e) {
			return;
		}

		$this->fail('No Exception was thrown even when a property could not be mapped.');
	}


	/**
	 * @test
	 */
	public function mapperThrowsExceptionIfValueIsMissing() {
		$mapperConfig = $this->getMapperConfig(array('class' => 'Tx_PtExtlist_Tests_Domain_Renderer_Default_ObjectMapper_testObject'));
		$mapperData = array(
			'value1' => 'test1',
		);

		try {
			$object = $this->objectMapper->convert($mapperData, $mapperConfig);
		} catch(Exception $e) {
			$this->fail('An Exception was thrown as a property was missing.');
		}
	}


	/**
	 * @param $settings
	 * @return Tx_PtExtlist_Domain_Configuration_Columns_ObjectMapperConfig
	 */
	protected function getMapperConfig($settings) {
		return new Tx_PtExtlist_Domain_Configuration_Columns_ObjectMapper_ObjectMapperConfig($this->configurationBuilderMock, $settings);
	}

}

class Tx_PtExtlist_Tests_Domain_Renderer_Default_ObjectMapper_testObject {

	protected $value1;
	protected $value2;

	public function setValue1($value1) {
		$this->value1 = $value1;
	}

	public function getValue1() {
		return $this->value1;
	}

	public function setValue2($value2) {
		$this->value2 = $value2;
	}

	public function getValue2() {
		return $this->value2;
	}
}

?>