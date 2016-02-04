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
 * Testcase for database utility class
 *
 * @package Tests
 * @subpackage Utility
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Utility_RenderValue
 */
class Tx_PtExtlist_Tests_Utility_RenderValueTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    protected $renderObj;



    protected $renderUserFunctions;



    public function setUp()
    {
        $this->renderObj = array('renderObj' => 'TEXT',
            'renderObj.' => array(
                'dataWrap' => '{field:allDisplayFields} ({field:rowCount})')
        );

        $this->renderUserFunctions = array('10' => 'EXT:pt_extlist/Tests/Utility/RenderValueTest.php:Tx_PtExtlist_Tests_Utility_RenderValueTest->RenderFunction');
    }



    public function testRenderDefaultWithSingleFields()
    {
        $renderedOutput = Tx_PtExtlist_Utility_RenderValue::renderDefault(array('x', 'y'));
        $this->assertEquals($renderedOutput, 'x, y');
    }



    public function testRenderDefaultWithArrays()
    {
        $renderedOutput = Tx_PtExtlist_Utility_RenderValue::renderDefault(array(array('x' => 'z'), array('x' => 'y')));

        $this->assertEquals($renderedOutput, 'x : z, x : y');
    }



    public function testRenderDefaultWithObjects()
    {
        $testObject = new Tx_PtExtlist_Tests_Utility_RenderValueTest_testclass();
        $renderedOutput = Tx_PtExtlist_Utility_RenderValue::renderDefault(array('field' => $testObject));
        $this->assertTrue(is_a($renderedOutput, 'Tx_PtExtlist_Tests_Utility_RenderValueTest_testclass'));
    }



    public function testRenderDefaultMixed()
    {
        $testObject = new Tx_PtExtlist_Tests_Utility_RenderValueTest_testclass();

        $renderedOutput = Tx_PtExtlist_Utility_RenderValue::renderDefault(array('field1' => $testObject, 'field2' => array('x' => 'y'), 'field3' => 'field3Value'));
        $this->assertEquals($renderedOutput, 'Tx_PtExtlist_Tests_Utility_RenderValueTest_testclass, x : y, field3Value');
    }



    public function testRenderValueByRenderUserFunctionArray()
    {
        $this->markTestIncomplete();
    }



    public function RenderFunction(array $params)
    {
        return array_pop($params);
    }
}


/**
 * Helper class for object rendering tests
 */
class Tx_PtExtlist_Tests_Utility_RenderValueTest_testclass
{
    public function getTestValue()
    {
        return 'testContent';
    }
}
