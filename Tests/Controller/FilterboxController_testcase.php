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
 * Testcase for filterbox controller class
 *
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Controller_FilterboxControllerTestcase extends Tx_PtExtlist_Tests_BaseTestcase {
    
   public function testSetup() {
        $this->assertTrue(class_exists('Tx_PtExtlist_Controller_FilterboxController', 'Class Tx_PtExtlist_Controller_FilterboxController does not exist!'));
    }
    
    
    
    public function testThrowExceptionOnNonExistingFilterboxIdentifier() {
    	try {
	    	$mockController = $this->getMock(
	          $this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'),
	          array('dummy'),array(), '', FALSE);
	        $mockController->injectSettings(array());
	        $this->fail('No exception has been thrown, when no filterbox identifier has been set!');
    	} catch(Exception $e) {
	        return; 
    	}
    }
    
    
    
    public function testShowAction() {
        $mockController = $this->getMock(
          $this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'),
          array('dummy'),array(), '', FALSE);
        $mockController->showAction();
    }
    
    
    
    public function testSubmitAction() {
        $mockController = $this->getMock(
          $this->buildAccessibleProxy('Tx_PtExtlist_Controller_FilterboxController'),
          array('dummy'),array(), '', FALSE);
        $mockController->submitAction();
    }
    
}

?>
