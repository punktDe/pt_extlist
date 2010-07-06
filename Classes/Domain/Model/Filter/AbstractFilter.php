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

// TODO ry21 autoload is not working in unit tests... so we need this require here...
require_once t3lib_extMgm::extPath('pt_extlist') . 'Classes/Domain/SessionPersistence/SessionPersistableInterface.php';

/**
 * Abstract filter class for all pt_extlist filter models
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
abstract class Tx_PtExtlist_Domain_Model_Filter_AbstractFilter 
    implements Tx_PtExtlist_Domain_Model_Filter_FilterInterface, Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface  {
	
    /**
     * Identifier of list to which this filter belongs to
     *
     * @var String
     */	
    protected $listIdentifier; 
    	
    	
	/**
	 * Identifier of this filter
	 *
	 * @var String
	 */
	protected $filterIdentifier;
	
	
	
	/**
	 * Holds a filter configuration for this filter
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig
	 */
	protected $filterConfig;
	
	
	
	/**
	 * Constructor for filter
	 *
	 * @param String $filterIdentifier     Identifier for filter
	 */
	public function __construct() {
		
	}
	
	
	
	/**
	 * Injects filter configuration for this filter
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig
	 */
	public function injectFilterConfig(Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig) {
		$this->filterConfig = $filterConfig;
        $this->filterIdentifier = $filterConfig->getFilterIdentifier();
        $this->listIdentifier = $filterConfig->getListIdentifier();
	}
	
	
	
	/**
	 * Returns filter identifier
	 *
	 * @return string Identifier of filter
	 */
	public function getFilterIdentifier() {
		return "test";
		return $this->filterIdentifier;
	}
	
	
	
	/**
	 * Returns list identifier
	 * 
	 * @return string Identifier of list to which this filter belongs to
	 */
	public function getListIdentifier() {
		return $this->listIdentifier;
	}
	
	
	
	/**
	 * Initializes the filter
	 * 
	 * @return void
	 */
	abstract public function init();	
	
	/****************************************************************************************************************
	 * Methods implementing "Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface"
	 *****************************************************************************************************************/

	/**
	 * Returns namespace for persisting this filter to session
	 * 
	 * @return string Namespace to persist this filter with
	 */
	public function getSessionNamespace() {
		// TODO ry21 insert filterbox identifier here
		return $this->listIdentifier . 'filters' . $this->filterIdentifier;
	}

	
	#abstract public function loadFromSession(array $sessionData);
	#abstract public function persistToSession();
	
	
}
?>