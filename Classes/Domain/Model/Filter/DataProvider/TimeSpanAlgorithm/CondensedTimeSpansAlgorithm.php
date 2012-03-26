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
 * The algorithm expects the input to be valid; i.e. every TimeSpan must stick
 * to the restriction: startTimestamp <= endTimestamp
 *
 * @package Domain
 * @subpackage Model\Filter\DataProvider\TimeSpanAlgorithm
 * @author Joachim Mathes
 */
class Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_CondensedTimeSpansAlgorithm {

	/**
	 * @var Tx_PtExtbase_Collection_SortableObjectCollection
	 */
	protected $timeSpans;



	/**
	 * @var Tx_PtExtbase_Collection_SortableObjectCollection
	 */
	protected $condensedTimeSpans;



	/**
	 * Constructor
	 */
	public function __construct() {
		$this->condensedTimeSpans = new Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpanCollection();
	}

	

	/**
	 * Process algorithm
	 *
	 * @return \Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpanCollection
	 */
	public function process() {
		$this->timeSpans->sort();
		foreach ($this->timeSpans as $timeSpan) { /** @var Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan $timeSpan */
			if (count($this->condensedTimeSpans) > 0) {
				$item = $this->condensedTimeSpans->pop(); /** @var Tx_PtExtlist_Domain_Model_Filter_DataProvider_TimeSpanAlgorithm_TimeSpan $item */
				if ($timeSpan->getStartDate()->format('U') <= $item->getEndDate()->format('U')
				    && $timeSpan->getEndDate()->format('U') >= $item->getEndDate()->format('U')) {
					$item->setEndDate($timeSpan->getEndDate());
					$this->condensedTimeSpans->push($item);
				} elseif ($item->getEndDate()->format('U') < $timeSpan->getStartDate()->format('U')) {
					$this->condensedTimeSpans->push($item);
					$this->condensedTimeSpans->push($timeSpan);
				} else {
					$this->condensedTimeSpans->push($item);
				}
			} else {
				$this->condensedTimeSpans->push($timeSpan);
			}
		}
		return $this->condensedTimeSpans;
	}


	
	/**
	 * @param \Tx_PtExtbase_Collection_SortableObjectCollection $timeSpans
	 * @return void
	 */
	public function setTimeSpans($timeSpans){
		$this->timeSpans = $timeSpans;
	}



	/**
	 * @return \Tx_PtExtbase_Collection_SortableObjectCollection
	 */
	public function getTimeSpans(){
		return $this->timeSpans;
	}



	/**
	 * @return \Tx_PtExtbase_Collection_SortableObjectCollection
	 */
	public function getCondensedTimeSpans(){
		return $this->condensedTimeSpans;
	}

}
?>