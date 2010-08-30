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
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>, Daniel Lienert <lienert@punkt.de>
 * @package pt_extlist
 * @subpackage Domain\Configuration\Renderer
 */
class Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfiguration {

	/**
	 * TODO add some comment!
	 *
	 * @var array
	 */
	protected $settings = array();
	
	protected $specialRow = NULL;
	
	protected $specialCell = NULL;
	
	protected $rendererClassName;
	
	/**
	 * @var boolean 
	 */
	protected $enabled;

	
	/**
	 * TODO add some comment!
	 *
	 * @param array $settings
	 */
	public function __construct(array $settings) {
		$this->settings = $settings;
		
		tx_pttools_assert::isNotEmpty($settings['rendererClassName'], array('message' => 'No className for the renderer configured. 1280236277'));
		tx_pttools_assert::isTrue(class_exists($settings['rendererClassName']),array('message' => 'Class name '.$className.' does not exist. 1280236512'));
		$this->rendererClassName = $settings['rendererClassName'];
		
		$this->addOptionalSettings($settings);
	}
	
	
	
	protected function addOptionalSettings($settings) {
		
		if(array_key_exists('specialRow', $settings)) {
			$this->specialRow = $settings['specialRow'];
		}
		
		if(array_key_exists('specialCell', $settings)) {
			$this->specialCell = $settings['specialCell'];
		}
		
		if(array_key_exists('enabled', $settings)) {
			$this->enabled = $settings['enabled'] == 1 ? true : false;
		}
	}
	
	

	public function getSettings() {
		return $this->settings;
	}
	
	
	
	public function getSpecialRow() {
		return $this->specialRow;
	}
	
	
	
	public function getSpecialCell() {
		return $this->specialCell;
	}
	
	
	
	public function isEnabled() {
		return $this->enabled;
	}
	
	
	
	public function getRendererClassName() {
		return $this->rendererClassName;
	}
}

?>