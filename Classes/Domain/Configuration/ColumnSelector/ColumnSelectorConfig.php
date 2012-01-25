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
 * Class implementing configuration for columnSelector
 * 
 * @package Domain
 * @subpackage Configuration\ColumnSelector
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Domain_Configuration_ColumnSelector_ColumnSelectorConfig extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration {

	/**
	 * Hide the columns from the listing which are visible by default
	 * @var boolean
	 */
	protected $hideDefaultVisibleInSelector;


	/**
	 * @var string
	 */
	protected $partialPath;


	/**
	 * @var boolean
	 */
	protected $enabled = false;


	/**
	 * @var boolean
	 */
	protected $persistToSession = false;


	/**
	 * @var boolean
	 */
	protected $onlyShowSelectedColumns = false;


	/**
	 * Init the configuration
	 */
	protected function init() {
		$this->setValueIfExistsAndNotNothing('partialPath');

		$this->setBooleanIfExistsAndNotNothing('hideDefaultVisibleInSelector');
		$this->setBooleanIfExistsAndNotNothing('enabled');
		$this->setBooleanIfExistsAndNotNothing('persistToSession');
		$this->setBooleanIfExistsAndNotNothing('onlyShowSelectedColumns');
	}

	/**
	 * @param boolean $hideDefaultVisibleInSelector
	 */
	public function setHideDefaultVisibleInSelector($hideDefaultVisibleInSelector) {
		$this->hideDefaultVisibleInSelector = $hideDefaultVisibleInSelector;
	}

	/**
	 * @return boolean
	 */
	public function getHideDefaultVisibleInSelector() {
		return $this->hideDefaultVisibleInSelector;
	}

	/**
	 * @param string $partialPath
	 */
	public function setPartialPath($partialPath) {
		$this->partialPath = $partialPath;
	}

	/**
	 * @return string
	 */
	public function getPartialPath() {
		return $this->partialPath;
	}

	/**
	 * @param boolean $enabled
	 */
	public function setEnabled($enabled) {
		$this->enabled = $enabled;
	}

	/**
	 * @return boolean
	 */
	public function getEnabled() {
		return $this->enabled;
	}

	/**
	 * @param boolean $persistToSession
	 */
	public function setPersistToSession($persistToSession) {
		$this->persistToSession = $persistToSession;
	}

	/**
	 * @return boolean
	 */
	public function getPersistToSession() {
		return $this->persistToSession;
	}

	/**
	 * @param boolean $onlyShowSelectedColumns
	 */
	public function setOnlyShowSelectedColumns($onlyShowSelectedColumns) {
		$this->onlyShowSelectedColumns = $onlyShowSelectedColumns;
	}

	/**
	 * @return boolean
	 */
	public function getOnlyShowSelectedColumns() {
		return $this->onlyShowSelectedColumns;
	}

}
?>