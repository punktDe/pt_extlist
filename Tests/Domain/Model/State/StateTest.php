<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2010 
*  All rights reserved
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
 * Test for stateobject
 *
 * @package Tests
 * @subpackage Domain\Model\State
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_Model_State_State_testcase extends Tx_PtExtlist_Tests_BaseTestcase {
	
	
	/** @test */
	public function setGetHash() {
		
		$state = new Tx_PtExtlist_Domain_Model_State_State();
		$state->setHash('hash');
		$this->assertEquals('hash', $state->getHash());
		
	}
	
	/** @test */
	public function setGetShortcode() {
		$state = new Tx_PtExtlist_Domain_Model_State_State();
		$state->setShortcode('shortcode');
		$this->assertEquals('shortcode', $state->getShortcode());
	}

	
	/** @test */
	public function setGetStateData() {
		
		$state = new Tx_PtExtlist_Domain_Model_State_State();
		$state->setStatedata('stateData');
		
		$this->assertEquals('stateData', $state->getStatedata());
		$this->assertEquals($state->calculateStateHash('stateData'), $state->getHash());
	}

	
	/** @test */
	public function setGetStateDataByArray() {
		$stateData = array('key' => 'value');
		$state = new Tx_PtExtlist_Domain_Model_State_State();
		
		$state->setStateDataByArray($stateData);
		
		$this->assertEquals($stateData, $state->getStateDataAsArray());
		$this->assertEquals($state->calculateStateHash(serialize($stateData)), $state->getHash());
	}
}
?>