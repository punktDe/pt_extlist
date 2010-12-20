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
 * @subpackage Configuration\Extension
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_Extension_ExtensionConfig extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration {

	/**
	 * Configure the list to store the state in sessions or in the database
	 * 
	 * @var boolean
	 */
	protected $useSession = true;
	
	
	/**
	 * Indicates if the list is in cached mode
	 * 
	 * @var boolean 
	 */
	protected $cachedMode = false;
	
	
	/**
	 * The pluginName (Pi1, Cached)
	 * 
	 * @var String
	 */
	protected $pluginName = 'Pi1';
	
	
	
	/**
	 * Set the properties
	 */
	protected function init() {
		$this->settings['pluginName'] = $this->determinePluginName();
		$this->setValueIfExists('pluginName');
		
		if($this->pluginName == 'Cached') {
			$this->cachedMode = true;
			$this->useSession = false;
		} else {
			$this->cachedMode = false;
			$this->useSession = true;
		}
	}

	
	
	/**
	 * Determine the plugin name
	 * TODO: Find out how we can get the name directly from typo3/dispatcher !!
	 * 
	 */
	protected function determinePluginName() {
		$frameWorkKonfiguration = Tx_Extbase_Dispatcher::getExtbaseFrameworkConfiguration();
		return $frameWorkKonfiguration['pluginName'];
	}
	

	/**
	 * @return boolean
	 */
	public function useSession() {
		return $this->useSession;
	}
	
	
	
	/**
	 * @return boolean
	 */
	public function isCached() {
		return $this->cachedMode;
	}
	
	
	
	/**
	 * Get the plugin name
	 * 
	 * @return string 
	 */
	public function getPluginName() {
		return $this->pluginName;
	}
}
?>