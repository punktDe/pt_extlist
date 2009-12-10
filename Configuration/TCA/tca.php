// // <?php
// if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
// 
// 
// 
// 
// $TCA['tx_ptextlist_domain_model_list'] = array(
// 	'ctrl' => $TCA['tx_ptextlist_domain_model_list']['ctrl'],
// 	'interface' => array(
// 		'showRecordFieldList' => 'listItems'
// 	),
// 	'types' => array(
// 		'1' => array('showitem' => 'listItems')
// 	),
// 	'palettes' => array(
// 		'1' => array('showitem' => '')
// 	),
// 	'columns' => array(
// 
// 		'sys_language_uid' => array (
// 			'exclude' => 1,
// 			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
// 			'config' => array (
// 				'type' => 'select',
// 				'foreign_table' => 'sys_language',
// 				'foreign_table_where' => 'ORDER BY sys_language.title',
// 				'items' => array(
// 					array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
// 					array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
// 				)
// 			)
// 		),
// 		'l18n_parent' => array (
// 			'displayCond' => 'FIELD:sys_language_uid:>:0',
// 			'exclude' => 1,
// 			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
// 			'config' => array (
// 				'type' => 'select',
// 				'items' => array (
// 					array('', 0),
// 				),
// 				'foreign_table' => 'tt_news',
// 				'foreign_table_where' => 'AND tt_news.uid=###REC_FIELD_l18n_parent### AND tt_news.sys_language_uid IN (-1,0)', // TODO
// 			)
// 		),
// 		'l18n_diffsource' => array(
// 			'config'=>array(
// 				'type'=>'passthrough')
// 		),
// 		't3ver_label' => array (
// 			'displayCond' => 'FIELD:t3ver_label:REQ:true',
// 			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
// 			'config' => array (
// 				'type'=>'none',
// 				'cols' => 27
// 			)
// 		),
// 		'hidden' => array(
// 			'exclude' => 1,
// 			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
// 			'config'  => array(
// 				'type' => 'check'
// 			)
// 		),
// 		
// 		'listItems' => array(
// 			'exclude' => 0,
// 			'label'   => 'listItems', // TODO 'LLL:EXT:blog_example/Resources/Private/Language/locallang_db.xml:tx_blogexample_domain_model_blog.title',
// 			'config'  => array(
// 				'type' => 'inline',
// 				'loadingStrategy' => 'proxy', // TODO: was "storage"
// 				'foreign_class' => 'Tx_PtExtlist_Domain_Model_ListItem',
// 				'foreign_table' => 'tx_ptextlist_domain_model_listitem',
// 				'foreign_field' => 'list_uid',
// 				'maxitems'      => 999999, // TODO This is only necessary because of a bug in tcemain
// 				'appearance' => array(
// 					'newRecordLinkPosition' => 'bottom',
// 					'collapseAll' => 1,
// 					'expandSingle' => 1,
// 				),
// 			)
// 		),
// 		
// 		
// 	),
// );
// 
// $TCA['tx_ptextlist_domain_model_listitem'] = array(
// 	'ctrl' => $TCA['tx_ptextlist_domain_model_listitem']['ctrl'],
// 	'interface' => array(
// 		'showRecordFieldList' => ''
// 	),
// 	'types' => array(
// 		'1' => array('showitem' => '')
// 	),
// 	'palettes' => array(
// 		'1' => array('showitem' => '')
// 	),
// 	'columns' => array(
// 
// 		'sys_language_uid' => array (
// 			'exclude' => 1,
// 			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
// 			'config' => array (
// 				'type' => 'select',
// 				'foreign_table' => 'sys_language',
// 				'foreign_table_where' => 'ORDER BY sys_language.title',
// 				'items' => array(
// 					array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
// 					array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
// 				)
// 			)
// 		),
// 		'l18n_parent' => array (
// 			'displayCond' => 'FIELD:sys_language_uid:>:0',
// 			'exclude' => 1,
// 			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
// 			'config' => array (
// 				'type' => 'select',
// 				'items' => array (
// 					array('', 0),
// 				),
// 				'foreign_table' => 'tt_news',
// 				'foreign_table_where' => 'AND tt_news.uid=###REC_FIELD_l18n_parent### AND tt_news.sys_language_uid IN (-1,0)', // TODO
// 			)
// 		),
// 		'l18n_diffsource' => array(
// 			'config'=>array(
// 				'type'=>'passthrough')
// 		),
// 		't3ver_label' => array (
// 			'displayCond' => 'FIELD:t3ver_label:REQ:true',
// 			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
// 			'config' => array (
// 				'type'=>'none',
// 				'cols' => 27
// 			)
// 		),
// 		'hidden' => array(
// 			'exclude' => 1,
// 			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
// 			'config'  => array(
// 				'type' => 'check'
// 			)
// 		),
// 		
// 		'' => array(
// 			'exclude' => 0,
// 			'label'   => '', // TODO 'LLL:EXT:blog_example/Resources/Private/Language/locallang_db.xml:tx_blogexample_domain_model_blog.title',
// 			'config'  => array(
// 				'type' => 'inline',
// 				'loadingStrategy' => 'proxy', // TODO: was "storage"
// 				'foreign_class' => 'Tx_PtExtlist_Domain_Model_ListItem',
// 				'foreign_table' => 'tx_ptextlist_domain_model_listitem',
// 				'foreign_field' => 'listitem_uid',
// 				'maxitems'      => 999999, // TODO This is only necessary because of a bug in tcemain
// 				'appearance' => array(
// 					'newRecordLinkPosition' => 'bottom',
// 					'collapseAll' => 1,
// 					'expandSingle' => 1,
// 				),
// 			)
// 		),
// 		
// 		
// 		'list_uid' => array(
// 			'config' => array(
// 				'type' => 'passthrough',
// 			)
// 		),
// 		
// 		'listitem_uid' => array(
// 			'config' => array(
// 				'type' => 'passthrough',
// 			)
// 		),
// 		
// 	),
// );
// 
// $TCA['tx_ptextlist_domain_model_simplelistitem'] = array(
// 	'ctrl' => $TCA['tx_ptextlist_domain_model_simplelistitem']['ctrl'],
// 	'interface' => array(
// 		'showRecordFieldList' => ''
// 	),
// 	'types' => array(
// 		'1' => array('showitem' => '')
// 	),
// 	'palettes' => array(
// 		'1' => array('showitem' => '')
// 	),
// 	'columns' => array(
// 
// 		'sys_language_uid' => array (
// 			'exclude' => 1,
// 			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
// 			'config' => array (
// 				'type' => 'select',
// 				'foreign_table' => 'sys_language',
// 				'foreign_table_where' => 'ORDER BY sys_language.title',
// 				'items' => array(
// 					array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
// 					array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
// 				)
// 			)
// 		),
// 		'l18n_parent' => array (
// 			'displayCond' => 'FIELD:sys_language_uid:>:0',
// 			'exclude' => 1,
// 			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
// 			'config' => array (
// 				'type' => 'select',
// 				'items' => array (
// 					array('', 0),
// 				),
// 				'foreign_table' => 'tt_news',
// 				'foreign_table_where' => 'AND tt_news.uid=###REC_FIELD_l18n_parent### AND tt_news.sys_language_uid IN (-1,0)', // TODO
// 			)
// 		),
// 		'l18n_diffsource' => array(
// 			'config'=>array(
// 				'type'=>'passthrough')
// 		),
// 		't3ver_label' => array (
// 			'displayCond' => 'FIELD:t3ver_label:REQ:true',
// 			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
// 			'config' => array (
// 				'type'=>'none',
// 				'cols' => 27
// 			)
// 		),
// 		'hidden' => array(
// 			'exclude' => 1,
// 			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
// 			'config'  => array(
// 				'type' => 'check'
// 			)
// 		),
// 		
// 		
// 	),
// );
// 
// $TCA['tx_ptextlist_domain_model_complexlistitem'] = array(
// 	'ctrl' => $TCA['tx_ptextlist_domain_model_complexlistitem']['ctrl'],
// 	'interface' => array(
// 		'showRecordFieldList' => ''
// 	),
// 	'types' => array(
// 		'1' => array('showitem' => '')
// 	),
// 	'palettes' => array(
// 		'1' => array('showitem' => '')
// 	),
// 	'columns' => array(
// 
// 		'sys_language_uid' => array (
// 			'exclude' => 1,
// 			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
// 			'config' => array (
// 				'type' => 'select',
// 				'foreign_table' => 'sys_language',
// 				'foreign_table_where' => 'ORDER BY sys_language.title',
// 				'items' => array(
// 					array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
// 					array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
// 				)
// 			)
// 		),
// 		'l18n_parent' => array (
// 			'displayCond' => 'FIELD:sys_language_uid:>:0',
// 			'exclude' => 1,
// 			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
// 			'config' => array (
// 				'type' => 'select',
// 				'items' => array (
// 					array('', 0),
// 				),
// 				'foreign_table' => 'tt_news',
// 				'foreign_table_where' => 'AND tt_news.uid=###REC_FIELD_l18n_parent### AND tt_news.sys_language_uid IN (-1,0)', // TODO
// 			)
// 		),
// 		'l18n_diffsource' => array(
// 			'config'=>array(
// 				'type'=>'passthrough')
// 		),
// 		't3ver_label' => array (
// 			'displayCond' => 'FIELD:t3ver_label:REQ:true',
// 			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
// 			'config' => array (
// 				'type'=>'none',
// 				'cols' => 27
// 			)
// 		),
// 		'hidden' => array(
// 			'exclude' => 1,
// 			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
// 			'config'  => array(
// 				'type' => 'check'
// 			)
// 		),
// 		
// 		
// 	),
// );
// 
// ?>