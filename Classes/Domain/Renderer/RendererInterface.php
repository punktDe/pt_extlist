<?php
namespace PunktDe\PtExtlist\Domain\Renderer;

use PunktDe\PtExtlist\Domain\Model\Lists\Header\ListHeader;
use PunktDe\PtExtlist\Domain\Model\Lists\ListData;
use PunktDe\PtExtlist\Domain\Model\Lists\Row;

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
 * Interface for list renderers
 *  
 * @package Domain
 * @subpackage Renderer
 * @author Christoph Ehscheidt 
 * @author Michael Knoll 
 * @author Daniel Lienert 
 */
interface RendererInterface
{
    /**
     * Renders the given list through TypoScript.
     * Also uses the column definitions.
     *  
     * @param ListData $listData
     * @return ListData
     */
    public function renderList(ListData $listData);
    
    
    
    /**
     * Renders the column captions out of the TS definition
     *  
     * @param ListHeader $listHeader
     * @return ListHeader
     */
    public function renderCaptions(ListHeader $listHeader);
    
    
    
    /**
     * Returns a rendered aggregate list for a given row of aggregates
     *
     * @param ListData $aggregateListData
     * @return ListData Rendererd List of aggregate rows
     */
    public function renderAggregateList(ListData $aggregateListData);



    /**
     * @abstract
     * @param Row $row
     * @param $rowIndex
     * @return Row
     *
     */
    public function renderSingleRow(Row $row, $rowIndex);
}
