<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/**
 * Configure the Plugin to call the
 * right combination of Controller and Action according to
 * the user input (default settings, FlexForm, URL etc.)
 *  
 * By default, first Action of first Controller is called
 * if no other settings are given.
 */
$controllerActions = [                                                            // An array holding the controller-action-combinations that are accessible
    'List' => 'list,sort', // The first controller and its first action will be the default
    'Export'=>'showLink,download',
    'Filterbox' => 'show,submit,reset,resetFilter',
    'Pager' => 'show',
    'Bookmark' => 'show,save,delete,restore',
    'BreadCrumbs' => 'index,resetFilter',
    'ColumnSelector' => 'show',
    'AjaxFilter' => 'getFilterElement'
];



\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'PtExtlist',                                                                        // The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
    'Pi1',                                                                            // A unique name of the plugin in UpperCamelCase
    $controllerActions,
    $controllerActions
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'PtExtlist',                                                                        // The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
    'Cached',                                                                        // A unique name of the plugin in UpperCamelCase
    $controllerActions,
    []
);


if (TYPO3_MODE == 'BE') {
    // Hooks
    $TYPO3_CONF_VARS['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['ptextlist_pi1']['pt_extlist'] = 'EXT:pt_extlist/Classes/Hooks/CMSLayoutHook.php:user_Tx_PtExtlist_Hooks_CMSLayoutHook->getExtensionSummary';
}

require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pt_extlist').'Classes/Utility/FlexformDataProvider.php';


/**
 * Register LifeCycle Manager
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['hook_eofe'][] = PunktDe\PtExtbase\Lifecycle\HookManager::class .'->updateEnd';
