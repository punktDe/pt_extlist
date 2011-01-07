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
 * Testcase for or criteria translator for extbase backend interpreter
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_OrCriteriaTranslator_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
    
    protected $repositoryMock;
    
    
    
    public function setup() {
        $this->repositoryMock = $this->getMock('Tx_Extbase_Persistence_Repository', array(), array(), '', FALSE); 
    }
    
    
     
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_OrCriteriaTranslator'));
	}
	
	
	
	public function testThrowExceptionOnWrongCriteriaType() {
		$criteria = new Tx_PtExtlist_Domain_QueryObject_NotCriteria(new Tx_PtExtlist_Domain_QueryObject_SimpleCriteria('field', 'value', '='));
        $query = $this->getMock('Tx_Extbase_Persistence_Query', array(), array(), '', FALSE);
        try {
            Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_OrCriteriaTranslator::translateCriteria(
                $criteria, $query, $this->repositoryMock
            );
        } catch(Exception $e) {
            return;
        }
        $this->fail('No exception has been thrown on trying to translate wrong criteria class!');
	}
	
}
?>