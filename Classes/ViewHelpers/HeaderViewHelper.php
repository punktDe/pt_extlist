<?php
namespace PunktDe\PtExtlist\ViewHelpers;

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

use PunktDe\PtExtbase\Exception\InternalException;
use PunktDe\PtExtlist\Domain\Model\Lists\Header\HeaderColumn;
use PunktDe\PtExtlist\Domain\Model\Lists\Header\ListHeader;
use PunktDe\PtExtlist\Domain\Model\Lists\Row;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper for rendering list headers.
 * 
 * This ViewHelper acts as a loop over headers given in list. Foreach
 * Header, the child elements of the ViewHelper are rendered. Therefore
 * additional variables are set in the template variable container and
 * hence made accessible for the child elements.
 * 
 * @package ViewHelpers
 * @author Daniel Lienert
 */
class HeaderViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    /**
     * Define arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('exclude', 'array', 'Define identifiers to exclude from the headers', false, []);
        $this->registerArgument('headers', ListHeader::class, 'ListHeader object', true);
        $this->registerArgument('captions', Row::class, 'Row object', true);
        $this->registerArgument('headerKey', 'string', 'headerKey', false, 'header');
        $this->registerArgument('captionKey', 'string', 'captionKey', false, 'caption');
    }


    /**
     * Renders SortingViewHelper
     *
     * Sets additional template variables for children of this viewhelper.
     *
     * @throws InternalException
     */
    public function render()
    {
        $headers = $this->arguments['headers']; /** @var ListHeader $headers */
        $captions = $this->arguments['captions'];
        $headerKey = $this->arguments['headerKey'];
        $captionKey = $this->arguments['captionKey'];

        if ($headers === null || $captions === null) {
            return '';
        }

        $output = '';

        foreach ($headers as $header) {
            /* @var $header HeaderColumn */

            if ($captions->hasItem($header->getColumnIdentifier()) && !in_array($header->getColumnIdentifier(), $this->arguments['exclude'])) {

                // Set additional variables in template vars for child elements
                $this->templateVariableContainer->add($captionKey, $captions->getItemById($header->getColumnIdentifier()));
                $this->templateVariableContainer->add('header', $header);
                $this->templateVariableContainer->add('sortable', $header->isSortable());
                $this->templateVariableContainer->add('sortingFields', $this->buildSortingFieldParams($header));
                $this->templateVariableContainer->add('sortColumnAtOnce', $header->getSortingConfig()->getColumnSorting());

                $output .= $this->renderChildren();

                $this->templateVariableContainer->remove('sortColumnAtOnce');
                $this->templateVariableContainer->remove('sortingFields');
                $this->templateVariableContainer->remove($captionKey);
                $this->templateVariableContainer->remove($headerKey);
                $this->templateVariableContainer->remove('sortable');
            }
        }

        return $output;
    }



    /**
     * Returns an array of sorting field parameters for given header.
     *
     * Each header can have multiple sorting fields attached, each one
     * can be sorted individually.
     *
     * @param HeaderColumn $header
     * @return array
     */
    protected function buildSortingFieldParams(HeaderColumn $header)
    {
        $sortingFieldsParams = [];

        foreach ($header->getColumnConfig()->getSortingConfig() as $sortingFieldConfig) { /* @var $sortingFieldConfig \Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig */
            $sortingFieldParams = [];
            $sortingFieldParams['field'] = $sortingFieldConfig->getField();
            $sortingFieldParams['label'] = $sortingFieldConfig->getLabel();
            $sortingFieldParams['forceDirection'] = $sortingFieldConfig->getForceDirection();
            $sortingFieldParams['direction'] = $sortingFieldConfig->getDirection();
            $sortingFieldParams['sortingFieldParameterAsc'] = $sortingFieldConfig->getField() . ':1';
            $sortingFieldParams['sortingFieldParameterDesc'] = $sortingFieldConfig->getField() . ':-1';
            $sortingFieldParams['sortingFieldParameterNone'] = $sortingFieldConfig->getField() . ':0';
            $sortingFieldParams['currentDirection'] = $header->getSortingDirectionForField($sortingFieldConfig->getField());
            $sortingFieldsParams[] = $sortingFieldParams;
        }

        return $sortingFieldsParams;
    }
}
