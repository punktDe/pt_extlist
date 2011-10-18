<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Joachim Mathes
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
 * Class implements a date picker filter
 *
 * @package Domain
 * @subpackage Model\Filter\TimeSpanAlgorithm
 * @author Joachim Mathes
 */
class Tx_PtExtlist_Domain_Model_Filter_TimeSpanAlgorithm_TimeSpan implements Tx_PtExtbase_Collection_SortableEntityInterface {

	/**
	 * @var int
	 */
	protected $startTimestamp;



	/**
	 * @var int
	 */
	protected $endTimestamp;



	/**
	 * @param int $startTimestamp
	 * @return void
	 */
	public function setStartTimestamp($startTimestamp) {
		$this->startTimestamp = $startTimestamp;
	}



	/**
	 * @return int
	 */
	public function getStartTimestamp() {
		return $this->startTimestamp;
	}



	/**
	 * @param int $endTimestamp
	 * @return void
	 */
	public function setEndTimestamp($endTimestamp) {
		$this->endTimestamp = $endTimestamp;
	}



	/**
	 * @return int
	 */
	public function getEndTimestamp() {
		return $this->endTimestamp;
	}


	
	/**
	 * Implementing SortableEntityInterface
	 * 
	 * @return int
	 */
	public function getSortingValue() {
		return $this->getStartTimestamp();
	}



	/**
	 * Return value as JSON string
	 *
	 * @return string
	 */
	public function getJsonValue() {
		return json_encode(array('start' => $this->getStartTimestamp(), 'end' => $this->getEndTimestamp()));
	}



 	/**
	  * @return string
	  */
	public function __toString() {
		return $this->getJsonValue();
	}

}
?>