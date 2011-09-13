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
 * Testcase for not criteria translator for extbase backend interpreter
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_NotCriteriaTranslator_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
     
	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_NotCriteriaTranslator'));
	}



    /** @test */
    public function translateCriteriaTranslatesNotCriteriaToCorrectExtbaseCriteriaIfNoOtherCriteriaIsSetOnExtbaseQuery() {
        $operand1 = $this->getMock('Tx_Extbase_Persistence_QOM_DynamicOperandInterface');
        $operand2 = $this->getMock('Tx_Extbase_Persistence_QOM_DynamicOperandInterface');
        $extbaseQueryInnerConstraint = new Tx_Extbase_Persistence_QOM_Comparison($operand1, 2, $operand2);

        $extbaseQueryMock = $this->getMock('Tx_Extbase_Persistence_Query', array('getConstraint', 'matching', 'logicalAnd', 'logicalNot'));
        $extbaseQueryMock->expects($this->any())->method('getConstraint')->will($this->returnValue($extbaseQueryInnerConstraint));

        $tmpQueryMock = $this->getMock('Tx_Extbase_Persistence_Query', array('getConstraint'), array(), '', FALSE);
        $tmpQueryMock->expects($this->at(0))->method('getConstraint')->will($this->returnValue(null));

        $extbaseRepositoryMock = $this->getMock('Tx_Extbase_Persistence_Repository', array('createQuery'), array(), '', FALSE);
        $extbaseRepositoryMock->expects($this->once())->method('createQuery')->will($this->returnValue($tmpQueryMock));

        $notCriteriaInnerCriteria = Tx_PtExtlist_Domain_QueryObject_SimpleCriteria::equals('test', 1);

        $notCriteria = $this->getMock('Tx_PtExtlist_Domain_QueryObject_NotCriteria', array('getCriteria'), array(), '', FALSE);
        $notCriteria->expects($this->any())->method('getCriteria')->will($this->returnValue($notCriteriaInnerCriteria));

        $translatedQuery = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_NotCriteriaTranslator::translateCriteria($notCriteria, $extbaseQueryMock, $extbaseRepositoryMock);
        $this->assertTrue(is_a($translatedQuery->getConstraint(), 'Tx_Extbase_Persistence_QOM_Comparison'));
        $translatedConstraint = $translatedQuery->getConstraint();
        $this->assertEquals($translatedConstraint->getOperator(), 2);
    }
	
}
?>