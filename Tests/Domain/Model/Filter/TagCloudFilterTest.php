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
 * Testcase for TagCloud Filter class
 *
 * @package Tests
 * @subpackage Domain\Model\Filter
 * @author Daniel Lienert 
 */
 class Tx_PtExtlist_Tests_Domain_Model_Filter_TagCloudFilter_testcase extends Tx_PtExtlist_Tests_BaseTestcase { 
	
 	protected $filterSettings = array('filterClassName' => 'Tx_PtExtlist_Domain_Model_Filter_TagCloudFilter',
								'partialPath' => 'partialPath', 
								'fieldIdentifier' => 'field1',
								'filterIdentifier' => 'testtagCloudFilter',
 								'maxItems' => 20,
		
								'minColor' => '#b8cffa',
								'maxColor' => '#1c448d',
		
								'minSize' => 9,
								'maxSize' => 24);
 	
 	
 	public function setup() {
 		$this->initDefaultConfigurationBuilderMock();
 	}
 	
 	
 	/**
 	 * @test
 	 */
    public function setTagCloudColorsFromSettings() {
    	$tagCloudFilter = $this->buildAccessibleTagCloudFilter($this->filterSettings);
    	$tagCloudFilter->_call('initColors');

    	$minColor = $tagCloudFilter->_get('minColor');
    	$this->assertTrue(is_array($minColor), 'MinColor is no array!');
    	$this->assertEquals(count($minColor),3);
    	$this->assertEquals($minColor[0],184);
    	$this->assertEquals($minColor[1],207);
    	$this->assertEquals($minColor[2],250);

    	$maxColor = $tagCloudFilter->_get('maxColor');
    	$this->assertTrue(is_array($maxColor));
    	$this->assertEquals(count($maxColor),3);
    	$this->assertEquals($maxColor[0],28);
    	$this->assertEquals($maxColor[1],68);
    	$this->assertEquals($maxColor[2],141);
    	
    }
	
	
	
	protected function buildAccessibleTagCloudFilter($filterSettings) {
		
		$tagCloudFilterMock = $this->getAccessibleMock('Tx_PtExtlist_Domain_Model_Filter_TagCloudFilter', array('dummy'), array());
		
		$filterConfig = new Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig($this->configurationBuilderMock, $filterSettings, 'someOtherBox');
		
		$tagCloudFilterMock->injectFilterConfig($filterConfig);
		
		return $tagCloudFilterMock;
	}
}
 
?>