<?php


namespace PunktDe\PtExtlist\Domain\Model\Lists\Aggregates;

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
 * Class implements field aggregator
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Lists\Aggregates
 * @see Tx_PtExtlist_Tests_Domain_Model_List_Aggregates_ArrayAggregatorTest
 */
class ArrayAggregator
{
    /**
     * Array of fielddata by column
     *
     * @var array
     */
    protected $fieldData = [];



    /**
     * Reference to the data Backend
     *
     * @var \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface
     */
    protected $dataBackend;



    /**
     * @param \PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface $dataBackend
     */
    public function injectDataBackend(\PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface $dataBackend)
    {
        $this->dataBackend = $dataBackend;
    }



    /**
     * Return a aggregated column by method
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfig $aggregateConfig
     * @throws Exception
     * @return number
     */
    public function getAggregateByConfig(\PunktDe\PtExtlist\Domain\Configuration\Data\Aggregates\AggregateConfig $aggregateConfig)
    {
        $fieldIdentifier = $aggregateConfig->getFieldIdentifier()->getIdentifier();

        if (!is_array($this->fieldData[$fieldIdentifier])) {
            $this->buildFieldData($fieldIdentifier);
        }

        $methodName = 'getField' . ucfirst($aggregateConfig->getMethod());
        if (!method_exists($this, $methodName)) {
            throw new Exception('The array aggregate Method "' . $aggregateConfig->getMethod() . '" is not implemented 1282905192');
        }

        return $this->$methodName($fieldIdentifier);
    }



    /**
     * Convert the list structure of "rows->columns->data" to an array
     * of 'columns->rows->data' for easy aggregate
     *
     * @param string $fieldIdentifier
     * @return array
     */
    protected function buildFieldData($fieldIdentifier)
    {
        $fieldData[$fieldIdentifier] = [];

        foreach ($this->dataBackend->getListData() as $row) {
            $this->fieldData[$fieldIdentifier][] = $row[$fieldIdentifier]->getValue();
        }
    }



    /**
     * sum all values of a column
     *
     * @param string $fieldIdentifier
     * @return number
     */
    protected function getFieldSum($fieldIdentifier)
    {
        if (!array_key_exists($fieldIdentifier, $this->fieldData) || !is_array($this->fieldData[$fieldIdentifier])) {
            return 0;
        }

        return array_sum($this->fieldData[$fieldIdentifier]);
    }



    /**
     * get the average of all values of a column
     *
     * @param string $fieldIdentifier
     * @return float
     */
    protected function getFieldAvg($fieldIdentifier)
    {
        if (!array_key_exists($fieldIdentifier, $this->fieldData) || !is_array($this->fieldData[$fieldIdentifier])) {
            return 0;
        }

        return array_sum($this->fieldData[$fieldIdentifier])/count($this->fieldData[$fieldIdentifier]);
    }



    /**
     * maximum of all values of a column
     *
     * @param string $fieldIdentifier
     * @return number
     */
    protected function getFieldMax($fieldIdentifier)
    {
        if (!array_key_exists($fieldIdentifier, $this->fieldData) || !is_array($this->fieldData[$fieldIdentifier])) {
            return 0;
        }

        return max($this->fieldData[$fieldIdentifier]);
    }



    /**
     * minimum of all values of a column
     *
     * @param string $fieldIdentifier
     * @return number
     */
    protected function getFieldMin($fieldIdentifier)
    {
        if (!array_key_exists($fieldIdentifier, $this->fieldData) || !is_array($this->fieldData[$fieldIdentifier])) {
            return 0;
        }

        return min($this->fieldData[$fieldIdentifier]);
    }
}
