<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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

use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Performance Testcase
 *
 * @package Tests
 * @subpackage Performance
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Tests_Performance_PerformanceTest extends Tx_PtExtlist_Tests_BaseTestcase
{
    /**
     * @var string
     */
    protected $baseConfigTSFile;



    /**
     * @var array
     */
    protected $listConfiguration = [];



    public function setUp()
    {
        $this->baseConfigTSFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pt_extlist') . 'Configuration/TypoScript/setup.txt';
    }



    /**
     * @return array
     */
    public function performanceDataProvider()
    {
        return [
            'performance 5:1 - Framework only' => [5, 1],
            //'performance 5:10000' => array(5,10000),
            //'performance 5:40000' => array(5,40000),
        ];
    }



    /**
     * @dataProvider performanceDataProvider
     * @test
     */
    public function listDataPerformance($colCount, $rowCount)
    {
        $this->markTestSkipped('Currently not running on Jenkins, hence skipped.');

        $listSettings = $this->getExtListTypoScript();

        $memoryBefore = memory_get_usage(true);
        $timeBefore = microtime(true);

        Tx_PtExtlist_ExtlistContext_ExtlistContextFactory::setExtListTyposSript($listSettings);
        //die('here');

        $extListContext = Tx_PtExtlist_ExtlistContext_ExtlistContextFactory::getContextByListIdentifier('performanceTestList');
        $extListContext->getDataBackend()->setColCount($colCount)->setRowCount($rowCount);
        $list = $extListContext->getList(true);
        $renderedListData = $extListContext->getRendererChain()->renderList($list->getListData());


        $usedMemory = memory_get_usage(true) - $memoryBefore;
        $readableMemoryUsage = $usedMemory / (1024 * 1024);
        $readableMemoryPeakUsage = memory_get_peak_usage(true) / (1024 * 1024);

        $usedMicroseconds = microtime(true) - $timeBefore;

        $info = sprintf("
			Memory Usage: %s MB <br />
			Memory Peak Usage %s MB <br />
			Processing Time: %s Mircroseconds.", $readableMemoryUsage, $readableMemoryPeakUsage, $usedMicroseconds);

        //die($info);

        $this->assertTrue(true);
    }



    /**
     * @test
     * @dataProvider performanceDataProvider
     * @param $colCount
     * @param $rowCount
     */
    public function iterationListDataPerformance($colCount, $rowCount)
    {
        $listSettings = $this->getExtListTypoScript();

        $memoryBefore = memory_get_usage(true);
        $timeBefore = microtime(true);

        // TODO we are calling a static method on an object here... make this non-static and remove static methods from extlist context factory!
        Tx_PtExtlist_ExtlistContext_ExtlistContextFactory::setExtListTyposSript($listSettings);
        $extListContext = Tx_PtExtlist_ExtlistContext_ExtlistContextFactory::getContextByListIdentifier('performanceTestList');
        $extListContext->getDataBackend()->setColCount($colCount)->setRowCount($rowCount);

        $iterationListData = $extListContext->getDataBackend()->getIterationListData();

        $iterationListData->current();

        /**
         * This loop renders the complete data set
         */
        foreach ($iterationListData as $row) {
            /**  @var $row Row */
        }

        $usedMemory = memory_get_usage(true) - $memoryBefore;
        $readableMemoryUsage = $usedMemory / (1024 * 1024);
        $readableMemoryPeakUsage = memory_get_peak_usage(true) / (1024 * 1024);

        $usedMicroseconds = microtime(true) - $timeBefore;

        $info = sprintf("
			Memory Usage: %s MB <br />
			Memory Peak Usage %s MB <br />
			Processing Time: %s Mircroseconds.", $readableMemoryUsage, $readableMemoryPeakUsage, $usedMicroseconds);

        //die($info);

        $this->assertTrue(true);
    }



    protected function getExtListTypoScript()
    {
        $extListConfigFile = __DIR__ . '/TestListConfiguration.ts';

        $tSString = $this->readTSString($this->baseConfigTSFile);
        $tSString .= $this->readTSString($extListConfigFile);

        $parserInstance = GeneralUtility::makeInstance(TypoScriptParser::class);
        /** @var $parserInstance TypoScriptParser */
        $tSString = $parserInstance->checkIncludeLines($tSString);
        $parserInstance->parse($tSString);


        $tsSettings = $parserInstance->setup;

        $typoScript = GeneralUtility::makeInstance(TypoScriptService::class)->convertTypoScriptArrayToPlainArray($tsSettings);

        return $typoScript['plugin']['tx_ptextlist'];
    }



    /**
     * Read the list configuration from  file
     *
     * @param $pathAndFileName
     * @return string
     */
    protected function readTSString($pathAndFileName)
    {
        $typoScriptArray = file($pathAndFileName);

        if ($typoScriptArray === false) {
            $this->fail('Could not read from file ' . $pathAndFileName);
        }

        return implode("\n", $typoScriptArray);
    }
}
