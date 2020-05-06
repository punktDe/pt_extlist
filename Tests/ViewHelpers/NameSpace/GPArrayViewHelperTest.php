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

use PunktDe\PtExtlist\ViewHelpers\Namespaces\GPArrayViewHelper;

/**
 * Testcase for getPostPropertyViewHelper
 * 
 * @author Daniel Lienert 
 * @package Tests
 * @subpackage Domain\Model\ViewHelpers\NameSpace
 * @see GPArrayViewHelper
 */
class Tx_PtExtlist_Tests_ViewHelpers_Namespace_GPArrayViewHelperTest extends PunktDe_PtExtlst_Tests_BaseTestcase
{
    /**
     * @var Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock
     */
    protected $configurationBuilderMock;
    
    
    
    public function setup()
    {
        $this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();
    }
    
    
    
    public function testGetArgumentArraySingleNoValue()
    {
        $GPArrayViewHelper = new GPArrayViewHelper();
        $GPArray = $GPArrayViewHelper->getArgumentArray('label');
        $this->assertEquals($GPArray, ['label' => null]);
    }
    
    
    
    public function testGetArgumentArrayMultiNoValue()
    {
        $GPArrayViewHelper = new GPArrayViewHelper();
        $GPArray = $GPArrayViewHelper->getArgumentArray('label,name');
        $this->assertEquals($GPArray, ['label' => null, 'name' => null]);
    }
    
    
    
    public function testGetArgumentArraySingle()
    {
        $GPArrayViewHelper = new GPArrayViewHelper();
        $GPArray = $GPArrayViewHelper->getArgumentArray('label:test');
        $this->assertEquals($GPArray, ['label' => 'test']);
    }
    
    
    
    public function testGetArgumentArrayMulti()
    {
        $GPArrayViewHelper = new GPArrayViewHelper();
        $GPArray = $GPArrayViewHelper->getArgumentArray('label:test,name:daniel');
        $this->assertEquals($GPArray, ['label' => 'test', 'name' => 'daniel']);
    }
    
    
    
    public function testRenderWithObject()
    {
        $sessionPersistenceManagerMock = $this->getMock('PunktDe_PtExtbase_State_Session_SessionPersistenceManager', ['addSessionRelatedArguments'], [], '', false);
        $sessionPersistenceManagerMock->expects($this->any())->method('addSessionRelatedArguments');
        $sessionPersistenceManagerBuilderMock = $this->getMock('PunktDe_PtExtbase_State_Session_SessionPersistenceManagerBuilder', ['getInstance'], [], '', false);
        $sessionPersistenceManagerBuilderMock->expects($this->any())->method('getInstance')->will($this->returnValue($sessionPersistenceManagerMock));
        /* @var $sessionPersistenceManagerBuilderMock PunktDe_PtExtbase_State_Session_SessionPersistenceManagerBuilder */

        $linkViewHelper = new GPArrayViewHelper();
        $linkViewHelper->injectSessionPersistenceManagerBuilder($sessionPersistenceManagerBuilderMock);
        
        $object = $this->getMock('Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn', ['getObjectNamespace', 'getLabel']);
        $object->expects($this->once())
            ->method('getObjectNamespace')
            ->will($this->returnValue('listName.objectType.objectName'));
        $object->expects($this->once())
            ->method('getLabel')
            ->will($this->returnValue('test'));
            
            
        $gpArray = $linkViewHelper->render('label', $object);
        
        $refArray['listName']['objectType']['objectName']['label'] = 'test';
        $this->assertEquals($gpArray, $refArray);
    }

    
    
    public function testRenderWithObjectAndValue()
    {
        $sessionPersistenceManagerMock = $this->getMock('PunktDe_PtExtbase_State_Session_SessionPersistenceManager', ['addSessionRelatedArguments'], [], '', false);
        $sessionPersistenceManagerMock->expects($this->any())->method('addSessionRelatedArguments');
        $sessionPersistenceManagerBuilderMock = $this->getMock('PunktDe_PtExtbase_State_Session_SessionPersistenceManagerBuilder', ['getInstance'], [], '', false);
        $sessionPersistenceManagerBuilderMock->expects($this->any())->method('getInstance')->will($this->returnValue($sessionPersistenceManagerMock));
        /* @var $sessionPersistenceManagerBuilderMock PunktDe_PtExtbase_State_Session_SessionPersistenceManagerBuilder */

        $linkViewHelper = new GPArrayViewHelper();

        $linkViewHelper->injectSessionPersistenceManagerBuilder($sessionPersistenceManagerBuilderMock);
        
        $object = $this->getMock('Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn', ['getObjectNamespace']);
        $object->expects($this->once())
            ->method('getObjectNamespace')
            ->will($this->returnValue('listName.objectType.objectName'));
            
            
        $gpArray = $linkViewHelper->render('label:test', $object);
        
        $refArray['listName']['objectType']['objectName']['label'] = 'test';
        $this->assertEquals($gpArray, $refArray);
    }
}
