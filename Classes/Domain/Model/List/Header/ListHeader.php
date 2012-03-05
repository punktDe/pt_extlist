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
 * Class implements list header collection which consists of all header columns that make up the header of the list.
 *
 * As the HeaderColumns represent in fact the columns, this collection should simply named columnCollection
 * TODO: Rename listHeader to ColumnCollection
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\List\Header
 */
class Tx_PtExtlist_Domain_Model_List_Header_ListHeader
    extends Tx_PtExtlist_Domain_Model_List_Row
    implements Tx_PtExtbase_State_GpVars_GpVarsInjectableInterface {

	/**
	 * ListIdentifier of the current list
	 * @var string
	 */
	protected $listIdentifier;



	/**
	 * @var array
	 */
	protected $gpVarData = array();


	
	/**
	 * @param string $listIdentifier
	 */
	public function __construct($listIdentifier) {
		$this->listIdentifier = $listIdentifier;
	}



	/**
	 * Injects GetPost Vars into object
	 *
	 * @param array $GPVars GP Var data to be injected into the object
	 */
	public function injectGPVars($GPVars) {
		$this->gpVarData = $GPVars;
	}



	/**
	 * Init the column collection
	 */
	public function init() {
	}



	/**
	 * Add a header column to the collection
	 *
	 * @param Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $columnHeader
	 * @param integer $columnIdentifier
	 */
	public function addHeaderColumn(Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $columnHeader, $columnIdentifier) {	
		$this->addItem($columnHeader, $columnIdentifier);
	}
	
	
	
	/**
	 * Return column Identifier if exists
	 * 
	 * @param string $columnIdentifier
	 * @return Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn
	 * @throws Exception
	 */
	public function getHeaderColumn($columnIdentifier) {
		if(!$this->hasItem($columnIdentifier)) {
			throw new Exception('The column header with column identifier ' . $columnIdentifier . ' does not exist! 1288303528');
		}
		
		return $this->itemsArr[$columnIdentifier];
	}
	
	
	
	/**
	 * Returns namespace for this object
	 * 
	 * @return string Namespace to identify this object
	 */
	public function getObjectNamespace() {
		return $this->listIdentifier . '.columns';
	}
	
	
	
	/**
	 * reset session of all list columnHeaders
	 * 
	 * @return void
	 */
	public function reset() {
		foreach($this->itemsArr as $headerColumn) { /* @var $headerColumn Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn */
			$headerColumn->reset();
		}
	}
	
	
	
	/**
	 * @return string
	 */
	public function getListIdentifier() {
		return $this->listIdentifier;
	}

}
?>