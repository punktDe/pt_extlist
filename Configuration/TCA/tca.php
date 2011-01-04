<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');


/**
 * Configuration for bookmars table
 */
$TCA['tx_ptextlist_domain_model_bookmarks_bookmark'] = array(
    'ctrl' => $TCA['tx_ptextlist_domain_model_bookmarks_bookmark']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'name,description,list_id,fe_user,fe_group,is_public,create_date,content'
    ),
    'types' => array(
        '1' => array('showitem' => 'name,description,list_id,fe_user,fe_group,is_public,create_date,content')
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    ),
    'columns' => array(

        'sys_language_uid' => array (
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
            'config' => array (
                'type' => 'select',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => array(
                    array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
                    array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
                )
            )
        ),
        
        'l18n_parent' => array (
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
            'config' => array (
                'type' => 'select',
                'items' => array (
                    array('', 0),
                ),
                'foreign_table' => 'tt_news',
                'foreign_table_where' => 'AND tt_news.uid=###REC_FIELD_l18n_parent### AND tt_news.sys_language_uid IN (-1,0)', // TODO
            )
        ),
        
        'l18n_diffsource' => array(
            'config'=>array(
                'type'=>'passthrough')
        ),
        
        't3ver_label' => array (
            'displayCond' => 'FIELD:t3ver_label:REQ:true',
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
            'config' => array (
                'type'=>'none',
                'cols' => 27
            )
        ),
    
        'hidden' => array(
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config'  => array(
                'type' => 'check'
            )
        ),
        
        'name' => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.name',
            'config'  => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            )
        ),
        
        'list_id' => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.list_id',
            'config'  => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            )
        ),
        
        'description' => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.description',
            'config'  => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            )
        ),
        
        'content' => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.content',
            'config'  => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            )
        ),
        
        'create_date' => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.create_date',
            'config'  => array(
                'type' => 'input',
                'size' => 12,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => '0',
                'default' => '0'
            )
        ),
        
        'fe_user' => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.fe_user',
            'config'  => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'fe_users',
                'foreign_class' => 'Tx_Extbase_Domain_Model_FrontendUser',
                'size' => 1,
                'minitems' => 0,
                'maxitems'      => 1,
                'multiple' => 0
            )
        ),
        
        'fe_group' => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.fe_group',
            'config'  => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'fe_users',
                'foreign_class' => 'Tx_Extbase_Domain_Model_FrontendUserGroup',
                'size' => 1,
                'minitems' => 0,
                'maxitems'      => 1,
                'multiple' => 0
            )
        ),
        
        'is_public' => array(
            'exclude' => 1,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.is_public',
            'config'  => array(
                'type' => 'check'
            )
        )
    )
);
?>