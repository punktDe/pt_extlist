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
 * Class implements the tagCloud filter
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter
 */
class Tx_PtExtlist_Domain_Model_Filter_TagCloudFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractOptionsFilter {	

	/**
	 * Maximum of elements to display 
	 * @var int
	 */
	protected $maxItems;
	
	
	/**
	 * Minimum font Size 
	 * @var int
	 */	
	protected $minSize;
	
	
	/**
	 * Maximum font Size 
	 * @var int
	 */	
	protected $maxSize;
	
	
	/**
	 * Minimum color as integer 
	 * @var int
	 */
	protected $minColor;
	
	
	/**
	 * Maximum color as integer 
	 * @var int
	 */
	protected $maxColor;
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilter()
	 */
	protected function initFilter() {
		$this->maxItems = (int) $this->filterConfig->getSettings('maxItems');
		
		$this->minSize = (int) $this->filterConfig->getSettings('minSize');
		$this->maxSize = (int) $this->filterConfig->getSettings('maxSize');
		
		$this->minColor = (int) hexdec($this->filterConfig->getSettings('minColor'));
		$this->maxColor = (int) hexdec($this->filterConfig->getSettings('maxColor'));
	}
	
	
	/**
	 * Returns an associative array of options as possible filter values
	 *
	 * @return array
	 */
	public function getOptions() {
		$dataProvider = Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderFactory::createInstance($this->filterConfig);

		$renderedOptions = $dataProvider->getRenderedOptions();
		
		$this->addTagCloudMetaDataToOptions($renderedOptions);
		$this->addInactiveOption($renderedOptions);
		$this->setSelectedOptions($renderedOptions);

		$GLOBALS['trace'] = 1;	trace($renderedOptions ,0,'Quick Trace in file ' . basename( __FILE__) . ' : ' . __CLASS__ . '->' . __FUNCTION__ . ' @ Line : ' . __LINE__ . ' @ Date : '   . date('H:i:s'));	$GLOBALS['trace'] = 0; // RY25 TODO Remove me
		
		return $renderedOptions;
	}

	
	protected function addTagCloudMetaDataToOptions(&$renderedOptions) {
		$renderedOptions = array_slice($renderedOptions, 0, $this->maxItems, true);
		
		$sizeStep = ($this->maxSize - $this->minSize) / count($renderedOptions);
		$colorStep = ($this->maxColor - $this->minColor) / count($renderedOptions);
		 
		$iterator = 0;
		
		foreach($renderedOptions as $key => $option) {
			$renderedOptions[$key]['fontSize'] = (int) ($this->maxSize - $iterator * $sizeStep);
			$renderedOptions[$key]['color'] = dechex((int) ($this->maxColor - $iterator * $colorStep));
			$iterator++; 
		}
	}
	
}