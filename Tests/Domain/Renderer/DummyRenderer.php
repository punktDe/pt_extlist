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
 * Dummy class implementing a renderer
 *
 * @package Tests
 * @subpackage pt_extlist
 * @author Michael Knoll 
 * @author Daniel Lienert 
 */
#require_once t3lib_extMgm::extPath('pt_extlist') . 'Classes/Domain/Renderer/RendererInterface.php';
#require_once t3lib_extMgm::extPath('pt_extlist') . 'Classes/Domain/Renderer/ConfigurableRendererInterface.php';
#require_once t3lib_extMgm::extPath('pt_extlist') . 'Classes/Domain/Renderer/AbstractRenderer.php';
class Tx_PtExtlist_Tests_Domain_Renderer_DummyRenderer extends Tx_PtExtlist_Domain_Renderer_AbstractRenderer {
	
	/**
	 * @see Tx_PtExtlist_Domain_Renderer_AbstractRenderer::injectConfiguration()
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig $rendererConfiguration
	 */
	public function _injectConfiguration(Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig $rendererConfiguration) {
	}
	
	
	
    /**
     * @see Tx_PtExtlist_Domain_Renderer_RendererInterface::renderCaptions()
     *
     * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader
     * @return Tx_PtExtlist_Domain_Model_List_Row
     */
    public function renderCaptions(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader) {
        return $listHeader;
    }



    /**
     * @see Tx_PtExtlist_Domain_Renderer_RendererInterface::renderList()
     *
     * @param Tx_PtExtlist_Domain_Model_List_ListData $listData
     * @return Tx_PtExtlist_Domain_Model_List_ListData
     */
    public function renderList(Tx_PtExtlist_Domain_Model_List_ListData $listData) {
        return $listData;
    }
    
    
    
    	/**
	 * Returns a rendered aggregate list for a given row of aggregates
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_ListData $aggregateListData
	 * @return Tx_PtExtlist_Domain_Model_List_ListData Rendererd List of aggregate rows
	 */
	public function renderAggregateList(Tx_PtExtlist_Domain_Model_List_ListData $aggregateListData) {
    	return new Tx_PtExtlist_Domain_Model_List_ListData();
    }
    
}
 
?>