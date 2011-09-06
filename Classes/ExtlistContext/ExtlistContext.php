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
 * Class implements ExtListContext
 *
 * @package ExtlistContext
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_ExtlistContext_ExtlistContext {
	
		
	/**
	 * Holds an instance of list configuration for this context
	 *
	 * @var array
	 */
	protected $listSettings;

	/**
	 * Holds list identifier for list kept in context
	 *
	 * @var string
	 */
	protected $listIdentifier;
	
	
	
	/**
	 * Holds data backend for list identifier
	 *
	 * @var Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 */
	protected $dataBackend = null;
	
	
	
	/**
	 * Holds an instance of renderer chain for this list
	 *
	 * @var Tx_PtExtlist_Domain_Renderer_RendererChain
	 */
	protected $rendererChain = null;
	
	
	
	/**
	 * Initialize the extlistContext
	 */
	public function init() {
		$this->rendererChain = Tx_PtExtlist_Domain_Renderer_RendererChainFactory::
		    getRendererChain($this->dataBackend->getConfigurationBuilder()->buildRendererChainConfiguration());
	}



	/**
	 * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	public function getConfigurationBuilder() {
		return $this->dataBackend->getConfigurationBuilder();
	}


	
	/**
	 * Returns renderer chain
	 *
	 * @return Tx_PtExtlist_Domain_Renderer_RendererChain
	 */
	public function getRendererChain() {
		return $this->rendererChain;
	}
	
	
	
	/**
	 * Returns data backend of list context
	 *
	 * @return Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 */
	public function getDataBackend() {
		return $this->dataBackend;
	}
	
	
	
	/**
	 * Returns pager collection fo databacken for this list context
	 *
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerCollection
	 */
	public function getPagerCollection() {
		return $this->dataBackend->getPagerCollection();
	}
	
	
	
	/**
	 * Get a pager object - if pagerIdentifier is null, get default pager
	 * 
	 * @param unknown_type $pagerIdentifier
	 */
	public function getPager($pagerIdentifier = '') {
		$pagerIdentifier = $pagerIdentifier ? $pagerIdentifier : 'default';
		return $this->dataBackend->getPagerCollection()->getPagerByIdentifier($pagerIdentifier);
	}
	
	

	/**
	 * Returns list object of this list context
	 *
	 * @return Tx_PtExtlist_Domain_Model_List_List
	 */
	public function getList() {
		return Tx_PtExtlist_Domain_Model_List_ListFactory::createList($this->dataBackend, $this->dataBackend->getConfigurationBuilder());
	}
	
	
	
	/**
	 * Returns list data for this list context
	 *
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getListData() {
		return $this->getList()->getListData();
	}
	
	
	
	/**
	 * Returns the filterboxCollection
	 * 
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection
	 */
	public function getFilterBoxCollection() {
		return $this->dataBackend->getFilterboxCollection();
	}
	
	
	/**
	 * Returns rendered list data of current list context
	 *
	 * @return Tx_PtExtlist_Domain_Model_List_ListData
	 */
	public function getRenderedListData() {
		return $this->getRendererChain()->renderList($this->getListData());
	}
	
	
	
	/**
	 * Resets pager collection for list context
	 */
	public function resetPagerCollection() {
		$this->dataBackend->getPagerCollection()->reset();
	}
	
	
	
	/**
	 * Resets filterbox collection for list context
	 */
	public function resetFilterboxCollection() {
		$this->dataBackend->getFilterboxCollection()->reset();
	}
	
	
	
	/**
	 * Inject the Databackend
	 * 
	 * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
	 */
	public function injectDataBackend(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend) {
		$this->dataBackend = $dataBackend;
	}
}
?>