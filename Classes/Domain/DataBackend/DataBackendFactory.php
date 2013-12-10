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
 * This class is no singleton, since we should get different backends for different settings that may change during
 * a single run of a TYPO3 request.
 * 
 * @package Domain
 * @subpackage DataBackend
 * @author Michael Knoll
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Domain_DataBackend_DataBackendFactoryTest
 */
class Tx_PtExtlist_Domain_DataBackend_DataBackendFactory { // NO SINGLETON!!! see comment above!

	/**
	 * Holds an instance of this class for deprecated static use. Remove this, once we remove static functions.
	 *
	 * TODO remove later!
	 *
	 * @var Tx_PtExtlist_Domain_DataBackend_DataBackendFactory
	 */
	private static $dataBackendFactoryInstance = NULL;



	/**
	 * Holds an associative array of instances of data backend objects
	 * Each list identifier holds its own data backend object
	 *
	 * @var Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer
	 */
	private $instancesContainer;



	/**
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory
	 */
	private $configurationBuilderFactory;



	/**
	 * @var Tx_Extbase_Object_ObjectManager
	 */
	private $objectManager;



	/**
	 * @var bool
	 */
	private $resetDataBackend = FALSE;



	/**
	 * Returns an instance of this class with optionally given settings injected in associated configuration builder.
	 *
	 * This is a helper method to create an instance of this class in static environment.
	 *
	 * @deprecated Remove this method, once all factories are non-static and properly use DI.
	 * @param array $settings
	 * @return \Tx_PtExtlist_Domain_DataBackend_DataBackendFactory
	 */
	public static function getInstance(array $settings = NULL) {
		$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
		$instance = $objectManager->get('Tx_PtExtlist_Domain_DataBackend_DataBackendFactory'); /* @var $instance Tx_PtExtlist_Domain_DataBackend_DataBackendFactory */
		return $instance;
	}



	/**
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory $configurationBuilderFactory
	 */
	public function injectConfigurationBuilderFactory(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory $configurationBuilderFactory) {
		$this->configurationBuilderFactory = $configurationBuilderFactory;
	}



	/**
	 * @param Tx_Extbase_Object_ObjectManager $objectManager
	 */
	public function injectObjectManager(Tx_Extbase_Object_ObjectManager $objectManager) {
		$this->objectManager = $objectManager;
	}



	/**
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer $instancesContainer
	 */
	public function injectInstancesContainer(Tx_PtExtlist_Domain_DataBackend_DataBackendInstancesContainer $instancesContainer) {
		$this->instancesContainer = $instancesContainer;
	}


	
	/**
	 * Returns an instance of data backend for a given list identifier.
	 *
	 * @param string $listIdentifier
	 * @param bool $throwExceptionOnNonExistingListIdentifier
	 * @return Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 * @throws Exception
	 * @deprecated Use non-static method!
	 */
	public static function getInstanceByListIdentifier($listIdentifier, $throwExceptionOnNonExistingListIdentifier = TRUE) {
		self::createStaticInstance();
		if (self::$dataBackendFactoryInstance->instancesContainer->contains($listIdentifier)) {
			return self::$dataBackendFactoryInstance->instancesContainer->get($listIdentifier);
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
	 * @deprecated Use non-static method!
	 */
	public static function createDataBackend(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $resetDataBackend = FALSE) {
		self::createStaticInstance();
		return self::$dataBackendFactoryInstance->getDataBackendInstanceByListIdentifier($configurationBuilder->getListIdentifier(), $resetDataBackend);
	}



	/**
	 * Returns data backend instance for given list identifier
	 *
	 * @param $listIdentifier
	 * @param bool $resetDataBackend
	 * @return \Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend
	 */
	public function getDataBackendInstanceByListIdentifier($listIdentifier, $resetDataBackend = FALSE) {
		if (!$this->instancesContainer->contains($listIdentifier) || $resetDataBackend) {
			$this->resetDataBackend = $resetDataBackend;
			$this->buildDataBackendForListIdentifier($listIdentifier);
		}
		return $this->instancesContainer->get($listIdentifier);
	}



	/**
	 * Returns data backend instance for given configuration builder
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return \Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend
	 */
	public function getDataBackendInstanceByConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$listIdentifier = $configurationBuilder->getListIdentifier();
		return $this->getDataBackendInstanceByListIdentifier($listIdentifier);
	}



	private static function createStaticInstance() {
		if (self::$dataBackendFactoryInstance === NULL) {
			self::$dataBackendFactoryInstance = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get('Tx_PtExtlist_Domain_DataBackend_DataBackendFactory');
		}
	}



	private function buildDataBackendForListIdentifier($listIdentifier) {
		$configurationBuilderForListIdentifier = $this->configurationBuilderFactory->getInstance($listIdentifier);
		$this->buildDataBackendForConfigurationBuilder($configurationBuilderForListIdentifier);
	}



	private function buildDataBackendForConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$dataBackendConfiguration = $configurationBuilder->buildDataBackendConfiguration();
		/** @var $dataBackendClassName string */
		$dataBackendClassName = $dataBackendConfiguration->getDataBackendClass();

		$dataBackend = $this->objectManager->get($dataBackendClassName, $configurationBuilder); /* @var $dataBackend Tx_PtExtlist_Domain_DataBackend_AbstractDataBackend */

		$this->instancesContainer->set($dataBackend); /* The reference has to be set here because otherwise every filter will create the databackend again -> recursion! */

		// Check whether backend class implements backend interface
		Tx_PtExtbase_Assertions_Assert::isTrue($dataBackend instanceof Tx_PtExtlist_Domain_DataBackend_DataBackendInterface, array('message' => 'Data Backend class ' . $dataBackendClassName . ' does not implement Tx_PtExtlist_Domain_DataBackend_DataBackendInterface 1280400022'));

		$dataBackend->_injectBackendConfiguration($configurationBuilder->buildDataBackendConfiguration());
		$dataBackend->_injectFieldConfigurationCollection($configurationBuilder->buildFieldsConfiguration());
		$dataBackend->_injectDataMapper($this->getDataMapper($configurationBuilder));
		$dataBackend->_injectDataSource($this->getDataSource($dataBackendClassName, $configurationBuilder));
		$dataBackend->_injectPagerCollection($this->getPagerCollection($configurationBuilder));
		$dataBackend->_injectSorter($this->getSorter($configurationBuilder));

		$dataBackend->_injectFilterboxCollection($this->getfilterboxCollection($configurationBuilder));

		if ($this->getQueryInterpreter($configurationBuilder) != NULL) {
			$dataBackend->_injectQueryInterpreter($this->getQueryInterpreter($configurationBuilder));
		}

		$dataBackend->init();
	}

	
	
	
	private function getDataSource($dataBackendClassName, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	 // Use data backend class to create data source, as only backend knows which data source to use and how to configure it!
         $dataSource = call_user_func($dataBackendClassName . '::createDataSource', $configurationBuilder);
         return $dataSource;
    }
    
    
    
    private function getDataMapper(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
        $dataMapper = Tx_PtExtlist_Domain_DataBackend_Mapper_MapperFactory::createDataMapper($configurationBuilder);
        return $dataMapper;
    }
    
    
    
    private function getFilterboxCollection(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	$filterboxCollection = Tx_PtExtlist_Domain_Model_Filter_FilterboxCollectionFactory::createInstance($configurationBuilder, $this->resetDataBackend);
    	return $filterboxCollection;
    }
    
    
    
    private function getPagerCollection(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	return Tx_PtExtlist_Domain_Model_Pager_PagerCollectionFactory::getInstance($configurationBuilder);
    }
    
    
    
    private function getQueryInterpreter(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	$backendConfiguration = $configurationBuilder->buildDataBackendConfiguration();
		$queryInterpreterClassName = $backendConfiguration->getQueryInterpreterClass();
        $queryInterpreter = new $queryInterpreterClassName;

	    return $queryInterpreter;
    }



    private function getSorter(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
        return Tx_PtExtlist_Domain_Model_Sorting_SorterFactory::getInstance($configurationBuilder);
    }
	
}