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
 * Class implementing filterbox controller
 *
 * @package Controller
 * @author Michael Knoll 
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Controller_FilterboxController extends Tx_PtExtlist_Controller_AbstractController {

	/**
	 * Holds filterbox identifier to be rendered by this controller
	 *
	 * @var string
	 */
	protected $filterboxIdentifier;
	
	
	
	/**
	 * Holds a pagerCollection.
	 * 
	 * @var Tx_PtExtlist_Domain_Model_Pager_PagerCollection
	 */
	protected $pagerCollection = NULL;
	
	
	
	/**
	 * Holds an instance of filterbox collection for processed list
	 *
	 * @var Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection
	 */
	protected $filterboxCollection = NULL;
	
	
	
	/**
	 * Holds an instance of filterbox processed by this controller
	 *
	 * @var Tx_PtExtlist_Domain_Model_Filter_Filterbox
	 */
	protected $filterbox = NULL;
    	
	
	
	/**
     * Initialize the Controller
     *
     * @param array $settings Settings container of the current extension
     * @return void
     */
    public function initializeAction() {
        Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->settings['filterboxIdentifier'], array('message' => 'No filterbox identifier has been set. Set filterbox identifier in flexform! 1277889418'));
        $this->filterboxIdentifier = $this->settings['filterboxIdentifier'];
        $this->filterboxCollection = $this->dataBackend->getFilterboxCollection();
        $this->filterbox = $this->filterboxCollection->getFilterboxByFilterboxIdentifier($this->filterboxIdentifier, true);
    }
    
    
    
    /**
     * Renders a filterbox
     * 
     * @param Tx_PtExtlist_Domain_Model_Messaging_MessageCollectionCollection $errors
     * @return string The rendered filterbox action
     */
    public function showAction(Tx_PtExtlist_Domain_Model_Messaging_MessageCollectionCollection $errors = null) {
		$this->view->assign('filterbox', $this->filterbox);
    	$this->view->assign('config', $this->configurationBuilder);
    }
    
    
    
    /**
     * Renders submit action
     * 
     * @return String
     */
    public function submitAction() {

    	if (!$this->filterbox->validate()) {
            $this->view->assign('filtersDontValidate', true);
        }
        
        $this->resetPagers();
    	$this->forward('show');
    }   

    
    
    /**
     * Resets all filters of filterbox
     * 
     * @return string Rendered reset action
     */
    public function resetAction() {
    	$this->filterbox->reset();
    	$this->resetPagers();
    	$this->forward('show');
    }
    
    
    
    /**
     * Reset all pagers for this list.
     * 
     */
    protected function resetPagers(){
    	// Reset pagers
    	if($this->pagerCollection === NULL) {
    		// Only get pagerCollection if it's not set already. Important for testing.	
	    	$this->pagerCollection = Tx_PtExtlist_Domain_Model_Pager_PagerCollectionFactory::getInstance($this->configurationBuilder);
    	}
    	$this->pagerCollection->reset();
    }
    
}

?>