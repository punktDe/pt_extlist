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
 * Abstract filter class for filter models
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
abstract class Tx_PtExtlist_Domain_Model_Filter_AbstractFilter 
    implements Tx_PtExtlist_Domain_Model_Filter_FilterInterface, 
               Tx_PtExtlist_Domain_StateAdapter_SessionPersistableInterface,
               Tx_PtExtlist_Domain_StateAdapter_GetPostVarInjectableInterface {
    	
    /**
     * Identifier of list to which this filter belongs to
     *
     * @var String
     */	
    protected $listIdentifier; 

    
    
    /**
     * Filter Box Identifier
     * 
     * @var String
     */
    protected $filterBoxIdentifier;
    	
    
    
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
	 * Holds data from session stored by this filter
	 *
	 * @var array
	 */
	protected $sessionFilterData;
	
	
	
	/**
	 * Holds data from GP vars submitted for this filter
	 *
	 * @var array
	 */
	protected $gpVarFilterData;
	
	
	
	/**
	 * Get/Post vars adapter
	 *
	 * @var Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter
	 */
	protected $gpVarAdapter = null;
	
	
	
	/**
	 * Holds query object for this filter
	 * 
	 * @var Tx_PtExtlist_Domain_QueryObject_Query
	 */
	protected $filterQuery = null;
	
	
	
	/**
	 * Holds an array of error messages if filter data does not validate
	 *
	 * @var array
	 */
	protected $errorMessages = array();
	
	
	
	/**
	 * Constructor for filter
	 *
	 * @param String $filterIdentifier     Identifier for filter
	 */
	public function __construct() {
		$this->filterQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
	}
	
	
	
	/**
	 * Injects filter configuration for this filter
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig
	 */
	public function injectFilterConfig(Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig) {
		$this->filterConfig = $filterConfig;
        $this->listIdentifier = $filterConfig->getListIdentifier();
        $this->filterBoxIdentifier = $filterConfig->getFilterboxIdentifier();
		$this->filterIdentifier = $filterConfig->getFilterIdentifier();
	}
	
	
	
	/**
	 * Injector for Get/Post Vars adapter
	 *
	 * @param Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter $gpVarAdapter Get/Post vars adapter to be injected
	 */
	public function injectGpVarAdapter(Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter $gpVarAdapter) {
		$this->gpVarAdapter = $gpVarAdapter;
	}
	
	
	
	/**
	 * Returns filter identifier
	 *
	 * @return string Identifier of filter
	 */
	public function getFilterIdentifier() {
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
	 * Returns filterbox identifier for this filter
	 * 
	 * @return String
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 06.07.2010
	 */
	public function getFilterBoxIdentifier() {
		return $this->filterBoxIdentifier;
	}
	
	
	
	/**
	 * Returns TS config of this filter
	 *
	 * @return array TS config of this filter
	 */
	public function getTsConfig() {
		return $this->filterConfig->getSettings();
	}
	
	
	
	/**
	 * Returns label of filter as configured in TS
	 *
	 * @return string Label of filter as configured in TS
	 */
	public function getLabel() {
		return $this->filterConfig->getSettings('label');
	}
	
	
	
	/**
	 * Returns query set up by filter. Query contains
	 * all criterias set by filter
	 *
	 * @return Tx_PtExtlist_Domain_QueryObject_Query
	 */
	public function getFilterQuery() {
		return $this->filterQuery;
	}
	
	
	
	/**
	 * Returns array of error messages if filter does not validate
	 *
	 * @return array
	 */
    public function getErrorMessages() {
    	return $this->errorMessages;
    }
	
	
	
	/**
	 * Initializes the filter
	 * 
	 * @return void
	 */
	public function init() {
		
		/**
		 * What happens during initialization:
		 * 
		 * 1. When being constructed, filter gets injected:
		 * 1.1. Filter configuration from TS
		 * 1.2. Session data from session adapter
		 * 1.3. Get / Post vars from gpVarAdapter
		 * 
		 * 2. After construction, init() is called
		 * 2.1. Init calls method to init filter by TS configuration
		 * 2.2. Init calls method to init filter by session data
		 * 2.3. Init calls method to init filter by get / post vars 
		 * 
		 * 3. Create filter query
		 * 
		 * I you want to change the way, a filter initializes itsel, you have
		 * to override init() in you own filter implementation!
		 */
		
		$this->initFilterByTsConfig();
		$this->initFilterBySession();
		$this->iniFilterByGpVars();
		
		$this->createFilterQuery();
		
	}
	
	
	
	/**
	 * Template method for initializing filter by TS configuration
	 */
	abstract protected function initFilterByTsConfig();
	
	

	/**
	 * Template method for initializing filter by session data
	 */
	abstract protected function initFilterBySession();
	
	

	/**
	 * Template method for initializing filter by get / post vars
	 */
	abstract protected function iniFilterByGpVars();
	
	
	
	/**
	 * Template method for creating filter query from initialized data
	 */
	abstract protected function createFilterQuery();
	
	
	
	/**
	 * Template method for validating filter data.
	 * 
	 * Method should write error messages to $this->errorMessages array
	 *
	 * @return bool True, if filter validates, false, if filter does not validate
	 */
	public function validate() {
		return true;
	}
	
	/****************************************************************************************************************
     * Methods implementing "Tx_PtExtlist_Domain_StateAdapter_GetPostVarInjectableInterface"
     *****************************************************************************************************************/
	
	/**
	 * Injector for get & post vars
	 *
	 * @param array $gpVars
	 */
	public function injectGPVars($gpVars) {
		$this->gpVarFilterData = $gpVars;
	}
	
	
	
	/****************************************************************************************************************
	 * Methods implementing "Tx_PtExtlist_Domain_SessionPersistence_SessionPersistableInterface"
	 *****************************************************************************************************************/

	/**
	 * Returns namespace for this object
	 * 
	 * @return string Namespace to identify this object
	 */
	public function getObjectNamespace() {
		return 'tx_ptextlist_pi1.' . $this->listIdentifier . '.filters.' . $this->filterBoxIdentifier . '.' . $this->filterIdentifier;
	}
	
	
	
	/**
	 * Injects data from session persistence for this filter
	 *
	 * @param array $sessionData Session data, that was previously stored to session by this filter
	 */
	public function injectSessionData(array $sessionData) {
		$this->sessionFilterData = $sessionData;
	}
	
}
?>
