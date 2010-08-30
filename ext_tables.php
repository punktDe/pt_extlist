<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');



/**
 * Load static template for Typoscript settings
 */
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', '[pt_extlist] Basic settings');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Demolist', '[pt_extlist] Static Countries Demolist');



/**
 * Register plugin in ExtBase
 */
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,// The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
	'Pi1',				// A unique name of the plugin in UpperCamelCase
	'pt_extlist'	// A title shown in the backend dropdown field
);



/**
 * Register plugin as page content
 */
$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);
$pluginSignature = strtolower($extensionName) . '_pi1';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_list.xml');



/**
 * Configuration for Bookmarks table
 */
t3lib_extMgm::allowTableOnStandardPages('tx_ptextlist_domain_model_bookmars_bookmark');
$TCA['tx_ptextlist_domain_model_bookmars_bookmark'] = array (
    'ctrl' => array (
        'title'             => 'Bookmark', 
        'label'             => 'name',
        'tstamp'            => 'tstamp',
        'crdate'            => 'crdate',
        'origUid'           => 't3_origuid',
        'languageField'     => 'sys_language_uid',
        'transOrigPointerField'     => 'l18n_parent',
        'transOrigDiffSourceField'  => 'l18n_diffsource',
        'delete'            => 'deleted',
        'enablecolumns'     => array(
            'disabled' => 'hidden'
            ),
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/tca.php', 
        'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/icon_tx_ptextlist_domain_model_bookmars_bookmark.gif'
    )
);


?>
