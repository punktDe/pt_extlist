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
 * Controller for filter breadcrumbs widget
 *
 * @package Controller
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Controller_BreadCrumbsController extends Tx_PtExtlist_Controller_AbstractController {
	
	/**
	 * Holds an instance of filterbox collection processed by this controller
	 *
	 * @var Tx_PtExtlist_Domain_Model_Filters_FilterboxCollection
	 */
	protected $filterboxCollection;
	
	
	
	/**
	 * Overwrites initAction for setting properties
	 * and enabling easy testing
	 */
	protected function initializeAction() {
		$this->filterboxCollection = $this->dataBackend->getFilterboxCollection();
	}
	
	
	
	/**
	 * Renders index action for breadcrumbs controller
	 * 
	 * @return string The rendered index action
	 */
	public function indexAction() {
		$breadcrumbs = Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollectionFactory::getInstanceByFilterboxCollection(
			$this->configurationBuilder,
		    $this->filterboxCollection
		);
		$this->view->assign('breadcrumbs', $breadcrumbs);
	}
	
	
	
	/**
	 * Resets given filter and forwards to index action
	 *
	 * @return string The rendered reset filter action
	 */
	public function resetFilterAction() {
		$breadcrumbs = Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollectionFactory::getInstanceByFilterboxCollection(
		    $this->configurationBuilder,
			$this->filterboxCollection
		);
		
		$breadcrumbs->resetFilters();
		 
		//$filterbox = $this->filterboxCollection->getFilterboxByFilterboxIdentifier($filterboxIdentifier);
		//$filter = $filterbox->getFilterByFilterIdentifier($filterIdentifier);
		//$filter->reset();
		$this->forward('index');
	}
	
}
 
?>