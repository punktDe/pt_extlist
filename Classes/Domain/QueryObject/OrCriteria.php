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
 * Class implements an or criteria
 *
 * @package Domain
 * @subpackage QueryObject
 * @author Daniel Lienert , Michael Knoll 
 */
class Tx_PtExtlist_Domain_QueryObject_OrCriteria extends Tx_PtExtlist_Domain_QueryObject_Criteria {

	/**
	 * Holds first criteria for or conjunction
	 *
	 * @var Tx_PtExtlist_Domain_QueryObject_Criteria
	 */
	protected $firstCriteria;
	
	
	
	/**
	 * Holds second criteria for or conjunction
	 *
	 * @var Tx_PtExtlist_Domain_QueryObject_Criteria
	 */
	protected $secondCriteria;
	
	
	
	/** 
	 * @param $firstCriteria Tx_PtExtlist_Domain_QueryObject_Criteria
	 * @param $secondCriteria Tx_PtExtlist_Domain_QueryObject_Criteria
	 * @return void
	 * @author Daniel Lienert 
	 * @since 28.07.2010
	 */
	public function __construct(Tx_PtExtlist_Domain_QueryObject_Criteria $firstCriteria, Tx_PtExtlist_Domain_QueryObject_Criteria $secondCriteria) {
		$this->firstCriteria = $firstCriteria;
		$this->secondCriteria = $secondCriteria;
	}
	
	
	
	/**
	 * Returns true, if criteria is equal to this criteria
	 *
	 * @param Tx_PtExtlist_Domain_QueryObject_Criteria $criteria Criteria to be compared with this criteria
	 * @return bool
	 */
    public function isEqualTo(Tx_PtExtlist_Domain_QueryObject_Criteria $criteria) {
        if (!is_a($criteria, __CLASS__)) {
        	return false;   
        } else {
        	if ($this->firstCriteria == $criteria->firstCriteria || $this->secondCriteria == $criteria->secondCriteria) {
        		return true;
        	} else {
        		return false;
        	}
        }
    }
	
	
	
	/**
	 * @return Tx_PtExtlist_Domain_QueryObject_Criteria
	 * @author Daniel Lienert 
	 * @since 28.07.2010
	 */
	public function getFirstCriteria() {
		return $this->firstCriteria;
	}
	
	
	
	/**
	 * @return Tx_PtExtlist_Domain_QueryObject_Criteria
	 * @author Daniel Lienert 
	 * @since 28.07.2010
	 */	
	public function getSecondCriteria() {
		return $this->secondCriteria;
	}
}
 
?>