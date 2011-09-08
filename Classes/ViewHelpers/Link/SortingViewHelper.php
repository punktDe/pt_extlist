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
 * ViewHelpers renders link for given sorting.
 *
 * Generates a link, that sorts a column as a whole.
 *
 * @package ViewHelpers
 * @subpackage Link
 * @author Michael Knoll
 */
class  Tx_PtExtlist_ViewHelpers_Link_SortingViewHelper extends Tx_Fluid_ViewHelpers_Link_ActionViewHelper {

	/**
     * Renders a link for given header
     *
	 * @param Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header
	 * @param string $action Rendered link for sorting action
	 */
	public function render(Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header, $action='sort') {
		$sortingFieldParams = array();

        // We generate sorting parameters for every sorting field configured for this column
        foreach($header->getColumnConfig()->getSortingConfig() as $sortingFieldConfig) { /* @var $sortingFieldConfig Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig */
            $newSortingDirection = (
                ($header->getSortingDirectionForField($sortingFieldConfig->getField()) != 0) ?
                        Tx_PtExtlist_Domain_QueryObject_Query::invertSortingState($header->getSortingDirectionForField($sortingFieldConfig->getField()))
                        : $sortingFieldConfig->getDirection()
            );
            $sortingFieldParams[] = $sortingFieldConfig->getField() . ':' . $newSortingDirection;
        }

        $sortingFieldParam = implode(';', $sortingFieldParams);

        // We set sortingDirectionParameter for children of this viewHelper
        $this->templateVariableContainer->add('sortingDirection', $this->getSortingDirectionForHeader($header));

        #echo "Sorting field param for " . $header->getColumnConfig()->getColumnIdentifier() . " = " . $sortingFieldParam . "<br>";
        #echo "Sorting direction for " . $header->getColumnConfig()->getColumnIdentifier() . " = ". $this->getSortingDirectionForHeader($header) . "<br>";

		$gpArrayViewHelper = new Tx_PtExtlist_ViewHelpers_Namespace_GPArrayViewHelper();
		$argumentArray = $gpArrayViewHelper->buildObjectValueArray($header, 'sortingFields', $sortingFieldParam);
		
		Tx_PtExtbase_State_Session_SessionPersistenceManagerFactory::getInstance()->addSessionRelatedArguments($argumentArray);

        $output = parent::render($action,$argumentArray);

        $this->templateVariableContainer->remove('sortingDirection');
		return $output;
	}



    /**
     * Returns sorting state for header
     *
     * We loop over each sorting field of header and take first sorting field
     * that has no forced direction and has a current sorting set in header.
     *
     * @param Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header
     * @return int Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE | Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC | Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC
     */
    protected function getSortingDirectionForHeader(Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header) {
        $sortingFieldConfigForHeader = $header->getColumnConfig()->getSortingConfig();
        foreach($sortingFieldConfigForHeader as $sortingFieldConfig) { /* @var $sortingFieldConfig Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig */
            if (!$sortingFieldConfig->getForceDirection()
                && $header->getSortingDirectionForField($sortingFieldConfig->getField()) != Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE) {
                return $header->getSortingDirectionForField($sortingFieldConfig->getField());
            }
        }
        return Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE;
    }
}

?>