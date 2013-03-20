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
 * Class implements a string filter
 *
 * @package Domain
 * @subpackage Model\Filter
 * @author Daniel Lienert 
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_Model_Filter_StringFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractSingleValueFilter {

	/**
	 * @var boolean
	 */
	protected $exactMatch = FALSE;


	/**
	 * @var string
	 */
	protected $orToken;


	/**
	 * @var string
	 */
	protected $andToken;


	protected function initFilterByTsConfig() {
		parent::initFilterByTsConfig();

		$settings = $this->filterConfig->getSettings();

		if(array_key_exists('exactMatch', $settings)) {
			$this->exactMatch = (int) $this->filterConfig->getSettings('exactMatch') == 1 ? TRUE : FALSE;
		}

		if(array_key_exists('orToken', $settings) && $settings['orToken']) {
			$token = $settings['orToken'];
			$this->orToken = (substr($token,0,1) == '|' && substr($token,-1,1) == '|') ? substr($token,1,-1) : $token;
		}

		if(array_key_exists('andToken', $settings) && $settings['andToken']) {
			$token = $settings['andToken'];
			$this->andToken = (substr($token,0,1) == '|' && substr($token,-1,1) == '|') ? substr($token,1,-1) : $token;
		}
	}



	/**
     * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier
     * @return null|Tx_PtExtlist_Domain_QueryObject_SimpleCriteria
     */
    protected function buildFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier) {
    	
    	if ($this->filterValue == '') return NULL; 


    	$fieldName = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($fieldIdentifier);

		if($this->orToken || $this->andToken) {
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
	 * @return null|Tx_PtExtlist_Domain_QueryObject_AndCriteria|Tx_PtExtlist_Domain_QueryObject_OrCriteria|Tx_PtExtlist_Domain_QueryObject_SimpleCriteria
	 */
	protected function buildOrCriteria($fieldName, $orLevelArray) {

		$criteria = NULL;

		foreach ($orLevelArray as $andLevelArray) {
			$singleORCriteria = $this->buildAndCriteria($fieldName, $andLevelArray);

			if ($criteria) {
				$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::orOp($criteria, $singleORCriteria);
			} else {
				$criteria = $singleORCriteria;
			}
		}

		return $criteria;
	}



	/**
	 * @param $fieldName
	 * @param $andLevelArray
	 * @return null|Tx_PtExtlist_Domain_QueryObject_AndCriteria|Tx_PtExtlist_Domain_QueryObject_SimpleCriteria
	 */
	public function buildAndCriteria($fieldName, $andLevelArray) {

		$criteria = NULL;

		foreach ($andLevelArray as $singleValue) {
			$singleAndCriteria = $this->buildFilterCriteriaForSingleValue($fieldName, $singleValue);

			if ($criteria) {
				$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::andOp($criteria, $singleAndCriteria);
			} else {
				$criteria = $singleAndCriteria;
			}
		}

		return $criteria;
	}



	/**
	 * @param $fieldName
	 * @param $filterValue
	 * @return Tx_PtExtlist_Domain_QueryObject_SimpleCriteria
	 */
	protected function buildFilterCriteriaForSingleValue($fieldName, $filterValue) {

		if($this->exactMatch) {
			$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::equals($fieldName, $filterValue);
		} else {
			$filterValue = '%'.$filterValue.'%';
			$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::like($fieldName, $filterValue);
		}

		return $criteria;
	}



	/**
	 * Creates an array of splitted or / and parts from a filterValue
	 *
	 * @param $filterValue
	 * @return array|mixed
	 */
	protected function prepareFilterValue($filterValue) {

		/*
		 * Changes to use space as or token
		 */
		$filterValue = str_replace('  ', ' ', $filterValue);
		if($this->andToken) {
			$filterValue = str_replace(array(' ' . $this->andToken, ' ' . $this->andToken . ' ', $this->andToken . ' '), $this->andToken, $filterValue);
		}
		if($this->orToken) {
			$filterValue = str_replace(array(' ' . $this->orToken, ' ' . $this->orToken . ' ', $this->orToken . ' '), $this->orToken, $filterValue);
		}

		/*
		 * Explode to array structure
		 */
		if($this->orToken && !$this->andToken) {
			$valueArray = t3lib_div::trimExplode($this->orToken, $filterValue);
			foreach($valueArray as &$value) {
				$value = array($value);
			}
		} elseif (!$this->orToken && $this->andToken) {
			$valueArray = array(t3lib_div::trimExplode($this->andToken, $filterValue));
		} elseif($this->orToken && $this->andToken) {
			$valueArray = t3lib_div::trimExplode($this->orToken, $filterValue);

			foreach($valueArray as &$orValue) {
				$orValue = t3lib_div::trimExplode($this->andToken, $orValue);
			}

		} else {
			$valueArray = array($filterValue);
		}

		return $valueArray;
	}
}
?>