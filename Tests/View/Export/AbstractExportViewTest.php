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
 * Testcase for Abstract export view
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Tests_View_Export_AbstractExportView_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	public function testSetup() {
		$this->assertTrue(class_exists('Tx_PtExtlist_View_Export_AbstractExportView'));
	}
	
	
	
	public function testInjectSettings() {
		$viewMock = new Tx_PtExtlist_Tests_View_Export_AbstractExportView_ConcreteExportView();
		$settings = array('test' => 'test');
		$viewMock->injectSettings($settings);
		$this->markTestIncomplete('Check for Settings after init process');
	}
	
}



/**
 * Private class for testing abstract export view
 *
 */
class Tx_PtExtlist_Tests_View_Export_AbstractExportView_ConcreteExportView extends Tx_PtExtlist_View_Export_AbstractExportView {
	
	/**
	 * @see Tx_PtExtlist_View_AbstractExportView::getDefaultFilePrefix()
	 *
	 * @return string
	 */
	protected function getDefaultFilePrefix() {
		return 'testprefix';
	}
	
}
?>