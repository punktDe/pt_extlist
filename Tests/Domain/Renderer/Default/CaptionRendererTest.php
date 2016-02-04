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
 * Testcase for default caption renderer
 * 
 * @package Tests
 * @subpackage Domain\Renderer\Default
 * @author Michael Knoll
 * @see Tx_PtExtlist_Domain_Renderer_Default_CaptionRenderer
 */
class Tx_PtExtlist_Tests_Domain_Renderer_Default_CaptionRendererTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * Holds a set of list headers to be rendered
     *
     * @var Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory
     */
    protected $listHeader;
    
    
    
    /**
     * Holds an instance of caption renderer to be tested
     *
     * @var Tx_PtExtlist_Domain_Renderer_Default_CaptionRenderer
     */
    protected $captionRenderer;
    
    
    
    /**
     * Sets up the testcase
     */
    public function setUp()
    {
        $this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();


        $listHeaderFactory = $this->objectManager->get('Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory'); /* @var $listHeaderFactory Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory */

        $this->listHeader = $listHeaderFactory->createInstance($this->configurationBuilderMock);
        $this->captionRenderer = $this->objectManager->get('Tx_PtExtlist_Domain_Renderer_Default_CaptionRenderer');
    }



    /** @test */
    public function renderCaptionRendersCaptionsForGivenConfiguration()
    {
        // see ConfigurationBuilderMock for column definitions
        $captions = $this->captionRenderer->renderCaptions($this->listHeader);
        $this->assertEquals('Column 1', $captions->getItemByIndex(0)->getValue());
    }



    /** @test */
    public function renderCaptionsReturnsLocalizedLabels()
    {
        $methods = array('getLabel', 'getColumnIdentifier');
        $returnMethods['getLabel'] = 'LLL:EXT:pt_extlist/Tests/Domain/Renderer/locallang.xml:test';
        $returnMethods['getColoumnIdentifier'] = 'test';

        $headerColumn = $this->getConfiguredMock('Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn', $methods, $returnMethods);
        
        $columnConfigMock = $this->getAccessibleMock('Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig', array('isAccessable'), array(), '', false);
        $columnConfigMock->expects($this->any())
            ->method('isAccessable')
            ->will($this->returnValue(true));
            
        $headerColumn->injectColumnConfig($columnConfigMock);
        
        
        // we need to give a list to the renderer
        $listHeader = new Tx_PtExtlist_Domain_Model_List_Header_ListHeader($this->configurationBuilderMock->getListIdentifier());
        $listHeader->addHeaderColumn($headerColumn, 'test');

        $captions = $this->captionRenderer->renderCaptions($listHeader);


        $this->assertEquals('TEST', $captions->getItemByIndex(0)->getValue());
    }



    /** @test */
    public function renderCaptionsCreatesSimpleTsLabels()
    {
        $this->markTestSkipped('This test is currently not working in T3 6.1.7 - problem arises in AbstractUserAuthentication.php::904');
        $ts = array('10' => array(
                        '_typoScriptNodeValue' => 'TEXT',
                        'value' => 'test',
                        ),
                    '_typoScriptNodeValue' => 'COA'
                    );

        $methods = array('getLabel', 'getColumnIdentifier');
        $returnMethods['getLabel'] = $ts;
        $returnMethods['getColumnIdentifier'] = 'bla';

        $headerColumn = $this->getConfiguredMock('Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn', $methods, $returnMethods);

        $columnConfigMock = $this->getAccessibleMock('Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig', array('isAccessable'), array(), '', false);
        $columnConfigMock->expects($this->any())
            ->method('isAccessable')
            ->will($this->returnValue(true));

        $headerColumn->injectColumnConfig($columnConfigMock);

        $listHeader = new Tx_PtExtlist_Domain_Model_List_Header_ListHeader($this->configurationBuilderMock->getListIdentifier());
        $listHeader->addHeaderColumn($headerColumn, 'bla');


        $captionRendererClass =  $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Renderer_Default_CaptionRenderer');
        $captionRenderer = new $captionRendererClass(); /* @var $captionRenderer Tx_PtExtlist_Domain_Renderer_Default_CaptionRenderer */

        $captions = $captionRenderer->renderCaptions($listHeader);
    }



    /**
     * Returns configured mocks
     *
     * @param string $className
     * @param array $methods
     * @param array $returnMethods
     * @return mixed
     */
    protected function getConfiguredMock($className, array $methods, array $returnMethods)
    {
        $columnConfig = $this->getMock($className,
                            $methods, array(), '', false);

        foreach ($returnMethods as $method => $returnValue) {
            $columnConfig->expects($this->any())->method($method)->will($this->returnValue($returnValue));
        }

        return $columnConfig;
    }
}
