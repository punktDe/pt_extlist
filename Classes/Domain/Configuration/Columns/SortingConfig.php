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
 * Sorting configuration for columns
 * 
 * @package Domain
 * @subpackage Configuration\Columns  
 * @author Daniel Lienert
 * @author Michael Knoll
 */
class Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig {
	
	/**
	 * Holds name of field for this sorting configuration
     *
	 * @var string
	 */
	protected $field; 



	/**
	 * Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC / SORTINGSTATE_DESC / SORTINGSTATE_NONE
     *
	 * @var integer 
	 */
	protected $direction;



	/**
	 * if this is set to true, the direction cannot be changed 
	 * 
	 * @var bool
	 */
	protected $forceDirection;



    /**
     * Holds a label that is used for rendering header for this sorting field
     *
     * @var string
     */
    protected $label;
	
	
	// TODO: implement sorting order
	

    /**
     * Constructor for sorting configuration
     *
     * @param $field
     * @param $direction
     * @param $forceDirection
     */
	public function __construct($field, $direction, $forceDirection, $label='') {
        // TODO assert that direction is 1 / 0 / -1
		$this->direction = $direction;
		$this->field = $field; 
		$this->forceDirection = $forceDirection;
        $this->label = $label;
	}
	
	

    /**
     * Setter for sorting direction
     *
     * @param $direction
     * @return void
     */
	public function setDirection($direction) {
		if($this->forceDirection == false) {
			$this->direction = $direction;
		}
	}
	
	

    /**
     * Getter for sorting direction
     *
     * @return int
     */
	public function getDirection() {
		return $this->direction;
	}
	
	

    /**
     * Returns true if direction is forced for sorted field
     *
     * @return bool
     */
	public function getForceDirection() {
		return $this->forceDirection;
	}
	
	

    /**
     * Name of field for which this sorting configuration is set
     *
     * @return string
     */
	public function getField() {
		return $this->field;
	}



    /**
     * Getter for label for this sorting field configuration
     * 
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }
    
}
?>