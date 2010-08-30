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
 * Class implements list header collection
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn implements Tx_PtExtlist_Domain_StateAdapter_SessionPersistableInterface, Tx_PtExtlist_Domain_StateAdapter_GetPostVarInjectableInterface {
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig
	 */
	protected $columnConfig;
	
	
	
	/**
	 * @var array session data
	 */
	protected $headerSessionData;
	
	
	
	/**
	 * 
	 * @var array GP-Var Data
	 */
	protected $headerGPVarData;
	
	
	
	/**
	 * @var string
	 */
	protected $listIdentifier;
	
	
	
	/**
	 * @var string
	 */
	protected $columnIdentifier;
	
	
	
	/**
	 * @var boolean
	 */
	protected $isSortable;
	
	
	
	/** 
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollection
	 */
	protected $sortingFieldConfig;
	
	
	
	/**
	 * @var integer
	 */
	protected $sortingState = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE;
	
	
	
	/**
	 * Array with the actual sorting state
	 * @var array
	 */
	protected $sorting;
	
	
	
	/**
	 * Sorting query object for this column
	 * @var Tx_PtExtlist_Domain_QueryObject_Query
	 */
	protected $sortingQuery;

	
	
	/**
	 * Session persistence manager
	 *
	 * @var Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager
	 */
	protected $sessionPersistenceManager = null;
	
	
	
	/**
	 * Injector for session persistence manager
	 *
	 * @param Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager $sessionPersistenceManager
	 */
	public function injectSessionPersistenceManager(Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager $sessionPersistenceManager) {
		$this->sessionPersistenceManager = $sessionPersistenceManager;
	}
	
	
	
	/**
     * Det the Columnheader Configuration
	 * 
	 * @param $columnConfig Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig
	 */
	public function injectColumnConfig(Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig $columnConfig) {
		$this->columnConfig = $columnConfig;
		$this->listIdentifier = $this->columnConfig->getListIdentifier();
		$this->columnIdentifier = $this->columnConfig->getColumnIdentifier();
		$this->sortingFieldConfig = $this->columnConfig->getSortingConfig();
	}
	
	
	
	/**
	 * Init the header column:
	 * 
	 * 1. Set state from Session
	 * 2. Overwrite state from GPVars
	 * 3. Build the sorting state from config and state
	 * 4. Build the sorting Query Object
	 * 5. Save to Session
	 */
	public function init() {	
		$this->initHeaderBySession();
		$this->initHeaderByGpVars();
		
		$this->buildSorting();
		$this->buildSortingQuery();
		
		$this->sessionPersistenceManager->persistToSession($this);
	}
	
	
	
	/**
	 * Template method for initializing filter by session data
	 */
	protected function initHeaderBySession() {
		if(array_key_exists('sortingState', $this->headerSessionData)) {
			$this->sortingState = (int) $this->headerSessionData['sortingState'];
    	}
	}
	
	

	/**
	 * Template method for initializing filter by get / post vars
	 */
	protected function initHeaderByGpVars() {
		if(array_key_exists('sortingState', $this->headerGPVarData)) {
    		$this->sortingState = (int) $this->headerGPVarData['sortingState'];
    	}
	}

	
	
    /**
	 * @return string column label
	 */
	public function getLabel() {
    	return $this->columnConfig->getLabel();
    }
    
    

	/**
	 * @return string column identifier
	 */
    public function getColumnIdentifier() {
    	return $this->columnIdentifier;
    }
       
    
    
    /**
     * Return an array with sorting definitions for this column
     *  
     * @return array of sortings
     * @author Daniel Lienert <lienert@punkt.de>
     */
    public function getSorting() {
    	return $this->sorting;
    }

    
    /**
     * build the sorting array for this column
     * @return void
     */
    protected function buildSorting() {
    	$sorting = array();    	
    	
    	if($this->sortingState == Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC || $this->sortingState == Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC) {
    		
    		foreach($this->sortingFieldConfig as $fieldConfig) {
    			if($fieldConfig->getForceDirection()){
    				$sorting[$fieldConfig->getField()] = $fieldConfig->getDirection();
    			} else {
    				$sorting[$fieldConfig->getField()] = $this->sortingState;
    			}
    		}
    	}
    	
		$this->sorting = $sorting;
    }
    
    
    /**
     * Build a QueryObject for sorting 
     * out of the sorting information array
     * @return void
     */
    protected function buildSortingQuery() {
    	$this->sortingQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
    	$this->sortingQuery->addSortingArray($this->getSorting());
    }
    
    
    
    /**
     * Get a query with sorting definition set
     * 
     * @return Tx_PtExtlist_Domain_QueryObject_Query
     * @author Daniel Lienert <lienert@punkt.de>
     * @since 02.08.2010
     */
    public function getSortingQuery() {
    	return $this->sortingQuery;
    }
    
    
    
    /**
     * return sorting State 
     * 
     * @return integer 1 = ASC, 0 = NONE,  -1 = DESC
     * @author Daniel Lienert <lienert@punkt.de>
     * @since 29.07.2010
     */
    public function getSortingState() {
    	return $this->sortingState;
    }
    
    
    
    /**
     * Return the column configuration.
     * @return Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig
     */
    public function getColumnConfig() {
    	return $this->columnConfig;
    }
    
    
    
    /**
     * Return the default image path to show for sorting link.
     * @return string
     */
    public function getSortingImageDefault() {
    	return $this->sortingImageDefault;
    }
    
    
    
    /**
     * Return the ASC image path to show for sorting link.
     * @return string
     */
    public function getSortingImageAsc() {
    	return $this->sortingImageAsc;
    }
    
    
    
    /**
     * Return the DESC image path to show for sorting link.
     * @return string
     */
    public function getSortingImageDesc() {
    	return $this->sortingImageDesc;
    }
    
    
    
    /**
     * Returns the image path to show for sorting link.
     * Depends on the sorting state.
     * 
     * @return string
     */
    public function getSortingImage() {
    	switch($this->sortingState) {
    		case Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC:
    			return $this->columnConfig->getSortingImageAsc();
    		case Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC:
    			return $this->columnConfig->getSortingImageDesc();
    		default:
    			return $this->columnConfig->getSortingImageDefault();	
    	}
    }
    
    
    
    /**
     * Returns if the column is sortable.
     * @return boolean True if sortable.
     */
    public function isSortable() {
    	return $this->columnConfig->getIsSortable();
    }
	
    
    
    /**
     * reset session state
     * 
     * @author Daniel Lienert <lienert@punkt.de>
     * @since 04.08.2010
     */
   	public function reset() {
   		$this->sortingState  = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE;
   		$this->headerSessionData = array();
   		$this->init();
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
		return 'tx_ptextlist_pi1.' . $this->listIdentifier . '.headerColumns.' . $this->columnIdentifier;
	}
	
	
	
	/**
	 * Returns list identifier of associated list
	 *
	 * @return string
	 */
	public function getListIdentifier() {
		return $this->listIdentifier;
	}
	
	
	
	/**
	 * Called by any mechanism to persist an object's state to session
	 *
	 */
    public function persistToSession() {
		return array('sortingState' => $this->sortingState);
    }
    
    
    
    /**
     * Called by any mechanism to inject an object's state from session
     *
     * @param array $sessionData Object's state to be persisted to session
     */
    public function injectSessionData(array $sessionData) {
		$this->headerSessionData = $sessionData;
    }
    
    
    
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/StateAdapter/Tx_PtExtlist_Domain_StateAdapter_GetPostVarInjectableInterface#injectGPVars()
	 */
    public function injectGPVars($GPVars) {
    	$this->headerGPVarData = $GPVars;
    }   
}
?>
