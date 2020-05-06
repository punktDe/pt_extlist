<?php


namespace PunktDe\PtExtlist\Domain\DataBackend\Mapper;

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
 * Class implements a mapper that maps array data to list data structure for a given
 * fields configuration.
 *
 * @package Domain
 * @subpackage DataBackend\Mapper
 * @author Michael Knoll
 */
class ArrayMapper extends \PunktDe\PtExtlist\Domain\DataBackend\Mapper\AbstractMapper
{
    /**
     * Maps given array data to list data structure.
     * If no configuration is set, the array is mapped 1:1 to the list data structure
     * If a configuration is given, this configuration is used to map the fields
     *
     * @param array $arrayData Raw data array to be mapped to list data structure
     * @return \PunktDe\PtExtlist\Domain\Model\Lists\ListData
     */
    public function getMappedListData(array &$arrayData)
    {
        if (is_null($this->fieldConfigurationCollection)) {
            $mappedListData = $this->mapWithoutConfiguration($arrayData);
        } else {
            $mappedListData = $this->mapWithConfiguration($arrayData);
        }

        unset($arrayData);

        return $mappedListData;
    }



    /**
     * Maps raw list data without any mapper configuration.
     * Each field of the given raw array is mapped to
     * a cell in the list data structure
     *
     * @param array $arrayData Raw array to be mapped
     * @return \PunktDe\PtExtlist\Domain\Model\Lists\ListData Mapped list data structure
     */
    protected function mapWithoutConfiguration(array &$arrayData)
    {
        $listData = new \PunktDe\PtExtlist\Domain\Model\Lists\ListData();
        foreach ($arrayData as &$row) {
            $row = $this->processRowForMapping($row);
            $mappedRow = new \PunktDe\PtExtlist\Domain\Model\Lists\Row();

            foreach ($row as $columnName => $value) {
                $mappedRow->createAndAddCell($value, $columnName);
            }

            $listData->addRow($mappedRow);
        }
        return $listData;
    }



    /**
     * Maps raw list data with given mapper configuration.
     *
     * @param array $arrayData Raw array to be mapped
     * @return \PunktDe\PtExtlist\Domain\Model\Lists\ListData Mapped list data structure
     */
    protected function mapWithConfiguration(array &$arrayData)
    {
        $listData = new \PunktDe\PtExtlist\Domain\Model\Lists\ListData();

        foreach ($arrayData as $row) {
            $mappedRow = $this->mapRowWithConfiguration($this->processRowForMapping($row));
            $listData->addRow($mappedRow);
        }

        return $listData;
    }



    /**
     * @param array $rowData
     * @return \PunktDe\PtExtlist\Domain\Model\Lists\Row
     */
    public function getMappedRow(array &$rowData)
    {
        if (is_null($this->fieldConfigurationCollection)) {
            $mappedRow = new \PunktDe\PtExtlist\Domain\Model\Lists\Row();
            foreach ($rowData as $columnName => $value) {
                $mappedRow->createAndAddCell($value, $columnName);
            }
        } else {
            $mappedRow = $this->mapRowWithConfiguration($rowData);
        }

        return $mappedRow;
    }



    /**
     * Maps a single row of a list data structure with given configuration.
     *
     * @param array $row   Row of raw list data array
     * @return \PunktDe\PtExtlist\Domain\Model\Lists\Row  Mapped row
     */
    protected function mapRowWithConfiguration(array &$row)
    {
        $mappedRow = new \PunktDe\PtExtlist\Domain\Model\Lists\Row();
        foreach ($this->fieldConfigurationCollection as $mapping) {
            /* @var $mapping Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig */
            $mappedCellValue = $this->getMappedCellValue($mapping, $row);
            $mappedRow->createAndAddCell($mappedCellValue, $mapping->getIdentifier());
        }

        unset($row);

        return $mappedRow;
    }



    /**
     * Maps a field of raw data to a cell of a list data structure
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig $mapping Mapping for this field / cell
     * @param array $row   Raw row of list data array
     * @throws Exception if array key defined in the mapping does not exist in the array
     * @return string  Value of raw data array field corresponding to given mapping
     */
    protected function getMappedCellValue(\PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig $mapping, array &$row)
    {
        if (array_key_exists($mapping->getIdentifier(), $row)) {
            if ($this->fieldConfigurationCollection[$mapping->getIdentifier()]->getExpandGroupRows()) {
                return $this->expandGroupedData($row, $mapping);
            } else {
                return $row[$mapping->getIdentifier()];
            }
        } else {
            return $this->handleMappingError($mapping, $row);
        }
    }


    /**
     * @param \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig $mapping
     * @param array $row
     * @throws Exception
     */
    protected function handleMappingError(\PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig $mapping, array $row)
    {
        throw new Exception('Array key ' . $mapping->getIdentifier() . ' does not exist in row. Perhaps wrong mapping configuration?', 1280317751);
    }


    /**
     * Expand the field by delimiter
     *
     * @param array $row
     * @param \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig $mapping
     * @return array
     */
    protected function expandGroupedData(&$row, \PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig $mapping)
    {
        return explode($mapping->getExpandGroupRowsSeparator(), $row[$mapping->getIdentifier()]);
    }


    /**
     * @param array $row
     * @return array
     */
    protected function processRowForMapping(array &$row)
    {
        return $row;
    }
}
