<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de>
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
 * Testcase for getPostPropertyViewHelper
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_ViewHelpers_Namespace_FormElementNameViewHelper_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	/**
	 * @var Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock
	 */
	protected $configurationBuilderMock;
	
	public function setup() {
		$this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
	}
	
	public function testRenderNamespacePart() {
		$linkViewHelper = new Tx_PtExtlist_ViewHelpers_Namespace_FormElementNameViewHelper();
		
		$object = $this->getMock('Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn', array('getObjectNamespace'));
        $object->expects($this->once())
            ->method('getObjectNamespace')
            ->will($this->returnValue('tx_ptextlist_pi1.listName.objectType.objectName'));
		
		$nameSpacepart = $linkViewHelper->renderNamespacePart($object);
		
		$this->assertEquals($nameSpacepart, 'listName[objectType][objectName]', 'NamespacePart should be listName[objectType][objectName] but is ' . $nameSpacepart);
	}
	
	public function testRenderWithSingleKey() {
		$linkViewHelper = new Tx_PtExtlist_ViewHelpers_Namespace_FormElementNameViewHelper();
		
		$object = $this->getMock('Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn', array('getObjectNamespace'));
        $object->expects($this->once())
            ->method('getObjectNamespace')
            ->will($this->returnValue('tx_ptextlist_pi1.listName.objectType.objectName'));
            
        $link = $linkViewHelper->render($object,'label');
        
        $this->assertEquals($link, 'listName[objectType][objectName][label]', 'NamespacePart should be listName[objectType][objectName][label] but is "' . $link. '"');
	}
}
?>