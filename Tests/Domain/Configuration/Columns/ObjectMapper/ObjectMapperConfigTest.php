<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Daniel Lienert <lienert@punkt.de>
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
* Class implementing tests for column config
*
* @package Tests
* @subpackage Domain\Configuration\Columns\ObjectMapper
* @author Daniel Lienert <linert@punkt.de>
*/
class Tx_PtExtlist_Tests_Domain_Configuration_Columns_ObjectMapper_ObjectMapperConfigTest extends Tx_PtExtlist_Tests_BaseTestcase {


	public function setup() {
		$this->initDefaultConfigurationBuilderMock();
	}


	/**
	 * @test
	 */
	public function getClassReturnsClassIfDefined() {
		$objectMapperConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ObjectMapper_ObjectMapperConfig($this->configurationBuilderMock, array('class' => 'Tx_PtExtlist_Tests_Domain_Configuration_Columns_ObjectMapper_ObjectMapperConfigTest'));
		$this->assertEquals('Tx_PtExtlist_Tests_Domain_Configuration_Columns_ObjectMapper_ObjectMapperConfigTest', $objectMapperConfig->getClass());
	}



	/**
	 * @test
	 */
	public function exceptionIsThrownIfClassIsNotDefined() {
		try {
			$objectMapperConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ObjectMapper_ObjectMapperConfig($this->configurationBuilderMock, array('class' => ''));
		} catch (Exception $e) {
			return;
		}

		$this->fail('No exception is thrown if class is missing');
	}



	/**
	 * @test
	 */
	public function exceptionIsThrownIfClassIsNotExistant() {
		try {
			$objectMapperConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ObjectMapper_ObjectMapperConfig($this->configurationBuilderMock, array('class' => 'blaUndSo'));
		} catch (Exception $e) {
			return;
		}

		$this->fail('No exception is thrown if class is missing');
	}


	/**
	 * @test
	 */
	public function mappingIsRetutrnedAsArray() {
		$mapping = array('x' => 'y', 'z' => 'a');

		$objectMapperConfig = new Tx_PtExtlist_Domain_Configuration_Columns_ObjectMapper_ObjectMapperConfig($this->configurationBuilderMock, array(
			'class' => 'Tx_PtExtlist_Tests_Domain_Configuration_Columns_ObjectMapper_ObjectMapperConfigTest',
			'mapping' => $mapping
			)
		);

		$this->assertEquals($mapping, $objectMapperConfig->getMapping());
	}
}
?>
