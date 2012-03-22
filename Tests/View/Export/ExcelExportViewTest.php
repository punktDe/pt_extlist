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
 * Testcase for Excel export view
 *
 * @package Tests
 * @subpackage View\Export
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Tests_View_List_ExcelListView_testcase extends Tx_PtExtlist_Tests_BaseTestcase {


	/**
	 * @var string
	 */
	protected $proxyClass;

	/**
	 * @var Tx_PtExtlist_View_Export_ExcelListView
	 */
	protected $proxy;


	public function setUp() {
		if(!file_exists('PHPExcel/PHPExcel.php')) {
			$this->markTestSkipped('PHPExcel is not available!');
		}

		$this->initDefaultConfigurationBuilderMock();

		$this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_View_Export_ExcelListView');
		$this->proxy = new $this->proxyClass();

		$this->proxy->setConfigurationBuilder($this->configurationBuilderMock);

		$exportSettings = $this->configurationBuilderMock->getSettings('export');
		$this->proxy->setExportConfiguration(new Tx_PtExtlist_Domain_Configuration_Export_ExportConfig($this->configurationBuilderMock, $exportSettings['exportConfigs']['test']));


	}


	public function tearDown() {
		unset($this->proxy);
	}

	
	/**
	 * @test
	 */
	public function renderCallsAllParts() {

		/** @var $viewMock Tx_PtExtlist_View_Export_ExcelListView */
		$viewMock = $this->getMockBuilder('Tx_PtExtlist_View_Export_ExcelListView')
				  ->setMethods(array('renderPreHeaderRows','renderHeader','renderBody', 'renderPostBodyRows', 'clearOutputBufferAndSendHeaders', 'saveOutputAndExit'))
					->getMock();


		$viewMock->setConfigurationBuilder($this->configurationBuilderMock);
		$exportSettings = $this->configurationBuilderMock->getSettings('export');
		$viewMock->setExportConfiguration(new Tx_PtExtlist_Domain_Configuration_Export_ExportConfig($this->configurationBuilderMock, $exportSettings['exportConfigs']['test']));

		$viewMock->expects($this->once())->method('renderPreHeaderRows');
		$viewMock->expects($this->once())->method('renderHeader');
		$viewMock->expects($this->once())->method('renderBody');
		$viewMock->expects($this->once())->method('renderPostBodyRows');
		
		$viewMock->render();
	}


	/**
	 * @test
	 */
	public function buildBorderStyle() {
		$settings = array('style' => '1006A3', 'color' => '1006A3');
		$expected = array('style' => '1006A3', 'color' => array('rgb' => '1006A3'));

		$this->assertEquals($expected, $this->proxy->_call('buildBorderStyle', $settings));
	}
	
	
	/**
	 * @test
	 */
	public function getExcelSettingsByColumnIdentifier() {
		$this->proxy->_call('init');

		$expected = array(
			'wrap' => 0,
			'vertical' => 'top'
		);

		$this->assertEquals($expected, $this->proxy->_call('getExcelSettingsByColumnIdentifier', 'column1'));
	}
}

?>