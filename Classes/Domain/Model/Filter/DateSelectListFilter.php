<?php
namespace PunktDe\PtExtlist\Domain\Model\Filter;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Simon Schaufelberger
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
 * Filter for time range
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter
 */
class DateSelectListFilter extends TimeSpanFilter
{
    /**
     * @var DataProvider_DataProviderFactory
     */
    protected $dataProviderFactory;



    /**
     * @param DataProvider_DataProviderFactory $dataProviderFactory
     */
    public function injectDataProviderFactory(DataProvider_DataProviderFactory $dataProviderFactory)
    {
        $this->dataProviderFactory = $dataProviderFactory;
    }



    /**
     * @return void
     */
    public function initFilterByGpVars()
    {
        if (is_array($this->gpVarFilterData) && array_key_exists('filterValues', $this->gpVarFilterData)) {
            list($this->gpVarFilterData['filterValueStart'], $this->gpVarFilterData['filterValueEnd']) = explode(',', $this->gpVarFilterData['filterValues']);
        }

        parent::initFilterByGpVars();
    }



    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/AbstractOptionsFilter::getOptions()
     */
    public function getOptions()
    {
        $dataProvider = $this->dataProviderFactory->createInstance($this->filterConfig);

        $renderedOptions = $dataProvider->getRenderedOptions();
        $this->addInactiveOption($renderedOptions);

        return $this->convertOptionsToSelectOptions($renderedOptions);
    }



    /**
     * @param array $renderedOptions
     * @return array
     */
    protected function convertOptionsToSelectOptions(&$renderedOptions)
    {
        $selectOptions = [];
        foreach ($renderedOptions as $optionKey => $optionValue) {
            $selectOptions[$optionKey] = $optionValue['value'];
        }

        return $selectOptions;
    }



    /**
     * Add inactiveFilterOpotion to rendered options
     *
     * @param array $renderedOptions
     * @return array
     */
    protected function addInactiveOption(&$renderedOptions)
    {
        if ($renderedOptions == null) {
            $renderedOptions = [];
        }

        if ($this->filterConfig->getInactiveOption()) {
            unset($renderedOptions[$this->filterConfig->getInactiveValue()]);

            if (count($this->filterValues) == 0) {
                $selected = true;
            } else {
                $selected = in_array($this->filterConfig->getInactiveValue(), $this->filterValues) ? true : false;
            }

            $inactiveValue = $this->filterConfig->getInactiveValue();

            $renderedInactiveOption[$inactiveValue] = ['value' => $this->filterConfig->getInactiveOption(),
                                                                 'selected' => $selected];

            $renderedOptions= $renderedInactiveOption + $renderedOptions;
        }

        return $renderedOptions;
    }



    /**
     * @return string
     */
    public function getValue()
    {
        return implode(',', parent::getValue());
    }



    /**
     * @return array
     */
    public function _persistToSession()
    {
        return [];
    }
}
