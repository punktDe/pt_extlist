<?php

########################################################################
# Extension Manager/Repository config file for ext "pt_extlist".
#
# Auto generated 10-12-2009 00:37
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Punkt List',
	'description' => 'A generic list',
	'category' => 'plugin',
	'author' => 'TODO',
	'author_email' => 'TODO',
	'shy' => '',
	'dependencies' => 'cms,extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.0',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'extbase' => '',
			'fluid' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:28:{s:12:"ext_icon.gif";s:4:"e922";s:17:"ext_localconf.php";s:4:"32fd";s:14:"ext_tables.php";s:4:"91e1";s:14:"ext_tables.sql";s:4:"3d0b";s:16:"kickstarter.json";s:4:"d738";s:37:"Classes/Controller/ListController.php";s:4:"c368";s:40:"Classes/Domain/Model/ComplexListItem.php";s:4:"a086";s:29:"Classes/Domain/Model/List.php";s:4:"fd99";s:33:"Classes/Domain/Model/ListItem.php";s:4:"7bff";s:39:"Classes/Domain/Model/SimpleListItem.php";s:4:"f776";s:45:"Classes/Domain/Repository/ListItemFactory.php";s:4:"f6eb";s:48:"Classes/Domain/Repository/ListItemRepository.php";s:4:"79df";s:37:"Classes/ViewHelper/ListViewHelper.php";s:4:"fe26";s:25:"Configuration/TCA/tca.php";s:4:"82c1";s:34:"Configuration/TypoScript/setup.txt";s:4:"8473";s:40:"Resources/Private/Language/locallang.xml";s:4:"a41e";s:38:"Resources/Private/Partials/header.html";s:4:"a761";s:43:"Resources/Private/Templates/List/index.html";s:4:"500a";s:44:"Resources/Public/Icons/bookmark_document.png";s:4:"7a43";s:40:"Resources/Public/Icons/button_cancel.png";s:4:"c1d3";s:43:"Resources/Public/Icons/icon_sorting_asc.png";s:4:"598e";s:47:"Resources/Public/Icons/icon_sorting_default.png";s:4:"6d4b";s:44:"Resources/Public/Icons/icon_sorting_desc.png";s:4:"7b73";s:45:"Resources/Public/Icons/library_bookmarked.png";s:4:"933f";s:39:"Resources/Public/Icons/mi_arr2_down.gif";s:4:"4cc6";s:37:"Resources/Public/Icons/mi_arr2_up.gif";s:4:"37aa";s:51:"Resources/Public/JavaScript/tx_ptlist_datePicker.js";s:4:"1af7";s:50:"Resources/Public/JavaScript/tx_ptlist_timeSpan2.js";s:4:"3ee1";}',
);

?>