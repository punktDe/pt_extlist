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
 * @author Christoph Ehscheidt <ehscheidt@punkt.de
 * @package pt_extlist
 * @subpackage Tests
 */
class Tx_PtExtlist_Tests_Domain_Lifecycle_LifecycleManagerTest extends Tx_Extbase_BaseTestCase {

	protected $lifecycleManager;
	
	public function setUp() {
		$this->lifecycleManager = new Tx_PtExtlist_Domain_Lifecycle_LifecycleManager();
	}
	
	public function testInitialState() {
		$state = $this->lifecycleManager->getState();
		$this->assertEquals(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::UNDEFINED, $state);
	}

	public function testStartState() {
		$this->lifecycleManager->updateState(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::START);
		$state = $this->lifecycleManager->getState();
		$this->assertEquals(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::START, $state);
	}
	
	public function testLowerStateUpdate() {
		$this->lifecycleManager->updateState(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::END);
		$this->lifecycleManager->updateState(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::START);
		$state = $this->lifecycleManager->getState();
		$this->assertEquals(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::END, $state);
	}
	
	public function testRegister() {
		$observerMock = $this->getMock('Tx_PtExtlist_Domain_Lifecycle_LifecycleEventInterface', array('lifecycleUpdate'), array());
		$this->lifecycleManager->register($observerMock);
	}
	
	public function testLifecycleUpdateCall() {
		$observerMock = $this->getMock('Tx_PtExtlist_Domain_Lifecycle_LifecycleEventInterface', array('lifecycleUpdate'), array());
		$observerMock->expects($this->once())
			->method('lifecycleUpdate')
			->with(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::START);
		
		$this->lifecycleManager->register($observerMock);
		$this->lifecycleManager->updateState(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::START);
		
	}
	
	public function testStaticObserver() {
		$observerMock = $this->getMock('Tx_PtExtlist_Domain_Lifecycle_LifecycleEventInterface', array('lifecycleUpdate'), array());
		$observerMock->expects($this->once())
			->method('lifecycleUpdate')
			->with(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::START);
		
		// register observer with static flag on (default)
		$this->lifecycleManager->register($observerMock);
		$this->lifecycleManager->register($observerMock);
		
		$this->lifecycleManager->updateState(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::START);
		
	}
	
	public function testNoStaticObserver() {
		$observerMock = $this->getMock('Tx_PtExtlist_Domain_Lifecycle_LifecycleEventInterface', array('lifecycleUpdate'), array());
		$observerMock->expects($this->any())
			->method('lifecycleUpdate')
			->with(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::START);
		
		// register observer with static flag off
		$this->lifecycleManager->register($observerMock,FALSE);
		$this->lifecycleManager->register($observerMock,FALSE);
		
		$this->lifecycleManager->updateState(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::START);
	}
	
	
}

?>