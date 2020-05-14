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
 * Testcase for row object. 
 *
 * @author Daniel Lienert
 * @author Michael Knoll 
 * @package Tests
 * @subpackage Domain\List
 * @see Row
 */
class Tx_PtExtlist_Tests_Domain_Model_List_RowTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    protected $dataArray = [
        'specialValues' => [
            'key' => 'value'
        ],

        'columns' => [
            'col1' => [
                'value' => 'testContent1',
                'specialValues' => null,
                'rowIndex' => null,
                'columnIndex' => null,
                'cssClass' => null
            ],

            'col2' => [
                'value' => 'testContent2',
                'specialValues' => null,
                'rowIndex' => null,
                'columnIndex' => null,
                'cssClass' => null
            ]
        ]
    ];



    /** @test */
    public function assertThatClassExists()
    {
        $this->assertClassExists('Row');
    }



    /**
     * @test
     */
    public function addCell()
    {
        $row = new Row();
        $row->createAndAddCell('testContent', 'testKey');

        $testCell = $row->getCell('testKey');

        $this->assertEquals('testContent', $testCell->getValue());
    }


    /**
     * @test
     */
    public function rowContent()
    {
        $row = new Row();
        $row->createAndAddCell('testContent', 'testKey');
        foreach ($row as $columnName => $cellContent) {
            $this->assertEquals($columnName, 'testKey');
            $this->assertEquals($cellContent->getValue(), 'testContent');
        }
    }


    /**
     * @test
     */
    public function rowArrayAccess()
    {
        $row = new Row();
        $row->createAndAddCell('testContent', 'testKey');
        $this->assertEquals($row['testKey']->getValue(), 'testContent');
    }


    public function setByArrayReducesCellCount()
    {
    }

    public function setByArrayIncreasesCellCount()
    {
    }




    /**
     * @test
     */
    public function setByArray()
    {
        $row = new Row();
        $row->setByArray($this->dataArray);

        $this->assertEquals(2, $row->count());
        $this->assertEquals('testContent1', $row->getCell('col1')->getValue());
        $this->assertEquals('testContent2', $row->getCell('col2')->getValue());
        $this->assertEquals(['key' => 'value'], $row->getSpecialValues());
    }


    /**
     * @test
     */
    public function getAsArray()
    {
        $row = new Row();
        $row->createAndAddCell('testContent1', 'col1');
        $row->createAndAddCell('testContent2', 'col2');
        $row->addSpecialValue('key', 'value');

        $actual = $row->getAsArray();

        $this->assertEquals($this->dataArray, $actual);
    }
}
