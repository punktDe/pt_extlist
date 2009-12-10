<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

Tx_Extbase_Utility_Extension::registerPlugin($_EXTKEY, 'pi1', 'Punkt List');

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Punkt List');

//$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_pi1'] = 'pi_flexform';
//t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_list.xml');


// t3lib_extMgm::allowTableOnStandardPages('tx_ptextlist_domain_model_list');
// $TCA['tx_ptextlist_domain_model_list'] = array (
// 	'ctrl' => array (
// 		'title'             => 'List', //'LLL:EXT:blog_example/Resources/Private/Language/locallang_db.xml:tx_blogexample_domain_model_blog', // TODO
// 		'label' 			=> 'listItems',
// 		'tstamp' 			=> 'tstamp',
// 		'crdate' 			=> 'crdate',
// 		'versioningWS' 		=> 2,
// 		'versioning_followPages'	=> true,
// 		'origUid' 			=> 't3_origuid',
// 		'languageField' 	=> 'sys_language_uid',
// 		'transOrigPointerField' 	=> 'l18n_parent',
// 		'transOrigDiffSourceField' 	=> 'l18n_diffsource',
// 		'delete' 			=> 'deleted',
// 		'enablecolumns' 	=> array(
// 			'disabled' => 'hidden'
// 			),
// 		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/tca.php', // TODO CREATE
// 		'iconfile' 			=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/icon_tx_ptextlist_domain_model_list.gif' // TODO CREATE
// 	)
// );
// 
// 
// t3lib_extMgm::allowTableOnStandardPages('tx_ptextlist_domain_model_listitem');
// $TCA['tx_ptextlist_domain_model_listitem'] = array (
// 	'ctrl' => array (
// 		'title'             => 'ListItem', //'LLL:EXT:blog_example/Resources/Private/Language/locallang_db.xml:tx_blogexample_domain_model_blog', // TODO
// 		'label' 			=> '',
// 		'tstamp' 			=> 'tstamp',
// 		'crdate' 			=> 'crdate',
// 		'versioningWS' 		=> 2,
// 		'versioning_followPages'	=> true,
// 		'origUid' 			=> 't3_origuid',
// 		'languageField' 	=> 'sys_language_uid',
// 		'transOrigPointerField' 	=> 'l18n_parent',
// 		'transOrigDiffSourceField' 	=> 'l18n_diffsource',
// 		'delete' 			=> 'deleted',
// 		'enablecolumns' 	=> array(
// 			'disabled' => 'hidden'
// 			),
// 		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/tca.php', // TODO CREATE
// 		'iconfile' 			=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/icon_tx_ptextlist_domain_model_listitem.gif' // TODO CREATE
// 	)
// );
// 
// 
// t3lib_extMgm::allowTableOnStandardPages('tx_ptextlist_domain_model_simplelistitem');
// $TCA['tx_ptextlist_domain_model_simplelistitem'] = array (
// 	'ctrl' => array (
// 		'title'             => 'SimpleListItem', //'LLL:EXT:blog_example/Resources/Private/Language/locallang_db.xml:tx_blogexample_domain_model_blog', // TODO
// 		'label' 			=> 'uid',
// 		'tstamp' 			=> 'tstamp',
// 		'crdate' 			=> 'crdate',
// 		'versioningWS' 		=> 2,
// 		'versioning_followPages'	=> true,
// 		'origUid' 			=> 't3_origuid',
// 		'languageField' 	=> 'sys_language_uid',
// 		'transOrigPointerField' 	=> 'l18n_parent',
// 		'transOrigDiffSourceField' 	=> 'l18n_diffsource',
// 		'delete' 			=> 'deleted',
// 		'enablecolumns' 	=> array(
// 			'disabled' => 'hidden'
// 			),
// 		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/tca.php', // TODO CREATE
// 		'iconfile' 			=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/icon_tx_ptextlist_domain_model_simplelistitem.gif' // TODO CREATE
// 	)
// );
// 
// 
// t3lib_extMgm::allowTableOnStandardPages('tx_ptextlist_domain_model_complexlistitem');
// $TCA['tx_ptextlist_domain_model_complexlistitem'] = array (
// 	'ctrl' => array (
// 		'title'             => 'ComplexListItem', //'LLL:EXT:blog_example/Resources/Private/Language/locallang_db.xml:tx_blogexample_domain_model_blog', // TODO
// 		'label' 			=> 'uid',
// 		'tstamp' 			=> 'tstamp',
// 		'crdate' 			=> 'crdate',
// 		'versioningWS' 		=> 2,
// 		'versioning_followPages'	=> true,
// 		'origUid' 			=> 't3_origuid',
// 		'languageField' 	=> 'sys_language_uid',
// 		'transOrigPointerField' 	=> 'l18n_parent',
// 		'transOrigDiffSourceField' 	=> 'l18n_diffsource',
// 		'delete' 			=> 'deleted',
// 		'enablecolumns' 	=> array(
// 			'disabled' => 'hidden'
// 			),
// 		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/tca.php', // TODO CREATE
// 		'iconfile' 			=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/icon_tx_ptextlist_domain_model_complexlistitem.gif' // TODO CREATE
// 	)
// );
// 
// 

?>
