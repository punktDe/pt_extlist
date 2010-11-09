<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de>
*  All rights reserved
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
 * Default rendering strategy for rendering column captions
 * 
 * @package Domain
 * @subpackage Renderer\Strategy
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Renderer_Default_CaptionRenderer {

	/**
	 * Holds an instance of cObject
	 *
	 * @var tslib_cObj
	 */
	protected $cObj;
	
	
	
	/**
	 * Constructor for rendering strategy
	 *
	 */
	public function __construct() {
		$this->cObj = $GLOBALS['TSFE']->cObj;
	}
	
	
	
	/**
	 * Renders captions
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader
	 * @return Tx_PtExtlist_Domain_Model_List_Row Rendered row
	 */
	public function renderCaptions(Tx_PtExtlist_Domain_Model_List_Header_ListHeader $listHeader) {
		tx_pttools_assert::isNotNull($listHeader, array(message => 'No header data available. 1280408235'));
		
		$row = new Tx_PtExtlist_Domain_Model_List_Row();
		foreach($listHeader as $headerColumn) {
			if($headerColumn->getColumnConfig()->isAccessable()) {
				$label = $headerColumn->getLabel();
				
				// TODO: use Tx_PtExtlist_Utility_RenderValue and configuration for rendering!
				
				// Use TS for rendering
				if(is_array($label)) {
					$conf = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray( $label );
					$label = $this->cObj->cObjGet($conf);
				} elseif(t3lib_div::isFirstPartOfStr($label, 'LLL:')) {
					// Localization
					$label = Tx_Extbase_Utility_Localization::translate($label);
				}
			    $row->createAndAddCell($label, $headerColumn->getColumnIdentifier());
			}
		}
		return $row;
	}
	
}

?>