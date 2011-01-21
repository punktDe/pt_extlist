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
 * AND criteria
 *
 * @package Domain
 * @subpackage QueryObject
 * @author Michael Knoll 
 */
 class Tx_PtExtlist_Domain_QueryObject_AndCriteria extends Tx_PtExtlist_Domain_QueryObject_Criteria {
 	
 	/**
 	 * Holds first criteria to be used with and conjunction
 	 *
 	 * @var Tx_PtExtlist_Domain_QueryObject_Criteria
 	 */
 	protected $firstCriteria;
 	
 	
 	
 	/**
 	 * Holds second criteria to be used with conjunction
 	 *
 	 * @var Tx_PtExtlist_Domain_QueryObject_Criteria
 	 */
 	protected $secondCriteria;
 	
 	
 	
 	/**
 	 * Constructor takes two criterias to be conjuncted with AND
 	 *
 	 * @param Tx_PtExtlist_Domain_QueryObject_Criteria $firstCriteria
 	 * @param Tx_PtExtlist_Domain_QueryObject_Criteria $secondCriteria
 	 */
 	public function __construct(Tx_PtExtlist_Domain_QueryObject_Criteria $firstCriteria, Tx_PtExtlist_Domain_QueryObject_Criteria $secondCriteria) {
 		$this->firstCriteria = $firstCriteria;
 		$this->secondCriteria = $secondCriteria;
 	}
 	
 	
 	
    /**
     * Returns true, if given criteria is equal to this object
     *
     * @param Tx_PtExtlist_Domain_QueryObject_Criteria $criteria Criteria to be compared with this object
     * @return bool
     */
 	public function isEqualTo(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria) {
 		if (is_a($criteria, __CLASS__)) {
 			if ($this->firstCriteria->isEqualTo($criteria->firstCriteria) && $this->secondCriteria->isEqualTo($criteria->secondCriteria)) {
 				return true;
 			}
 		}
 		return false;
 	}
 	
 	
 	
 	/**
 	 * Return first criteria of and criteria
 	 *
 	 * @return Tx_PtExtlist_Domain_QueryObject_Criteria
 	 */
 	public function getFirstCriteria() {
 		return $this->firstCriteria;
 	}
 	
 	
 	
 	/**
 	 * Returns second criteria of and criteria
 	 *
 	 * @return Tx_PtExtlist_Domain_QueryObject_Criteria
 	 */
 	public function getSecondCriteria() {
 		return $this->secondCriteria;
 	}
 	
 }
 
 
 ?>