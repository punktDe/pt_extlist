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
 * Class implementing filterbox controller
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Controller_FilterboxController extends Tx_PtExtlist_Controller_AbstractController {

	/**
	 * Holds filterbox identifier to be rendered by this controller
	 *
	 * @var string
	 */
	protected $filterboxIdentifier;
	
	
	
	/**
     * Injects the settings of the extension.
     *
     * @param array $settings Settings container of the current extension
     * @return void
     */
    public function injectSettings(array $settings) {
        parent::injectSettings($settings);
        tx_pttools_assert::isNotEmptyString($settings['filterboxIdentifier'], array('message' => 'No filterbox identifier has been set. Set filterbox identifier in flexform! 1277889418'));
        $this->filterboxIdentifier = $settings['filterboxIdentifier'];
    }
    
    
    
    /**
     * Renders a filterbox
     * 
     * @param Tx_PtExtlist_Domain_Model_Messaging_MessageCollectionCollection $errors
     * @return string The rendered filterbox action
     */
    public function showAction(Tx_PtExtlist_Domain_Model_Messaging_MessageCollectionCollection $errors = null) {
    	$filterbox = $this->getFilterboxForControllerSettings();
        $this->view->assign('filterbox', $filterbox);
    }
    
    
    
    /**
     * Renders submit action
     * 
     * @return String
     */
    public function submitAction() {
    	$filterbox = $this->getFilterboxForControllerSettings();
    	if (!$filterbox->validate()) {
            $this->view->assign('filtersDontValidate', true);
        }
    	$this->forward('show');
    }   

    
    
    /**
     * Resets all filters of filterbox
     * 
     * @return string Rendered reset action
     */
    public function resetAction() {
    	$filterbox = $this->getFilterboxForControllerSettings();
    	$filterbox->reset();
    	$this->forward('show');
    }
    
    
    
    /**
     * Returns filterbox configured to be handled by this controller
     *
     * @return Tx_PtExtlist_Domain_Model_Filter_Filterbox
     */
    protected function getFilterboxForControllerSettings() {
    	$filterboxCollection = $this->dataBackend->getFilterboxCollection();
        $filterbox = $filterboxCollection[$this->filterboxIdentifier];
        return $filterbox;
    }
}
?>