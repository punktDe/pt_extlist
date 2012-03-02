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
 * Controller for filter column selector widget
 *
 * @package Controller
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Controller_ColumnSelectorController extends Tx_PtExtlist_Controller_AbstractController {

	/**
	 * Holds an instance of list renderer
	 *
	 * @var Tx_PtExtlist_Domain_Renderer_RendererChain
	 */
	protected $rendererChain;


	/**
	 * Overwrites initAction for setting properties
	 * and enabling easy testing
	 */
	protected function initializeAction() {
		$this->rendererChain = Tx_PtExtlist_Domain_Renderer_RendererChainFactory::getRendererChain($this->configurationBuilder->buildRendererChainConfiguration());
	}



	/**
	 * Renders show action for column selector controller
	 *
	 * @return string The rendered index action
	 */
	public function showAction() {
		$list = Tx_PtExtlist_Domain_Model_List_ListFactory::createList($this->dataBackend, $this->configurationBuilder);
		$renderedCaptions = $this->rendererChain->renderCaptions($list->getListHeader());
		$columnSelector = Tx_PtExtlist_Domain_Model_ColumnSelector_ColumnSelectorFactory::getInstance($this->configurationBuilder);

		$this->view->assign('columnSelector', $columnSelector);
		$this->view->assign('listHeader', $list->getListHeader());
		$this->view->assign('listCaptions', $renderedCaptions);
	}
}
?>