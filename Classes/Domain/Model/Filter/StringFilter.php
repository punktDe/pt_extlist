<?php

namespace PunktDe\PtExtlist\Domain\Model\Filter;

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

use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig;
use PunktDe\PtExtlist\Domain\QueryObject\AndCriteria;
use PunktDe\PtExtlist\Domain\QueryObject\Criteria;
use PunktDe\PtExtlist\Domain\QueryObject\OrCriteria;
use PunktDe\PtExtlist\Domain\QueryObject\SimpleCriteria;
use PunktDe\PtExtlist\Utility\DbUtils;

/**
 * Class implements a string filter
 *
 * @package Domain
 * @subpackage Model\Filter
 * @author Daniel Lienert
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Model_Filter_StringFilterTest
 */
class StringFilter extends AbstractSingleValueFilter
{
    /**
     * @var boolean
     */
    protected $exactMatch = false;



    /**
     * @var string
     */
    protected $orToken;



    /**
     * @var string
     */
    protected $andToken;


    /**
     * @var boolean
     */
    protected $treatValueAsString = false;



    protected function initFilterByTsConfig()
    {
        parent::initFilterByTsConfig();

        $settings = $this->filterConfig->getSettings();

        if (array_key_exists('exactMatch', $settings)) {
            $this->exactMatch = (int)$this->filterConfig->getSettings('exactMatch') == 1 ? true : false;
        }

        if (array_key_exists('orToken', $settings) && $settings['orToken']) {
            $token = $settings['orToken'];
            $this->orToken = (substr($token, 0, 1) == '|' && substr($token, -1, 1) == '|') ? substr($token, 1, -1) : $token;
        }

        if (array_key_exists('andToken', $settings) && $settings['andToken']) {
            $token = $settings['andToken'];
            $this->andToken = (substr($token, 0, 1) == '|' && substr($token, -1, 1) == '|') ? substr($token, 1, -1) : $token;
        }
    }



    /**
     * @param FieldConfig $fieldIdentifier
     * @return null|SimpleCriteria
     */
    protected function buildFilterCriteria(FieldConfig $fieldIdentifier)
    {
        if ($this->filterValue == '') {
            return null;
        }

        $this->treatValueAsString = $fieldIdentifier->getTreatValueAsString();

        $fieldName = DbUtils::getSelectPartByFieldConfig($fieldIdentifier);

        if ($this->orToken || $this->andToken) {
            $filterValueArray = $this->prepareFilterValue($this->filterValue);
            $criteria = $this->buildOrCriteria($fieldName, $filterValueArray);
        } else {
            $criteria = $this->buildFilterCriteriaForSingleValue($fieldName, $this->filterValue);
        }

        return $criteria;
    }



    /**
     * @param $fieldName
     * @param $orLevelArray
     * @return null|AndCriteria|OrCriteria|SimpleCriteria
     */
    protected function buildOrCriteria($fieldName, $orLevelArray)
    {
        $criteria = null;

        foreach ($orLevelArray as $andLevelArray) {
            $singleORCriteria = $this->buildAndCriteria($fieldName, $andLevelArray);

            if ($criteria) {
                $criteria = Criteria::orOp($criteria, $singleORCriteria);
            } else {
                $criteria = $singleORCriteria;
            }
        }

        return $criteria;
    }



    /**
     * @param $fieldName
     * @param $andLevelArray
     * @return null|AndCriteria|SimpleCriteria
     */
    public function buildAndCriteria($fieldName, $andLevelArray)
    {
        $criteria = null;

        foreach ($andLevelArray as $singleValue) {
            $singleAndCriteria = $this->buildFilterCriteriaForSingleValue($fieldName, $singleValue);

            if ($criteria) {
                $criteria = Criteria::andOp($criteria, $singleAndCriteria);
            } else {
                $criteria = $singleAndCriteria;
            }
        }

        return $criteria;
    }



    /**
     * @param $fieldName
     * @param $filterValue
     * @return SimpleCriteria
     */
    protected function buildFilterCriteriaForSingleValue($fieldName, $filterValue)
    {
        if ($this->exactMatch) {
            $criteria = Criteria::equals($fieldName, $filterValue, $this->treatValueAsString);
        } else {
            $filterValue = '%' . $filterValue . '%';
            $criteria = Criteria::like($fieldName, $filterValue);
        }

        return $criteria;
    }



    /**
     * Creates an array of splitted or / and parts from a filterValue
     *
     * @param $filterValue
     * @return array|mixed
     */
    protected function prepareFilterValue($filterValue)
    {

        /*
         * Changes to use space as or token
         */
        $filterValue = str_replace('  ', ' ', $filterValue);
        if ($this->andToken) {
            $filterValue = str_replace([' ' . $this->andToken, ' ' . $this->andToken . ' ', $this->andToken . ' '], $this->andToken, $filterValue);
        }
        if ($this->orToken) {
            $filterValue = str_replace([' ' . $this->orToken, ' ' . $this->orToken . ' ', $this->orToken . ' '], $this->orToken, $filterValue);
        }

        /*
         * Explode to array structure
         */
        if ($this->orToken && !$this->andToken) {
            $valueArray = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode($this->orToken, $filterValue);
            foreach ($valueArray as &$value) {
                $value = [$value];
            }
        } elseif (!$this->orToken && $this->andToken) {
            $valueArray = [\TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode($this->andToken, $filterValue)];
        } elseif ($this->orToken && $this->andToken) {
            $valueArray = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode($this->orToken, $filterValue);

            foreach ($valueArray as &$orValue) {
                $orValue = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode($this->andToken, $orValue);
            }
        } else {
            $valueArray = [$filterValue];
        }

        return $valueArray;
    }
}
