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
 * Controller for export actions
 *
 * @package Controller
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Controller_ExportController extends Tx_PtExtlist_Controller_AbstractController {

	/**
	 * Reset ConfigurationBuilder for actions in this Controller
	 *
	 * @var bool
	 */
	protected $resetConfigurationBuilder = TRUE;



	/**
	 * @var string
	 */
	protected $exportListIdentifier;



	/**
	 * @return void
	 */
	public function initializeAction() {
		parent::initializeAction();

		$this->exportListIdentifier = $this->settings['exportListIdentifier'];
		if(!$this->exportListIdentifier) $this->exportListIdentifier = $this->listIdentifier;
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->exportListIdentifier, array('message' => 'No exportListidentifier set. 1316446015'));
	}



	/**
	 * @return void
	 */
	public function showLinkAction() {
		$fileExtension = $this->configurationBuilder->buildExportConfiguration()->getFileExtension();
		$this->view->assign('fileExtension', $fileExtension);
	}



	/**
	 * Returns download for given parameters
	 *
	 * @return string
	 */
	public function downloadAction() {

		if($this->listIdentifier == $this->exportListIdentifier || !$this->exportListIdentifier) {
			$list = Tx_PtExtlist_Domain_Model_List_ListFactory::createList($this->dataBackend, $this->configurationBuilder);
			$rendererChain = Tx_PtExtlist_Domain_Renderer_RendererChainFactory::getRendererChain($this->configurationBuilder->buildRendererChainConfiguration());
			
		} else {
			$exportListConfiguration = $this->settings['listConfig'][$this->exportListIdentifier];
			
			if(!is_array($exportListConfiguration)) {
				throw new Exception('No export list configuration found for listIdentifier ' . $this->exportListIdentifier . ' 1317116470');
			}
			
			$extlistContext = Tx_PtExtlist_ExtlistContext_ExtlistContextFactory::getContextByCustomConfiguration($exportListConfiguration, $this->listIdentifier, false);
			
			$list = $extlistContext->getList(true);
			$rendererChain = $extlistContext->getRendererChain();
		}

		$renderedListData = $rendererChain->renderList($list->getListData());
		$renderedCaptions = $rendererChain->renderCaptions($list->getListHeader());
		$renderedAggregateRows = $rendererChain->renderAggregateList($list->getAggregateListData());

		$this->view->assign('listHeader', $list->getListHeader());
		$this->view->assign('listCaptions', $renderedCaptions);
		$this->view->assign('listData', $renderedListData);
		$this->view->assign('aggregateRows', $renderedAggregateRows);

		return $this->view->render();
	}

}
?>