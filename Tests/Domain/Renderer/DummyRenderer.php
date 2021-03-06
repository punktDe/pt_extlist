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
#require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pt_extlist') . 'Classes/Domain/Renderer/RendererInterface.php';
#require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pt_extlist') . 'Classes/Domain/Renderer/ConfigurableRendererInterface.php';
#require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pt_extlist') . 'Classes/Domain/Renderer/AbstractRenderer.php';
class DummyRenderer extends \PunktDe\PtExtlist\Domain\Renderer\AbstractRenderer
{
    /**
     * @see Tx_PtExtlist_Domain_Renderer_AbstractRenderer::injectConfiguration()
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererConfig $rendererConfiguration
     */
    public function _injectConfiguration(\PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererConfig $rendererConfiguration)
    {
    }
    
    
    
    /**
     * @see Tx_PtExtlist_Domain_Renderer_RendererInterface::renderCaptions()
     *
     * @param ListHeader $listHeader
     * @return Row
     */
    public function renderCaptions(ListHeader $listHeader)
    {
        return $listHeader;
    }



    /**
     * @see Tx_PtExtlist_Domain_Renderer_RendererInterface::renderList()
     *
     * @param ListData $listData
     * @return ListData
     */
    public function renderList(ListData $listData)
    {
        return $listData;
    }
    
    
    
        /**
     * Returns a rendered aggregate list for a given row of aggregates
     *
     * @param ListData $aggregateListData
     * @return ListData Rendererd List of aggregate rows
     */
    public function renderAggregateList(ListData $aggregateListData)
    {
        return new ListData();
    }



    /**
     * Initializes the renderer
     *
     * @return void
     */
    public function initRenderer()
    {
    }
}
