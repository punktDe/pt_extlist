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
 * Class implements configuration for list defaults
 *
 * @package Domain
 * @subpackage Configuration\List
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_List_ListConfig {

	/**
	 * Holds list identifier of current list
	 *
	 * @var string
	 */
	protected $listIdentifier;
	
	
	/**
	 * @var array
	 */
	protected $settings;
	
	
	/**
	 * Configure the list to store the state in sessions or use GEt-Vars only
	 * 
	 * @var boolean
	 */
	protected $useSessions = true;
	
	
	
	/**
	 * @var string 
	 */
	protected $headerPartial;
	
	
	/**
	 * @var string
	 */
	protected $bodyPartial;
	
	
	/**
	 * @var string headerPartial
	 */
	protected $agregateRowsPartial;
	
	
	/**
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
		$this->settings = $configurationBuilder->getListSettings();
		$this->initPropertiesFromSettings();
	}
	
	
	
	/**
	 * Set the properties
	 */
	protected function initPropertiesFromSettings() {
			
		if(array_key_exists('headerPartial', $this->settings)) {
			$this->headerPartial = $this->settings['headerPartial'];
		}
			
		if(array_key_exists('bodyPartial', $this->settings)) {
			$this->bodyPartial = $this->settings['bodyPartial'];
		}
			
		if(array_key_exists('agregateRowsPartial', $this->settings)) {
			$this->agregateRowsPartial = $this->settings['agregateRowsPartial'];
		}
		
		if(array_key_exists('useSessions', $this->settings)) {
			$this->useSessions = $this->settings['useSessions'];
		}
	}
	
	

	/**
	 * @return string
	 */
	public function getHeaderPartial() {
		return $this->headerPartial;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getBodyPartial() {
		return $this->bodyPartial;		
	}
	
	
	
	/**
	 * @return string
	 */
	public function getAgregateRowsPartial() {
		return $this->agregateRowsPartial;
	}
	
	
	
	/**
	 * @return boolean use Sessions
	 */
	public function useSessions() {
		return $this->useSessions;
	}
}
?>