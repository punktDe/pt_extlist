<?php
/***************************************************************
*  Copyright notice
*
*  (c)  TODO - INSERT COPYRIGHT
*  All rights reserved
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
 * The list controller
 *
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_PtExtlist_Controller_ListController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var  Tx_PtExtlist_Domain_Repository_ListItemRepository
	 */
	protected $listItemRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	public function initializeAction() {
		$this->listItemRepository = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Repository_ListItemRepository');
		$this->listItemRepository->setSettings($this->settings);
	}

	/**
	 * Index action for this controller. Displays a list.
	 *
	 * @param array The ordering as key => value pair of an array
	 * @return string The rendered view
	 */
	public function indexAction($ordering = NULL) {
		$currentOrder = ($ordering === NULL) ? 'default' : strtolower(current($ordering));
		$list = new Tx_PtExtlist_Domain_Model_List($this->listItemRepository->findListItemsFor('Tx_PtExtlist_Domain_Model_Countries', $ordering, $this->settings['limit'], $this->settings['offset']));
		$list->setTitle('A List of Countries');
		$this->view->assign('list', $list);
		$this->view->assign('sorting', array('property' => key($ordering), 'currentOrder' => $currentOrder));
	}

	/**
	 * This action switches the sorting direction and forwards to the index action
	 *
	 * @param array $sorting The sorting 
	 * @return string The rendered view
	 */
	public function switchOrderingAction($sorting = NULL) {
		if ($sorting['currentOrder'] === 'asc') {
			$ordering[$sorting['property']] = Tx_Extbase_Persistence_QueryInterface::ORDER_DESCENDING;
		} else {
			$ordering[$sorting['property']] = Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING;
		}
		$this->forward('index', NULL, NULL, array('ordering' => $ordering));
	}
	
}
?>