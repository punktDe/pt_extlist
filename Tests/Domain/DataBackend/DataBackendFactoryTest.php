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
 * Testcase for pt_extlist data backend factory 
 * 
 * @author Michael Knoll 
 * @author Daniel Lienert 
 * @package Tests
 * @subpackage Domain\DataBackend
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_DataBackendFactoryTest extends Tx_PtExtlist_Tests_BaseTestcase {

	public function setUp() {
		$this->initDefaultConfigurationBuilderMock();
	}
	
	
	
    public function testCreateDataBackend() {
    	$dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilderMock);
        
        $this->assertTrue(is_a($dataBackend, 'Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend'));
    }
    
    
    
    public function testGetSingletonInstances() {
    	$mockConfigurationBuilderForTest1 = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance($this->getConfigForFirstDbe());
    	$mockConfigurationBuilderForTest2 = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance($this->getConfigForSecondDbe());
    	
    	$dataBackendForTest1 = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($mockConfigurationBuilderForTest1);
    	$dataBackendForTest2 = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($mockConfigurationBuilderForTest2);
    	
    	$this->assertTrue($dataBackendForTest1 != $dataBackendForTest2);
    	
    	$duplicatedDataBackendForTest1 = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($mockConfigurationBuilderForTest1);
    	  	
    	$this->assertTrue($dataBackendForTest1 === $duplicatedDataBackendForTest1);
    }
    
    
    
    protected function getConfigForFirstDbe() {
    	return array(
                'listIdentifier' => 'testBackend1',
                'abc' => '1',
    		  	'prototype' => array(),
                'listConfig' => array(
                     'testBackend1' => array(
    				 'backendConfig' => array (
								'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend',
								'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
						 		'dataSourceClass' => 'Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource',
								'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
								'dataSource' => array(
									'testKey' => 'testValue',
								)
							),
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
                                 '10' => array(
                                    'filterIdentifier' => 'filter1',
                                    'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                    'fieldIdentifier' => 'field1',
                                    'partialPath' => 'partialPath'
                                 ),
                                 '20' => array(
                                    'filterIdentifier' => 'filter2',
                                    'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                    'fieldIdentifier' => 'field2',
                                    'partialPath' => 'partialPath'
                                 )
                             )
                        ),
                        'pager' => array(
                            'itemsPerPage'   => '10',
	                    	'pagerConfigs' => array(
	                    		'default' => array(
			                    	'templatePath' => 'EXT:pt_extlist/',
                     			    'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager',
			                        'enabled' => '1'
	                    		),
	                    	),
	                    ),
                    )
                )
            );
    }
    
    
    
    protected function getConfigForSecondDbe() {
    	return array(
                'listIdentifier' => 'testBackend2',
                'abc' => '1',
    			'prototype' => array(),
                'listConfig' => array(
                     'testBackend2' => array(
    				'backendConfig' => array (
								'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend',
								'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
								'dataSourceClass' => 'Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource',
								'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
								'dataSource' => array(
									'testKey' => 'testValue',
								)
							),
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
                                 '10' => array(
                                    'filterIdentifier' => 'filter1',
                                    'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                    'fieldIdentifier' => 'field1',
                                    'partialPath' => 'partialPath'
                                 ),
                                 '20' => array(
                                    'filterIdentifier' => 'filter2',
                                    'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
                                    'fieldIdentifier' => 'field1',
                                    'partialPath' => 'partialPath'
                                 )
                             )
                        ),
                        'pager' => array(
                            'itemsPerPage'   => '10',
	                    	'pagerConfigs' => array(
	                    		'default' => array(
                        			'templatePath' => 'EXT:pt_extlist/',
			                    	'pagerClassName' => 'Tx_PtExtlist_Domain_Model_Pager_DefaultPager',
			                        'enabled' => '1'
	                    		),
	                    	),
	                    ),
                    )
                )
            );
    }
}

?>