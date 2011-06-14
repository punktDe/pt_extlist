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
 * Class implements a select filter
 * 
 * @author Michael Knoll, Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_SelectFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter {	
	
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
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter::getOptions()
	 */
	public function getOptions() {
		$options = parent::getOptions();

		$selectOptions = array();
		foreach($options as $optionKey => $optionValue) {
			$selectOptions[$optionKey] = $optionValue['value'];
		}
		
		return $selectOptions;
	}
	
	
	
	/**
	 * Returns value of selected option, return an array always
	 *
	 * @return array
	 */
	public function getValue() {
		return $this->multiple ? $this->filterValues : current($this->filterValues);
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

?>