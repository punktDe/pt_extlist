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
 * Testcase for getPostPropertyViewHelper
 * 
 * @author Daniel Lienert 
 * @package Tests
 * @subpackage Domain\Model\ViewHelpers\NameSpace
 */
class Tx_PtExtlist_Tests_ViewHelpers_Namespace_GPArrayViewHelper_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	/**
	 * @var Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock
	 */
	protected $configurationBuilderMock;
	
	
	
	public function setup() {
		$this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
	}
	
	
	
	public function testGetArgumentArraySingleNoValue() {
		$GPArrayViewHelper = new Tx_PtExtlist_ViewHelpers_Namespace_GPArrayViewHelper();
		$GPArray = $GPArrayViewHelper->getArgumentArray('label');
		$this->assertEquals($GPArray, array('label' => NULL));
	}
	
	
	
	public function testGetArgumentArrayMultiNoValue() {
		$GPArrayViewHelper = new Tx_PtExtlist_ViewHelpers_Namespace_GPArrayViewHelper();
		$GPArray = $GPArrayViewHelper->getArgumentArray('label,name');
		$this->assertEquals($GPArray, array('label' => NULL, 'name' => NULL));
	}
	
	
	
	public function testGetArgumentArraySingle() {
		$GPArrayViewHelper = new Tx_PtExtlist_ViewHelpers_Namespace_GPArrayViewHelper();
		$GPArray = $GPArrayViewHelper->getArgumentArray('label:test');
		$this->assertEquals($GPArray, array('label' => 'test'));
	}
	
	
	
	public function testGetArgumentArrayMulti() {
		$GPArrayViewHelper = new Tx_PtExtlist_ViewHelpers_Namespace_GPArrayViewHelper();
		$GPArray = $GPArrayViewHelper->getArgumentArray('label:test,name:daniel');
		$this->assertEquals($GPArray, array('label' => 'test', 'name' => 'daniel'));
	}
	
	
	
	public function testRenderWithObject() {
		$linkViewHelper = new Tx_PtExtlist_ViewHelpers_Namespace_GPArrayViewHelper();
		
		$object = $this->getMock('Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn', array('getObjectNamespace','getLabel'));
        $object->expects($this->once())
            ->method('getObjectNamespace')
            ->will($this->returnValue('listName.objectType.objectName'));
		$object->expects($this->once())
            ->method('getLabel')
            ->will($this->returnValue('test'));
            
            
		$gpArray = $linkViewHelper->render('label',$object);
		
		$refArray['listName']['objectType']['objectName']['label'] = 'test';
		$this->assertEquals($gpArray, $refArray);
	}

	
	
	public function testRenderWithObjectAndValue() {
		$linkViewHelper = new Tx_PtExtlist_ViewHelpers_Namespace_GPArrayViewHelper();
		
		$object = $this->getMock('Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn', array('getObjectNamespace'));
        $object->expects($this->once())
            ->method('getObjectNamespace')
            ->will($this->returnValue('listName.objectType.objectName'));
            
            
		$gpArray = $linkViewHelper->render('label:test', $object);
		
		$refArray['listName']['objectType']['objectName']['label'] = 'test';
		$this->assertEquals($gpArray, $refArray);
	}

}

?>