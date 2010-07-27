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
 * Class implements a collection of filterboxes
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Domain_Model_Filter_FilterboxCollection extends tx_pttools_objectCollection {
    
	/**
	 * Restrict collection to filter box class
	 *
	 * @var String
	 */
    protected $restrictedClassName = 'Tx_PtExtlist_Domain_Model_Filter_Filterbox';
    
    
    
    /**
     * List identifier of the list to which this filterbox collection belongs to
     *
     * @var String
     */
    protected $listIdentifier;
    
    
    
    /**
     * Constructor for filterbox collection
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     */
    public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
    	$this->listIdentifier = $configurationBuilder->getListIdentifier();
    }
    
    
    
    /**
     * Returns list identifier of list to which filterbox belongs to
     *
     * @return String
     */
    public function getListIdentifier() {
    	return $this->listIdentifier;
    }
	
}

?>