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
 * Class implements an abstract configuration object
 *
 * @package Domain
 * @subpackage Configuration
 * @author Michael Knoll <knoll@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 */
abstract class Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration extends Tx_PtExtlist_Domain_Configuration_AbstractConfiguration {

	/**
	 * The listidentifier this config object belings to
	 * 
	 * @var string
	 */
	protected $listIdentifier;
	
	
	/**
	 * Injects configurationbuilder
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function injectConfigurationBuilder(Tx_PtExtlist_Domain_Configuration_AbstractConfigurationBuilder $configurationBuilder) {
		$this->configurationBuilder = $configurationBuilder;
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
	}
	
	
	
	/**
	 * Constructor for configuration object
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_AbstractConfigurationBuilder $configurationBuilder
	 * @param array $settings
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_AbstractConfigurationBuilder $configurationBuilder, array $settings = array()) {
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
		parent::__construct($configurationBuilder, $settings);
	}
	
	
	/**
	 * @return string listIdentifier
	 */
	public function getListIdentifier() {
		return $this->listIdentifier;
	}
}
?>