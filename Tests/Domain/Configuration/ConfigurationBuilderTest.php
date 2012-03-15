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
 * 
 *
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilder_testcase extends Tx_Extbase_Tests_Unit_BaseTestCase {

	protected $settings = array(
		'listIdentifier' => 'test',
		'abc' => '1',
		'prototype' => array(
			'pager' => array(
				'default' => array(
					'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager',
					'pagerProperty' => 'pagerValue'
				)
			),
			'column' => array(
				'xy' => 'z',
			),
			'bookmarks' => array(
			),
		),
		'listConfig' => array(
			'test' => array(
				'abc' => '2',
				'def' => '3',
				'fields' => array(
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
				),
				'columns' => array(
					10 => array(
						'columnIdentifier' => 'column1',
						'fieldIdentifier' => 'field1',
						'label' => 'Column 1'
					),
					20 => array(
						'columnIdentifier' => 'column2',
						'fieldIdentifier' => 'field2',
						'label' => 'Column 2'
					)
				),
				'filters' => array(
					'testfilterbox' => array(
						10 => array(
							'testkey' => 'testvalue',
							'filterIdentifier' => 'filter1',
							'fieldIdentifier' => 'field1',
							'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
							'partialPath' => 'Filter/StringFilter',
						)
					)
				),
				'pager' => array(
					'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager'
				),

				'aggregateData' => array(
					'sumField1' => array(
						'fieldIdentifier' => 'field1',
						'method' => 'sum',
					),
					'avgField2' => array(
						'fieldIdentifier' => 'field2',
						'method' => 'avg',
					),
				),

				'bookmarks' => array(
					'showPublicBookmarks' => '1',
					'showUserBookmarks' => '1',
					'showGroupBookmarks' => '1',
					'bookmarksPid' => '1,2,3',
					'feUsersAllowedToEdit' => '2,3,4',
					'feGroupsAllowedToEdit' => '3,4,5',
					'groupIdsToShowBookmarksFor' => '4,5,6'
				),


				'aggregateRows' => array(
					10 => array(
						'column2' => array(
							'aggregateDataIdentifier' => 'avgField2',
						)
					)
				),

				'controller' => array(
					'Export' => array(
						'download' => array(
							'view' => 'export.exportConfigs.test'
						)
					)
				),

				'rendererChain' => array(
					'rendererConfigs' => array(
						100 => array(
							'rendererClassName' => 'Tx_PtExtlist_Domain_Renderer_Default_Renderer'
						)
					)
				),

				'export' => array(
					'exportConfigs' => array(
						'test' => array(
							'downloadType' => 'D',
							'fileName' => 'testfile',
							'fileExtension' => 'ext',
							'addDateToFilename' => 1,
							'pager' => array('enabled' => 0),
							'viewClassName' => 'Tx_PtExtlist_View_Export_CsvListView',
						)
					)
				)

			)
		)
	);

		
	
	public function testSetup() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($this->settings);
	}
	
	

	public function testNoListConfigException() {
		try {  
		    $configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance(NULL);
		} catch(Exception $e) {
			return;
		}
		$this->fail('No Exceptions has been raised for misconfiguration');
	}
	
	
	public function testSetAndMergeGlobalAndLocalConfig() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		$settings = $configurationBuilder->getSettings();
		$this->assertEquals($settings['abc'], 2);
		$this->assertEquals($settings['def'], 3);
	}
	
	
	
	public function testGetListIdentifier() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		$this->assertEquals($configurationBuilder->getListIdentifier(), $this->settings['listIdentifier']);
	}
	
	
	
	public function testBuildFieldsConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		$fieldConfigCollection = $configurationBuilder->buildFieldsConfiguration();
		$this->assertTrue(is_a($fieldConfigCollection, 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection'));
	}


	public function testBuildcolumnSelectorConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		$columnSelectorConfig = $configurationBuilder->buildColumnSelectorConfiguration();
		$this->assertTrue(is_a($columnSelectorConfig, 'Tx_PtExtlist_Domain_Configuration_ColumnSelector_ColumnSelectorConfig'), 'The method returned ' . get_class($columnSelectorConfig));
	}

	
	public function testBuildExportConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		$exportConfig = $configurationBuilder->buildExportConfiguration();
		$this->assertTrue(is_a($exportConfig, 'Tx_PtExtlist_Domain_Configuration_Export_ExportConfig'));
	}
	
	
	public function testBuildAggregateDataConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		$aggregateDataConfigCollection = $configurationBuilder->buildAggregateDataConfig();
		$this->assertTrue(is_a($aggregateDataConfigCollection, 'Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfigCollection'));
	}
	
	
	public function testBuildAggregateRowsConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		$aggregateRowConfigCollection = $configurationBuilder->buildAggregateRowsConfig();
		$this->assertTrue(is_a($aggregateRowConfigCollection, 'Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfigCollection'));
	}
	
	
	public function testBuildColumnsConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		$columnConfigCollection = $configurationBuilder->buildColumnsConfiguration();
		$this->assertTrue(is_a($columnConfigCollection, 'Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection'));
	}
	
	
	public function testGetFilterboxIdentifier() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
        $filterboxConfiguration = $configurationBuilder->getFilterboxConfigurationByFilterboxIdentifier('testfilterbox');
        
        $this->assertEquals($filterboxConfiguration->getFilterboxIdentifier(),'testfilterbox', 'Expected filterboxvalue was "testvalue" got "' . $filterboxConfiguration->getFilterboxIdentifier() . '" instead!');
	}
	
	
	public function testGetPagerSettings() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
        $pagerSettings = $configurationBuilder->getSettingsForConfigObject('pager');
        $pagerClassName = $pagerSettings['pagerClassName'];
        $this->assertEquals($pagerClassName,'Tx_PtExtlist_Domain_Model_Pager_DefaultPager', 'Expected pagerClassName was "Tx_PtExtlist_Domain_Model_Pager_DefaultPager" got "' . $pagerClassName . '" instead!'); 
	}
	
	
	public function testThrowExceptionOnEmptyFilterboxIdentifier() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
        try {
        	$configurationBuilder->getFilterboxConfigurationByFilterboxIdentifier('');
        	$this->fail('No exception thrown on empty filterbox identifier');
        } catch(Exception $e) {
        	return;
        }
	}
	
	
	public function testGetFilterSettings() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		$this->assertEquals($configurationBuilder->getSettingsForConfigObject('filter'), $this->settings['listConfig']['test']['filters']);
	}
	
	
	
	public function testGetPrototypeSettingsForObject() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		
		$prototypeSettings = $configurationBuilder->getPrototypeSettingsForObject('pager.default');
		$this->assertTrue(is_array($prototypeSettings), 'The return value must be an array');
		$this->assertEquals($prototypeSettings, $this->settings['prototype']['pager']['default']);
		
		$prototypeSettings = $configurationBuilder->getPrototypeSettingsForObject('somthingThatDoesNotExist');
		$this->assertTrue(is_array($prototypeSettings), 'The return value must be an array');
	}
	
	
	
	public function testGetMergedSettingsWithPrototype() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		
		$settings = $configurationBuilder->getMergedSettingsWithPrototype($this->settings['listConfig']['test']['pager'], 'pager.default');
		$this->assertTrue(is_array($settings), 'The return value must be an array');

		$this->assertEquals($this->settings['prototype']['pager']['default']['pagerProperty'], $settings['pagerProperty']);
	}
	
	
	public function testGetBookmarksSettings() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		$bookmarkSettings = $configurationBuilder->getSettingsForConfigObject('bookmarks');
		$this->assertEquals($this->settings['listConfig']['test']['bookmarks'], $bookmarkSettings);
	}
	
	
	
	public function testBuildBookmarksConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		$bookmarkConfig = $configurationBuilder->buildBookmarksConfiguration();
		$this->assertTrue(is_a($bookmarkConfig, 'Tx_PtExtlist_Domain_Configuration_Bookmarks_BookmarksConfig'));
	}
	
	
	public function testBuildRendererChainConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance('test');
		$rendererChainConfig = $configurationBuilder->buildRendererChainConfiguration();

		$this->assertNotNull($rendererChainConfig);
		$this->assertTrue(is_a($rendererChainConfig, 'Tx_PtExtlist_Domain_Configuration_Renderer_RendererChainConfig'), 'Got ' . get_class($rendererChainConfig));
	}
	
}
?>