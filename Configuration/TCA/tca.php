<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');


/**
 * Configuration for bookmars table
 */
$TCA['tx_ptextlist_domain_model_bookmars_bookmark'] = array(
    'ctrl' => $TCA['tx_ptextlist_domain_model_bookmars_bookmark']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'name,description,fe_user,list_id,create_date,content'
    ),
    'types' => array(
        '1' => array('showitem' => 'name,description,fe_user,list_id,create_date,content')
    ),
    'palettes' => array(
        '1' => array('showitem' => '')
    ),
    'columns' => array(

        'hidden' => array(
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config'  => array(
                'type' => 'check'
            )
        ),
        
        'name' => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang_db.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.name',
            'config'  => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            )
        ),
        
        'list_id' => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang_db.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.list_id',
            'config'  => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            )
        ),
        
        'description' => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang_db.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.description',
            'config'  => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            )
        ),
        
        'content' => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang_db.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.content',
            'config'  => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            )
        ),
        
        'create_date' => array(
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang_db.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.create_date',
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
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang_db.xml:tx_pt_extlist_domain_model_bookmarks_bookmark.fe_user',
            'config'  => array(
                'type' => 'select',
                'foreign_table' => 'fe_users',
                'foreign_class' => 'Tx_Extbase_Domain_Model_FrontendUser',
                'size' => 1,
                'minitems' => 1,
                'maxitems'      => 1,
                'multiple' => 0
            )
        )
    )
);


?>