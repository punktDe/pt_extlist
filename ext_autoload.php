<?php

/**
 * All ExtBase-conform files are included automatically. 
 * So only files and classes from 'old' Extensions are 
 * listed here.
 */

// TODO ry21 why does this not work in Unit Tests?

return array(
    /* Pt_ExtList depending classes */
    'tx_ptextlist_tests_domain_sessionpersistence_stubs_persistableobject' => t3lib_extMgm::extPath('pt_extlist').'Tests/Domain/SessionPersistence/Stubs/PersistableObject.php',
    'tx_ptextlist_tests_basetestcase' => t3lib_extMgm::extPath('pt_extlist').'Tests/BaseTestcase.php',
    'tx_ptextlist_tests_domain_model_filter_stubs_filterboxconfigurationcollectionmock' => 'Tests/Domain/Model/Filter/Stubs/FilterBoxConfigurationCollectionMock.php',


    /* External Extensions */
    'tx_pttools_objectcollection'       => t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_objectCollection.php',
    'tx_pttools_collection'             => t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_collection.php',
    'tx_pttools_assert'                 => t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php',
    'tx_pttools_sessionstorageadapter'  => t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_sessionStorageAdapter.php'

);

?>