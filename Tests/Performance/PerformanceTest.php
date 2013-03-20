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

/**
 * Performance Testcase
 *
 * @package Tests
 * @subpackage Performance
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Tests_Performance_Performance_testcase extends Tx_PtExtlist_Tests_BaseTestcase {

	/**
	 * @var string
	 */
	protected $baseConfigTSFile;


	/**
	 * @var array
	 */
	protected $listConfiguration = array();


	public function setup() {
		$this->baseConfigTSFile = t3lib_extMgm::extPath('pt_extlist') . 'Configuration/TypoScript/setup.txt';
	}


	/**
	 * @return array
	 */
	public function performanceDataProvider() {
		return array(
			'performance 5:1 - Framework only' => array(5,1),
			//'performance 5:10000' => array(5,10000),
			//'performance 5:40000' => array(5,40000),
		);
	}



	/**
	 * @dataProvider performanceDataProvider
	 * @test
	 */
	public function listDataPerformance($colCount, $rowCount) {

		$listSettings = $this->getExtListTypoScript();

		$memoryBefore = memory_get_usage(true);
		$timeBefore = microtime(true);

		Tx_PtExtlist_ExtlistContext_ExtlistContextFactory::setExtListTyposSript($listSettings);
		$extListContext = Tx_PtExtlist_ExtlistContext_ExtlistContextFactory::getContextByListIdentifier('performanceTestList');
		$extListContext->getDataBackend()->setColCount($colCount)->setRowCount($rowCount);
		$list= $extListContext->getList(TRUE);
		$renderedListData = $extListContext->getRendererChain()->renderList($list->getListData());


		$usedMemory = memory_get_usage(true) - $memoryBefore;
		$readableMemoryUsage = $usedMemory / (1024*1024);
		$readableMemoryPeakUsage = memory_get_peak_usage(true) / (1024*1024);

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
	public function iterationListDataPerformance($colCount, $rowCount) {
		$listSettings = $this->getExtListTypoScript();

		$memoryBefore = memory_get_usage(true);
		$timeBefore = microtime(true);

		Tx_PtExtlist_ExtlistContext_ExtlistContextFactory::setExtListTyposSript($listSettings);
		$extListContext = Tx_PtExtlist_ExtlistContext_ExtlistContextFactory::getContextByListIdentifier('performanceTestList');
		$extListContext->getDataBackend()->setColCount($colCount)->setRowCount($rowCount);

		$iterationListData = $extListContext->getDataBackend()->getIterationListData();

		$iterationListData->current();

		/**
		 * This loop renders the complete data set
		 */
		foreach($iterationListData as $row) { /**  @var $row Tx_PtExtlist_Domain_Model_List_Row */
		}

		$usedMemory = memory_get_usage(true) - $memoryBefore;
		$readableMemoryUsage = $usedMemory / (1024*1024);
		$readableMemoryPeakUsage = memory_get_peak_usage(true) / (1024*1024);

		$usedMicroseconds = microtime(true) - $timeBefore;

		$info = sprintf("
			Memory Usage: %s MB <br />
			Memory Peak Usage %s MB <br />
			Processing Time: %s Mircroseconds.", $readableMemoryUsage, $readableMemoryPeakUsage, $usedMicroseconds);

		//die($info);

		$this->assertTrue(true);

	}


	protected function getExtListTypoScript() {

		$extListConfigFile = __DIR__ . '/TestListConfiguration.ts';

		$tSString = $this->readTSString($this->baseConfigTSFile);
		$tSString .= $this->readTSString($extListConfigFile);

		$parserInstance = t3lib_div::makeInstance('t3lib_tsparser'); /** @var $parserInstance t3lib_tsparser */
		$tSString = $parserInstance->checkIncludeLines($tSString);
		$parserInstance->parse($tSString);


		$tsSettings = $parserInstance->setup;

		$typoScript = Tx_PtExtbase_Compatibility_Extbase_Service_TypoScript::convertTypoScriptArrayToPlainArray($tsSettings);

		return $typoScript['plugin']['tx_ptextlist'];
	}



	/**
	 * Read the list configuration from  file
	 *
	 * @param $pathAndFileName
	 * @return string
	 */
	protected function readTSString($pathAndFileName) {

		$typoScriptArray = file($pathAndFileName);

		if($typoScriptArray === FALSE) {
			$this->fail('Could not read from file ' . $pathAndFileName);
		}

		return implode("\n", $typoScriptArray);
	}

}

?>