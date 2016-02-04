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
 * @package Tests
 * @subpackage Domain\DataBackend\ExtBaseDataBackend\ExtBaseInterpreter
 * @author Michael Knoll
 * @see Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_NotCriteriaTranslator
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_NotCriteriaTranslatorTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /** @test */
    public function assertThatClassExists()
    {
        $this->assertClassExists('Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_NotCriteriaTranslator');
    }



    /** @test */
    public function translateCriteriaTranslatesNotCriteriaToCorrectExtbaseCriteriaIfNoOtherCriteriaIsSetOnExtbaseQuery()
    {
        $this->markTestSkipped('It seems not possible to use a mock as constraint. We need a solution here');
        
        $operand1 = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Qom\DynamicOperandInterface');
        $operand2 = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Qom\DynamicOperandInterface');
        $extbaseQueryInnerConstraint = new \TYPO3\CMS\Extbase\Persistence\Generic\Qom\Comparison($operand1, 2, $operand2);

        $extbaseQueryMock = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query', array('getConstraint', 'matching', 'logicalAnd', 'logicalNot'), array('any'));
        $extbaseQueryMock->expects($this->any())->method('getConstraint')->will($this->returnValue($extbaseQueryInnerConstraint));

        $tmpQueryMock = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Generic\Query', array('getConstraint'), array('any'), '', false);
        $tmpQueryMock->expects($this->at(0))->method('getConstraint')->will($this->returnValue(null));

        $extbaseRepositoryMock = $this->getMock('\TYPO3\CMS\Extbase\Persistence\Repository', array('createQuery'), array(), '', false);
        $extbaseRepositoryMock->expects($this->once())->method('createQuery')->will($this->returnValue($tmpQueryMock));

        $notCriteriaInnerCriteria = Tx_PtExtlist_Domain_QueryObject_SimpleCriteria::equals('test', 1);

        $notCriteria = $this->getMock('Tx_PtExtlist_Domain_QueryObject_NotCriteria', array('getCriteria'), array(), '', false);
        $notCriteria->expects($this->any())->method('getCriteria')->will($this->returnValue($notCriteriaInnerCriteria));

        $translatedQuery = Tx_PtExtlist_Domain_DataBackend_ExtBaseDataBackend_ExtBaseInterpreter_NotCriteriaTranslator::translateCriteria($notCriteria, $extbaseQueryMock, $extbaseRepositoryMock);
        $this->assertTrue(is_a($translatedQuery->getConstraint(), '\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Comparison'));
        $translatedConstraint = $translatedQuery->getConstraint();
        $this->assertEquals($translatedConstraint->getOperator(), 2);
    }
}
