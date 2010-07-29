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
 */
class Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn implements Tx_PtExtlist_Domain_StateAdapter_SessionPersistableInterface, Tx_PtExtlist_Domain_StateAdapter_GetPostVarInjectableInterface {
	
	/**
	 * 
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig
	 */
	protected $columnConfig;
	
	/**
	 * @var string
	 */
	protected $listIdentifier;
	
	/**
	 * 
	 * @var string
	 */
	protected $columnIdentifier;
	
	/**
	 *
	 * @var boolean
	 */
	protected $isSortable;
	
	/** 
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollection
	 */
	protected $sortingFieldConfig;
	
	
	protected $sortingState = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE;

	
	/**
	 * 
	 * @param $columnConfig Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig
	 * @return void
	 * @author Daniel Lienert <lienert@punkt.de>
	 * @since 28.07.2010
	 */
	public function injectColumnConfig(Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig $columnConfig) {
		$this->columnConfig = $columnConfig;
		$this->listIdentifier = $columnConfig->getListIdentifier();
		$this->columnIdentifier = $columnConfig->getColumnIdentifier();
		$this->sortingFieldConfig = $columnConfig->getSortingConfig();
	}
	
	public function init() {
			
		
	}
	
	
	public function getLabel() {
    	return $this->columnConfig->getLabel();
    }
    
    public function getColumnIdentifier() {
    	return $this->columnIdentifier;
    }
    
    
    /**
     * Build an array with sorting definitions for this column
     *  
     * @return array
     * @author Daniel Lienert <lienert@punkt.de>
     * @since 29.07.2010
     */
    public function getSorting() {
    	
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
    	
    	return $sorting;
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
    	if(array_key_exists('sortingState', $sessionData)) {
    		$this->sortingState = $sessionData['sortingState'];
    	}
    }
    
	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/StateAdapter/Tx_PtExtlist_Domain_StateAdapter_GetPostVarInjectableInterface#injectGPVars()
	 */
    public function injectGPVars($GPVars) {
    	
    }   
}
?>