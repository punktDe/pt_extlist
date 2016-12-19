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
 * @author Christiane Helmchen
 * @see Tx_PtExtlist_Domain_Model_Bookmark_Bookmark
 */
class Tx_PtExtlist_Tests_Domain_Model_Bookmark_BookmarkTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * @var string
     */
    protected $proxyClass;



    /**
     * @var Tx_PtExtlist_Domain_Model_Bookmark_Bookmark
     */
    protected $proxy;



    public function setUp()
    {
        $this->proxyClass = $this->buildAccessibleProxy('Tx_PtExtlist_Domain_Model_Bookmark_Bookmark');
        $this->proxy = new $this->proxyClass;
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
        $this->assertTrue(class_exists('Tx_PtExtlist_Domain_Model_Bookmark_Bookmark'));
    }



    /**
     * @test
     */
    public function canAccessConstants()
    {
        $className = 'Tx_PtExtlist_Domain_Model_Bookmark_Bookmark';
        $constantName = 'PTEXTLIST_BOOKMARK_PUBLIC';
        $expected = 1;
        $actual = constant(sprintf('%s::%s', $className, $constantName));
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function getTypeGetsType()
    {
        $expected = 2;
        $this->proxy->_set('type', $expected);
        $actual = $this->proxy->getType();
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function setTypeSetsType()
    {
        $expected = 3;
        $this->proxy->setType($expected);
        $actual = $this->proxy->_get('type');
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function getNameGetsName()
    {
        $expected = 'myname';
        $this->proxy->_set('name', $expected);
        $actual = $this->proxy->getName();
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function setNameSetsName()
    {
        $expected = 'notmyname';
        $this->proxy->setName($expected);
        $actual = $this->proxy->_get('name');
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function getListIdGetsListId()
    {
        $expected = 'listid';
        $this->proxy->_set('listId', $expected);
        $actual = $this->proxy->getListId();
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function setListIdSetsListId()
    {
        $expected = 'listid';
        $this->proxy->setListId($expected);
        $actual = $this->proxy->_get('listId');
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function getFeGroupGetsFeGroup()
    {
        $expected = $this->getMock('\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup');
        $this->proxy->_set('feGroup', $expected);
        $actual = $this->proxy->getFeGroup();
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function setFeGroupSetsFegroup()
    {
        $expected = $this->getMock('\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup');
        $this->proxy->setFeGroup($expected);
        $actual = $this->proxy->_get('feGroup');
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function getFeUserGetsFeUser()
    {
        $expected = $this->getMock('\TYPO3\CMS\Extbase\Domain\Model\FrontendUser');
        $this->proxy->_set('feUser', $expected);
        $actual = $this->proxy->getFeUser();
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function setFeUserSetsFeUser()
    {
        $expected = $this->getMock('\TYPO3\CMS\Extbase\Domain\Model\FrontendUser');
        $this->proxy->setFeUser($expected);
        $actual = $this->proxy->_get('feUser');
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function getDescriptionGetsDescription()
    {
        $expected = 'desc';
        $this->proxy->_set('description', $expected);
        $actual = $this->proxy->getDescription();
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function setDescriptionSetsDescription()
    {
        $expected = 'desc';
        $this->proxy->setDescription($expected);
        $actual = $this->proxy->_get('description');
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function getCreateDateGetsCreateDate()
    {
        $expected = '123456789';
        $this->proxy->_set('createDate', $expected);
        $actual = $this->proxy->getCreateDate();
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function setCreateDateSetsCreateDate()
    {
        $expected = '987654321';
        $this->proxy->setCreateDate($expected);
        $actual = $this->proxy->_get('createDate');
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function getContentGetsContent()
    {
        $expected = 'test';
        $this->proxy->_set('content', $expected);
        $actual = $this->proxy->getContent();
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function setContentSetsContent()
    {
        $expected = 'test';
        $this->proxy->setContent($expected);
        $actual = $this->proxy->_get('content');
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function getPidGetsPid()
    {
        $expected = '21';
        $this->proxy->_set('pid', $expected);
        $actual = $this->proxy->getPid();
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function setPidSetsPid()
    {
        $expected = '42';
        $this->proxy->setPid($expected);
        $actual = $this->proxy->_get('pid');
        $this->assertEquals($expected, $actual);
    }



    /**
     * @test
     */
    public function getObjectNamespaceBuildsCorrectObjectNamespace()
    {
        $listId = 'hallo';
        $uid = 3;
        $expected = 'hallo.bookmark.3';
        $this->proxy->_set('listId', $listId);
        $this->proxy->_set('uid', $uid);
        $actual = $this->proxy->getObjectNamespace();
        $this->assertEquals($expected, $actual);
    }
}
