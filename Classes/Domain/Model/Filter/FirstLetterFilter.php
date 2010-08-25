<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * Class implements the firstLetter filter
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Model_Filter_FirstLetterFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractGroupDataFilter {	
	
	/**
	 * Returns an array of options to be displayed by filter
	 * for a given array of fields
	 *
	 * @param array Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 * @return array Options to be displayed by filter
	 */
	protected function getOptionsByFields($fields) {
		$groupDataQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
		
		reset($this->displayFields);
		$displayField = current($this->displayFields);	
				
        if ($this->additionalTables != '') {
           $groupDataQuery->addFrom($this->additionalTables);
        }
        
        //TODO only works with SQL!
        $groupDataQuery->addField(sprintf('UPPER(LEFT(%1$s,1)) as firstLetter', $displayField->getTableFieldCombined()));
        $groupDataQuery->addSorting('firstLetter', Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
        
        if($this->showRowCount) {
        	// TODO only works with SQL!
        	$groupDataQuery->addField(sprintf('count("%s") as rowCount', $this->filterField->getTableFieldCombined()));
			$groupDataQuery->addGroupBy('firstLetter'); 
        }

        $excludeFiltersArray = $this->buildExcludeFiltersArray();
        
        $options = $this->dataBackend->getGroupData($groupDataQuery, $excludeFiltersArray);
        
        $namedOptions = array();
        foreach($options as $option) {
        	$key = $option['firstLetter'] . '%';
        	$namedOptions[$key] = $option;
        }       
        return $namedOptions;
	}
	
	/**
	 * Returns an associative array of options 
	 * option[key] = array(value => '...', selected =
	 *
	 * @return array
	 */
	public function getOptions() {
		$renderedOptions = parent::getOptions();
		$optionsArray = array();
		foreach($renderedOptions as $optionKey => $optionValue) {
			$selected = in_array($optionKey, $this->filterValues)  ? true : false;
			$optionsArray[$optionKey] = array('value' => $optionValue,
											  'selected' => $selected);
		}
		return $optionsArray;
		
	}
	
	
}