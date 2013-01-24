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
 * Factory for data backend objects
 * 
 * @package Domain
 * @subpackage DataBackend
 * @author Michael Knoll, Daniel Lienert
 */
class Tx_PtExtlist_Domain_DataBackend_DataBackendFactory {
	
	/**
     * Holds an associative array of instances of data backend objects
     * Each list identifier holds its own data backend object
     * @var array<Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder>
     */
	private static $instances = array();


	/**
	 * @var boolean
	 */
	protected static $resetDataBackend = FALSE;

	
	/**
	 * Returns an instance of data backend for a given list identifier.
	 *
	 * @param string $listIdentifier
	 * @param bool $throwExceptionOnNonExistingListIdentifier
	 * @return Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 * @throws Exception
	 */
	public static function getInstanceByListIdentifier($listIdentifier, $throwExceptionOnNonExistingListIdentifier = true) {
		if (array_key_exists($listIdentifier, self::$instances)) {
			return self::$instances[$listIdentifier];
		} else {
			if ($throwExceptionOnNonExistingListIdentifier) {
				throw new Exception('No data backend found for list identifier ' . $listIdentifier, 1280770617);
			} else {
				return NULL;
			}
		}
	}
	
	
	
	/**
	 * Create new data backend object for given configuration
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @param boolean $resetDataBackend
	 * @return Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend
	 */
	public static function createDataBackend(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $resetDataBackend = FALSE) {
		$listIdentifier = $configurationBuilder->getListIdentifier();

		if (!array_key_exists($listIdentifier, self::$instances) || $resetDataBackend) {
			self::$resetDataBackend = $resetDataBackend;

			$dataBackendConfiguration = $configurationBuilder->buildDataBackendConfiguration();
			$dataBackendClassName = $dataBackendConfiguration->getDataBackendClass();

			$dataBackend = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get($dataBackendClassName, $configurationBuilder); /* @var $dataBackend Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend */

			self::$instances[$listIdentifier] = $dataBackend; /* The reference has to be set here bercause otherwise every filter will create the databackend again -> recursion! */

			// Check whether backend class implements backend interface
			Tx_PtExtbase_Assertions_Assert::isTrue($dataBackend instanceof Tx_PtExtlist_Domain_DataBackend_DataBackendInterface, array('message' => 'Data Backend class ' . $dataBackendClassName . ' does not implement Tx_PtExtlist_Domain_DataBackend_DataBackendInterface 1280400022'));

			$dataBackend->_injectBackendConfiguration($configurationBuilder->buildDataBackendConfiguration());
			//$dataBackend->_injectBookmarkManager(self::getBookmarkManagerAndProcessBookmark($configurationBuilder));
			$dataBackend->_injectFieldConfigurationCollection($configurationBuilder->buildFieldsConfiguration());
			$dataBackend->_injectDataMapper(self::getDataMapper($configurationBuilder));
			$dataBackend->_injectDataSource(self::getDataSource($dataBackendClassName, $configurationBuilder));
			$dataBackend->_injectPagerCollection(self::getPagerCollection($configurationBuilder));
			$dataBackend->_injectSorter(self::getSorter($configurationBuilder));

			$dataBackend->_injectFilterboxCollection(self::getfilterboxCollection($configurationBuilder));

			if (self::getQueryInterpreter($configurationBuilder) != NULL) {
				$dataBackend->_injectQueryInterpreter(self::getQueryInterpreter($configurationBuilder));
			}

			$dataBackend->init();
		}

		return self::$instances[$listIdentifier];
	}
	
	
	
	/**
	 * Initializes the data source used for this data backend
	 *
	 * @param string $dataBackendClassName
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_DataBackend_DataSource_IterationDataSourceInterface
	 */
    protected static function getDataSource($dataBackendClassName, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	 // Use data backend class to create data source, as only backend knows which data source to use and how to configure it!
         $dataSource = call_user_func($dataBackendClassName . '::createDataSource', $configurationBuilder);
         return $dataSource;
    }
    
    
    
    /**
     * Injects the data mapper used for created backend
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface
     */
    protected static function getDataMapper(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
        $dataMapper = Tx_PtExtlist_Domain_DataBackend_Mapper_MapperFactory::createDataMapper($configurationBuilder);
        return $dataMapper;
    }
    
    
    
    /**
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection
     */
    protected static function getFilterboxCollection(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) { 		
    	$filterboxCollection = Tx_PtExtlist_Domain_Model_Filter_FilterboxCollectionFactory::createInstance($configurationBuilder, self::$resetDataBackend);
    	return $filterboxCollection;
    }
    
    
    
    /**
     * 
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Model_Pager_PagerCollection
     */
    protected static function getPagerCollection(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	return Tx_PtExtlist_Domain_Model_Pager_PagerCollectionFactory::getInstance($configurationBuilder);
    }
    
    
    
    /**
     * Creates an instance of a query interpreter as configured in configuration builder
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_DataBackend_AbstractQueryInterpreter
     */
    protected static function getQueryInterpreter(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	$backendConfiguration = $configurationBuilder->buildDataBackendConfiguration();
		$queryInterpreterClassName = $backendConfiguration->getQueryInterpreterClass();
        $queryInterpreter = new $queryInterpreterClassName;

	    return $queryInterpreter;
    }
    
    
    
    /**
     * Creates an instance of a bookmark manager
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManager
     */
    protected static function getBookmarkManagerAndProcessBookmark(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	$bookmarkManager = Tx_PtExtlist_Domain_Model_Bookmarks_BookmarkManagerFactory::getInstanceByConfigurationBuilder($configurationBuilder);
    	// That's the part where bookmarks are written back to session
    	$bookmarkManager->processBookmark();
    	return $bookmarkManager;
    }



    /**
     * Creates an instance of a sorter
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Model_Sorting_Sorter
     */
    protected static function getSorter(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
        return Tx_PtExtlist_Domain_Model_Sorting_SorterFactory::getInstance($configurationBuilder);
    }
	
}
?>