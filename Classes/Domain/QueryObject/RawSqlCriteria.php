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
 * Implements a criteria for sending raw sql queries to databackend
 *
 * WARNING: No quoting will be used to handle query. Developer is
 * responsible for correct quoting of SQL string!
 *
 * @package Domain
 * @subpackage QueryObject
 * @author Michael Knoll
 */
class Tx_PtExtlist_Domain_QueryObject_RawSqlCriteria extends Tx_PtExtlist_Domain_QueryObject_Criteria {
	 
	/**
	 * Holds raw SQL string of criteria
	 *
	 * @var string
	 */
	protected $rawSqlString;



    /**
     * Constructor for raw sql criteria.
     *
     * WARNING: No quoting will be used to handle query. Developer is
     * responsible for correct quoting of SQL string!
     *
     * @param string $rawSqlString
     */
    public function __construct($rawSqlString) {
        $this->rawSqlString = $rawSqlString;
    }
	


    /**
     * Getter for raw sql string
     * 
     * @return string
     */
    public function getRawSqlString() {
        return $this->rawSqlString;
    }
    
}
?>