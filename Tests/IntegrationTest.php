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
 * Testcase for header column class
 * 
 * @author Daniel Lienert 
 * @package Tests
 */
class Tx_PtExtlist_Tests_IntegrationTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

	/**
	 * Url of Page to User For IntegrationTest
	 * @var unknown_type
	 */
	protected $serverUrl; 
	
	protected $ptExtlistPage;
	
	public function SetUp() {
		$this->serverUrl = 'http://cid642l771.devel.intern.punkt.de/';
		$this->ptExtlistPage = 'index.php?id=4';
	}
	
	/**
	 * checks if the page randers without exception
	 * YES - in know that this is no unit test ;)
	 * 
	 * @author Daniel Lienert 
	 * @since 06.08.2010
	 */
	public function testPageRenderingWithoutException() {
		
		$this->markTestSkipped();
		
		$handle = fopen ($this->serverUrl, 'r');
		if(!$handle) {
			 $this->markTestSkipped(
              'Test skipped cause integration server is not reachable!.'
            );
		}
		fclose($handle);
		
		$handle = fopen ($this->serverUrl.$this->ptExtlistPage, 'r');
		if(!$handle) {
			$this->assertEquals(false, 'While rendering the page, an exeption appears.');
		}
		
		while (!feof($handle)) {
		  $buffer = fgets($handle, 4096);
		  if(strstr($buffer, 'Exception')) {
		  	$this->assertEquals(false, 'While rendering the page, the exeption: <br>' . $buffer . '<br> appears.');
		  }
		}
		fclose($handle);	
	}
}
?>