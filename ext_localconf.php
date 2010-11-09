<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

/**
 * Configure the Plugin to call the
 * right combination of Controller and Action according to
 * the user input (default settings, FlexForm, URL etc.)
 * 
 * By default, first Action of first Controller is called
 * if no other settings are given.
 */

$controllerActions = array(															// An array holding the controller-action-combinations that are accessible 
		'List' => 'list,sort,export',	                                            // The first controller and its first action will be the default
		'Filterbox' => 'show,submit,reset',
		'Pager' => 'show,submit',
	    'Bookmarks' => 'show,process,edit,update,delete,create,new',
	    'BreadCrumbs' => 'index,resetFilter',
	    'DocBook' => 'createTsDocBook'
		);

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,																		// The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
	'Pi1',																			// A unique name of the plugin in UpperCamelCase
	$controllerActions,
	$controllerActions
);


require_once t3lib_extMgm::extPath('pt_extlist').'Classes/Utility/FlexformDataProvider.php';


/**
 * Register LifeCycle Manager
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['hook_eofe'][] = 'EXT:pt_extlist/Classes/Domain/Lifecycle/LifecycleHookManager.php:tx_PtExtlist_Domain_Lifecycle_LifecycleHookManager->updateEnd';
?>
