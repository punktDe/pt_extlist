<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
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
 * Aggregate Column Config Object 
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package Domain
 * @subpackage Configuration\Aggregates
 */
class Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateColumnConfig implements Tx_PtExtlist_Domain_Configuration_ColumnConfigInterface {
	
	/**
	 * Identifier of list to which this column belongs to
	 *
	 * @var string
	 */
	protected $listIdentifier;
	
	
	/** 
	 * @var string
	 */
	protected $columnIdentifier;

	
	/** 
	 * @var array
	 */
	protected $aggregateDataIdentifier;

	
	/**
	 * @var array
	 */
	protected $renderUserFunctions = NULL;
	
	
	/**
	 * @var array
	 */
	protected $renderObj = null;
	
	
	/**
	 * Path to fluid template
	 * @var string
	 */
	protected $renderTemplate;
	
		
	/**
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @param string $columnIdentifier 
	 * @param array $aggregateColumnSettings array of coumn settings
	 * @return void
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $columnIdentifier,  array $aggregateColumnSettings) {
		tx_pttools_assert::isNotEmptyString($columnIdentifier, array(message => 'Column identifier for aggregate not given 1282916617'));
		tx_pttools_assert::isNotEmptyString($aggregateColumnSettings['aggregateDataIdentifier'], array(message => 'Aggregate data identifier not given for aggregate column "'.$columnIdentifier.'" 1282916619'));
		
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
		$this->columnIdentifier = $columnIdentifier;
		$this->aggregateDataIdentifier = t3lib_div::trimExplode(',', $aggregateColumnSettings['aggregateDataIdentifier']);
				
		$this->setOptionalSettings($aggregateColumnSettings);
	}	
	
	
	
	/**
	 * Set optional definable columnsettings
	 * 
	 * @param $columnSettings
	 * @return void
	 */
	protected function setOptionalSettings($aggregateColumnSettings) {

		if(array_key_exists('renderUserFunctions', $columnSettings) && is_array($aggregateColumnSettings['renderUserFunctions'])) {
			asort($aggregateColumnSettings['renderUserFunctions']);
			$this->renderUserFunctions = $aggregateColumnSettings['renderUserFunctions'];
		}
				
		if(array_key_exists('renderObj', $aggregateColumnSettings)) {
        	$this->renderObj = Tx_Extbase_Utility_TypoScript::convertPlainArrayToTypoScriptArray(array('renderObj' => $aggregateColumnSettings['renderObj']));
        }
        
		if(array_key_exists('renderTemplate', $aggregateColumnSettings)) {
			$this->renderTemplate = $aggregateColumnSettings['renderTemplate'];
		}
	}
	
	
	
	/**
	 * @return string listIdentifier
	 */
	public function getListIdentifier() {
		return $this->listIdentifier;
	}
	
	
	
	/**
	 * @return string columnIdentifier
	 */
	public function getColumnIdentifier() {
		return $this->columnIdentifier;
	}
	
	
	
	/**
	 * @return array aggregateDataIdentifier
	 */
	public function getAggregateDataIdentifier() {
		return $this->aggregateDataIdentifier;
	} 
	
	
	
	/**
	 * This method exists to fullfill the interface
	 * The renderer expects the method to map da data to the column
	 * 
	 * (non-PHPdoc)
	 * @see Classes/Domain/Configuration/Tx_PtExtlist_Domain_Configuration_ColumnConfigInterface::getFieldIdentifier()
	 */
	public function getFieldIdentifier() {
		return $this->aggregateDataIdentifier;
	}
	
	
	
	public function getSpecialCell() {
		return '';
	}
	
	
	public function getContainsArrayData() {
		return false;
	}
	
	
	/**
	 * @return array
	 */
	public function getRenderObj() {
		return $this->renderObj;
	}
	
	
	
	/**
	 * @return array
	 */
	public function getRenderUserFunctions() {
		return $this->renderUserFunctions;
	}
	
	
	
    /**
     * @return string renderTemplate
     */
    public function getRenderTemplate() {
    	return $this->renderTemplate;
    }
    
    
    public function getCellCssClass() {
    	// TODO implement me
    	return NULL;
    }
}
?>