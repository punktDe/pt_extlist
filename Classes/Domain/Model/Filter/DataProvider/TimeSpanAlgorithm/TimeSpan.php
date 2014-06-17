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
 * @subpackage Model\Filter\DataProvider\TimeSpanAlgorithm
 * @author Joachim Mathes
 */
class Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan implements Tx_PtExtbase_Collection_SortableEntityInterface {

	/**
	 * @var DateTime
	 */
	protected $startDate;



	/**
	 * @var DateTime
	 */
	protected $endDate;



	/**
	 * @param DateTime $startDate
	 * @return void
	 */
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}



	/**
	 * @return DateTime
	 */
	public function getStartDate() {
		return $this->startDate;
	}



	/**
	 * @param DateTime $endDate
	 * @return void
	 */
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}



	/**
	 * @return DateTime
	 */
	public function getEndDate() {
		return $this->endDate;
	}


	
	/**
	 * Implementing SortableEntityInterface
	 * 
	 * @return int Timestamp
	 */
	public function getSortingValue() {
		return $this->getStartDate()->format('U');
	}



	/**
	 * Return value as JSON string
	 *
	 * @return string
	 */
	public function getJsonValue() {
		return json_encode(array(
			'start' => $this->getStartDate()->format('Ymd'),
			'end' => $this->getEndDate()->format('Ymd'))
		);
	}



 	/**
	 * @return string
	 */
	public function __toString() {
		return $this->getJsonValue();
	}

}
?>