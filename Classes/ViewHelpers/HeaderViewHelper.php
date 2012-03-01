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
class Tx_PtExtlist_ViewHelpers_HeaderViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Define arguments
	 */
	public function initializeArguments() {
		$this->registerArgument('exclude', 'array', 'Define identifiers to exclude from the headers', FALSE, array());
	}


	/**
	 * Renders SortingViewHelper
	 *
	 * Sets additional template variables for children of this viewhelper.
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeader $headers
	 * @param Tx_PtExtlist_Domain_Model_List_Row $captions
	 * @param string $headerKey
	 * @param string $captionKey
	 */
	public function render(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $headers, Tx_PtExtlist_Domain_Model_List_Row $captions, $headerKey='header', $captionKey="caption") {
		if ($headers === NULL || $captions === NULL) {
			return '';
		}

		$output = '';

		foreach ($headers as $header) {
			/* @var $header Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn */

			if($captions->hasItem($header->getColumnIdentifier()) && !in_array($header->getColumnIdentifier(), $this->arguments['exclude']) ) {

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
     * @param Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header
     * @return array
     */
    protected function buildSortingFieldParams(Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header) {
        $sortingFieldsParams = array();

        foreach ($header->getColumnConfig()->getSortingConfig() as $sortingFieldConfig) { /* @var $sortingFieldConfig Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig */
            $sortingFieldParams = array();
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

?>