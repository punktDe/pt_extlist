<?php

/**
 * All ExtBase-conform files are included automatically. 
 * So only files and classes from 'old' Extensions are 
 * listed here.
 */

// TODO ry21 why does this not work in Unit Tests?

return array(
    /* Pt_ExtList depending classes */
    'tx_ptextlist_tests_domain_stateadapter_stubs_persistableobject' => t3lib_extMgm::extPath('pt_extlist').'Tests/Domain/StateAdapter/Stubs/PersistableObject.php',
    'tx_ptextlist_tests_domain_stateadapter_stubs_getpostvarobject' => t3lib_extMgm::extPath('pt_extlist').'Tests/Domain/StateAdapter/Stubs/GetPostVarObject.php',
    'tx_ptextlist_tests_domain_stateadapter_stubs_sessionadaptermock' => t3lib_extMgm::extPath('pt_extlist').'Tests/Domain/StateAdapter/Stubs/SessionAdapterMock.php',
    'tx_ptextlist_tests_domain_databackend_datasource_datasourcemock' => t3lib_extMgm::extPath('pt_extlist').'Tests/Domain/DataBackend/DataSource/DataSourceMock.php',
    'tx_ptextlist_tests_basetestcase' => t3lib_extMgm::extPath('pt_extlist').'Tests/BaseTestcase.php',
    'tx_ptextlist_tests_domain_configuration_filters_stubs_filterboxconfigurationcollectionmock' => t3lib_extMgm::extPath('pt_extlist').'Tests/Domain/Configuration/Filters/Stubs/FilterboxConfigurationCollectionMock.php',
    'tx_ptextlist_tests_domain_configuration_configurationbuildermock' => t3lib_extMgm::extPath('pt_extlist').'Tests/Domain/Configuration/ConfigurationBuilderMock.php',
    'tx_ptextlist_tests_domain_configuration_filters_stubs_filterboxconfigurationmock' => t3lib_extMgm::extPath('pt_extlist').'Tests/Domain/Configuration/Filters/Stubs/FilterboxConfigurationMock.php',
    'tx_ptextlist_tests_domain_databackend_abstractdatabackendbasetest' => t3lib_extMgm::extPath('pt_extlist').'Tests/Domain/DataBackend/AbstractDataBackendBaseTest.php',
    'tx_ptextlist_tests_domain_model_filter_stubs_filterstub' => t3lib_extMgm::extPath('pt_extlist').'Tests/Domain/Model/Filter/Stubs/FilterStub.php',
    'tx_ptextlist_view_export_abstractexportview' => t3lib_extMgm::extPath('pt_extlist').'Classes/View/Export/AbstractExportView.php',
    'tx_ptextlist_view_baseview' => t3lib_extMgm::extPath('pt_extlist').'Classes/View/BaseView.php',

    /* External Extensions */
    'tx_pttools_objectcollection'       => t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_objectCollection.php',
    'tx_pttools_collection'             => t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_collection.php',
    'tx_pttools_assert'                 => t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php',
    'tx_pttools_sessionstorageadapter'  => t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_sessionStorageAdapter.php',
    'tx_pttools_div'                    => t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'

);

?>