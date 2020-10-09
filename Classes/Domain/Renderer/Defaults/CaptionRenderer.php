<?php
namespace PunktDe\PtExtlist\Domain\Renderer\Defaults;


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

use PunktDe\PtExtbase\Assertions\Assert;
use PunktDe\PtExtlist\Domain\Model\Lists\Header\HeaderColumn;
use PunktDe\PtExtlist\Domain\Model\Lists\Header\ListHeader;
use PunktDe\PtExtlist\Utility\RenderValue;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Default rendering strategy for rendering column captions
 * 
 * @package Domain
 * @subpackage Renderer\Strategy
 * @author Michael Knoll
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Domain_Renderer_Default_CaptionRendererTest
 */
class CaptionRenderer implements SingletonInterface
{
    /**
     * Renders captions
     *
     * @param ListHeader $listHeader
     * @return ListHeader $listHeader
     */
    public function renderCaptions(ListHeader $listHeader)
    {
        Assert::isNotNull($listHeader, ['message' => 'No header data available. 1280408235']);
        
        $renderedListHeader = new ListHeader($listHeader->getListIdentifier());

        foreach ($listHeader as $headerColumn) { /* @var $headerColumn HeaderColumn */

            if ($headerColumn->getColumnConfig()->isAccessable() && $headerColumn->getIsVisible()) {
                $label = $this->renderColumnLabel($headerColumn);
                $renderedListHeader->createAndAddCell($label, $headerColumn->getColumnIdentifier());
            }
        }

        return $renderedListHeader;
    }



    /**
     * @param HeaderColumn $headerColumn
     * @return string
     */
    public function renderColumnLabel(HeaderColumn $headerColumn)
    {
        $label = $headerColumn->getLabel();

        $label = RenderValue::stdWrapIfPlainArray($label);

        if (GeneralUtility::isFirstPartOfStr($label, 'LLL:')) {
            $label = LocalizationUtility::translate($label, '');
        }

        return $label;
    }
}
