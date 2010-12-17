<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

/**
 * Load static templates for Typoscript settings
 */
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', '[pt_extlist] Basic settings');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Export', '[pt_extlist] Export settings');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Demolist', '[pt_extlist] Demolist Package');

$pluginModes = array(
	'Pi1' => 'ExtList',
	'Cached' => 'ExtList (Cached)'
);

foreach ($pluginModes as $ident => $label) {
	
	/**
	 * Register plugin in ExtBase
	 */
	Tx_Extbase_Utility_Extension::registerPlugin(
		$_EXTKEY,           // The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
		$ident,				// A unique name of the plugin in UpperCamelCase
		$label	    // A title shown in the backend dropdown field
	);
	
	/**
	 * Register plugin as page content
	 */
	$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);
	$pluginSignature = strtolower($extensionName) . '_' . strtolower($ident);
	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature]='layout,select_key,pages';
	
	
	/**
	 * Register flexform
	 */
	t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform.xml');
	
}


/**
 * Configuration for Bookmarks table
 */
t3lib_extMgm::allowTableOnStandardPages('tx_ptextlist_domain_model_bookmarks_bookmark');
$TCA['tx_ptextlist_domain_model_bookmarks_bookmark'] = array (
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

t3lib_extMgm::addLLrefForTCAdescr('Tx_PtExtlist_Domain_Model_StateStorage_State', 'EXT:pt_extlist/Resources/Private/Language/locallang_csh_tx_ptextlist_domain_model_state.xml');
t3lib_extMgm::allowTableOnStandardPages('Tx_PtExtlist_Domain_Model_StateStorage_State');
$TCA['tx_ptextlist_domain_model_state'] = array (
	'ctrl' => array (
		'title'             => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang_db.xml:tx_ptextlist_domain_model_statestorage_state',
		'label' 			=> 'hash',
		'tstamp' 			=> 'tstamp',
		'crdate' 			=> 'crdate',
		'versioningWS' 		=> 2,
		'versioning_followPages'	=> TRUE,
		'origUid' 			=> 't3_origuid',
		'languageField' 	=> 'sys_language_uid',
		'transOrigPointerField' 	=> 'l18n_parent',
		'transOrigDiffSourceField' 	=> 'l18n_diffsource',
		'delete' 			=> 'deleted',
		'enablecolumns' 	=> array(
			'disabled' => 'hidden'
			),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/State.php',
		'iconfile' 			=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_ptextlist_domain_model_statestorage_state'
	)
);


?>