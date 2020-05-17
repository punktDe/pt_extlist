<?php
namespace PunktDe\PtExtlist\Domain\Renderer;

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
 * Class implements a chain of renderers, responsible for renderering list data
 *
 * @package Domain
 * @subpackage Renderer
 * @author Michael Knoll 
 * @author Daniel Lienert 
 */

use PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererChainConfig;
use PunktDe\PtExtlist\Domain\Model\Lists\Header\ListHeader;
use PunktDe\PtExtlist\Domain\Model\Lists\ListData;
use PunktDe\PtExtlist\Domain\Model\Lists\Row;

class RendererChain implements RendererInterface
{
    /**
     * Holds an array of renderers
     *
     * @var array<\PunktDe\PtExtlist\Domain\Renderer\RendererInterface>
     */
    protected $renderers = [];
    
    
    
    /**
     * Holds an instance of renderer chain configuration
     * @var RendererChainConfig
     */
    protected $rendererChainConfiguration;

    
    
    /**
     * Constructor for rendering chain
     * @param RendererChainConfig $rendererChainConfiguration
     */
    public function __construct(RendererChainConfig $rendererChainConfiguration)
    {
        $this->rendererChainConfiguration = $rendererChainConfiguration;
    }
    
    
    
    /**
     * @see Tx_PtExtlist_Domain_Renderer_RendererInterface::renderCaptions()
     *
     * @param ListHeader $listHeader
     * @return Row
     */
    public function renderCaptions(ListHeader $listHeader)
    {
        foreach ($this->renderers as $renderer) { /* @var $renderer RendererInterface */
            $listHeader = $renderer->renderCaptions($listHeader);
        }
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
        if (!$this->rendererChainConfiguration->isEnabled()) {
            return $listData;
        }
        foreach ($this->renderers as $renderer) { /* @var $renderer RendererInterface */
            $listData = $renderer->renderList($listData);
        }
        return $listData;
    }



    /**
     * @param Row $row
     * @param $rowIndex
     * @return Row
     */
    public function renderSingleRow(Row $row, $rowIndex)
    {
        if (!$this->rendererChainConfiguration->isEnabled()) {
            return $row;
        }
        foreach ($this->renderers as $renderer) { /* @var $renderer RendererInterface */
            $row = $renderer->renderSingleRow($row, $rowIndex);
        }

        return $row;
    }



    /**
     * Renders aggregated list data
     *  
     * @see RendererInterface::renderAggregateList()
     *
     * @param ListData $aggregatedListData Row to be renderer
     * @return ListData Rendered aggregated list data
     */
    public function renderAggregateList(ListData $aggregatedListData)
    {
        foreach ($this->renderers as $renderer) { /* @var $renderer RendererInterface */
            $aggregatedListData = $renderer->renderAggregateList($aggregatedListData);
        }
        return $aggregatedListData;
    }
    
    
    
    /**
     * Adds a renderer to the list of renderers
     *
     * @param RendererInterface $renderer
     */
    public function addRenderer(RendererInterface $renderer)
    {
        $this->renderers[] = $renderer;
    }
    
    
    
    /**
     * Returns all renderers added to this chain
     *
     * @return array
     */
    public function getRenderers()
    {
        return $this->renderers;
    }
}
