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
	protected $rendererSettings;
	
	
	/**
	 * @var boolean 
	 */
	protected $enabled;

	
	/**
	 * Name of the renderer Class name
	 * @var string
	 */
	protected $rendererClassName;
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Renderer_RenderConfigCollection
	 */
	protected $renderConfigCollection;
	
	
	/**
	 * Build the configuration object
	 *
	 * @param array $settings
	 */
	public function __construct(array $rendererSettings) {
		$this->rendererSettings = $rendererSettings;

		tx_pttools_assert::isNotEmptyString($rendererSettings['rendererClassName'],array('message' => 'No class name given for renderer. 1280408323'));
		tx_pttools_assert::isTrue(class_exists($rendererSettings['rendererClassName']), array('message' => 'Given pager class ' . $rendererSettings['rendererClassName'] . ' does not exist or is not loaded! 1279541306'));
		
		$this->initPropertiesFromSettings($rendererSettings);
	}
	
	
	/**
	 * Init additional (optional) properties
	 * @param array $settings
	 */
	protected function initPropertiesFromSettings($rendererSettings) {
			
		if(array_key_exists('enabled', $rendererSettings)) {
			$this->enabled = $settings['enabled'] == 1 ? true : false;
		}
	}
	
	
	/**
	 * @return array settings
	 */
	public function getSettings() {
		return $this->rendererSettings;
	}
	

	
	/**
	 * @return boolean enables
	 */
	public function isEnabled() {
		return $this->enabled;
	}
	
	
	
	/**
	 * @return string rendererClassName
	 */
	public function getRendererClassName() {
		return $this->rendererClassName;
	}
}

?>