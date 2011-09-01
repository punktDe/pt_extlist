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
 * Class implements factory for filter objects
 * 
 * @package Domain
 * @subpackage Model\Filter
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_Model_Filter_FilterFactory {
	
	/**
	 * Creates an instance of a filter for a given configuration
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterInterface
	 */
	public static function createInstance(Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig) {
		$filter = self::createFilterObject($filterConfig->getFilterClassName()); /* @var $filter Tx_PtExtlist_Domain_Model_Filter_FilterInterface */
		$filter->injectFilterConfig($filterConfig);
        
		$sessionPersistenceManager = Tx_PtExtbase_State_Session_SessionPersistenceManagerFactory::getInstance();
        $sessionPersistenceManager->registerObjectAndLoadFromSession($filter);
        
        $gpVarsAdapter = Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapterFactory::getInstance();
        $gpVarsAdapter->injectParametersInObject($filter);
        $filter->injectGpVarAdapter($gpVarsAdapter);         
        $filter->injectDataBackend(Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($filterConfig->getConfigurationBuilder()));

        $filter->init();

		return $filter;
	}
	
	
	
	/**
	 * Creates filter object for given filter class name
	 *
	 * @param string $filterClassName
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterInterface
	 */
	private static function createFilterObject($filterClassName) {
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($filterClassName, array('message' => 'No filter class name given, check TS configuration! 1277889459'));
		Tx_PtExtbase_Assertions_Assert::isTrue(class_exists($filterClassName), array('message' => 'Given filter class ' . $filterClassName . ' does not exist or is not loaded! 1277889460'));
        $filter = new $filterClassName();
        Tx_PtExtbase_Assertions_Assert::isTrue(is_a($filter, 'Tx_PtExtlist_Domain_Model_Filter_FilterInterface'), array('message' => 'Given filter class does not implement filter interface! 1277889461'));
        return $filter;
	}
	
}

?>