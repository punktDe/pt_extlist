<?php
namespace PunktDe\PtExtlist\Domain\DataBackend\Typo3DataBackend;

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

use PunktDe\PtExtbase\Exception\Assertion;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\DataBackend\DataSource\DatabaseDataSourceConfiguration;
use PunktDe\PtExtlist\Domain\DataBackend\DataSource\Typo3DataSource;
use PunktDe\PtExtlist\Domain\DataBackend\DataSource\Typo3DataSourceFactory;
use PunktDe\PtExtlist\Domain\DataBackend\MySqlDataBackend\MySqlDataBackend;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * Data backend for TYPO3 database
 *
 * @author Daniel Lienert
 * @author Michael Knoll
 * @package Domain
 * @subpackage DataBackend\Typo3DataBackend
 *
 * TODO implement T3 db object methods for query (hidden fields, deleted rows etc...)
 *    
 */
class Typo3DataBackend extends MySqlDataBackend
{

    /**
     * Builder for SQL query. Gathers information from
     * all parts of plugin (ts-config, pager, filters etc.)
     * and generates SQL query out of this information
     *
     * @return QueryBuilder
     * @throws Assertion
     */
    public function buildQuery($execute= false): QueryBuilder
    {
        $queryBuilder = parent::buildQuery($execute);

        $this->setQueryBuilderRestrictions(
            $queryBuilder
        );

        return $queryBuilder;
    }


    /**
     * Factory method for data source
     *  
     * Only DataBackend knows, which data source to use and how to instantiate it.
     * So there cannot be a generic factory for data sources and data backend factory cannot instantiate it either!
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @return Typo3DataSource Data source object for this data backend
     * @throws \Exception
     */
    public static function createDataSource(ConfigurationBuilder $configurationBuilder)
    {
        $dataSourceConfiguration = new DatabaseDataSourceConfiguration($configurationBuilder->buildDataBackendConfiguration()->getDataSourceSettings());
        $dataSource =  Typo3DataSourceFactory::createInstance($configurationBuilder->buildDataBackendConfiguration()->getDataSourceClass(), $dataSourceConfiguration);
        return $dataSource;
    }
    


    /**
     * Builds where part of query from all parts of plugin
     *
     * @return string WHERE part of query without 'WHERE'
     * @param array $excludeFilters Define filters from which no where clause should be returned (array('filterboxIdentifier' => array('filterIdentifier')))
     */
    public function buildWherePart($excludeFilters = [])
    {
        $baseWhereClause = $this->getBaseWhereClause();
        $whereClauseFromFilterBoxes = $this->getWhereClauseFromFilterboxes($excludeFilters);
        
        if ($baseWhereClause && $whereClauseFromFilterBoxes) {
            $wherePart = '(' . $baseWhereClause . ') AND ' . $whereClauseFromFilterBoxes;
        } else {
            $wherePart = $baseWhereClause.$whereClauseFromFilterBoxes;
        }

        return $wherePart;
    }



    /**
     * Build and return whereClause part with TYPO3 enablefields criterias
     * for all tables which are defined in backendConfig.tables and in TCA
     *
     * @param $queryBuilder QueryBuilder
     */
    protected function setQueryBuilderRestrictions(&$queryBuilder): void
    {
        if ($queryBuilder) {
            $queryBuilder->getRestrictions()->removeAll();

            if ($this->backendConfiguration->getDataBackendSettings('useEnableFields')) {
                $typo3Tables = GeneralUtility::trimExplode(',', $this->tables, true);

                foreach ($typo3Tables as $typo3Table) {
                    list($table, $aliasNotUsed) = GeneralUtility::trimExplode(' ', $typo3Table, true);

                    if (is_array($GLOBALS['TCA'][$table])) {
                        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));
                    }
                }

                if ($typo3Tables) {
                    $queryBuilder->limitRestrictionsToTables($typo3Tables);
                }
            }
        }
    }
}
