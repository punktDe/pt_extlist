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
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

require_once ExtensionManagementUtility::extPath('pt_extbase') . 'Classes/Div.php';
require_once ExtensionManagementUtility::extPath('pt_extbase') . 'Classes/Assertions/Assert.php';

/**
 * Utilitty to get selectable options from typoscript
 *
 * @package Utility
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Utility_FlexformDataProviderTest
 */
class user_Tx_PtExtlist_Utility_FlexformDataProvider
{
    /**
     * Current pid
     *
     * @var integer
     */
    protected $currentPid;



    /**
     * The extlist Typoscript part
     *
     * @var array
     */
    protected $extListTypoScript = null;



    /**
     * Get a List of defined extList listconfigs
     *
     * @param array $config
     *
     * @return array $config
     */
    public function getDefinedListConfigs(array $config)
    {
        $this->initTsDataProvider($config);
        $config['items'] = array_merge($config['items'], $this->getTypoScriptKeyList('settings.listConfig'));
        return $config;
    }



    /**
     * Get the defined export types
     *
     * @param array $config
     *
     * @return array $config
     */
    public function getDefinedExportConfigs(array $config)
    {
        $exportConfigs = array();
        if (!is_array($config['items'])) {
            $config['items'] = array();
        }
        $this->initTsDataProvider($config);
        $tsArray = $this->getTSArrayByPath('settings.export.exportConfigs');
        foreach ($tsArray as $key => $exportConfig) {
            if (array_key_exists('viewClassName', $exportConfig) && $exportConfig['viewClassName']) {
                $exportConfigs[] = array($key, 'export.exportConfigs.' . $key);
            }
        }
        ksort($exportConfigs);
        $config['items'] = array_merge($config['items'], $exportConfigs);
        return $config;
    }



    /**
     * Get a List of defined extList listconfigs
     *
     * @param array $config
     *
     * @return array $config
     */
    public function getDefinedFilterConfigs(array $config)
    {
        $config = $this->extendOptionsForFlexform('filters', $config);
        return $config;
    }



    /**
     * Get a List of defined extList listconfigs
     *
     * @param array $config
     *
     * @return array $config
     */
    public function getDefinedPagerConfigs(array $config)
    {
        $config = $this->extendOptionsForFlexform('pager.pagerConfigs', $config);
        return $config;
    }



    /**
     * @param $listConfigPart
     * @param $config
     *
     * @return mixed
     */
    private function extendOptionsForFlexform($listConfigPart, $config)
    {
        $this->initTsDataProvider($config);
        $typoScriptOptions = $this->getTypoScriptOptionsOfList($listConfigPart, $config);
        $config['items'] = array_merge($config['items'], $typoScriptOptions);
        return $config;
    }



    /**
     * @param string $subpartName
     * @param array $config
     *
     * @return array
     */
    protected function getTypoScriptOptionsOfList($subpartName, $config)
    {
        $options = [];
        $activeListIdentifier = $this->getFlexformValue('settings.listIdentifier', $config);
        if ($activeListIdentifier !== null) {
            $options = $this->getTypoScriptKeyList('settings.listConfig.' . $activeListIdentifier . '.' . $subpartName);
        }
        return $options;
    }



    /**
     * @param string $optionName
     * @param array $config
     *
     * @return string
     */
    protected function getFlexformValue($optionName, $config)
    {
        if (isset($config['flexParentDatabaseRow']['pi_flexform'])) {
            $flexformContent = $config['flexParentDatabaseRow']['pi_flexform'];
        } else {
            $flexformContent = GeneralUtility::xml2array($config['row']['pi_flexform']);
        }
        if (is_array($flexformContent)
            && array_key_exists('data', $flexformContent)
            && array_key_exists('sDefault', $flexformContent['data'])
            && array_key_exists('lDEF', $flexformContent['data']['sDefault'])
            && array_key_exists($optionName, $flexformContent['data']['sDefault']['lDEF'])
            && array_key_exists('vDEF', $flexformContent['data']['sDefault']['lDEF'][$optionName])
        ) {
            return $flexformContent['data']['sDefault']['lDEF']['settings.listIdentifier']['vDEF'];
        }
        return null;
    }



    /**
     * Init the DataProvider
     *
     * @param array $config
     */
    protected function initTsDataProvider($config)
    {
        $this->currentPid = $this->getCurrentPID($config);
        $this->loadExtListTyposcriptArray();
    }



    /**
     * get the current pid from config array
     * remove (int)$config['row']['pid'] as soon 6.2 is no longer supported
     *
     * @param array $config
     *
     * @return integer
     */
    protected function getCurrentPID($config)
    {
        return isset($config['flexParentDatabaseRow']['pid']) ? (int)$config['flexParentDatabaseRow']['pid'] : (int)$config['row']['pid'];
    }



    /**
     * Load the complete extlist part from typoscript
     */
    protected function loadExtListTyposcriptArray()
    {
        if (is_null($this->extListTypoScript)) {
            $extListTS = Tx_PtExtbase_Div::typoscriptRegistry('plugin.tx_ptextlist.', $this->currentPid);
            $this->extListTypoScript = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\TypoScriptService')->convertTypoScriptArrayToPlainArray($extListTS);
        }
    }



    /**
     * Return a list of typoscript keys beneath the current path
     *
     * @param string $typoScriptPath
     *
     * @return array
     */
    protected function getTypoScriptKeyList($typoScriptPath)
    {
        $keyList = array();
        $tsArray = $this->getTSArrayByPath($typoScriptPath);
        ksort($tsArray);
        foreach ($tsArray as $key => $valueArray) {
            $keyList[] = array($key, $key);
        }
        return $keyList;
    }



    /**
     * return a typoscript array by given typoscript path
     *
     * @param string $typoScriptPath
     *
     * @return array
     */
    protected function getTSArrayByPath($typoScriptPath)
    {
        $pathArray = explode('.', $typoScriptPath);
        $outTSArray = \TYPO3\CMS\Extbase\Utility\ArrayUtility::getValueByPath($this->extListTypoScript, $pathArray);
        if (!is_array($outTSArray)) {
            $outTSArray = array();
        }
        return $outTSArray;
    }
}
