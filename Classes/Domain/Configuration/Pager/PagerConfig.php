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
 * @package pt_extlist
 * @subpackage Domain\Configuration\Pager
 * @author Michael Knoll <knoll@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_Pager_PagerConfig {
		
	/**
	 * Identifier of list to which this filter belongs to
	 *
	 * @var string
	 */
	protected $listIdentifier;
	
	
	/**
	 * The pager identifier.
	 * 
	 * @var string
	 */
	protected $pagerIdentifier;
	
	
	/**
	 * Holds class name for pager
	 *
	 * @var string
	 */
	protected $pagerClassName = '';
	
	
	/**
	 * If true, pager is enabled
	 *
	 * @var boolean
	 */
	protected $enabled;
	
	
	/**
	 * Holds the templatePath to override default extbase settings.
	 * 
	 * @var string
	 */
	protected $templatePath = NULL;
	
	
	/**
	 * Global value for all filters
	 * 
	 * @var integer
	 */
	protected $itemsPerPage;
	
	
	/** 
	 * Enter description here ...
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @param string $pagerIdentifier
	 * @param array $pagerSettings
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $pagerIdentifier,  array $pagerSettings) {

		tx_pttools_assert::isNotEmptyString($pagerSettings['pagerClassName'],array('message' => 'No class name given for pager "' . $pagerIdentifier . '" 1280408323'));
		tx_pttools_assert::isTrue(class_exists($pagerSettings['pagerClassName']), array('message' => 'Given pager class ' . $pagerSettings['pagerClassName'] . ' does not exist or is not loaded! 1279541306'));
		
		tx_pttools_assert::isNotEmptyString($pagerSettings['templatePath'],array('message' => 'No template path given for pager "' . $pagerIdentifier . '" 1283377261'));
		
		$this->settings = $pagerSettings;
		$this->pagerIdentifier = $pagerIdentifier;
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
		$this->pagerClassName = $pagerSettings['pagerClassName'];
		$this->templatePath = $pagerSettings['templatePath'];
		
		$this->setOptionalSettings($pagerSettings);
	}
	
	
	/**
	 * Set the optional pager Settings
	 * 
	 * @param array $pagerSettings
	 */
	protected function setOptionalSettings(array $pagerSettings) {
		if(array_key_exists('enabled', $pagerSettings)) {
			$this->enabled = $pagerSettings['enabled'] == 1 ? true : false;
		}

		if(array_key_exists('itemsPerPage', $pagerSettings)) {
			$this->itemsPerPage = $pagerSettings['itemsPerPage'];
		}
	
	}
	
	
	/**
	 * Returns the pager identifier.
	 * 
	 * @return string
	 */
	public function getPagerIdentifier() {
		return $this->pagerIdentifier;
	}
	
	
	
	
	public function getTemplatePath() {
		return $this->templatePath;
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
	
	
	
	/**
	 * Returns true, if pager is enabled
	 *
	 * @return bool True, if pager is enabled
	 */
	public function getEnabled() {
		return $this->enabled;
	}
	
	
	
	/**
	 * @return integer itemsPerPage
	 */
	public function getItemsPerPage() {
		return $this->itemsPerPage;
	}
}
?>