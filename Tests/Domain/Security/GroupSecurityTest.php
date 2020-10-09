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
 * Class implements a testcase for group security
 *
 * @author Christoph Ehscheidt
 * @package Tests
 * @subpackage Security
 * @see Tx_PtExtlist_Domain_Security_GroupSecurity
 */
class Tx_PtExtlist_Tests_Domain_Security_GroupSecurityTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    protected $filterConfigMock;



    protected $securityMock;



    public function setUp()
    {
        $this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance();

        $settings = $this->configurationBuilderMock->getSettings();

        $settings['prototype'] = [];
        $settings['listIdentifier'] = 'test';

        $settings['listConfig']['test']['fields']['field1']['accessGroups'] = 'foo, bar';
        $settings['listConfig']['test']['fields']['field3']['accessGroups'] = 'foobar';
        $settings['listConfig']['test']['fields']['field4']['accessGroups'] = 'whatever';
        $settings['listConfig']['test']['fields']['field4']['table'] = 'whatever';
        $settings['listConfig']['test']['fields']['field4']['field'] = 'whatever';
        $settings['listConfig']['test']['fields']['field5']['accessGroups'] = '';
        $settings['listConfig']['test']['fields']['field5']['table'] = 'whatever';
        $settings['listConfig']['test']['fields']['field5']['field'] = 'whatever';


        $this->configurationBuilderMock = Tx_PtExtlist_Tests_Domain_Configuration_ConfigurationBuilderMock::getInstance($settings);

        $this->filterConfigMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig', ['getAccessGroups', 'getFieldIdentifier'], [$this->configurationBuilderMock], '', false);

        $this->securityMock = $this->getAccessibleMock('Tx_PtExtlist_Domain_Security_GroupSecurity', ['dummy'], [], '', false);
    }



    public function testGroups()
    {
        $collection = $this->configurationBuilderMock->buildFieldsConfiguration();
        $groups = $collection->getItemById('field1')->getAccessGroups();
        $this->assertEquals('foo', $groups[0]);
        $this->assertEquals('bar', $groups[1]);
    }



    public function testNoAccessSet()
    {
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field2')));

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);

        $this->assertTrue($access);
    }



    public function testFieldAccess()
    {
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field1')));


        $this->securityMock->_set('userGroups', 'foo');
        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertTrue($access);

        $this->securityMock->_set('userGroups', 'bar');
        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertTrue($access);
    }



    public function testMoreFieldsAccess()
    {
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field1,field3')));


        $this->securityMock->_set('userGroups', 'foobar, bar');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertTrue($access);
    }



    public function testFieldDenied()
    {
        $this->filterConfigMock
                ->expects($this->once())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field1')));

        $this->securityMock->_set('userGroups', 'whatever');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertFalse($access);
    }



    public function testMoreFieldsDenied()
    {
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field1,field3')));


        $this->securityMock->_set('userGroups', 'foobar,whatever');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertFalse($access);
    }



    public function testFilterAccess()
    {
        // Field2 doesn't have a accessRule
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field2')));

        $this->filterConfigMock
                ->expects($this->any())
                ->method('getAccessGroups')
                ->will($this->returnValue(['foobar']));

        $this->securityMock->_set('userGroups', 'foobar');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertTrue($access);
    }



    public function testFilterDenied()
    {
        // Field2 doesn't have a accessRule
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field2')));

        $this->filterConfigMock
                ->expects($this->any())
                ->method('getAccessGroups')
                ->will($this->returnValue(['foobar']));

        $this->securityMock->_set('userGroups', 'noaccess');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertFalse($access);
    }



    public function testFilterAndFieldSameGroupsAccess()
    {
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field1')));

        $this->filterConfigMock
                ->expects($this->any())
                ->method('getAccessGroups')
                ->will($this->returnValue(['foo']));

        $this->securityMock->_set('userGroups', 'foo');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertTrue($access);
    }



    public function testFilterAndFieldDifferentGroupsAccess()
    {
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field1')));

        $this->filterConfigMock
                ->expects($this->any())
                ->method('getAccessGroups')
                ->will($this->returnValue(['foobar']));

        $this->securityMock->_set('userGroups', 'foo,foobar');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertTrue($access);
    }



    public function testFilterAndFieldSameGroupsDenied()
    {
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field1')));

        $this->filterConfigMock
                ->expects($this->any())
                ->method('getAccessGroups')
                ->will($this->returnValue(['foo']));

        $this->securityMock->_set('userGroups', 'foobar');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertFalse($access);
    }



    public function testFilterAndFieldDifferentGroupsDeniedCausedByOnlyOneGroup()
    {
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field1')));

        $this->filterConfigMock
                ->expects($this->any())
                ->method('getAccessGroups')
                ->will($this->returnValue(['foobar']));

        $this->securityMock->_set('userGroups', 'foo');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertFalse($access);
    }



    public function testFilterAndFieldDifferentGroupsDeniedCausedByWrongGroups()
    {
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field1')));

        $this->filterConfigMock
                ->expects($this->any())
                ->method('getAccessGroups')
                ->will($this->returnValue(['bar']));

        $this->securityMock->_set('userGroups', 'foo,foobar');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertFalse($access);
    }



    public function testFilterAndFieldSameGroupsDeniedCausedByWrongGroups()
    {
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field1')));

        $this->filterConfigMock
                ->expects($this->any())
                ->method('getAccessGroups')
                ->will($this->returnValue(['foo']));

        $this->securityMock->_set('userGroups', 'foobar');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertFalse($access);
    }



    public function testEmptyAccessGroups()
    {
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field5')));

        $this->filterConfigMock
                ->expects($this->any())
                ->method('getAccessGroups')
                ->will($this->returnValue([' ']));

        $this->securityMock->_set('userGroups', 'foobar');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertTrue($access);
    }



    public function testEmptyAccessGroupsSpaces()
    {
        $this->filterConfigMock
                ->expects($this->any())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($this->buildFieldCollection('field5')));

        $this->filterConfigMock
                ->expects($this->any())
                ->method('getAccessGroups')
                ->will($this->returnValue([' ']));

        $this->securityMock->_set('userGroups', 'foobar');

        $access = $this->securityMock->isAccessableFilter($this->filterConfigMock, $this->configurationBuilderMock);
        $this->assertTrue($access);
    }



    public function testColumnAccess()
    {
        $fieldConfigCollection = new FieldConfigCollection();
        $fieldConfigCollection->addFieldConfig(new FieldConfig($this->configurationBuilderMock, 'field2', ['special' => 'test']));

        $columnConfigMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig', ['getFieldIdentifier', 'getAccessGroups'], [], '', false);
        $columnConfigMock
                ->expects($this->once())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($fieldConfigCollection));
        $columnConfigMock
                ->expects($this->once())
                ->method('getAccessGroups')
                ->will($this->returnValue(['foo']));

        $this->securityMock->_set('userGroups', 'foo');

        $access = $this->securityMock->isAccessableColumn($columnConfigMock, $this->configurationBuilderMock);
        $this->assertTrue($access);
    }



    public function testColumnDenied()
    {
        $fieldConfigCollection = new FieldConfigCollection();
        $fieldConfigCollection->addFieldConfig(new FieldConfig($this->configurationBuilderMock, 'field2', ['special' => 'test']));

        $columnConfigMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig', ['getFieldIdentifier', 'getAccessGroups'], [], '', false);
        $columnConfigMock
                ->expects($this->once())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($fieldConfigCollection));
        $columnConfigMock
                ->expects($this->once())
                ->method('getAccessGroups')
                ->will($this->returnValue(['foo']));

        $this->securityMock->_set('userGroups', 'foobar');

        $access = $this->securityMock->isAccessableColumn($columnConfigMock, $this->configurationBuilderMock);
        $this->assertFalse($access);
    }



    public function testColumnAndFieldSameGroupsAccess()
    {
        $fieldConfigCollection = new FieldConfigCollection();
        $fieldConfigCollection->addFieldConfig(new FieldConfig($this->configurationBuilderMock, 'field1', ['special' => 'test', 'accessGroups' => 'foo, bar']));

        $columnConfigMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig', ['getFieldIdentifier', 'getAccessGroups'], [], '', false);
        $columnConfigMock
                ->expects($this->once())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($fieldConfigCollection));

        $columnConfigMock
                ->expects($this->once())
                ->method('getAccessGroups')
                ->will($this->returnValue(['foo']));

        $this->securityMock->_set('userGroups', 'foo');

        $access = $this->securityMock->isAccessableColumn($columnConfigMock, $this->configurationBuilderMock);
        $this->assertTrue($access);
    }



    public function testColumnAndFieldDeniedCausedByDifferentGroups()
    {
        $fieldConfigCollection = new FieldConfigCollection();
        $fieldConfigCollection->addFieldConfig(new FieldConfig($this->configurationBuilderMock, 'field1', ['special' => 'test', 'accessGroups' => 'foo, bar']));

        $columnConfigMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig', ['getFieldIdentifier', 'getAccessGroups'], [], '', false);
        $columnConfigMock
                ->expects($this->once())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($fieldConfigCollection));

        $columnConfigMock
                ->expects($this->once())
                ->method('getAccessGroups')
                ->will($this->returnValue(['foobar']));

        $this->securityMock->_set('userGroups', 'foo');

        $access = $this->securityMock->isAccessableColumn($columnConfigMock, $this->configurationBuilderMock);
        $this->assertFalse($access);
    }



    public function testColumnAndFieldDifferentGroupsAccess()
    {
        $fieldConfigCollection = new FieldConfigCollection();
        $fieldConfigCollection->addFieldConfig(new FieldConfig($this->configurationBuilderMock, 'field1', ['special' => 'test', 'accessGroups' => 'foo, bar']));

        $columnConfigMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig', ['getFieldIdentifier', 'getAccessGroups'], [], '', false);
        $columnConfigMock
                ->expects($this->once())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($fieldConfigCollection));

        $columnConfigMock
                ->expects($this->once())
                ->method('getAccessGroups')
                ->will($this->returnValue(['foobar', 'whatever']));

        $this->securityMock->_set('userGroups', 'foo,foobar');

        $access = $this->securityMock->isAccessableColumn($columnConfigMock, $this->configurationBuilderMock);
        $this->assertTrue($access);
    }



    public function testColumnAndFieldDifferentGroupsDeniedCausedByOnlyOneGroup()
    {
        $fieldConfigCollection = new FieldConfigCollection();
        $fieldConfigCollection->addFieldConfig(new FieldConfig($this->configurationBuilderMock, 'field1', ['special' => 'test', 'accessGroups' => 'foo, bar']));

        $columnConfigMock = $this->getMock('Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig', ['getFieldIdentifier', 'getAccessGroups'], [], '', false);
        $columnConfigMock
                ->expects($this->once())
                ->method('getFieldIdentifier')
                ->will($this->returnValue($fieldConfigCollection));

        $columnConfigMock
                ->expects($this->never())
                ->method('getAccessGroups');


        $this->securityMock->_set('userGroups', 'foobar');

        $access = $this->securityMock->isAccessableColumn($columnConfigMock, $this->configurationBuilderMock);
        $this->assertFalse($access);
    }



    protected function buildFieldCollection($fields)
    {
        $fieldIdentifierList = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $fields);
        return $this->configurationBuilderMock->buildFieldsConfiguration()->extractCollectionByIdentifierList($fieldIdentifierList);
    }
}
