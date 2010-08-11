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
 * Class implements a select filter
 * 
 * @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Model_Filter_SelectFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractGroupDataFilter {	
	
	/**
	 * Multiple or dropdown
	 * @var integer
	 */
	protected $multiple;
	
	

	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilterByTsConfig()
	 *
	 */
	protected function initFilterByTsConfig() {
		parent::initFilterByTsConfig();
	    
		if ($this->filterConfig->getSettings('multiple')) {
        	$this->multiple = $this->filterConfig->getSettings('multiple');
        }        
	}
	
	
	
	/**
	 * Returns an array expected by f:form.select viewhelper:
	 * 
	 * array(<keyForReturnAsSelectedValue> => <valueToBeShownAsSelectValue>)
	 *
	 * @param array $groupData 
	 * @return array
	 */
	protected function getRenderedOptionsByGroupData(array $groupData = array()) {
	   $renderedOptions = array();

	   foreach($groupData as $option) {
            // TODO Add render-option to TS config for this stuff here
            $key = '';
            list($filterTable, $filterField) = explode('.', $this->filterField);
            $key .= $option[$filterField];
            $value = '';
            foreach($this->displayFields as $displayField) {
                list($displayTable, $displayField) = explode('.', trim($displayField));
                $value .= $option[$displayField];
            }
            
            if($this->showRowCount) {
            	$value .= ' (' . $option['rowCount'] . ')';
            }        	    
 
            $renderedOptions[$key] = $value;
        }
        
        return $renderedOptions;
	}

	
	/**
	 * 
	 * Multiple or dropdown select
	 * @return integer
	 */
	public function getMultiple() {
		return $this->multiple;
	}
}