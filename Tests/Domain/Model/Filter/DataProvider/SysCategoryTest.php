<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2014 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert
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
 * Testcase for sysCategory dataprovider class
 *
 * @package Tests
 * @subpackage Somain\Model\Filter\DataProvider
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_SysCategoryTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * @var string
     */
    protected $dataProviderClassName = '\PunktDe\PtExtlist\Domain\Model\Filter\DataProvider\SysCategory';


    protected $defaultFilterSettings = array(
               'filterIdentifier' => 'tagCloudTest',
               'filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_TagCloudFilter',
               'partialPath' => 'Filter/Options/TagCloudFilter',
               'fieldIdentifier' => 'field1',
               'displayFields' => 'field1',
               'filterField' => 'field3',
               'invert' => '0'
                );


    public function setup()
    {
        $this->initDefaultConfigurationBuilderMock();
    }


    /**
     * @test
     */
    public function classExists()
    {
        $this->assertTrue(class_exists($this->dataProviderClassName), 'CategoryDataProvider ' . $this->dataProviderClassName . ' class not found.');
    }
}
