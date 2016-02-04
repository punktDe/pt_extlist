<?php
defined('TYPO3_MODE') or die();


$pluginModes = array(
    'Pi1' => 'ExtList',
    'Cached' => 'ExtList (Cached)'
);

foreach ($pluginModes as $ident => $label) {

    /**
     * Register plugin as page content
     */
    $extensionName = \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase('pt_extlist');
    $pluginSignature = strtolower($extensionName) . '_' . strtolower($ident);
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature]='layout,select_key,pages';


    /**
     * Register flexform
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:pt_extlist/Configuration/FlexForms/Flexform.xml');
}
