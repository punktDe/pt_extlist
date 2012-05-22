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
 * Implements data provider for grouped list data
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
class Tx_PtExtlist_Domain_Model_Filter_DataProvider_FirstLetter extends Tx_PtExtlist_Domain_Model_Filter_DataProvider_GroupData {

	/**
	 * Build the group data query to retrieve the group data
	 * 
	 * @param array Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fields
	 * @return string
	 */
	protected function buildGroupDataQuery($fields) {
		$groupDataQuery = new Tx_PtExtlist_Domain_QueryObject_Query();
		
		$displayField = $this->displayFields->getItemByIndex(0);	
				
        if ($this->additionalTables != '') {
           $groupDataQuery->addFrom($this->additionalTables);
        }
        
        //TODO only works with SQL!
        $groupDataQuery->addField(sprintf('UPPER(LEFT(%1$s,1)) as firstLetter', $displayField->getTableFieldCombined()));
        $groupDataQuery->addSorting('firstLetter', Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC);
        
        if($this->showRowCount) {
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
	 * @param array Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 * @return array Options to be displayed by filter
	 */
	protected function getRenderedOptionsByFields($fields) {
		
		$options =& $this->getOptionsByFields($fields);

        foreach($options as $optionData) {
        	$optionKey = $optionData['firstLetter'];
        	
        	$renderedOptions[$optionKey] = array('value' => $this->renderOptionData($optionData),
												'hasRecords' => TRUE,
        										'selected' => FALSE);
        }


		
		$missingLetters = $this->getMissingLetters();
		if($missingLetters) {
			$renderedOptions = $this->addMissingLetters($renderedOptions, $missingLetters);
		}


        return $renderedOptions;
	}



	/**
	 * @param $renderedOptions
	 * @param $missingLetters
	 * @return array
	 */
	protected function addMissingLetters($renderedOptions, $missingLetters) {

		foreach($missingLetters as $letter) {

			if(!array_key_exists($letter, $renderedOptions)) {

				$renderedOptions[$letter] = array('value' => $this->renderOptionData(array('firstLetter' => $letter)),
					'hasRecords' => FALSE,
					'selected' => FALSE);
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
	protected function getMissingLetters() {
		$missingLettersString = $this->filterConfig->getSettings('addLettersIfMissing');
		if($missingLettersString) {
			return t3lib_div::trimExplode(',', $missingLettersString);
		} else {
			return NULL;
		}
	}


	/**
	 * Render a single option line by cObject or default
	 *
	 * @param array $optionData
	 * @return string
	 */
	protected function renderOptionData($optionData) {
		$option = Tx_PtExtlist_Utility_RenderValue::renderByConfigObjectUncached($optionData, $this->filterConfig);
		return $option;
	}
}
?>