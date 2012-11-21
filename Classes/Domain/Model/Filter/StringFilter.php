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
	protected $splitOrToken;


	/**
	 * @var string
	 */
	protected $splitAndToken;


	protected function initFilterByTsConfig() {
		parent::initFilterByTsConfig();

		$settings = $this->filterConfig->getSettings();

		if(array_key_exists('exactMatch', $settings)) {
			$this->exactMatch = (int) $this->filterConfig->getSettings('exactMatch') == 1 ? TRUE : FALSE;
		}

		if(array_key_exists('splitOrToken', $settings) && $settings['splitOrToken']) {
			$token = $settings['splitOrToken'];
			$this->splitOrToken = (substr($token,0,1) == '|' && substr($token,-1,1) == '|') ? substr($token,1,-1) : $token;
		}

		if(array_key_exists('splitAndToken', $settings) && $settings['splitAndToken']) {
			$token = $settings['splitAndToken'];
			$this->splitAndToken = (substr($token,0,1) == '|' && substr($token,-1,1) == '|') ? substr($token,1,-1) : $token;
		}
	}


	/**
     * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier
     * @return null|Tx_PtExtlist_Domain_QueryObject_SimpleCriteria
     */
    protected function buildFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier) {
    	
    	if ($this->filterValue == '') return NULL; 

    	$fieldName = Tx_PtExtlist_Utility_DbUtils::getSelectPartByFieldConfig($fieldIdentifier);

        if($this->exactMatch) {
            $criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::equals($fieldName, $this->filterValue);
        } else {
            $filterValue = '%'.$this->filterValue.'%';
    	    $criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::like($fieldName, $filterValue);
        }

    	return $criteria;
    }


	protected function prepareFilterValue($filterValue) {

		if($this->filterConfig->getSettings('splitOr')) {

		}

	}
}
?>