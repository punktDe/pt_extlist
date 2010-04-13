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
 * Abstract filter class for all pt_extlist filter models
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
abstract class Tx_PtExtlist_Domain_Model_Filter_AbstractFilter implements Tx_PtExtlist_Domain_Model_Filter_FilterInterface {
	
	/**
	 * Identifier of this filter
	 *
	 * @var String
	 */
	protected $filterIdentifier;
	
	
	
	/**
	 * Holds a filter configuration for this filter
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig
	 */
	protected $filterConfig;
	
	
	
	/**
	 * Constructor for filter
	 *
	 * @param String $filterIdentifier     Identifier for filter
	 */
	public function __construct($filterIdentifier) {
		// TODO use assertion message
		tx_pttools_assert::isNotEmptyString($filterIdentifier); // filter identifier must not be empty!
		$this->filterIdentifier = $filterIdentifier;
	}
	
	
	
	/**
	 * Injects filter configuration for this filter
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig
	 */
	public function injectFilterConfig(Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig) {
		$this->filterConfig = $filterConfig;
	}
	
	
	
	/**
	 * Returns filter identifier
	 *
	 * @return string
	 */
	public function getFilterIdentifier() {
		return $this->filterIdentifier;
	}
	
}

?>