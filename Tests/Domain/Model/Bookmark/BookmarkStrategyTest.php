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
 * Testcase for bookmark domain object
 *
 * @package Tests
 * @subpackage Domain\Model\Bookmark
 * @author David Vogt
 */
class Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkStrategyTest extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * @var string
	 */
	protected $proxyClass;



	/**
	 * @var Tx_PtExtlist_Domain_Model_Bookmark_BookmarkStrategy
	 */
	protected $proxy;



	protected $simpleSessionData =
		array(
			'identifier' => array(
				'headerColumns' => 'sorting',
				'filters' => 'allAvailableFilters'
			)
		);

	protected $justFiltersSessionData =
		array(
			'identifier' => array(
				'filters' => 'allAvailableFilters'
			)
		);

	protected $justHeadersSessionData =
		array(
			'identifier' => array(
				'headerColumns' => 'sorting'
			)
		);

	protected $pagerSessionData =
		array(
			'identifier' => array(
				'headerColumns' => 'sorting',
				'filters' => 'allAvailableFilters',
				'pageCollection' => 'pages'
			)
		);

	protected $complexSessionData =
		array(
			'identifier' => array(
				'headerColumns' => 'sorting',
				'filters' => 'allAvailableFilters',
				'pageCollection' => 'pages'
			),
			'anotherIdentifier' => array(
				'headerColumns' => 'otherSorting',
				'filters' => 'allOtherAvailableFilters',
				'pageCollection' => 'otherPages'
			)
		);

	protected $expectedContent = 'a:1:{s:10:"identifier";a:2:{s:13:"headerColumns";s:7:"sorting";s:7:"filters";s:19:"allAvailableFilters";}}';

	protected $expectedContentJustFilters = 'a:1:{s:10:"identifier";a:1:{s:7:"filters";s:19:"allAvailableFilters";}}';

	protected $expectedContentJustHeaders = 'a:1:{s:10:"identifier";a:1:{s:13:"headerColumns";s:7:"sorting";}}';


	
	public function setUp() {
		$this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Bookmark_BookmarkStrategy');
		$this->proxy = new $this->proxyClass;
	}



	public function tearDown(){
		$this->proxyClass = NULL;
		$this->proxy = NULL;
	}



	/**
	 * @test
	 */
	public function classExists() {
		$this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Bookmark_BookmarkStrategy'));
	}



	/**
	 * @test
	 */
	public function addContentToBookmarkAddsEmptyArrayToBookmarkContent() {
		$listIdentifier = 'identifier';
		$configurationBuilder = $this->getMock('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder', array('getListIdentifier'), array(), '', FALSE);
		$bookmark = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmark_Bookmark', array('setContent'));

		$configurationBuilder->expects($this->once())->method('getListIdentifier')->will($this->returnValue($listIdentifier));
		$bookmark->expects($this->once())->method('setContent')->with($this->equalTo('a:0:{}'));
		$this->proxy->addContentToBookmark($bookmark,$configurationBuilder,array());
	}



	/**
	 * @test
	 */
	public function addContentToBookmarkAddsSimpleSessionDataToBookmarkContent() {
		$listIdentifier = 'identifier';
		$configurationBuilder = $this->getMock('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder', array('getListIdentifier'), array(), '', FALSE);
		$bookmark = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmark_Bookmark', array('setContent'));

		$configurationBuilder->expects($this->once())->method('getListIdentifier')->will($this->returnValue($listIdentifier));
		$bookmark->expects($this->once())->method('setContent')->with($this->equalTo($this->expectedContent));
		$this->proxy->addContentToBookmark($bookmark,$configurationBuilder,$this->simpleSessionData);
	}



	/**
	 * @test
	 */
	public function addContentToBookmarkAddsJustFiltersSessionDataToBookmarkContent() {
		$listIdentifier = 'identifier';
		$configurationBuilder = $this->getMock('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder', array('getListIdentifier'), array(), '', FALSE);
		$bookmark = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmark_Bookmark', array('setContent'));

		$configurationBuilder->expects($this->once())->method('getListIdentifier')->will($this->returnValue($listIdentifier));
		$bookmark->expects($this->once())->method('setContent')->with($this->equalTo($this->expectedContentJustFilters));
		$this->proxy->addContentToBookmark($bookmark,$configurationBuilder,$this->justFiltersSessionData);
	}



	/**
	 * @test
	 */
	public function addContentToBookmarkAddsJustHeadersSessionDataToBookmarkContent() {
		$listIdentifier = 'identifier';
		$configurationBuilder = $this->getMock('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder', array('getListIdentifier'), array(), '', FALSE);
		$bookmark = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmark_Bookmark', array('setContent'));

		$configurationBuilder->expects($this->once())->method('getListIdentifier')->will($this->returnValue($listIdentifier));
		$bookmark->expects($this->once())->method('setContent')->with($this->equalTo($this->expectedContentJustHeaders));
		$this->proxy->addContentToBookmark($bookmark,$configurationBuilder,$this->justHeadersSessionData);
	}



	/**
	 * @test
	 */
	public function addContentToBookmarkAddsPagerSessionDataToBookmarkContent() {
		$listIdentifier = 'identifier';
		$configurationBuilder = $this->getMock('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder', array('getListIdentifier'), array(), '', FALSE);
		$bookmark = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmark_Bookmark', array('setContent'));

		$configurationBuilder->expects($this->once())->method('getListIdentifier')->will($this->returnValue($listIdentifier));
		$bookmark->expects($this->once())->method('setContent')->with($this->equalTo($this->expectedContent));
		$this->proxy->addContentToBookmark($bookmark,$configurationBuilder,$this->pagerSessionData);
	}



	/**
	 * @test
	 */
	public function addContentToBookmarkAddsComplexSessionDataToBookmarkContent() {
		$listIdentifier = 'identifier';
		$configurationBuilder = $this->getMock('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder', array('getListIdentifier'), array(), '', FALSE);
		$bookmark = $this->getMock('Tx_PtExtlist_Domain_Model_Bookmark_Bookmark', array('setContent'));

		$configurationBuilder->expects($this->once())->method('getListIdentifier')->will($this->returnValue($listIdentifier));
		$bookmark->expects($this->once())->method('setContent')->with($this->equalTo($this->expectedContent));
		$this->proxy->addContentToBookmark($bookmark,$configurationBuilder,$this->complexSessionData);
	}





}