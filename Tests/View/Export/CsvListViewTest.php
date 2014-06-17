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
 * Testcase for CSV export view
 *
 * @package Tests
 * @subpackage View\Export
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Tests_View_List_CsvListView_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * @var Tx_PtExtlist_View_Export_CsvListView
	 */
	protected $fixture;


	public function setUp() {
		$proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_View_Export_CsvListView');
		$this->fixture = new $proxyClass();

		$buffer = fopen('php://temp', 'w');

		$this->fixture->setOutputStreamHandle($buffer);
		$this->fixture->_set('templateVariableContainer', $this->createTemplateVariableContainer());
	}



    public function testSetup() {
        $this->assertTrue(class_exists('Tx_PtExtlist_View_Export_CsvListView'));
    }


	/**
	 *  @test
	 */
	public function initConfiguration() {

		$settings = array(
			'outputEncoding' => 'UTF888',
			'delimiter' => '|',
			'enclosure' => '#'
		);

		$overWriteSettings['listConfig']['test']['export']['exportConfigs']['test'] = $settings;

		$this->initDefaultConfigurationBuilderMock($overWriteSettings);

		$exportConfig = $this->configurationBuilderMock->buildExportConfiguration();

		$this->fixture->_set('exportConfiguration', $exportConfig);
		$this->fixture->initConfiguration();

		$this->assertEquals($settings['outputEncoding'], $this->fixture->_get('outputEncoding'));
		$this->assertEquals($settings['delimiter'], $this->fixture->_get('delimiter'));
		$this->assertEquals($settings['enclosure'], $this->fixture->_get('enclosure'));
	}


	/**
	 * @test
	 */
	public function renderHeader() {
		$overWriteSettings['listConfig']['test']['export']['exportConfigs']['test'] = array(
			'delimiter' => '|',
			'enclosure' => "'"
		);

		$this->initDefaultConfigurationBuilderMock($overWriteSettings);
		$this->fixture->setExportConfiguration($this->configurationBuilderMock->buildExportConfiguration());
		$this->fixture->initConfiguration();

		$this->fixture->renderHeader();

		$buffer = $this->fixture->getOutputStreamHandle();
		rewind($buffer);
		$data = stream_get_contents($buffer);

		$this->assertEquals("header1Value|header2Value|header3Value\n", $data);
	}


	/**
	 * @test
	 */
	public function  renderData() {
		$overWriteSettings['listConfig']['test']['export']['exportConfigs']['test'] = array(
			'delimiter' => '|',
			'enclosure' => "'"
		);

		$templateVariableContainer = $this->createTemplateVariableContainer();

		$this->initDefaultConfigurationBuilderMock($overWriteSettings);
		$this->fixture->setExportConfiguration($this->configurationBuilderMock->buildExportConfiguration());
		$this->fixture->initConfiguration();

		$this->fixture->renderData($templateVariableContainer['listData']);

		$buffer = $this->fixture->getOutputStreamHandle();
		rewind($buffer);
		$data = stream_get_contents($buffer);

		$this->assertEquals("col1Value|col2Value|col3Value\n", $data);
	}



	protected function createTemplateVariableContainer() {
		$templateVariableContainer = new Tx_Fluid_Core_ViewHelper_TemplateVariableContainer();


		$header = new Tx_PtExtlist_Domain_Model_List_Row();
		$header->createAndAddCell('header1Value', 'col1');
		$header->createAndAddCell('header2Value', 'col2');
		$header->createAndAddCell('header3Value', 'col3');

		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		$row->createAndAddCell('col1Value', 'col1');
		$row->createAndAddCell('col2Value', 'col2');
		$row->createAndAddCell('col3Value', 'col3');

		$listData = new Tx_PtExtlist_Domain_Model_List_ListData();
		$listData->addRow($row);

		$templateVariableContainer->add('listCaptions', $header);
		$templateVariableContainer->add('listData', $listData);

		return $templateVariableContainer;
	}

    
}
?>