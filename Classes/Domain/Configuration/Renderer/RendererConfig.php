<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de
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
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Domain
 * @subpackage Configuration\Renderer
 */
class Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig {


	/**
	 * Renderer settings
	 * @var array 
	 */
	protected $settings;
	
	
	/**
	 * @var boolean 
	 */
	protected $enabled;

	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Renderer_RenderConfigCollection
	 */
	protected $renderConfigCollection;
	
	
	/**
	 * Build the configuration object
	 *
	 * @param array $settings
	 */
	public function __construct(array $settings) {
		$this->settings = $settings;
				
		$this->initPropertiesFromSettings($settings);
	}
	
	
	
	protected function initPropertiesFromSettings($settings) {
			
		if(array_key_exists('enabled', $settings)) {
			$this->enabled = $settings['enabled'] == 1 ? true : false;
		}
	}
	
	

	public function getSettings() {
		return $this->settings;
	}
	

	
	
	public function isEnabled() {
		return $this->enabled;
	}
	
	
	
	public function getRendererClassName() {
		return $this->rendererClassName;
	}
}

?>