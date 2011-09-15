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
 * Collection of sorting field configurations
 *
 * @package 		Domain
 * @subpackage 		Configuration\Columns  
 * @author         	Daniel Lienert
 * @author          Michael Knoll
 */
class Tx_PtExtlist_Domain_Configuration_Columns_SortingConfigCollection extends Tx_PtExtbase_Collection_ObjectCollection {

    /**
     * Class name to which this collection should be restricted to.
     * Collection accepts only items of this class.
     * 
     * @var string
     */
    protected $restrictedClassName = 'Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig';



    /**
     * If set to true, this collection sets up a sorting column
     * that has multiple fields to be sorted AT ONCE
     *
     * @var bool
     */
    protected $columnSorting;



    /**
     * Constructor for sorting config collection
     *
     * If columnSorting is set to true, this configuration sets up a column
     * that can only be sorted as a whole.
     *
     * @param bool $columnSorting
     */
    public function __construct($columnSorting = false) {
        $this->columnSorting = $columnSorting;
    }



    /**
     * Adds a sorting field by given fieldIdentifier
     *
     * @param Tx_PtExtlist_Domain_Configuration_Columns_SortingConfig $sortingField
     * @param string $fieldIdentifier
     * @return void
     */
	public function addSortingField($sortingField, $fieldIdentifier) {
		$this->addItem($sortingField, $fieldIdentifier);
	}



    /**
     * Returns true, if column can only be sorted as a whole
     * 
     * @return bool
     */
    public function getColumnSorting() {
        return $this->columnSorting;
    }
    
}
?>