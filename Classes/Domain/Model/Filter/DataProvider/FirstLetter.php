<?php


namespace PunktDe\PtExtlist\Domain\Model\Filter\DataProvider;

use PunktDe\PtExtbase\Exception\InternalException;
use PunktDe\PtExtlist\Domain\QueryObject\Query;
use PunktDe\PtExtlist\Utility\RenderValue;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * Implements data provider for grouped list data
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 * @see Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_FirstLetterTest
 */
class FirstLetter extends GroupData
{
    /**
     * Build the group data query to retrieve the group data
     *
     * @param array FieldConfig $fields
     * @return string
     * @throws InternalException
     */
    protected function buildGroupDataQuery($fields)
    {
        $groupDataQuery = new Query();

        $displayField = $this->displayFields->getItemByIndex(0);

        if ($this->additionalTables != '') {
            $groupDataQuery->addFrom($this->additionalTables);
        }

        //TODO only works with SQL!
        $groupDataQuery->addField(sprintf('UPPER(LEFT(%1$s,1)) as firstLetter', $displayField->getTableFieldCombined()));
        $groupDataQuery->addSorting('firstLetter', Query::SORTINGSTATE_ASC);

        if ($this->showRowCount) {
            // TODO only works with SQL!
            $groupDataQuery->addField(sprintf('count("%s") as rowCount', $this->filterField->getTableFieldCombined()));
        }

        $groupDataQuery->addGroupBy('firstLetter');

        return $groupDataQuery;
    }



    /**
     * Returns an array of options to be displayed by filter
     * for a given array of fields
     *
     * @param array FieldConfig
     * @return array Options to be displayed by filter
     */
    protected function getRenderedOptionsByFields($fields)
    {
        $renderedOptions = [];
        $options =& $this->getOptionsByFields($fields);

        foreach ($options as $optionData) {
            $optionKey = $optionData['firstLetter'];

            $renderedOptions[$optionKey] = ['value' => $this->renderOptionData($optionData),
                'hasRecords' => true,
                'selected' => false];
        }


        $missingLetters = $this->getMissingLetters();
        if ($missingLetters) {
            $renderedOptions = $this->addMissingLetters($renderedOptions, $missingLetters);
        }


        return $renderedOptions;
    }



    /**
     * @param $renderedOptions
     * @param $missingLetters
     * @return array
     */
    protected function addMissingLetters($renderedOptions, $missingLetters)
    {
        foreach ($missingLetters as $letter) {
            if (!array_key_exists($letter, $renderedOptions)) {
                $renderedOptions[$letter] = ['value' => $this->renderOptionData(['firstLetter' => $letter]),
                    'hasRecords' => false,
                    'selected' => false];
            }
        }

        ksort($renderedOptions);

        return $renderedOptions;
    }



    /**
     * If the missin leters setting is set - return an array with these letters
     *
     * @return array|null
     */
    protected function getMissingLetters()
    {
        $missingLettersString = $this->filterConfig->getSettings('addLettersIfMissing');
        if ($missingLettersString) {
            return GeneralUtility::trimExplode(',', $missingLettersString);
        } else {
            return null;
        }
    }



    /**
     * Render a single option line by cObject or default
     *
     * @param array $optionData
     * @return string
     */
    protected function renderOptionData($optionData)
    {
        $option = RenderValue::renderByConfigObjectUncached($optionData, $this->filterConfig);
        return $option;
    }
}
