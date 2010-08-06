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
 * 
 *
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilder_testcase extends Tx_Extbase_BaseTestcase {
	
	protected $settings = array();
	
	public function setup() {
		$this->settings = array(
		    'listIdentifier' => 'test',
		    'abc' => '1',
			'prototype' => array(
				'pager' => array(
					'default' => array(
						'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager',
						'pagerProperty' => 'pagerValue'
					)
					),
				'column' => array (
						'xy' => 'z',
					),
				) ,
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
			        		10 => array (
			        			'testkey' => 'testvalue',
			        			'filterIdentifier' => 'filter1',
			        			'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
								'partialPath' => 'Filter/StringFilter',
			        		)   
			            )
			        )
		        )
		    ),
		    'pager' => array(
		        'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager' 
		    )
		);
	}
	
	
	
	public function testSetup() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
	}
	
	

	public function testNoListConfigException() {
		try {
		  $configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance(array());
		} catch(Exception $e) {
			return;
		}
		$this->fail('No Exceptions has been raised for misconfiguration');
	}
	
	
	public function testSetAndMergeGlobalAndLocalConfig() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		$settings = $configurationBuilder->getSettings();
		$this->assertEquals($settings['abc'], 2);
		$this->assertEquals($settings['def'], 3);
	}
	
	
	
	public function testGetListIdentifier() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		$this->assertEquals($configurationBuilder->getListIdentifier(), $this->settings['listIdentifier']);
	}
	
	
	
	public function testBuildFieldsConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		$fieldConfigCollection = $configurationBuilder->buildFieldsConfiguration();
		$this->assertTrue(is_a($fieldConfigCollection, 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection'));
	}
	
	
	public function testBuildColumnsConfiguration() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		$columnConfigCollection = $configurationBuilder->buildColumnsConfiguration();
		$this->assertTrue(is_a($columnConfigCollection, 'Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection'));
	}
	
	
	public function testGetFilterboxIdentifier() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
        $filterboxConfiguration = $configurationBuilder->getFilterboxConfigurationByFilterboxIdentifier('testfilterbox');
        
        $this->assertEquals($filterboxConfiguration->getFilterboxIdentifier(),'testfilterbox', 'Expected filterboxvalue was "testvalue" got "' . $filterboxConfiguration->getFilterboxIdentifier() . '" instead!');
	}
	
	
	public function testGetPagerSettings() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
        $pagerSettings = $configurationBuilder->getPagerSettings();
        $pagerClassName = $pagerSettings['pagerClassName'];
        $this->assertEquals($pagerClassName,'Tx_PtExtlist_Domain_Model_Pager_DefaultPager', 'Expected pagerClassName was "Tx_PtExtlist_Domain_Model_Pager_DefaultPager" got "' . $pagerClassName . '" instead!'); 
	}
	
	
	public function testThrowExceptionOnEmptyFilterboxIdentifier() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
        try {
        	$configurationBuilder->getFilterboxConfigurationByFilterboxIdentifier('');
        	$this->fail('No exception thrown on empty filterbox identifier');
        } catch(Exception $e) {
        	return;
        }
	}
	
	
	public function testGetFilterSettings() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		$this->assertEquals($configurationBuilder->getFilterSettings(), $this->settings['listConfig']['test']['filters']);
	}
	
	public function testGetPrototypeSettingsForObject() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		
		$prototypeSettings = $configurationBuilder->getPrototypeSettingsForObject('pager.default');
		$this->assertTrue(is_array($prototypeSettings), 'The return value must be an array');
		$this->assertEquals($prototypeSettings, $this->settings['prototype']['pager']['default']);
		
		$prototypeSettings = $configurationBuilder->getPrototypeSettingsForObject('somthingThatDoesNotExist');
		$this->assertTrue(is_array($prototypeSettings), 'The return value must be an array');
	}
	
	public function testGetMergedSettingsWithPrototype() {
		$configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		
		$settings = $configurationBuilder->getMergedSettingsWithPrototype($this->settings['listConfig']['test']['pager'], 'pager.default');
		$this->assertTrue(is_array($settings), 'The return value must be an array');

		$this->assertEquals($this->settings['prototype']['pager']['default']['pagerProperty'], $settings['pagerProperty']);
	}
	
}
?>