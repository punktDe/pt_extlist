<?php

namespace PunktDe\PtExtlist\Domain\Model\Filter\DataProvider;

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
use TYPO3\CMS\Core\Database\DatabaseConnection;

/**
 * Implements data provider for explicit defined SQL query
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
class ExplicitSQLQuery extends \PunktDe\PtExtlist\Domain\Model\Filter\DataProvider\AbstractDataProvider
{
    /**
     * @var string
     */
    protected $selectPart;



    /**
     * @var string
     */
    protected $fromPart;



    /**
     * @var string
     */
    protected $wherePart;



    /**
     * @var string
     */
    protected $groupByPart;



    /**
     * @var string
     */
    protected $orderByPart;



    /**
     * @var string
     */
    protected $limitPart;



    /**
     * @var array
     */
    protected $displayFields;



    /**
     * @var string
     */
    protected $filterField;



    /**
     * @var DatabaseConnection
     */
    protected $dbObj;



    /**
     * Init the data provider
     */
    public function init()
    {
        // TODO use DI here!
        $this->dbObj = $GLOBALS['TYPO3_DB'];

        $sqlQuerySettings = $this->filterConfig->getSettings('optionsSqlQuery');

        foreach ($sqlQuerySettings as $type => $part) {
            if (!is_array($part)) {
                $sqlQuerySettings[$type] = trim($part);
            }
        }

        if ($sqlQuerySettings['select']) {
            $this->selectPart = \PunktDe\PtExtlist\Utility\RenderValue::stdWrapIfPlainArray($sqlQuerySettings['select']);
        }
        if ($sqlQuerySettings['from']) {
            $this->fromPart = \PunktDe\PtExtlist\Utility\RenderValue::stdWrapIfPlainArray($sqlQuerySettings['from']);
        }
        if ($sqlQuerySettings['where']) {
            $this->wherePart = \PunktDe\PtExtlist\Utility\RenderValue::stdWrapIfPlainArray($sqlQuerySettings['where']);
        }
        if ($sqlQuerySettings['orderBy']) {
            $this->orderByPart = \PunktDe\PtExtlist\Utility\RenderValue::stdWrapIfPlainArray($sqlQuerySettings['orderBy']);
        }
        if ($sqlQuerySettings['groupBy']) {
            $this->groupByPart = \PunktDe\PtExtlist\Utility\RenderValue::stdWrapIfPlainArray($sqlQuerySettings['groupBy']);
        }
        if ($sqlQuerySettings['limit']) {
            $this->limitPart = \PunktDe\PtExtlist\Utility\RenderValue::stdWrapIfPlainArray($sqlQuerySettings['limit']);
        }


        $this->filterField = trim($this->filterConfig->getSettings('filterField'));
        $this->displayFields = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $this->filterConfig->getSettings('displayFields'));

        PunktDe_PtExtbase_Assertions_Assert::isNotEmptyString($this->filterField, ['info' => 'No filter field is given for filter ' . $this->filterConfig->getFilterIdentifier() . ' 1315221957']);
        PunktDe_PtExtbase_Assertions_Assert::isNotEmptyString($this->selectPart, ['info' => 'No Select part is given for filter ' . $this->filterConfig->getFilterIdentifier() . ' 1315221958']);
        PunktDe_PtExtbase_Assertions_Assert::isNotEmptyString($this->fromPart, ['info' => 'No from part is given for filter ' . $this->filterConfig->getFilterIdentifier() . ' 1315221959']);
    }



    /**
     * Return the rendered filterOptions
     *
     * @return array filter options
     */
    public function getRenderedOptions()
    {
        $renderedOptions = [];
        $options = $this->getDataFromSqlServer();
        foreach ($options as $optionData) {
            $optionKey = $optionData[$this->filterField];

            $renderedOptions[$optionKey] = $optionData;
            $renderedOptions[$optionKey]['value'] = $this->renderOptionData($optionData);
            $renderedOptions[$optionKey]['selected'] = false;
        }
        return $renderedOptions;
    }



    /**
     * Render a single option line by cObject or default
     *
     * @param array $optionData
     * @return string The rendered option
     */
    protected function renderOptionData($optionData)
    {
        $values = [];
        if (is_array($this->displayFields)) {
            foreach ($this->displayFields as $displayField) {
                $values[] = $optionData[$displayField];
            }
        }
        $optionData['allDisplayFields'] = implode(' ', $values);
        $option = \PunktDe\PtExtlist\Utility\RenderValue::renderByConfigObjectUncached($optionData, $this->filterConfig);
        return $option;
    }



    /**
     * @throws Exception
     * @return array of options
     */
    protected function getDataFromSqlServer()
    {
        $query = $this->dbObj->SELECTquery($this->selectPart, $this->fromPart, $this->wherePart, $this->groupByPart, $this->orderByPart, $this->limitPart); // this method only combines the parts

        $dataSource = \PunktDe\PtExtlist\Domain\DataBackend\DataBackendFactory::getInstanceByListIdentifier($this->filterConfig->getListIdentifier())->getDataSource();

        if (!method_exists($dataSource, 'executeQuery')) {
            throw new Exception('The defined dataSource has no method executeQuery and is therefore not usable with this dataProvider!', 1315216209);
        }

        $data =  $dataSource->executeQuery($query)->fetchAll();

        if (TYPO3_DLOG) {
            \TYPO3\CMS\Core\Utility\GeneralUtility::devLog('MYSQL QUERY : ' . $this->filterConfig->getListIdentifier() . ' -> Filter::ExplicitSQLQuery', 'pt_extlist', 1, ['executionTime' => $dataSource->getLastQueryExecutionTime(), 'query' => $query]);
        }

        return $data;
    }
}
