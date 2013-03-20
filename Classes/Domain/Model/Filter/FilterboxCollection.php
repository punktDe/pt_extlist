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
 * Class implements a collection of filterboxes
 * 
 * @author Daniel Lienert
 * @author Michael Knoll 
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection extends Tx_PtExtbase_Collection_ObjectCollection {
    
	/**
	 * Restrict collection to filter box class
	 *
	 * @var String
	 */
    protected $restrictedClassName = 'Tx_PtExtlist_Domain_Model_Filter_Filterbox';
    
    
    
    /**
     * List identifier of the list to which this filterbox collection belongs to
     *
     * @var String
     */
    protected $listIdentifier;
    
    
    
    /**
     * Constructor for filterbox collection
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     */
    public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder = NULL) {
    	if($configurationBuilder != NULL) {
	    	$this->listIdentifier = $configurationBuilder->getListIdentifier();
    	}
    }
    
    
    
    /**
     * Sets the listIdentifier
     * 
     * @param string $listIdentifier
     */
    public function setListIdentifier($listIdentifier) {
    	$this->listIdentifier = $listIdentifier;
    }
    
    
    
    /**
     * Add Filterbox to Collection
     *  
     * @param $filterBox Tx_PtExtlist_Domain_Model_Filter_Filterbox
     * @param $filterBoxIdentifier string
     * @author Daniel Lienert 
     */
    public function addFilterBox(Tx_PtExtlist_Domain_Model_Filter_Filterbox $filterBox, $filterBoxIdentifier) {
    	$this->addItem($filterBox, $filterBoxIdentifier);
    }
    
    
    
    /**
     * Returns a filterbox for a given filterbox identifier
     *
     * @param string $filterBoxIdentifier Identifier of filterbox to be returned
     * @param bool $throwExceptionOnNonExistingIdentifier If set to true, an exception will be thrown, if no filterbox is registered for given identifier
     * @return Tx_PtExtlist_Domain_Model_Filter_Filterbox
	 * @throws Exception
     */
    public function getFilterboxByFilterboxIdentifier($filterBoxIdentifier, $throwExceptionOnNonExistingIdentifier = false) {
    	if ($this->hasItem($filterBoxIdentifier)) {
    		return $this->getItemById($filterBoxIdentifier);
    	} elseif($throwExceptionOnNonExistingIdentifier) {
    		throw new Exception('No filterBox can be found for ID ' . $filterBoxIdentifier, 1280857703);
    	}
    }
    
    
    
    public function getAccessableFilterboxCollection() {
     # @todo 
    }
    
    
    
    /**
     * Returns list identifier of list to which filterbox belongs to
     *
     * @return String
     */
    public function getListIdentifier() {
    	return $this->listIdentifier;
    }
    
    
    
    /**
     * Resets all filterboxes of this collection.
     *
     * Resetting filterbox includes reset of their filters.
     */
    public function reset() {
    	foreach($this->itemsArr as $filterBox) { /* @var $filterBox Tx_PtExtlist_Domain_Model_Filter_Filterbox */
    		$filterBox->reset();
    	}
    }



    /**
     * Returns filterbox of this collection which is currently set
     * to be submitted filterbox of current request.
     * 
     * @return null|Tx_PtExtlist_Domain_Model_Filter_Filterbox
     */
    public function getSubmittedFilterbox() {
        foreach ($this->itemsArr as $filterboxIdentifier => $filterbox) { /* @var $filterbox Tx_PtExtlist_Domain_Model_Filter_Filterbox */
            if ($filterbox->isSubmittedFilterbox()) {
                return $filterbox;
            }
        }
        return null;
    }



    /**
     * Returns array with exclude filters for filterbox collection.
     *
     * There is one filterbox submitted for this request. If we have
     * exclude filters configured for this filterbox, they will be returned here.
     *
     * @return array
     */
    public function getExcludeFilters() {
        $submittedFilterbox = $this->getSubmittedFilterbox();
        if ($submittedFilterbox) {
            return $submittedFilterbox->getFilterboxConfiguration()->getExcludeFilters();
        }
        return array();
    }



	/**
	 * @return void
	 */
    public function resetIsSubmittedFilterbox() {
        foreach ($this->itemsArr as $filterboxIdentifier => $filterbox) { /* @var $filterbox Tx_PtExtlist_Domain_Model_Filter_Filterbox */
            $filterbox->resetIsSubmittedFilterbox();
        }
    }



	/**
	 * Returns filter for given full filter name (filterboxIdentifier.filterIdentifier)
	 *
	 * TODO since we have this method here, refactor extlistContext and put a proxy method into abstract backend
	 *
	 * @param $fullFilterName
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterInterface
	 */
	public function getFilterByFullFiltername($fullFilterName) {
		list($filterboxIdentifier, $filterIdentifier) = explode('.', $fullFilterName);
		$filterbox = $this->getFilterboxByFilterboxIdentifier($filterboxIdentifier);
		$filter = $filterbox->getFilterByFilterIdentifier($filterIdentifier);
		return $filter;
	}

}
?>