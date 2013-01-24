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
	 * @var array
	 */
	protected $minColor = array();
	
	
	/**
	 * Maximum color as integer 
	 * @var array
	 */
	protected $maxColor = array();
	
	
	/**
	 * @see Tx_PtExtlist_Domain_Model_Filter_AbstractFilter::initFilter()
	 */
	protected function initFilter() {

	}
	
	
	protected function initFilterByTsConfig() {
		parent::initFilterByTsConfig();
		
		$this->maxItems = (int) $this->filterConfig->getSettings('maxItems');
		
		$this->minSize = (int) $this->filterConfig->getSettings('minSize');
		$this->maxSize = (int) $this->filterConfig->getSettings('maxSize');
		
		$this->initColors();
	}


	/**
	 * @see Tx_PtExtbase_State_Session_SessionPersistableInterface::persistToSession()
	 * @return array
	 */
	public function persistToSession() {
		return array('filterValues' => current($this->filterValues), 'invert' => $this->invert);
	}

	
	/**
	 * Init the Color range
	 */
	protected function initColors() {
		$minColorHex = $this->filterConfig->getSettings('minColor');
		if(substr($minColorHex,0,1) == '#') $minColorHex = substr($minColorHex, 1);
		
		$maxColorHex = $this->filterConfig->getSettings('maxColor');
		if(substr($maxColorHex,0,1) == '#') $maxColorHex = substr($maxColorHex, 1);
		
		$this->minColor = array(hexdec(substr($minColorHex,0,2)), hexdec(substr($minColorHex,2,2)), hexdec(substr($minColorHex,4,2)));
		$this->maxColor  = array(hexdec(substr($maxColorHex,0,2)), hexdec(substr($maxColorHex,2,2)), hexdec(substr($maxColorHex,4,2)));
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

		return $renderedOptions;
	}

	
	
	protected function addTagCloudMetaDataToOptions(&$renderedOptions) {
		$renderedOptions = array_slice($renderedOptions, 0, $this->maxItems, true);
		
		$tagCount =  count($renderedOptions);
		
		$firstItem = current($renderedOptions);
		$maxItemCount = $firstItem['elementCount'];
		
		$lastItem = end($renderedOptions);
		$minItemCount = $lastItem['elementCount'];

		 
		$iterator = 0;
		foreach($renderedOptions as $key => $option) {
			$renderedOptions[$key]['fontSize'] = $this->calculateSize($minItemCount, $maxItemCount, $option['elementCount']);
			$renderedOptions[$key]['color'] = $this->calculateColor($tagCount, $iterator);
			$iterator++; 
		}
	}
	

	protected function calculateSize($minItemCount, $maxItemCount, $itemCount) {
		$sizeRange = $this->maxSize - $this->minSize;
		$countRange = $maxItemCount - $minItemCount;
		
		$sizePerCount = $sizeRange / $countRange;
		return $this->minSize + (int) ($itemCount * $sizePerCount);
	}
	
	
	/**
	 * Calculate color
	 * 
	 * @param int $colorStep
	 * @param int $iterator
	 */
	protected function calculateColor($tagCount, $iterator) {
		
		$color = '#';
		
		for($i = 0; $i<3; $i++) {
			$delta = ($this->maxColor[$i] - $this->minColor[$i]) / $tagCount;
			
			$newColor = round($this->minColor[$i] + $iterator * $delta);
			if ($newColor < 0) $newColor = 0;
			if ($newColor > 255) $newColor = 255;
			$color .= str_pad(dechex($newColor),'0',STR_PAD_LEFT);
		}
		
		return $color;
	}
	
}