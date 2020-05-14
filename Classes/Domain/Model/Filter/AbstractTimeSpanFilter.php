<?php
namespace PunktDe\PtExtlist\Domain\Model\Filter;

use PunktDe\PtExtbase\Assertions\Assert;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig;
use PunktDe\PtExtlist\Domain\QueryObject\Criteria;

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
 * Abstract filter base class for all filters implementing single value filter
 *
 * @author Daniel Lienert
 * @author Michael Knoll
 * @package Domain
 * @subpackage Model\Filter
 * @see Tx_PtExtlist_Tests_Domain_Model_Filter_AbstractTimeSpanFilterTest
 */
abstract class AbstractTimeSpanFilter extends AbstractFilter
{
    /**
     * Holds timestamp of start-time for filter
     *
     * @var \DateTime
     */
    protected $filterValueStart = null;



    /**
     * Holds time stamp of end-time for filter
     *
     * @var \DateTime
     */
    protected $filterValueEnd = null;



    /**
     * @var string format of the DB date field
     */
    protected $dbTimeFormat;



    /**
     * A timeSpan can have a startTime and an endTime
     * Therefore the dateFieldIdentifiers are 2-dimensional arrays of start/end field tuples
     *
     * @var array
     */
    protected $dateFieldConfigs;



    /**
     * Build the filterCriteria for a timeSpan filter
     *
     * @return Criteria
     */
    protected function buildFilterCriteriaForAllFields()
    {
        $criteria = null;

        foreach ($this->dateFieldConfigs as $dateFieldTuple) {
            $singleCriteria = $this->buildTimeSpanFilterCriteria($dateFieldTuple['start'], $dateFieldTuple['end']);

            if ($criteria) {
                $criteria = Criteria::orOp($criteria, $singleCriteria);
            } else {
                $criteria = $singleCriteria;
            }
        }

        return $criteria;
    }



    /**
     * @abstract
     * @param FieldConfig $startField
     * @param FieldConfig $endField
     * @return void
     */
    abstract protected function buildTimeSpanFilterCriteria(FieldConfig $startField, FieldConfig $endField);


    /**
     * Set generic config variables that exist for all filters
     *
     */
    protected function initGenericFilterByTSConfig()
    {
        Assert::isString($this->filterConfig->getSettings('dbTimeFormat'), ['message' => 'No dbTimeFormat defined for filter ' . $this->filterIdentifier . ' 1314579114']);
        ;
        $this->dbTimeFormat = $this->filterConfig->getSettings('dbTimeFormat');

        $this->invert = $this->filterConfig->getInvert();
        $this->buildDateFieldConfigArray();
    }



    /**
     * Build the dateFieldConfigArray
     *
     * @return void
     */
    protected function buildDateFieldConfigArray()
    {
        $fieldIdentifier = $this->filterConfig->getSettings('fieldIdentifier');

        $this->dateFieldConfigs = [];

        if (is_array($fieldIdentifier)) {
            foreach ($fieldIdentifier as $tupleId => $dateFieldTuple) {
                if (!array_key_exists('start', $dateFieldTuple) || !array_key_exists('start', $dateFieldTuple)) {
                    throw new Exception('Found a fieldIdentifier array, but the array was not suitable for a timeSpanFilter. 1314627131');
                }

                $this->dateFieldConfigs[$tupleId] = [
                    'start' => $this->filterConfig->getConfigurationBuilder()->buildFieldsConfiguration()->getFieldConfigByIdentifier($dateFieldTuple['start']),
                    'end' => $this->filterConfig->getConfigurationBuilder()->buildFieldsConfiguration()->getFieldConfigByIdentifier($dateFieldTuple['end'])
                ];
            }
        } else {
            foreach ($this->filterConfig->getFieldIdentifier() as $fieldIdentifierConfig) {
                $this->dateFieldConfigs[] = [
                    'start' => $fieldIdentifierConfig,
                    'end' => $fieldIdentifierConfig
                ];
            }
        }
    }



    /**
     * @return void
     */
    protected function initFilterByTsConfig()
    {
        $defaultValue = $this->filterConfig->getDefaultValue();
        if (is_array($defaultValue)) {
            if ($defaultValue['start']) {
                $this->filterValueStart =  date_create('@' . (int) $defaultValue['start']);
            }
            if ($defaultValue['end']) {
                $this->filterValueStart =  date_create('@' . (int) $defaultValue['end']);
            }
        }
    }



    /**
     * Template method for initializing filter by session data
     */
    protected function initFilterBySession()
    {
        if (array_key_exists('filterValueStart', $this->sessionFilterData) && $this->sessionFilterData['filterValueStart']) {
            $this->filterValueStart = date_create('@' . (int) $this->sessionFilterData['filterValueStart']);
        }
        if (array_key_exists('filterValueEnd', $this->sessionFilterData) && $this->sessionFilterData['filterValueEnd']) {
            $this->filterValueEnd = date_create('@' . (int) $this->sessionFilterData['filterValueEnd']);
        }
    }



    /**
     * Template method for initializing filter by get / post vars
     */
    protected function initFilterByGpVars()
    {
        if (array_key_exists('filterValueStart', $this->gpVarFilterData) && $this->gpVarFilterData['filterValueStart']) {
            $this->filterValueStart = date_create('@' . (int) $this->gpVarFilterData['filterValueStart']);
        }
        if (array_key_exists('filterValueEnd', $this->gpVarFilterData) && $this->gpVarFilterData['filterValueEnd']) {
            $this->filterValueEnd = date_create('@' . (int) $this->gpVarFilterData['filterValueEnd']);
        }
    }



    /**
     * @return array of fieldIdentifiers
     */
    public function getDateFieldsConfigs()
    {
        return $this->dateFieldConfigs;
    }


    /**
     * Persists filter state to session
     *
     * @return array Array of filter data to persist to session
     */
    public function _persistToSession()
    {
        $sessionArray['invert'] = $this->invert;
        if ($this->filterValueStart) {
            $sessionArray['filterValueStart'] = $this->filterValueStart->format('U');
        }
        if ($this->filterValueEnd) {
            $sessionArray['filterValueEnd'] = $this->filterValueEnd->format('U');
        }

        return $sessionArray;
    }



    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/AbstractFilter::reset()
     */
    public function reset()
    {
        $this->filterValueStart = null;
        $this->filterValueEnd = null;
        parent::reset();
    }



    /**
     * @param $fieldIdentifier
     * @return void
     */
    protected function buildFilterCriteria(FieldConfig $fieldIdentifier)
    {
        // This method can not be used in a timeSpan filter
    }


    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/AbstractFilter::setActiveState()
     */
    protected function setActiveState()
    {
        $this->isActive = is_object($this->filterValueStart) && is_object($this->filterValueEnd);
    }



    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/AbstractFilter::initFilter()
     */
    protected function initFilter()
    {
    }



    /**
     * @return array
     */
    public function getValue()
    {
        $returnArray = [];
        if ($this->filterValueStart) {
            $returnArray['filterValueStart'] = $this->filterValueStart->format('U');
        }
        if ($this->filterValueEnd) {
            $returnArray['filterValueEnd'] = $this->filterValueEnd->format('U');
        }

        return $returnArray;
    }



    /**
     * @return string return the combined value as <start>,<end>
     */
    public function getValueCombined()
    {
        return implode(',', $this->getValue());
    }



    /**
     * Getter for FROM filter value
     *
     * @return DateTime
     */
    public function getFilterValueStart()
    {
        return $this->filterValueStart;
    }



    /**
     * Getter for TO filter value
     *
     * @return DateTime
     */
    public function getFilterValueEnd()
    {
        return $this->filterValueEnd;
    }



    /**
     * @return string Filter value in db format
     */
    public function getFilterValueStartInDBFormat()
    {
        return $this->filterValueStart->format($this->dbTimeFormat);
    }



    /**
     * @return string Filter value in db format
     */
    public function getFilterValueEndInDBFormat()
    {
        return $this->filterValueEnd->format($this->dbTimeFormat);
    }
}
