<?php
return [
    'ctrl' => [
        'title'             => 'Bookmark',
        'label'             => 'name',
        'tstamp'            => 'tstamp',
        'crdate'            => 'crdate',
        'origUid'           => 't3_origuid',
        'languageField'     => 'sys_language_uid',
        'transOrigPointerField'     => 'l18n_parent',
        'transOrigDiffSourceField'  => 'l18n_diffsource',
        'delete'            => 'deleted',
        'enablecolumns'     => [
            'disabled' => 'hidden'
        ],
        'iconfile'          => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('pt_extlist') . 'Resources/Public/Icons/icon_tx_ptextlist_domain_model_bookmark_bookmark.png'
    ],
    'interface' => [
        'showRecordFieldList' => 'name,description,list_id,fe_user,fe_group,is_public,create_date,content'
    ],
    'types' => [
        '1' => ['showitem' => 'name,description,list_id,fe_user,fe_group,is_public,create_date,content']
    ],
    'palettes' => [
        '1' => ['showitem' => '']
    ],
    'columns' => [

        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1],
                    ['LLL:EXT:lang/locallang_general.php:LGL.default_value',0]
                ]
            ]
        ],
        
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tt_news',
                'foreign_table_where' => 'AND tt_news.uid=###REC_FIELD_l18n_parent### AND tt_news.sys_language_uid IN (-1,0)', // TODO
            ]
        ],
        
        'l18n_diffsource' => [
            'config'=> [
                'type'=>'passthrough']
        ],
        
        't3ver_label' => [
            'displayCond' => 'FIELD:t3ver_label:REQ:true',
            'label' => 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
            'config' => [
                'type'=>'none',
                'cols' => 27
            ]
        ],
    
        'hidden' => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
            'config'  => [
                'type' => 'check'
            ]
        ],
        
        'name' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmark_bookmark.name',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ]
        ],
        
        'list_id' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmark_bookmark.list_id',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ]
        ],
        
        'description' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmark_bookmark.description',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],
        
        'content' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmark_bookmark.content',
            'config'  => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],
        
        'create_date' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmark_bookmark.create_date',
            'config'  => [
                'type' => 'input',
                'size' => 12,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => '0',
                'default' => '0'
            ]
        ],
        
        'fe_user' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmark_bookmark.fe_user',
            'config'  => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'fe_users',
                'foreign_class' => 'Tx_Extbase_Domain_Model_FrontendUser',
                'size' => 1,
                'minitems' => 0,
                'maxitems'      => 1,
                'multiple' => 0
            ]
        ],
        
        'fe_group' => [
            'exclude' => 0,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmark_bookmark.fe_group',
            'config'  => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'fe_users',
                'foreign_class' => 'Tx_Extbase_Domain_Model_FrontendUserGroup',
                'size' => 1,
                'minitems' => 0,
                'maxitems'      => 1,
                'multiple' => 0
            ]
        ],
        
        'type' => [
            'exclude' => 1,
            'label'   => 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_pt_extlist_domain_model_bookmark_bookmark.type',
            'config'  => [
                'type' => 'select',
                'items' => [
                    ['public', 1],
                    ['private', 2],
                    ['group', 3]
                ]
            ]
        ]
    ]
];
