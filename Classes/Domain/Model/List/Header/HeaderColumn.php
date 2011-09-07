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
 * Class implements a single column of a list header
 * 
 * @author Daniel Lienert
 * @author Michael Knoll
 * @package Domain
 * @subpackage Model\List\Header
 */
class Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn
    implements Tx_PtExtbase_State_GpVars_GpVarsInjectableInterface,
               Tx_PtExtbase_State_Session_SessionPersistableInterface,
               Tx_PtExtlist_Domain_Model_Sorting_SortingObserverInterface {
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig
	 */
	protected $columnConfig;
	
	
	
	/**
	 * 
	 * @var array GP-Var Data
	 */
	protected $headerGPVarData;



    /**
     * Holds session data array
     * 
     * @var array
     */
    protected $headerSessionData;
	
	
	
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
	protected $sortingDirection = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE;
	
	
	
	/**
	 * Array with the actual sorting state
	 * @var Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection
	 */
	protected $sortingStateCollection;



    /**
     * Holds instance of sorter
     * 
     * @var Tx_PtExtlist_Domain_Model_Sorting_Sorter
     */
    protected $sorter;
	
	
	
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
	 * 1. Set state from Sorter
	 * 2. Overwrite state from GPVars
	 * 3. Build the sorting state from config and state
	 * 4. Build the sorting Query Object
	 */
	public function init() {	
		$this->initBySession();
		$this->initByGpVars();
		$this->buildSortingStateCollection();
	}



    /**
	 * Template method for initializing filter by session data
	 */
	protected function initBySession() {
		if(array_key_exists('sortingDirection', $this->headerSessionData)) {
			$this->sortingDirection = (int) $this->headerSessionData['sortingDirection'];
    	}
	}

	

	/**
	 * Template method for initializing filter by get / post vars
	 */
	protected function initByGpVars() {
		if(array_key_exists('sortingState', $this->headerGPVarData)) {
    		$this->sortingDirection = (int) $this->headerGPVarData['sortingState'];
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
     * @return Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection
     */
    public function getSortingStateCollection() {
        $this->buildSortingStateCollection();
    	return $this->sortingStateCollection;
    }


    
    /**
     * Build sorting state collection for this column
     *
     * @return void
     */
    protected function buildSortingStateCollection() {
    	$this->sortingStateCollection = new Tx_PtExtlist_Domain_Model_Sorting_SortingStateCollection();

    	if($this->sortingDirection == Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC
           || $this->sortingDirection == Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC) {

    		foreach($this->sortingFieldConfig as $fieldConfig) {
                if($fieldConfig->getForceDirection()){
                    $sortingState = Tx_PtExtlist_Domain_Model_Sorting_SortingState::getInstanceByFieldIdentifierAndSortingDirection($this->columnConfig->getConfigurationBuilder(), $fieldConfig->getField(), $fieldConfig->getDirection());
                } else {
                    $sortingState = Tx_PtExtlist_Domain_Model_Sorting_SortingState::getInstanceByFieldIdentifierAndSortingDirection($this->columnConfig->getConfigurationBuilder(), $fieldConfig->getField(), $this->sortingDirection);
    			}
                $this->sortingStateCollection->addSortingState($sortingState);
    		}
    	}
    }
    
    
    
    /**
     * Returns sorting direction
     * 
     * @return integer 1 = ASC, 0 = NONE,  -1 = DESC
     */
    public function getSortingDirection() {
    	return $this->sortingDirection;
    }

    
    
    /**
     * Set the sorting state
     * 
     * @param integer $sortingState
     */
    public function setSortingDirection($sortingState) {
    	$this->sortingDirection = $sortingState;
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
     *
     * TODO remove this!
     *
     * @return string
     */
    public function getSortingImageDefault() {
    	return $this->sortingImageDefault;
    }
    
    
    
    /**
     * Return the ASC image path to show for sorting link.
     *
     * TODO remove this!
     *
     * @return string
     */
    public function getSortingImageAsc() {
    	return $this->sortingImageAsc;
    }
    
    
    
    /**
     * Return the DESC image path to show for sorting link.
     *
     * TODO remove this!
     *
     * @return string
     */
    public function getSortingImageDesc() {
    	return $this->sortingImageDesc;
    }
    
    
    
    /**
     * Returns the image path to show for sorting link.
     * Depends on the sorting state.
     *
     * TODO remove this!
     * 
     * @return string
     */
    public function getSortingImage() {
    	switch($this->sortingDirection) {
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
     */
   	public function reset() {
   		$this->sortingDirection  = Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE;
        $this->headerSessionData = array();
        // we must not reset header GP data!
   		$this->init();
   	}



	/**
	 * Returns namespace for this object
	 * 
	 * @return string Namespace to identify this object
	 */
	public function getObjectNamespace() {
		return  $this->listIdentifier . '.headerColumns.' . $this->columnIdentifier;
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
	 * (non-PHPdoc)
	 * @see Tx_PtExtbase_State_GpVars_GpVarsInjectableInterface::injectGPVars()
	 */
    public function injectGPVars($GPVars) {
    	$this->headerGPVarData = $GPVars;
    }



    /**
     * Registers a sorter which observes implementing object.
     *
     * @param Tx_PtExtlist_Domain_Model_Sorting_Sorter $sorter
     */
    public function registerSorter(Tx_PtExtlist_Domain_Model_Sorting_Sorter $sorter) {
        $this->sorter = $sorter;
    }



    /**
     * Resets sorting of implementing object.
     *
     */
    public function resetSorting() {
        $this->reset();
    }



    /**
	 * Called by any mechanism to persist an object's state to session
	 *
	 */
    public function persistToSession() {
		if($this->sortingDirection != Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE) {
            $sessionPersistedArray = array('sortingDirection' => $this->sortingDirection);
    		return $sessionPersistedArray;
		}
    }



    /**
     * Called by any mechanism to inject an object's state from session
     *
     * @param array $sessionData Object's state to be persisted to session
     */
    public function injectSessionData(array $sessionData) {
		$this->headerSessionData = $sessionData;
    }

}
?>
