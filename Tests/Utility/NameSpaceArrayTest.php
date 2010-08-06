<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>, Christoph Ehscheidt <ehscheidt@punkt.de>
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
 * Class implements a testcase for a get / post var adapter
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Tests_Utility_NameSpaceArray_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	protected $varArray;
	
	public function setUp() {
		$this->varArray = array('key1' => array(
		    'key2' => array(
		        'key3' => array(
		            'key4' => 'value1',
		            'key5' => 'value2'		
		         )
		    )
		)
		);
	}
	
	public function testGetArrayContentByArrayAndNamespace() {
		$extractedValue = Tx_PtExtlist_Utility_NameSpaceArray::getArrayContentByArrayAndNamespace($this->varArray,'key1.key2.key3.key4');
		$this->assertEquals($extractedValue, 'value1', 'The extracted Value should be Value 1');
	}
	
	public function testGetArrayContentByArrayAndNamespaceWithEmptyArray() {
		$extractedValue = Tx_PtExtlist_Utility_NameSpaceArray::getArrayContentByArrayAndNamespace(array(),'key1.key2.key3.key4');
		$this->assertEquals($extractedValue, array(), 'The method should return an empty array');
	}
	
	public function testSaveDataInNamespaceTree() {
		$testArray['key1']['key2']['key3'] = 'test';
		$testArray2['key1']['key2']['key4'] = 'test4';
		
		$testArray = Tx_PtExtlist_Utility_NameSpaceArray::saveDataInNamespaceTree('key1.key2.key3', $testArray, 'test2');
		
		$refArray['key1']['key2']['key3'] = 'test2';
		$refArray2['key1']['key2']['key4'] = 'test4';
		$this->assertEquals($testArray, $refArray);
		$this->assertEquals($testArray2, $refArray2);
	}
	
	public function testSaveDataInNamespaceTreeWithEmptyArray() {
		$testArray = array();
		$testArray = Tx_PtExtlist_Utility_NameSpaceArray::saveDataInNamespaceTree('key1.key2.key3', $testArray, 'test2');
		
		$refArray['key1']['key2']['key3'] = 'test2';
		$this->assertEquals($testArray, $refArray);
	}
}
?>