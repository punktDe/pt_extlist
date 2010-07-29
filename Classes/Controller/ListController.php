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
 * Controller for all list actsions
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Controller_ListController extends Tx_PtExtlist_Controller_AbstractController {
	
	

	public function renderAsPluginAction() {
		/**
		 * Plugin bekommt von Flexform einen Listennamen
		 * und kann damit die zugeh�rige Konfiguration 
		 * aus Typoscript auslesen.
		 */
		
		$configurationBuilder = Tx_PtExtlist_Configuration_ConfigurationBuilder::getInstance();
		
		$backend = Tx_bla_backendfactory::getInstance(Tx_ptextlist_configuration::getBackenConfiguration());
		$listStructure = $backend->getListStructure();
		$renderer = Tx_bla_rendererFactory::getInstance(Tx_ptexlist_configuration::getRendererConfiguration());
		$renderedListItems = $renderer->render($listStructure);
		$this->view->assign('listItems', $renderedListItems);
	}
	
	
	
	public function renderByListName($listName) {
		/**
		 * Subcontroller Aufruf aus anderer Extension, 
		 * bei der die Konfiguration und damit auch die DATEN ebenfalls
		 * durch einen Sch�ssel aus Typoscript
		 * ausgelesen wird
		 */
	}
	
	
	
	public function renderByListNameAndData($listName, $listData) {
		/**
		 * Subcontroller Aufruf aus anderer Extension,
		 * bei dem der Listennamen und die Daten dem
		 * Subcontroller Aufruf �bergeben werden.
		 */
	}

	
	
	/**
	 * List action rendering list
	 *
	 * @return string  Rendered list for given list identifier
	 * @author Michael Knoll <knoll@punkt.de>
	 */
	public function listAction() {
		
		$listFactory = new Tx_PtExtlist_Domain_Model_List_ListFactory($this->configurationBuilder);
		
		$renderer = Tx_PtExtlist_Domain_Renderer_RendererFactory::getRenderer(
					$this->configurationBuilder->buildRendererConfiguration());
		
		$list = $listFactory->createList();
		
		$renderedListData = $renderer->render($list);
		$renderedCaptions = $renderer->renderCaptions($list);
		
		$this->view->assign('listCaptions', $renderedCaptions);
		$this->view->assign('listData', $renderedListData);
	}
	
}

?>