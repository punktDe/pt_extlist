<?php


namespace PunktDe\PtExtlist\Domain\Security;


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

use PunktDe\PtExtlist\Domain\Configuration\Columns\ColumnConfig;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\Filters\FilterConfig;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Christoph Ehscheidt
 *
 * @package Domain
 * @subpackage Security
 * @see Tx_PtExtlist_Tests_Domain_Security_GroupSecurityTest
 */
class GroupSecurity implements SecurityInterface, SingletonInterface
{
    /**
     * Comma separated list of user groups
     *
     * @var string
     */
    protected $userGroups;



    public function __construct()
    {
        $this->userGroups = $GLOBALS['TSFE']->fe_user->user['usergroup'];
    }



    /**
     * Evaluates if a column is accessable by the FE-User(-Group).
     *
     * @param ColumnConfig $columnConfig
     *
     * @return bool
     */
    public function isAccessableColumn(ColumnConfig $columnConfig)
    {

        // FAIL if one of this tests are failing.
        if (!$this->checkFields($columnConfig->getFieldIdentifier())) {
            return false;
        }
        // OR
        if (!$this->checkColumn($columnConfig)) {
            return false;
        }

        // OTHERWISE allow access.
        return true;
    }



    protected function checkColumn(ColumnConfig $columnConfig)
    {
        $groups = $columnConfig->getAccessGroups();

        if (!is_array($groups)) {
            return true;
        } // for testing purposes
        if (empty($groups)) {
            return true;
        }

        return $this->compareAccess($groups);
    }



    /**
     * Evaluates if a filter is accessable by the FE-User(-Group).
     *
     * @param FilterConfig $filterConfig
     * @param ConfigurationBuilder $configBuilder
     *
     * @return bool
     */
    public function isAccessableFilter(FilterConfig $filterConfig, ConfigurationBuilder $configBuilder)
    {
        $fieldConfigCollection = $filterConfig->getFieldIdentifier();

        // FAIL if one of this tests are failing.
        if (!$this->checkFields($fieldConfigCollection)) {
            return false;
        }

        // OR
        if (!$this->checkFilter($filterConfig)) {
            return false;
        }

        // OTHERWISE allow access.
        return true;
    }



    /**
     * Check field access
     *
     * @param FieldConfigCollection $fieldConfigCollection
     * @return bool
     */
    protected function checkFields(FieldConfigCollection $fieldConfigCollection)
    {
        foreach ($fieldConfigCollection as $fieldConfig) { /* @var $fieldConfig FieldConfig */
            $ident = $fieldConfig->getAccessGroups();

            if (empty($ident)) {
                continue;
            }
            if (!$this->compareAccess($ident)) {
                return false;
            }
        }

        return true;
    }



    protected function checkFilter(FilterConfig $filter)
    {
        $groups = $filter->getAccessGroups();
        if (!is_array($groups)) {
            return true;
        } // for testing purposes

        if (empty($groups)) {
            return true;
        }

        return $this->compareAccess($groups);
    }



    /**
     * Compare the defined access credentials with internal data (e.g. FE-USER-GROUP)
     *
     * @param array $groups
     * @return bool
     */
    protected function compareAccess(array $groups)
    {
        if (count($groups) == 0) {
            return true;
        }

        foreach ($groups as $group) {
            $group = trim($group);
            if (empty($group)) {
                return true;
            }

            $groupArray = GeneralUtility::trimExplode(',', $this->userGroups);

            foreach ($groupArray as $groupData) {
                if ($group == $groupData) {
                    return true;
                }
            }
        }

        return false;
    }
}
