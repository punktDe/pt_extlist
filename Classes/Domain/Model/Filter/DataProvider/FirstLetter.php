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
 * Implements data provider for grouped list data
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
class Tx_PtExtlist_Domain_Model_Filter_DataProvider_FirstLetter extends  Tx_PtExtlist_Domain_Model_Filter_DataProvider_GroupData {

	/**
	 * Build the group data query to retrieve the group data
	 * 
	 * @param array Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fields
	 */
	protected function buildGroupDataQuery($fields) {
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
        										'selected' => false);
        }

        return $renderedOptions;
	}
	
	
	
	/**
	 * Render a single option line by cObject or default
	 *
	 * @param array $optionData
	 */
	protected function renderOptionData($optionData) {
		$option = Tx_PtExtlist_Utility_RenderValue::renderByConfigObjectUncached($optionData, $this->filterConfig);
		return $option;
	}
	
	
}
?>