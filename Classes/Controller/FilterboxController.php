<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Class implementing filterbox controller.
 * 
 * Filters are organized in filterboxes, so a single filter cannot be displayed "alone".
 * Hence this controller handles all filter-dependent actions.
 * 
 * TODO think about avoiding redirect() in resetAction()
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
		$this->pagerCollection = $this->dataBackend->getPagerCollection();
	}
	


	/**
	 * Renders a filterbox
	 *
	 * @param Tx_PtExtlist_Domain_Model_Messaging_MessageCollectionCollection $errors
	 * @return string The rendered filterbox action
	 */
	public function showAction() {
		$this->view->assign('filterbox', $this->filterbox);
		$this->view->assign('config', $this->configurationBuilder);
		$this->view->assign('pagerCollection', $this->pagerCollection);
	}


	/**
	 * Renders submit action
	 *
	 * @return String
	 */
	public function submitAction() {
		/**
		 * Some explanation on what happens here:
		 *
		 * 1. We check whether filter validates. If not, we set error in template and forward to 'show'
		 * 2. Whenever a filter is submitted, this action is called. Previously active state of filter is stored in session, so
		 *	 we have to reset this. Active state for filterbox is set in init() of filterbox again, if GP vars are available.
		 * 3. We have to reset the pagers, as changed filter state normally means changed pager state
		 * 4. We check, whether we have a redirect on submit configured for this filter and do the redirect if we have done so
		 */

		if (!$this->filterbox->validate()) {
			$this->view->assign('filtersDontValidate', true);
            $this->forward('show');
        }
        
        $this->filterboxCollection->resetIsSubmittedFilterbox();

        $this->resetPagers();

		if ($this->filterbox->getFilterboxConfiguration()->getResetToDefaultSortingOnSubmit()) {
			$this->resetSorter();
		}

        // check whether we have a redirect on submit configured for this filter
        if ($this->filterbox->isSubmittedFilterbox() && $this->filterbox->getFilterboxConfiguration()->doRedirectOnSubmit()) {
            $this->redirect(
                 $this->filterbox->getFilterboxConfiguration()->getRedirectOnSubmitActionName(),      // action name
                 $this->filterbox->getFilterboxConfiguration()->getRedirectOnSubmitControllerName(),  // controller name
                 null,                                                                                // extension name
                 null,                                                                                // arguments
                 $this->filterbox->getFilterboxConfiguration()->getRedirectOnSubmitPageId()           // page id
            );
        }

		if ($this->configurationBuilder->getSettings('redirectOnSubmitFilter') == 1) {
			/**
			 * Whenever a filter is not first widget on page, we have to do a redirect
			 * for reset pager and all other components whenever a filter values changed
			 */
			$this->redirect('show');
		} else {
			$this->forward('show');
		}
	}



	/**
	 * Resets all filters of filterbox
	 *
	 * @param string $filterboxIdentifier Identifier of filter which should be reset
	 * @return string Rendered reset action
	 */
	public function resetAction($filterboxIdentifier) {
		// TODO refactor me, as we use this twice!
		if ($this->filterboxCollection->hasItem($filterboxIdentifier)) {
			$this->filterboxCollection->getFilterboxByFilterboxIdentifier($filterboxIdentifier)->reset();
		}

		$this->resetPagers();
		$this->resetSorter();

		/**
		 * TODO try to figure out a way how to handle this without redirect
		 *
		 * The problem is, that although GP-vars mapping is done automatically,
		 * we cannot trigger any action when resetting filterboxes without using
		 * the controller. The controller can be executed "too late", so that the filters
		 * are not reset, if their values are requested.
		 *
		 * We should introduce a "global" controller that handles certain actions
		 * before any other controller (and only once!).
		 */
		$this->redirect('show');
	}



	/**
	 * Resets a single filter
	 *
	 * Here we reset a single filter within a filterbox. Make sure to give a full qualified filter identifier
	 * which consists of filterboxIdentifier.filterIdentifier.
	 *
	 * @param $fullQualifiedFilterIdentifier FilterboxIdentifier.FilterIdentifier Identifier of filter to be reseted
	 * @return string Rendered resetFilter Action
	 */
	public function resetFilterAction($fullQualifiedFilterIdentifier) {
		// TODO refactor me, as we use this twice!
		list($filterboxIdentifier, $filterIdentifier) = explode('.', $fullQualifiedFilterIdentifier);
		if ($this->filterboxCollection->hasItem($filterboxIdentifier)) {
			$filterbox = $this->filterboxCollection->getFilterboxByFilterboxIdentifier($filterboxIdentifier);
			if ($filterbox->hasItem($filterIdentifier)) {
				$filterbox->getFilterByFilterIdentifier($filterIdentifier)->reset();
			}
	  	}

		/**
		 * TODO try to figure out a way how to handle this without redirect
		 *
		 * The problem is, that although GP-vars mapping is done automatically,
		 * we cannot trigger any action when resetting filterboxes without using
		 * the controller. The controller can be executed "too late", so that the filters
		 * are not reset, if their values are requested.
		 *
		 * We should introduce a "global" controller that handles certain actions
		 * before any other controller (and only once!).
		 */
		$this->redirect('show');
	}
	


	/**
	 * Reset all pagers for this list.
	 *
	 */
	protected function resetPagers() {
		// TODO put this into abstract controller

		// Reset pagers
		if ($this->pagerCollection === NULL) {
			// Only get pagerCollection if it's not set already. Important for testing.
			$this->pagerCollection = $this->dataBackend->getPagerCollection();
		}
		$this->pagerCollection->reset();
		$this->dataBackend->resetListDataCache();
	}



	/**
	 * Resets sorting for this list.
	 *
	 * If we have setting resetToDefaultSortingOnSubmit = 1 within FILTERBOX setting
	 * we reset to default, otherwise we reset completely.
	 *
	 */
	protected function resetSorter() {
		// TODO put this into abstract controller
		if ($this->filterbox->getFilterboxConfiguration()->getResetToDefaultSortingOnSubmit()) {
			$this->dataBackend->getSorter()->resetToDefault();
		} else {
			$this->dataBackend->getSorter()->reset();
		}

		// TODO add method to data backend that resets sorting and resets cache!
		$this->dataBackend->resetListDataCache();
	}

}
?>