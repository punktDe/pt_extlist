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
 * Testcase for database utility class
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Tests_Utility_RenderValue_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	protected $renderObj;
	
	protected $renderUserFunctions;
	
	
	public function setUp() {
		$this->renderObj = array('renderObj' => 'TEXT',
								'renderObj.' => Array(
             						'dataWrap' => '{field:allDisplayFields} ({field:rowCount})')
							);
		
		$this->renderUserFunctions = array('10' => 'EXT:pt_extlist/Tests/Utility/RenderValueTest.php:Tx_PtExtlist_Tests_Utility_RenderValue_testcase->RenderFunction');
		
	}
	
	
	public function testRenderDefault() {
		$renderedOutput = Tx_PtExtlist_Utility_RenderValue::renderDefault(array('x', 'y'));
		$this->assertEquals($renderedOutput, 'x, y');
	}
	
	
	public function testRenderValueByRenderObject() {
		$data = array('allDisplayFields' => 'several fields', 'rowCount' => 20);
		$renderOutput = Tx_PtExtlist_Utility_RenderValue::renderValueByRenderObject($data, $this->renderObj);
		$this->assertEquals('several fields (20)', $renderOutput);
	}
	
	public function testRenderValueByRenderUserFunctionArray() {
		$this->markTestIncomplete();
	}
	
	
	public function RenderFunction(array $params) {
		return arrray_pop($params);
	} 
	
}

?>