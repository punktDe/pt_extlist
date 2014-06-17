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
 * Class implements a renderer for rows in list data
 * 
 * @author Michael Knoll 
 * @package Domain
 * @subpackage Renderer\Default
 */
class Tx_PtExtlist_Domain_Renderer_Default_RowRenderer {

	/**
	 * Holds an instance of renderer configuration
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig
	 */
	protected $rendererConfiguration;



	/**
	 * Holds an instance of cell renderer
	 * 
	 * @var Tx_PtExtlist_Domain_Renderer_Default_CellRenderer
	 */
	protected $cellRenderer;
	
	
	
	
	/**
	 * Injector for renderer configuration
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig $rendererConfiguration
	 */
	public function injectRendererConfiguration(Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig $rendererConfiguration) {
		$this->rendererConfiguration = $rendererConfiguration;
	}
	
	
	
	/**
	 * Injector for cell renderer
	 * 
	 * @param Tx_PtExtlist_Domain_Renderer_Default_CellRenderer $cellRenderer
	 */
	public function injectCellRenderer(Tx_PtExtlist_Domain_Renderer_Default_CellRenderer $cellRenderer) {
		$this->cellRenderer = $cellRenderer;
	}
	
	
	
	/**
	 * Returns rendering configuration
	 *
	 * @return Tx_PtExtlist_Domain_Configuration_Renderer_RendererConfig
	 */
	public function getRendererConfiguration() {
		return $this->rendererConfiguration;
	}
	
	
	
    /**
     * Renders a row
     *
     * @param Tx_PtExtlist_Domain_Model_List_Row $row Row to be rendered
     * @param mixed $rowIndex Holds index of row in listData structure
     * @return Tx_PtExtlist_Domain_Model_List_Row Rendered row
     */
    public function renderRow(Tx_PtExtlist_Domain_Model_List_Row $row, $rowIndex) {
        $renderedRow = new Tx_PtExtlist_Domain_Model_List_Row();

		// copy special values
		$renderedRow->setSpecialValues($row->getSpecialValues());

        $columnCollection = $this->getColumnCollection();
    
        $columnIndex=0;
        foreach($columnCollection as $columnIdentifier => $column) {
           $columnConfig = $column->getColumnConfig();

            // Only render if FE-User is allowed to see the column
            if($columnConfig->isAccessable() && $column->getIsVisible()) {
                // Use strategy to render cells
                $cell = $this->renderCell($columnConfig, $row, $columnIndex, $rowIndex);
                $renderedRow->addCell($cell, $columnIdentifier);
            } 
            
            $columnIndex++;
        }

		unset($row);
        
        return $renderedRow;
    }
    
    
    
    /**
     * Renders an aggregate row for given aggregate row configuration and given row index
     *
     * @param Tx_PtExtlist_Domain_Model_List_Row $aggregateDataRow Row to be rendered
     * @param Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfig $aggregateRowConfig Config used to render aggregate row
     * @param int $rowIndex Index of rendered row
     * @return Tx_PtExtlist_Domain_Model_List_ListData Rendered aggregate row
     */
	public function renderAggregateRow(Tx_PtExtlist_Domain_Model_List_Row $aggregateDataRow,
												  Tx_PtExtlist_Domain_Configuration_Aggregates_AggregateRowConfig $aggregateRowConfig,
												  $rowIndex) {

		$renderedRow = new Tx_PtExtlist_Domain_Model_List_Row();
		$columnCollection = $this->getColumnCollection();

		foreach ($columnCollection as $columnIdentifier => $column) {
			$columnConfiguration = $column->getColumnConfig();

			if ($columnConfiguration->isAccessable() && $column->getIsVisible()) {

				if ($aggregateRowConfig->hasItem($columnConfiguration->getColumnIdentifier())) {

					$cell = $this->renderCell($aggregateRowConfig->getItemById($columnConfiguration->getColumnIdentifier()),
						$aggregateDataRow,
						$columnIdentifier,
						$rowIndex);

				} else {
					$cell = new Tx_PtExtlist_Domain_Model_List_Cell();
				}

				$renderedRow->addCell($cell, $columnIdentifier);
			}
		}

		unset($aggregateDataRow);

		return $renderedRow;
	}


	/**
	 * Renders a cell
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ColumnConfigInterface $columnConfig
	 * @param Tx_PtExtlist_Domain_Model_List_Row $data
	 * @param int $columnIndex
	 * @param int $rowIndex
	 * @return Tx_Pt_extlist_Domain_Model_List_Cell
	 */
	protected function renderCell(Tx_PtExtlist_Domain_Configuration_ColumnConfigInterface $columnConfig, Tx_PtExtlist_Domain_Model_List_Row $data, $columnIndex, $rowIndex) {
		return $this->cellRenderer->renderCell($columnConfig, $data, $columnIndex, $rowIndex);
	}



	/**
	 * @return Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 */
	protected function getColumnCollection() {
		return Tx_PtExtlist_Domain_Model_List_Header_ListHeaderFactory::createInstance($this->rendererConfiguration->getConfigurationBuilder());
	}


	/**
	 * @return Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection
	 */
	protected function getColumnConfigurationCollection() {
		return $this->rendererConfiguration->getConfigurationBuilder()->buildColumnsConfiguration();
	}
}