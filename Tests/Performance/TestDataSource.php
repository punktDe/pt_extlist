<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
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
 * Test DataSource for generating test data
 * 
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Tests\Performance\TestDataBackend
 *
 */
class Tx_PtExtlist_Tests_Performance_TestDataSource implements Tx_PtExtlist_Domain_DataBackend_DataSource_IterationDataSourceInterface {

	/**
	 * @var int
	 */
	protected $rowCount = 10;


	/**
	 * @var int
	 */
	protected $colCount = 10;


	/**
	 * @var array
	 */
	protected $data;


	public function __construct($rowCount = 10, $colCount = 10) {
		$this->rowCount = $rowCount;
		$this->colCount = $colCount;
		$this->data = $this->buildData();
	}



	protected function buildData() {
		$rawData = array();

		for($i = 0; $i < $this->rowCount; $i++) {
			for($j = 1; $j <= $this->colCount; $j++) {
				$rawData[$i]['field' . $j] = "Testdaten aus der Koordinate $i:$j" ;
			}
		}

		return $rawData;
	}


	/**
	 * @return array
	 */
	public function fetchAll() {
		return $this->data;
	}


	/**
	 * Return data row as array
	 *
	 * @return array
	 */
	public function fetchRow() {
		return current($this->data);
		next($this->data);
	}



	/**
	 * Return record count
	 *
	 * @return int
	 */
	public function count() {
		return count($this->data);
	}


	/**
	 * Rewind the cursor to the first row
	 */
	public function rewind() {
		reset($this->data);
	}


}

?>