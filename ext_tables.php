<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}


if (TYPO3_MODE == 'BE') {

    // register the cache in BE so it will be cleared with "clear all caches"
// 	try {
// 		t3lib_cache::initializeCachingFramework();
// 			// State cache
// 		$GLOBALS['typo3CacheFactory']->create(
// 			'tx_ptextlist_cache_state',
// 			$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tx_ptextbase']['frontend'],
// 			$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tx_ptextbase']['backend'],
// 			$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['tx_ptextbase']['options']
// 		);

// 	} catch(t3lib_cache_exception_NoSuchCache $exception) {

// 	}
}


/**
 * Load static templates for Typoscript settings
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', '[pt_extlist] Basic settings');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Export', '[pt_extlist] Export settings');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Demolist', '[pt_extlist] Demolist Package');

$pluginModes = array(
    'Pi1' => 'ExtList',
    'Cached' => 'ExtList (Cached)'
);

foreach ($pluginModes as $ident => $label) {
    
    /**
     * Register plugin in ExtBase
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        $_EXTKEY,           // The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
        $ident,                // A unique name of the plugin in UpperCamelCase
        $label        // A title shown in the backend dropdown field
    );
}



\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_ptextlist_domain_model_bookmark_bookmark');
