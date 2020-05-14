<?php

namespace PunktDe\PtExtlist\Domain\Renderer\Defaults;

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

use PunktDe\PtExtlist\Domain\Configuration\ColumnConfigInterface;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\Renderer\RendererConfig;
use PunktDe\PtExtlist\Domain\Model\Lists\Cell;
use PunktDe\PtExtlist\Domain\Model\Lists\Row;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Form\Mvc\Configuration\TypoScriptService;

/**
 * Default renderer for a cell in a row in list data
 *  
 * @package Domain
 * @subpackage Renderer\Default
 * @author Daniel Lienert
 */
class CellRenderer
{
    /**
     * Reference to the ConfigurationBuilder
     * @var ConfigurationBuilder
     */
    protected $configurationBuilder;



    /**
     *
     * @var RendererConfig
     */
    protected $rendererConfiguration;



    /**
     * Special renderer userfunciton
     *  
     * @var string
     */
    protected $renderSpecialCellUserFunc;



    /**
     * Construct the strategy.
     *
     * @param RendererConfig $rendererConfiguration
     */
    public function __construct(RendererConfig $rendererConfiguration)
    {
        $this->rendererConfiguration = $rendererConfiguration;
        $this->configurationBuilder = $rendererConfiguration->getConfigurationBuilder();
        #$this->renderSpecialCellUserFunc = $this->rendererConfiguration->getSpecialCell();
    }


    /**
     * Renders the cell content.
     *
     * @param ColumnConfigInterface $columnConfig
     * @param Row $data The table data.
     * @param integer $columnIndex Current column index.
     * @param integer $rowIndex Current row index.
     *
     * @internal param string $columnIdentifier The columnIdentifier.
     * @return Cell
     */
    public function renderCell(ColumnConfigInterface $columnConfig, Row $data, $columnIndex, $rowIndex)
    {

        // Load all available fields
        $fieldSet = $this->createFieldSet($data, $columnConfig);

        $caching = $columnConfig->getCacheRendering() || $this->configurationBuilder->buildListConfiguration()->getCacheRendering();

        // TODO: Include the objectMapper here ...
        // if($columnConfig->getObjectMapperConfig() instanceof Tx_PtExtlist_Domain_Configuration_Columns_ObjectMapper_ObjectMapperConfig) {}
        if ($columnConfig->getRawFields()) {
            $content = $fieldSet;
        } else {
            $content = \PunktDe\PtExtlist\Utility\RenderValue::renderByConfigObject($fieldSet, $columnConfig, $caching);
        }

        // Create new cell
        $cell = new Cell($content);
        $cell->setRowIndex($rowIndex);
        $cell->setColumnIndex($columnIndex);

        // render cell css class
        if ($columnConfig->getCellCSSClass()) {
            $cell->setCSSClass($this->renderCellCSSClass($fieldSet, $columnConfig));
        }

        return $cell;
    }



    /**
     * render the cells CSS Class
     *  
     * @param array $fieldSet
     * @param ColumnConfigInterface $columnConfig
     * @return string
     */
    protected function renderCellCSSClass($fieldSet, ColumnConfigInterface $columnConfig)
    {
        $cellCSSConfig = $columnConfig->getCellCSSClass();

        if (is_array($cellCSSConfig)) {
            $renderObj =            array_key_exists('renderObj', $cellCSSConfig)            ? GeneralUtility::makeInstance(TypoScriptService::class)->convertPlainArrayToTypoScriptArray(['renderObj' => $cellCSSConfig['renderObj']]) : null;
            $renderUserFunction =    array_key_exists('renderUserFunction', $cellCSSConfig)    ? $cellCSSConfig['renderUserFunction'] : null;

            return \PunktDe\PtExtlist\Utility\RenderValue::render($fieldSet, $renderObj, $renderUserFunction);
        } else {
            return $cellCSSConfig;
        }
    }



    /**
     * Call user functions for building special values.
     * renderer.specialCell gets overridden by column.specialCell
     *
     * @param Cell $cell
     * @param ColumnConfigInterface $columnConfig
     */
    protected function renderSpecialValues(Cell $cell, ColumnConfigInterface $columnConfig)
    {
        $rendererUserFunc = $this->rendererConfiguration->getSpecialCell();

        if (!is_null($columnConfig->getSpecialCell())) {
            $rendererUserFunc = $columnConfig->getSpecialCell();
        }

        if (!empty($rendererUserFunc)) {
            $ref = '';
            $specialValues = GeneralUtility::callUserFunction($rendererUserFunc, $cell, $ref);
            $cell->setSpecialValues($specialValues);
        }
    }



    /**
     * Creates a set of fields which are available. Defined by the 'fields' TS setup.
     *
     * @param Row $row
     * @param ColumnConfigInterface $columnConfig
     * @return array
     */
    protected function createFieldSet(Row $row, ColumnConfigInterface $columnConfig)
    {
        $fieldSet = [];
        foreach ($columnConfig->getFieldIdentifier() as $fieldConfig) {
            $fieldIdentifier = (string) $fieldConfig;
            $fieldSet[$fieldIdentifier] = $row->getCell($fieldIdentifier)->getValue();
        }

        if ($columnConfig->getContainsArrayData()) {
            $fieldSet = $this->createArrayDataFieldSet($fieldSet);
        }

        return $fieldSet;
    }



    /**
     * Create an array data fieldset from an array column
     * 1. Search for first field with array data
     * 2. Loop through this data 
     *  2a add the data of other array fields to the output data
     *  2b duplicate non array fields
     *      
     * @param array fieldSet
     * @throws \Exception
     * @return array
     */
    protected function createArrayDataFieldSet(array $fieldSet)
    {
        $loopArray = null;

        foreach ($fieldSet as $field) {
            if (is_array($field)) {
                $loopArray = $field;
            }
        }

        if (!is_array($loopArray)) {
            throw new \Exception('Error Column with Flag "containsArrayData" contains no Field with array-value!', 1283426460);
        }

        $outDataArray = [];

        foreach ($loopArray as $index => $value) {
            foreach ($fieldSet as $fieldIdentifier => $field) {
                if (is_array($field)) {
                    $outDataArray[$index][$fieldIdentifier] = $field[$index];
                } else {
                    $outDataArray[$index][$fieldIdentifier] = $field;
                }
            }
        }

        return $outDataArray;
    }
}
