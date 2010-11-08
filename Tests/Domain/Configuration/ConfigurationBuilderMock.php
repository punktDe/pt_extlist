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
 * Class implementing a mock for configuration builder
 *
 * @package Tests
 * @subpackage Domain\Configuration
 * @author Michael Knoll <knoll@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock extends Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder {

    /**
     * Returns array of settings for current plugin configuration
     *
     * @return array
     */
    public function getPluginSettings() {
    	return $this->origSettings;
    }
    
    
	
    /**
     * Returns a singleton instance of this class
     * @param $settings The current settings for this extension.
     * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder   Singleton instance of this class
     */
    public static function getInstance($settings = null, $overwriteSettings = null) {
    	if ($settings != null) {
    		$configurationBuilderMock = new Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock($settings);
    	} else {
    		
    		$settings = array(
	            'listIdentifier' => 'test',

    			'abc' => '1',
            	'prototype' => array(

				'backend' => array (
					'mysql' => array (
						'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlDataBackend',
						'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
						'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
						
						
					)
					),
				'column' => array (
						'xy' => 'z',
					),
				),
	            'listConfig' => array(
	                 'test' => array(
				
						'default' => array (
    						'sortingColumn' => 'column3', 
    					),
						
						'backendConfig' => array (
								'dataBackendClass' => 'Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend',
								'dataMapperClass' => 'Tx_PtExtlist_Domain_DataBackend_Mapper_ArrayMapper',
								'queryInterpreterClass' => 'Tx_PtExtlist_Domain_DataBackend_MySqlDataBackend_MySqlInterpreter_MySqlInterpreter',
				
				
								'dataSource' => array(
									'testKey' => 'testValue',
									'username' => 'user',
									'password' => 'pass',
									'host' => 'localhost',
									'port' => 3306,
									'databaseName' => 'typo3',
								),
								
								'baseFromClause' => 'companies',
								'baseGroupByClause' => 'company',
								'baseWhereClause' => 'employees > 0'	
							),
						
	                     'abc' => '2',
	                     'def' => '3',
						 
	                     'controller' => array (
							'List' => array (
								'export' => array(
									'view' => 'export.exportConfigs.test'
								)
							)
						),
							
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
	                             'isSortable' => '1',
	                             'access' => '1,2,3,4'
	                         ),
	                         'field3' => array( 
	                         	 'special' => 'special',
	                             'isSortable' => '1',
	                             'access' => '1,2,3,4'
	                         ),
	                         'field4' => array( 
	                             'table' => 'tableName4',
	                             'field' => 'fieldName4',
	                         )
	                     ),
	                    'columns' => array(
	                        10 => array( 
	                            'columnIdentifier' => 'column1',
	                            'fieldIdentifier' => 'field1',
	                            'label' => 'Column 1',
	                            'isSortable' => '0',
	                            
	                        ),
	                        20 => array( 
	                            'columnIdentifier' => 'column2',
	                            'fieldIdentifier' => 'field2',
	                            'label' => 'Column 2',  
	                            'isSortable' => '1',
	                        	'sorting' => 'tstamp, title',
	                            
	                        ),
	                        30 => array( 
	                            'columnIdentifier' => 'column3',
	                            'fieldIdentifier' => 'field3',
	                            'label' => 'Column 3',  
	                            'isSortable' => '1',
	                        	'sorting' => 'tstamp asc, title !DeSc',
	                            'accessGroups' => '1,2,3,4',
	                            'cellCSSClass' => 'class',
	                        ),
	                        40 => array( 
	                            'columnIdentifier' => 'column4',
	                            'fieldIdentifier' => 'field4',
	                            'label' => 'Column 4',  
	                        	//'renderTemplate' => 'typo3conf/ext/pt_extlist/Configuration/TypoScript/Demolist/Demolist_Typo3_02.hierarchicStructure.html',
	                        )
	                    ),
	                    
	                    'rendererChain' => array(
	                    	'enabled' => 1,
	                    	 'rendererConfigs' => array(
	                    		 100 => array(
	                    			'renderClassName' => 'Tx_PtExtlist_Domain_Renderer_DefaultRenderer',	 	
	                    		 )
	                    	  )
	                    ),
	                    
	                    'filters' => array(
	                         'testfilterbox' => array(
	                            'filterConfigs' => array( 
		                         	'10' => array(
		                                'filterIdentifier' => 'filter1',
		                                'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
	                                    'fieldIdentifier' => 'field1',
		                                'partialPath' => 'Filter/StringFilter',
		                                'defaultValue' => 'default',
		                             ),
		                             '20' => array(
		                                'filterIdentifier' => 'filter2',
		                                'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_StringFilter',
	                                    'fieldIdentifier' => 'field1',
	                                    'partialPath' => 'Filter/StringFilter',
	                                    'accessGroups' => '1,2,3'
		                             )
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

	                    
	                    'aggregateData' => array(
					    	'sumField1' => array (
					    		'fieldIdentifier' => 'field1',
					    		'method' => 'sum',
	                    		'scope' => 'page',
					    	),
					    	'avgField2' => array (
					    		'fieldIdentifier' => 'field2',
					    		'method' => 'avg',
					    	),
					    ),
					    
					    
				    	'aggregateRows' => array (
				        	10 => array (
				        		'column2' => array (
				        			'aggregateDataIdentifier' => 'sumField1',
								)
							)
					    ),
					    
					    'export' => array (
					    	'exportConfigs' => array (
					    		'test' => array (
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
    		
	        if(is_array($overwriteSettings)) {
	        	$settings = t3lib_div::array_merge_recursive_overrule($settings, $overwriteSettings);
	        }
	        
            $configurationBuilderMock = new Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock($settings);
            $configurationBuilderMock->settings = $configurationBuilderMock->origSettings['listConfig'][$configurationBuilderMock->origSettings['listIdentifier']];
    	}
    	return $configurationBuilderMock;
    }
    
}

?>