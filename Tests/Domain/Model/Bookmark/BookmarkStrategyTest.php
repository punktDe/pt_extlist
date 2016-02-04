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
 * @see Tx_PtExtlist_Domain_Model_Bookmark_BookmarkStrategy
 */
class Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkStrategyTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * @var string
     */
    protected $proxyClass;



    /**
     * @var Tx_PtExtlist_Domain_Model_Bookmark_BookmarkStrategy
     */
    protected $proxy;


    
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $bookmark;


    
    /**
     * @var string
     */
    protected $listIdentifier = 'identifier';



    protected $simpleSessionData =
        array(
            'identifier' => array(
                'headerColumns' => 'sorting',
                'filters' => 'allAvailableFilters'
            )
        );

    protected $modifiedSimpleSessionData =
        array(
            'identifier' => array(
                'headerColumns' => 'savedSorting',
                'filters' => 'allAvailableSavedFilters'
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

    protected $modifiedComplexSessionData =
        array(
            'anotherIdentifier' => array(
                'headerColumns' => 'otherSorting',
                'filters' => 'allOtherAvailableFilters',
                'pageCollection' => 'otherPages'
            ),
            'identifier' => array(
                'headerColumns' => 'savedSorting',
                'filters' => 'allAvailableSavedFilters',
            )
        );

    protected $modifiedComplexSessionDataJustFilters =
        array(
            'identifier' => array(
                'filters' => 'allAvailableSavedFilters',
            ),
            'anotherIdentifier' => array(
                'headerColumns' => 'otherSorting',
                'filters' => 'allOtherAvailableFilters',
                'pageCollection' => 'otherPages'
            )
        );

    protected $modifiedComplexSessionDataJustHeaders =
        array(
            'identifier' => array(
                'headerColumns' => 'savedSorting',
            ),
            'anotherIdentifier' => array(
                'headerColumns' => 'otherSorting',
                'filters' => 'allOtherAvailableFilters',
                'pageCollection' => 'otherPages'
            )
        );

    protected $modifiedComplexSessionDataEmpty =
        array(
            'identifier' => array(),
            'anotherIdentifier' => array(
                'headerColumns' => 'otherSorting',
                'filters' => 'allOtherAvailableFilters',
                'pageCollection' => 'otherPages'
            )
        );

    protected $otherSessionData =
    array(
        'otherIdentifier' => array(
            'headerColumns' => 'sorting',
            'filters' => 'allAvailableFilters',
            'pageCollection' => 'pages'
        )
    );

    protected $modifiedOtherSessionData =
        array(
            'otherIdentifier' => array(
                'headerColumns' => 'sorting',
                'filters' => 'allAvailableFilters',
                'pageCollection' => 'pages'
            ),
            'identifier' => array(
                'headerColumns' => 'savedSorting',
                'filters' => 'allAvailableSavedFilters',
            )
        );

    protected $expectedContent = 'a:1:{s:10:"identifier";a:2:{s:13:"headerColumns";s:7:"sorting";s:7:"filters";s:19:"allAvailableFilters";}}';
    protected $savedContent = 'a:1:{s:10:"identifier";a:2:{s:13:"headerColumns";s:12:"savedSorting";s:7:"filters";s:24:"allAvailableSavedFilters";}}';
    protected $savedContentEmpty = 'a:1:{s:10:"identifier";a:0:{}}';

    protected $expectedContentJustFilters = 'a:1:{s:10:"identifier";a:1:{s:7:"filters";s:19:"allAvailableFilters";}}';
    protected $savedContentJustFilters = 'a:1:{s:10:"identifier";a:1:{s:7:"filters";s:24:"allAvailableSavedFilters";}}';

    protected $expectedContentJustHeaders = 'a:1:{s:10:"identifier";a:1:{s:13:"headerColumns";s:7:"sorting";}}';
    protected $savedContentJustHeaders = 'a:1:{s:10:"identifier";a:1:{s:13:"headerColumns";s:12:"savedSorting";}}';

    
    public function setUp()
    {
        $this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Bookmark_BookmarkStrategy');
        $this->proxy = new $this->proxyClass;
        $this->bookmark = $this->getMockBuilder('Tx_PtExtlist_Domain_Model_Bookmark_Bookmark')
            ->setMethods(array('setContent', 'getListId', 'getContent'))
            ->getMock();
    }



    public function tearDown()
    {
        $this->proxyClass = null;
        $this->proxy = null;
    }



    /**
     * @test
     */
    public function classExists()
    {
        $this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Bookmark_BookmarkStrategy'));
    }



    /**
     * @test
     */
    public function addContentToBookmarkAddsEmptyArrayToBookmarkContent()
    {
        $configurationBuilder = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder')
            ->setMethods(array('getListIdentifier'))
            ->disableOriginalConstructor()
            ->getMock();

        $configurationBuilder->expects($this->once())
            ->method('getListIdentifier')
            ->will($this->returnValue($this->listIdentifier));
        $this->bookmark->expects($this->once())
            ->method('setContent')
            ->with($this->equalTo('a:0:{}'));

        $this->proxy->addContentToBookmark($this->bookmark, $configurationBuilder, array());
    }



    /**
     * @test
     */
    public function addContentToBookmarkAddsSimpleSessionDataToBookmarkContent()
    {
        $configurationBuilder = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder')
            ->setMethods(array('getListIdentifier'))
            ->disableOriginalConstructor()
            ->getMock();

        $configurationBuilder->expects($this->once())
            ->method('getListIdentifier')
            ->will($this->returnValue($this->listIdentifier));
        $this->bookmark->expects($this->once())
            ->method('setContent')
            ->with($this->equalTo($this->expectedContent));

        $this->proxy->addContentToBookmark($this->bookmark, $configurationBuilder, $this->simpleSessionData);
    }



    /**
     * @test
     */
    public function addContentToBookmarkAddsJustFiltersSessionDataToBookmarkContent()
    {
        $configurationBuilder = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder')
            ->setMethods(array('getListIdentifier'))
            ->disableOriginalConstructor()
            ->getMock();

        $configurationBuilder->expects($this->once())
            ->method('getListIdentifier')
            ->will($this->returnValue($this->listIdentifier));
        $this->bookmark->expects($this->once())
            ->method('setContent')
            ->with($this->equalTo($this->expectedContentJustFilters));

        $this->proxy->addContentToBookmark($this->bookmark, $configurationBuilder, $this->justFiltersSessionData);
    }



    /**
     * @test
     */
    public function addContentToBookmarkAddsJustHeadersSessionDataToBookmarkContent()
    {
        $configurationBuilder = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder')
            ->setMethods(array('getListIdentifier'))
            ->disableOriginalConstructor()
            ->getMock();

        $configurationBuilder->expects($this->once())
            ->method('getListIdentifier')
            ->will($this->returnValue($this->listIdentifier));
        $this->bookmark->expects($this->once())
            ->method('setContent')
            ->with($this->equalTo($this->expectedContentJustHeaders));

        $this->proxy->addContentToBookmark($this->bookmark, $configurationBuilder, $this->justHeadersSessionData);
    }



    /**
     * @test
     */
    public function addContentToBookmarkAddsPagerSessionDataToBookmarkContent()
    {
        $configurationBuilder = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder')
            ->setMethods(array('getListIdentifier'))
            ->disableOriginalConstructor()
            ->getMock();

        $configurationBuilder->expects($this->once())
            ->method('getListIdentifier')
            ->will($this->returnValue($this->listIdentifier));
        $this->bookmark->expects($this->once())
            ->method('setContent')
            ->with($this->equalTo($this->expectedContent));

        $this->proxy->addContentToBookmark($this->bookmark, $configurationBuilder, $this->pagerSessionData);
    }



    /**
     * @test
     */
    public function addContentToBookmarkAddsComplexSessionDataToBookmarkContent()
    {
        $configurationBuilder = $this->getMockBuilder('Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder')
            ->setMethods(array('getListIdentifier'))
            ->disableOriginalConstructor()
            ->getMock();

        $configurationBuilder->expects($this->once())
            ->method('getListIdentifier')
            ->will($this->returnValue($this->listIdentifier));
        $this->bookmark->expects($this->once())
            ->method('setContent')
            ->with($this->equalTo($this->expectedContent));

        $this->proxy->addContentToBookmark($this->bookmark, $configurationBuilder, $this->complexSessionData);
    }



    /**
     * @test
     */
    public function mergeSessionAndBookmarkMergesEmptySessionArrayAndEmptyBookmarkContent()
    {
        $this->bookmark->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue('a:0:{}'));
        $this->bookmark->expects($this->once())
            ->method('getListId')
            ->will($this->returnValue($this->listIdentifier));

        $expected = array();
        $actual = $this->proxy->mergeSessionAndBookmark($this->bookmark, array());

        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function mergeSessionAndBookmarkMergesEmptySessionArrayAndEmptyArrayInBookmarkContent()
    {
        $this->bookmark->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue('a:0:{}'));
        $this->bookmark->expects($this->once())
            ->method('getListId')
            ->will($this->returnValue($this->listIdentifier));

        $expected = array();
        $actual = $this->proxy->mergeSessionAndBookmark($this->bookmark, array());

        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function mergeSessionAndBookmarkMergesEmptySessionAndBookmarkWithContent()
    {
        $this->bookmark->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($this->savedContent));
        $this->bookmark->expects($this->once())
            ->method('getListId')
            ->will($this->returnValue($this->listIdentifier));

        $expected = $this->modifiedSimpleSessionData;
        $actual = $this->proxy->mergeSessionAndBookmark($this->bookmark, array());

        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function mergeSessionAndBookmarkMergesSimpleSessionAndEmptyArrayInBookmarkContent()
    {
        $this->bookmark->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue('a:0:{}'));
        $this->bookmark->expects($this->once())
            ->method('getListId')
            ->will($this->returnValue($this->listIdentifier));

        $expected = $this->simpleSessionData;
        $actual = $this->proxy->mergeSessionAndBookmark($this->bookmark, $this->simpleSessionData);

        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function mergeSessionAndBookmarkMergesSimpleSessionAndBookmarkWithContent()
    {
        $this->bookmark->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($this->savedContent));
        $this->bookmark->expects($this->once())
            ->method('getListId')
            ->will($this->returnValue($this->listIdentifier));

        $expected = $this->modifiedSimpleSessionData;
        $actual = $this->proxy->mergeSessionAndBookmark($this->bookmark, $this->simpleSessionData);

        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function mergeSessionAndBookmarkMergesComplexSessionAndBookmarkWithContent()
    {
        $this->bookmark->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($this->savedContent));
        $this->bookmark->expects($this->once())
            ->method('getListId')
            ->will($this->returnValue($this->listIdentifier));

        $expected = $this->modifiedComplexSessionData;
        $actual = $this->proxy->mergeSessionAndBookmark($this->bookmark, $this->complexSessionData);

        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function mergeSessionAndBookmarkMergesOtherSessionAndBookmarkWithContent()
    {
        $this->bookmark->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($this->savedContent));
        $this->bookmark->expects($this->once())
            ->method('getListId')
            ->will($this->returnValue($this->listIdentifier));

        $expected = $this->modifiedOtherSessionData;
        $actual = $this->proxy->mergeSessionAndBookmark($this->bookmark, $this->otherSessionData);

        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function mergeSessionAndBookmarkMergesComplexSessionAndBookmarkWithOnlyFiltersInContent()
    {
        $this->bookmark->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($this->savedContentJustFilters));
        $this->bookmark->expects($this->once())
            ->method('getListId')
            ->will($this->returnValue($this->listIdentifier));

        $expected = $this->modifiedComplexSessionDataJustFilters;
        $actual = $this->proxy->mergeSessionAndBookmark($this->bookmark, $this->complexSessionData);

        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function mergeSessionAndBookmarkMergesComplexSessionAndBookmarkWithOnlyHeadersInContent()
    {
        $this->bookmark->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($this->savedContentJustHeaders));
        $this->bookmark->expects($this->once())
            ->method('getListId')
            ->will($this->returnValue($this->listIdentifier));

        $expected = $this->modifiedComplexSessionDataJustHeaders;
        $actual = $this->proxy->mergeSessionAndBookmark($this->bookmark, $this->complexSessionData);

        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function mergeSessionAndBookmarkMergesComplexSessionAndBookmarkWithEmptyListIdentifierInContent()
    {
        $this->bookmark->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($this->savedContentEmpty));
        $this->bookmark->expects($this->once())
            ->method('getListId')
            ->will($this->returnValue($this->listIdentifier));

        $expected = $this->modifiedComplexSessionDataEmpty;
        $actual = $this->proxy->mergeSessionAndBookmark($this->bookmark, $this->complexSessionData);

        $this->assertEquals($expected, $actual);
    }
}
