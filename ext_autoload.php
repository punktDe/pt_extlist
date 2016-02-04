<?php

$extensionBasePath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pt_extlist');

/**
 * All ExtBase-conform files are included automatically. 
 * So only files and classes from 'old' Extensions are 
 * listed here.
 */
return array(
    /* Pt_ExtList depending classes */
    'tx_ptextlist_tests_domain_stateadapter_stubs_persistableobject' => $extensionBasePath.'Tests/Domain/StateAdapter/Stubs/PersistableObject.php',
    'tx_ptextlist_tests_domain_stateadapter_stubs_getpostvarobject' => $extensionBasePath.'Tests/Domain/StateAdapter/Stubs/GetPostVarObject.php',
    'tx_ptextlist_tests_domain_stateadapter_stubs_sessionadaptermock' => $extensionBasePath.'Tests/Domain/StateAdapter/Stubs/SessionAdapterMock.php',
    'tx_ptextlist_tests_domain_databackend_datasource_datasourcemock' => $extensionBasePath.'Tests/Domain/DataBackend/DataSource/DataSourceMock.php',
    'tx_ptextlist_tests_basetestcase' => $extensionBasePath.'Tests/BaseTestcase.php',
    'tx_ptextlist_tests_domain_configuration_filters_stubs_filterboxconfigurationcollectionmock' => $extensionBasePath.'Tests/Domain/Configuration/Filters/Stubs/FilterboxConfigurationCollectionMock.php',
    'tx_ptextlist_tests_domain_configuration_configurationbuildermock' => $extensionBasePath.'Tests/Domain/Configuration/ConfigurationBuilderMock.php',
    'tx_ptextlist_tests_domain_configuration_filters_stubs_filterboxconfigurationmock' => $extensionBasePath.'Tests/Domain/Configuration/Filters/Stubs/FilterboxConfigurationMock.php',
    'tx_ptextlist_tests_domain_databackend_abstractdatabackendbasetest' => $extensionBasePath.'Tests/Domain/DataBackend/AbstractDataBackendBaseTest.php',
    'tx_ptextlist_tests_domain_model_filter_stubs_filterstub' => $extensionBasePath.'Tests/Domain/Model/Filter/Stubs/FilterStub.php',
    'tx_ptextlist_tests_domain_renderer_dummyrenderer' => $extensionBasePath.'Tests/Domain/Renderer/DummyRenderer.php',
    'tx_ptextlist_tests_view_testview' => $extensionBasePath.'Tests/View/TestView.php',
    'tx_ptextlist_view_export_abstractexportview' => $extensionBasePath.'Classes/View/Export/AbstractExportView.php',
    'tx_ptextlist_view_baseview' => $extensionBasePath.'Classes/View/BaseView.php',
    'tx_ptextlist_view_configurableviewinterface' => $extensionBasePath.'Classes/View/ConfigurableViewInterface.php',
    'tx_ptextlist_tests_performance_testdatabackend' => $extensionBasePath . 'Tests/Performance/TestDataBackend.php',
    'tx_ptextlist_tests_performance_testdatasource' => $extensionBasePath . 'Tests/Performance/TestDataSource.php',
    'tx_ptextlist_domain_databackend_datasource_iterationdatasourceinterface' => $extensionBasePath . 'Classes/Domain/DataBackend/DataSource/IterationDataSourceInterface.php',
    'tx_ptextlist_tests_domain_model_filter_dataprovider_abstractdataproviderbasetestcase' => $extensionBasePath . 'Tests/Domain/Model/Filter/DataProvider/AbstractDataProviderBaseTestcase.php',
    'tx_ptextlist_foreign_phpexcel' => $extensionBasePath . 'Classes/Foreign/PHPExcel',

    'user_tx_ptextlist_utility_flexformdataprovider' => $extensionBasePath.'Classes/Utility/FlexformDataProvider.php',

    'tx_ptextbase_configuration_abstractconfigurationbuilder' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pt_extbase').'Classes/Configuration/AbstractConfigurationBuilder.php'
);
