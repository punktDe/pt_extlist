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
	 * Returns an array of options to be displayed by filter
	 * for a given array of fields
	 *
	 * @param array Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 * @return array Options to be displayed by filter
	 */
	protected function getRenderedOptionsByFields($fields) {
		$options =& $this->getOptionsByFields($fields);
		
        foreach($options as $optionData) {
        	$optionKey = $optionData[$this->filterField->getIdentifier()];
        	$renderedOptions[$optionKey] =  $this->renderOptionData($optionData);
        }
        
        return $renderedOptions;
	}
	
	
	/**
	 * Add inactiveFilterOpotion to rendered options
	 * 
	 * @param array $renderedOptions
	 */
	protected function addInactiveOption(&$renderedOptions) {
        
		if($this->filterConfig->getInactiveOption()) {
        	$renderedOptions[''] = $this->filterConfig->getInactiveOption();
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