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
 * Class implements configuration for pager
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_Pager_PagerConfiguration {
	

	/**
	 * Holds an array of settings for pager
	 *
	 * @var array
	 */
	protected $settings = array();
	
	
	/**
	 * Identifier of list to which this filter belongs to
	 *
	 * @var string
	 */
	protected $listIdentifier;
	
	
	
	/**
	 * Holds class name for pager
	 *
	 * @var string
	 */
	protected $pagerClassName = '';
	
	
	
	/**
	 * Constructor for pager configuration
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$pagerSettings = $configurationBuilder->getPagerSettings();
		
		if (!is_array($pagerSettings)) {
    		throw new Exception('No pager configuration available for list ' . $this->getListIdentifier() . '. 1280408324');
    	}
		
		tx_pttools_assert::isNotEmptyString($pagerSettings['pagerClassName'],array('message' => 'No class name given for pager settings 1280408323'));
		$this->settings = $pagerSettings;
		
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
		$this->pagerClassName = $pagerSettings['pagerClassName'];
	}
	
	
	/**
	 * Returns class name of pager
	 *
	 * @return string Class name of pager
	 */
	public function getPagerClassName() {
		return $this->pagerClassName;
	}
	
	
	
	/**
	 * Returns settings array of pager
	 *
	 * @return array Array with settings for pager
	 */
	public function getPagerSettings() {
		return $this->settings;
	}
	
	/**
	 * @return string
	 */
	public function getListIdentifier() {
		return $this->listIdentifier;
	}
}
?>