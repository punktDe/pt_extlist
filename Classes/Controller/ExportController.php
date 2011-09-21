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
 * Controller for all list actions
 *
 * @package Controller
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Controller_ExportController extends Tx_PtExtlist_Controller_AbstractController {


	/**
	 * @var string
	 */
	protected $exportListIdentifier;


	/**
	 * @return void
	 */
	public function initializeAction() {
		parent::initializeAction();

		$this->exportListIdentifier = $this->settings['exportListidentifier'];
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString(array('message' => 'No exportListidentifier set. 1316446015'));
	}


	/**
	 * @return void
	 */
	public function showLinkAction() {

	}


	public function downloadAction() {
		$list = Tx_PtExtlist_Domain_Model_List_ListFactory::createList($this->dataBackend, $this->configurationBuilder);

		$renderedListData = $this->rendererChain->renderList($list->getListData());
		$renderedCaptions = $this->rendererChain->renderCaptions($list->getListHeader());
		$renderedAggregateRows = $this->rendererChain->renderAggregateList($list->getAggregateListData());

		$this->view->assign('config', $this->configurationBuilder);
		$this->view->assign('listHeader', $list->getListHeader());
		$this->view->assign('listCaptions', $renderedCaptions);
		$this->view->assign('listData', $renderedListData);
		$this->view->assign('aggregateRows', $renderedAggregateRows);
		return $this->view->render();
	}
    
}
?>