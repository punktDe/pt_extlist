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
 * Testcase for subcontroller wrapper
 *
 * @package TYPO3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_Controller_SubcontrollerWrapper_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
    public function testSetup() {
    	$this->assertTrue(class_exists('Tx_PtExtlist_Controller_SubcontrollerWrapper'));
    }
    

    
    public function setup() {
        parent::setup();
        $this->setupDispatcher();
    }
    
    
    
    public function testInjectSubcontroller() {
    	$subcontrollerWrapper = new Tx_PtExtlist_Controller_SubcontrollerWrapper();
    	$this->assertTrue(method_exists($subcontrollerWrapper, 'injectSubcontroller'));
    	$subcontrollerMock = $this->getMock('Tx_PtExtlist_Controller_ListController', array(), array(), '', FALSE);
    	$subcontrollerWrapper->injectSubcontroller($subcontrollerMock);
    }
    
    
    
    public function testInjectSubcontrollerFactory() {
    	$subcontrollerWrapper = new Tx_PtExtlist_Controller_SubcontrollerWrapper();
    	$this->assertTrue(method_exists($subcontrollerWrapper, 'injectSubcontrollerFactory'));
    	$subcontrollerFactoryMock = $this->getMock('Tx_PtExtlist_Controller_SubcontrollerFactory', array(), array(), '', FALSE);
    	$subcontrollerWrapper->injectSubcontrollerFactory($subcontrollerFactoryMock);
    }
	
}

?>