<?php


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
	 * List action rendering list for given
	 * list identifier
	 *
	 * @return string  Rendered list for given list identifier
	 * @author Michael Knoll <knoll@punkt.de>
	 */
	public function listAction() {
		$listData = $this->dataBackend->getListData();
		print_r($listData);
	}
	
}

?>