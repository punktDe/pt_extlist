<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Class implementing configuration for the objectMapper
 * 
 * @package Domain
 * @subpackage Configuration\Column
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Domain_Configuration_Columns_ObjectMapper_ObjectMapperConfig extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration {

	/**
	 * The class of the target object
	 * @var string
	 */
	protected $class;


	/**
	 * @var array
	 */
	protected $mapping;


	/**
	 * Init the configuration
	 */
	protected function init() {
		$this->setRequiredValue('class', 'No class for target object given. 1328218072');
		if(!class_exists($this->class)) {
			throw new Exception('The class ' . $this->class . ' could not be loaded. 1328218148');
		}

		if(array_key_exists('mapping',$this->settings) && is_array($this->settings['mapping'])) {
			$this->mapping = $this->settings['mapping'];
		}
	}

	/**
	 * @param string $class
	 */
	public function setClass($class) {
		$this->class = $class;
	}

	/**
	 * @return string
	 */
	public function getClass() {
		return $this->class;
	}

	/**
	 * @param array $mapping
	 */
	public function setMapping($mapping) {
		$this->mapping = $mapping;
	}

	/**
	 * @return array
	 */
	public function getMapping() {
		return $this->mapping;
	}
}
?>