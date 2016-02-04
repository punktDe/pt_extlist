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
 * Class implements data source for mysql databases
 *
 * @author Christiane Helmchen
 * @author David Vogt
 * @package Domain
 * @subpackage DataBackend\DataSource
 */
class Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlProfilingDataSource extends Tx_PtExtlist_Domain_DataBackend_DataSource_MySqlDataSource
{
    private static $queryCounter = 1;


    /**
     * @var array
     */
    protected $preProcessHookObjects;



    /**
     * @var array
     */
    protected $postProcessHookObjects;


    public function __construct()
    {
        $this->preProcessHookObjects = array();
        $this->postProcessHookObjects = array();
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_db.php']['queryProcessors'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_db.php']['queryProcessors'] as $classRef) {
                $hookObject = \TYPO3\CMS\Core\Utility\GeneralUtility::getUserObj($classRef);

                if (!($hookObject instanceof \TYPO3\CMS\Core\Database\PreProcessQueryHookInterface || $hookObject instanceof \TYPO3\CMS\Core\Database\PostProcessQueryHookInterface)) {
                    throw new UnexpectedValueException('$hookObject must either implement interface t3lib_DB_preProcessQueryHook or interface t3lib_DB_postProcessQueryHook', 1299158548);
                }
                if ($hookObject instanceof \TYPO3\CMS\Core\Database\PreProcessQueryHookInterface) {
                    $this->preProcessHookObjects[] = $hookObject;
                }
                if ($hookObject instanceof \TYPO3\CMS\Core\Database\PostProcessQueryHookInterface) {
                    $this->postProcessHookObjects[] = $hookObject;
                }
            }
        }
    }



    public function executeQuery($query)
    {
        // Added to log select queries
        foreach ($this->preProcessHookObjects as $preProcessHookObject) { /* @var $preProcessHookObject Tx_SandstormmediaPlumber_Hooks_Hook */
            $preProcessHookObject->extlist_preProcessAction(self::$queryCounter++ . ' ' . $query);
        }
        $result = parent::executeQuery($query);

        // Added to log select queries
        foreach ($this->postProcessHookObjects as $postProcessHookObject) { /* @var $postProcessHookObject Tx_SandstormmediaPlumber_Hooks_Hook */
            $postProcessHookObject->extlist_postProcessAction();
        }
        return $result;
    }
}
