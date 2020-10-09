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
 * Testcase for mapper mapping domain object data on list data
 *
 * @package Tests
 * @subpackage pt_extlist
 * @author Michael Knoll
 * @see Mapper_DomainObjectMapper
 */
class Tx_PtExtlist_Tests_Domain_DataBackend_Mapper_DomainObjectMapperTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    public function setUp()
    {
        $this->initDefaultConfigurationBuilderMock();
    }


    /** @test */
    public function assertThatClassExists()
    {
        $this->assertTrue(class_exists('Mapper_DomainObjectMapper'));
    }



    /** @test */
    public function getMappedListDataReturnsExpectedListData()
    {
        $domainObjectMapper = new Mapper_DomainObjectMapper($this->configurationBuilderMock);
        $domainObjectMapper->_injectMapperConfiguration($this->createMapperConfiguration());
        $mappedListData = $domainObjectMapper->getMappedListData($this->createMappingTestData());
        $this->assertEquals(count($mappedListData), 4);
        $this->assertEquals(get_class($mappedListData[0]), 'Row');
        $this->assertEquals(count($mappedListData[0]), 2);
        $row = $mappedListData[0]; /* @var $row Row */
        $this->assertEquals(get_class($row['field1']), 'Cell');
        $this->assertEquals($row['field1']->getValue(), 'group 1');
    }



    /** @test */
    public function getMappedListDataThrowsExceptionOnNonExistingMappingConfiguration()
    {
        $domainObjectMapper = new Mapper_DomainObjectMapper($this->configurationBuilderMock);
        try {
            $domainObjectMapper->getMappedListData($this->createMappingTestData());
        } catch (Exception $e) {
            return;
        }
        $this->fail('No Exception has been thrown on non-existing mapping configuration');
    }



    /** @test */
    public function resolveObjectPathResolvesPathAsExpected()
    {
        $domainObjectMapper = new Mapper_DomainObjectMapper($this->configurationBuilderMock);
        $domainObjectMapper->_injectMapperConfiguration($this->createMapperConfiguration());
        $rightObjectMock = $this->getMock('\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup', ['getName'], [], '', false);
        $rightObjectMock->expects($this->any())->method('getName')->will($this->returnValue('test'));
        $groupObjectMock = $this->getMock('\TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup', ['getRight'], [], '', false);
        $groupObjectMock->expects($this->any())->method('getRight')->will($this->returnValue($rightObjectMock));
        $userObjectMock = $this->getMock('\TYPO3\CMS\Extbase\Domain\Model\FrontendUser', ['getGroup'], [], '', false);
        $userObjectMock->expects($this->any())->method('getGroup')->will($this->returnValue($groupObjectMock));
        $objectPath = 'group.right.name';

        $resolvedObject = $domainObjectMapper->resolveObjectPath($userObjectMock, $objectPath);

        $this->assertEquals($rightObjectMock, $resolvedObject);
        $this->assertEquals('test', $resolvedObject->getName());

        // test throw exception on non exisiting property (wrong object path)
        try {
            $objectPath = 'group.rights.name';
            $resolvedObject = $domainObjectMapper->resolveObjectPath($userObjectMock, $objectPath);
        } catch (Exception $e) {
            return;
        }
        $this->fail('No exception has been thrown on trying to access non-existing property!');
    }



    protected function createMappingTestData()
    {
        $objectCollection = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $feGroup1 = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup('group 1');
        $feGroup2 = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup('group 2');
        $feGroup3 = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup('group 3');
        $feGroup4 = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUserGroup('group 4');
        $objectCollection->attach($feGroup1);
        $objectCollection->attach($feGroup2);
        $objectCollection->attach($feGroup3);
        $objectCollection->attach($feGroup4);
        return $objectCollection;
    }



    protected function createMapperConfiguration()
    {
        $mapperConfiguration = new FieldConfigCollection();
        $field1Configuration = new FieldConfig($this->configurationBuilderMock, 'field1', ['table' => '__self__', 'field' => 'title']);
        $field2Configuration = new FieldConfig($this->configurationBuilderMock, 'field2', ['table' => '__self__', 'field' => 'title']);
        $mapperConfiguration->addFieldConfig($field1Configuration);
        $mapperConfiguration->addFieldConfig($field2Configuration);
        return $mapperConfiguration;
    }
}
