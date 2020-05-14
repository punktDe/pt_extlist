<?php


namespace PunktDe\PtExtlist\Domain\Model\Filter;


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


use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig;
use PunktDe\PtExtlist\Domain\Model\Filter\DataProvider\DataProviderFactory;
use PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria;

/**
 * Class implements an abstract filter for all options filters
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter
 * @see Tx_PtExtlist_Tests_Domain_Model_Filter_AbstractOptionsFilterTest
 */
abstract class AbstractOptionsFilter extends AbstractFilter
{
    /**
     * Holds an array of filter values
     *
     * @var array
     */
    protected $filterValues = [];



    /**
     * Array with selectable filter options
     *
     * @var array
     */
    protected $options = null;



    /**
     * @var DataProviderFactory
     */
    protected $dataProviderFactory;



    /**
     * @param DataProviderFactory $dataProviderFactory
     */
    public function injectDataProviderFactory(DataProviderFactory $dataProviderFactory)
    {
        $this->dataProviderFactory = $dataProviderFactory;
    }



    /**
     * @see PunktDe_PtExtbase_State_Session_SessionPersistableInterface::persistToSession()
     * @return array
     */
    public function _persistToSession()
    {
        return ['filterValues' => $this->filterValues, 'invert' => $this->invert];
    }



    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/AbstractFilter::reset()
     */
    public function reset()
    {
        $this->filterValues = []; // We need an empty array here, to overwrite a TS default value after reset
        parent::reset();
    }



    /**
     * @see AbstractFilter::initFilter()
     */
    protected function initFilter()
    {
    }



    /**
     * Build the criteria for a single field
     *
     * @param FieldConfig $fieldIdentifier
     * @return SimpleCriteria
     */
    protected function buildFilterCriteria(FieldConfig $fieldIdentifier)
    {
        $fieldName = DbUtils::getSelectPartByFieldConfig($fieldIdentifier);
        $singleCriteria = null;

        if ($fieldIdentifier->getIsRelation()) {
            $singleCriteria = Tx_PtExtlist_Domain_QueryObject_Criteria::relation($fieldName, current($this->filterValues));
        } elseif (is_array($this->filterValues) && count($this->filterValues) == 1) {
            $singleCriteria = Tx_PtExtlist_Domain_QueryObject_Criteria::equals($fieldName, current($this->filterValues), $fieldIdentifier->getTreatValueAsString());
        } elseif (is_array($this->filterValues) && count($this->filterValues) > 1) {
            $singleCriteria = Tx_PtExtlist_Domain_QueryObject_Criteria::in($fieldName, $this->filterValues);
        }

        return $singleCriteria;
    }



    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/AbstractFilter::setActiveState()
     */
    protected function setActiveState()
    {
        if (is_array($this->filterValues)) {
            $this->isActive = (in_array($this->filterConfig->getInactiveValue(), $this->filterValues) || count($this->filterValues) == 0 ? false : true);
        } else {
            $this->isActive = false;
        }
    }



    /**
     * @see AbstractFilter::initFilterByGpVars()
     *
     */
    protected function initFilterByGpVars()
    {
        if (array_key_exists('filterValues', $this->gpVarFilterData)) {
            $filterValues = $this->gpVarFilterData['filterValues'];

            if (is_array($filterValues)) {
                $this->filterValues = array_filter($filterValues);

                if (in_array($this->filterConfig->getInactiveValue(), $this->filterValues)) {
                    $this->filterValues = [$this->filterConfig->getInactiveValue()];
                }
            } else {
                $this->filterValues = trim($filterValues) != '' ? [$filterValues => $filterValues] : [];
            }
        }
    }



    /**
     * @see AbstractFilter::initFilterBySession()
     *
     */
    protected function initFilterBySession()
    {
        if (!empty($this->sessionFilterData['filterValues'])) {
            $this->filterValues = $this->sessionFilterData['filterValues'];
        }
    }



    /**
     * @see AbstractFilter::initFilterByTsConfig()
     *
     */
    protected function initFilterByTsConfig()
    {
        if ($this->filterConfig->getDefaultValue()) {
            $this->setDefaultValuesFromTSConfig($this->filterConfig->getDefaultValue());
        }
    }



    /**
     * Set the groupfilters default value
     *
     * @param mixed $defaultValue single value or array of preselected values
     */
    protected function setDefaultValuesFromTSConfig($defaultValue)
    {
        if (is_array($defaultValue)) {
            foreach ($defaultValue as $value) {
                $this->filterValues[$value] = $value;
            }
        } else {
            $this->filterValues[$defaultValue] = $defaultValue;
        }
    }



    /**
     * @return DataProvider_DataProviderInterface
     */
    protected function buildDataProvider()
    {
        return $this->dataProviderFactory->createInstance($this->filterConfig);
    }



    /**
     * Get cached renderes options from data provider
     *
     * @return array|null
     */
    protected function getOptionsFromDataProvider()
    {
        if ($this->options === null) {
            $this->options = $this->buildDataProvider()->getRenderedOptions();
        }

        return $this->options;
    }



    /**
     * Returns an associative array of options as possible filter values
     *
     * @return array
     */
    public function getOptions()
    {
        $renderedOptions = $this->getOptionsFromDataProvider();
        $this->addInactiveOption($renderedOptions);
        $this->setSelectedOptions($renderedOptions);

        return $renderedOptions;
    }



    /**
     * Set the the selected state in rendered Values
     *
     * @param array $renderedOptions
     */
    protected function setSelectedOptions(&$renderedOptions)
    {
        foreach ($this->filterValues as $filterValue) {
            if (!is_array($filterValue) && array_key_exists($filterValue, $renderedOptions)) {
                $renderedOptions[$filterValue]['selected'] = true;
            }
        }
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

            $renderedInactiveOption[$inactiveValue] = ['value' => $this->filterConfig->getInactiveOption(), 'selected' => $selected];

            $renderedOptions = $renderedInactiveOption + $renderedOptions;
        }

        return $renderedOptions;
    }



    /**
     * Returns value of selected option
     *
     * @return mixed String for single value, array for multiple values
     */
    public function getValue()
    {
        if (count($this->filterValues) > 1) {
            return $this->filterValues;
        } else {
            reset($this->filterValues);
            return current($this->filterValues);
        }
    }



    /**
     * Return filter value string for breadcrumb
     *
     * @return string
     */
    public function getDisplayValue()
    {
        $options = $this->getOptions();
        $displayValues = [];

        foreach ($options as $key => $option) {
            if ($option['selected'] === true) {
                $displayValues[] = $option['value'];
            }
        }

        return implode(', ', $displayValues);
    }
}
