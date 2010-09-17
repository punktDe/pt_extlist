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
 * Class implements a factory for a collection of breadcrumbs
 *
 * @package Domain
 * @subpackage Model\BreadCrumbs
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollectionFactory {

	/**
	 * Holds an array of instances for each list identifier
	 *
	 * @var array
	 */
	protected static $instances = array();
	
	
	
	/**
	 * Factory method creates instance of breadcrumbs collection as list identifier-specific singleton
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollection
	 */
    public static function getInstanceByConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	if (!array_key_exists($configurationBuilder->getListIdentifier(), self::$instances) 
    	    || self::$instances[$configurationBuilder->getListIdentifier()] == null) {
    	    
    	    $breadCrumbCollection = new Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollection();
    	    
    	    $filterboxCollection = Tx_PtExtlist_Domain_Model_Filter_FilterboxCollectionFactory::createInstance($configurationBuilder);
    	    foreach($filterboxCollection as $filterbox) { /* @var $filterbox Tx_PtExtlist_Domain_Model_Filter_Filterbox */
    	    	foreach($filterbox as $filter) { /* @var $filter Tx_PtExtlist_Domain_Model_Filter_FilterInterface */
    	    		if ($filter->isActive()) {
    	    			$breadCrumbCollection->addBreadCrumb($filter->getFilterBreadCrumb());
    	    		}
    	    	}
    	    }
    	    
    	    self::$instances[$configurationBuilder->getListIdentifier()] = $breadCrumbCollection;
    	}
    	
    	return self::$instances[$configurationBuilder->getListIdentifier()];
    	
    }
    
}
?>