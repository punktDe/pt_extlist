***************
Configuraration
***************


Setting up Lists
================
In this section you will learn how to set up lists using pt_extlist. We will guide you step by step through the TypoScript configuration and show you how to use pt_extlist's widgets as page content.

Widgets overview
-----------------
Get an overview of what the individual widgets are doing and how they look like in the frontend. All widgets depend on a list identifier set up in TypoScript and selected within the FlexForm of your plugin.

List widget
-----------------
Renders a list of data set up by configuration. Can use headers for sorting list data by certain columns.

Filter widgets
-----------------
Renders filterboxes containing multiple filters defined by configuration.

Pager widget
-----------------
Renders a pager as configured by configuration. Pager limits rows of list to configured amount per page.

Breadcrumbs widget
------------------
Breadcrumbs show which filters are activated and which values they have.

Bookmarks widget
-----------------
Bookmarks enable user to save certain list settings like filters, pager, sortings and reload them again afterwards.

TypoScript configuration
------------------------

List Identifier and TypoScript namespace
Each list has its own identifier.

Sample Configuration::

    The following listing shows a sample configuration as it ships with pt_extlist.
    plugin.tx_ptextlist.settings {
    
    _LOCAL_LANG.default.emptyList = empty list
    
    listConfig.demolist < plugin.tx_ptextlist.prototype.listConfig.default
    listConfig.demolist {
    
    backendConfig < plugin.tx_ptextlist.prototype.backend.typo3
    backendConfig {
    
    datasource {
    # no configuration required here
    }
    
    tables (
    static_countries,
    static_territories st_continent,
    static_territories st_subcontinent
    )
    
    baseFromClause (
    static_countries
    LEFT JOIN static_territories AS st_subcontinent ON (static_countries.cn_parent_tr_iso_nr = st_subcontinent.tr_iso_nr)
    LEFT JOIN static_territories AS st_continent ON (st_subcontinent.tr_parent_iso_nr = st_continent.tr_iso_nr)
    )
    
    baseWhereClause (
    st_continent.tr_name_en <> ""
    AND st_subcontinent.tr_name_en <> ""
    )
    }
    
    fields {
    name_local {
    table = static_countries
    field = cn_short_local
    isSortable = 1
    }
    
    name_en {
    table = static_countries
    field = cn_short_en
    }
    
    uno_member {
    table = static_countries
    field = cn_uno_member
    }
    
    capital {
    table = static_countries
    field = cn_capital
    }
    
    iso2 {
    table = static_countries
    field = cn_iso_2
    isSortable = 0
    }
    
    phone {
    table = static_countries
    field = cn_phone
    }
    
    isoNo {
    table = static_countries
    field = cn_currency_iso_nr
    }
    
    continent {
    table = st_continent
    field = tr_name_en
    }
    
    subcontinent {
    table = st_subcontinent
    field = tr_name_en
    }
    
    countryuid {
    table = static_countries
    field = uid
    }
    }
    
    pager {
    pagerConfigs {
    second {
    enabled = 1
    pagerClassName = Tx_PtExtlist_Domain_Model_Pager_DefaultPager
    templatePath = EXT:pt_extlist/Resources/Private/Templates/Pager/second.html
    
    showNextLink = 1
    showPreviousLink = 1
    showFirstLink = 0
    showLastLink = 0
    }
    }
    }
    
    columns {
    
    10 {
    columnIdentifier = nameColumn
    label = LLL:EXT:pt_extlist/Configuration/TypoScript/Demolist/locallang.xml:column_nameColumn
    
    fieldIdentifier = name_local, name_en, countryuid, uno_member
    isSortable = 1
    sorting = name_local
    
    renderObj = COA
    renderObj {
    5 = IMAGE
    5.if {
    value.data = field:uno_member
    equals = 1
    }
    5.file = EXT:pt_list/typoscript/static/demolist/un.gif
    5.stdWrap.typolink.parameter = http://www.un.org
    5.stdWrap.typolink.ATagParams = class="un-link"
    
    10 = TEXT
    10.data = field:name_en
    10.append = TEXT
    10.append {
    data = field:name_local
    if {
    value.data = field:name_local
    equals.data = field:name_en
    negate = 1
    }
    }
    10.append.noTrimWrap = | (\|)|
    10.wrap3 = \|&nbsp;
    
    20 = TEXT
    20.value = Details
    20.typolink.parameter = 1
    20.typolink.additionalParams.dataWrap = &tx_unseretolleextension_controller_details[countryuid]={field:countryuid}
    }
    }
    
    11 {
    label = Capital
    columnIdentifier = capital
    fieldIdentifier = capital
    cellCSSClass {
    renderObj = TEXT
    renderObj.dataWrap = {field:capital}
    }
    }
    
    20 {
    label = LLL:EXT:pt_extlist/Configuration/TypoScript/Demolist/locallang.xml:column_isoNoColumn
    columnIdentifier = isoNoColumn
    fieldIdentifier = iso2
    isSortable = 1
    }
    
    30 {
    label = Phone
    columnIdentifier = phoneColumn
    fieldIdentifier = phone
    }
    
    40 {
    label = Continent
    columnIdentifier = continent
    fieldIdentifier = continent
    }
    
    50 {
    label = Subcontinent
    columnIdentifier = subcontinent
    fieldIdentifier = subcontinent
    accessGroups = 3
    }
    }
    
    aggregateData {
    sumPhone {
    fieldIdentifier = phone
    method = sum
    }
    avgPhone {
    fieldIdentifier = phone
    method = avg
    }
    maxPhone {
    fieldIdentifier = phone
    method = max
    }
    minPhone {
    fieldIdentifier = phone
    method = min
    }
    }
    
    aggregateRows {
    10 {
    phoneColumn {
    aggregateDataIdentifier = sumPhone, avgPhone, maxPhone, minPhone
    renderObj = TEXT
    renderObj.dataWrap (
    Min.: <b>{field:minPhone}</b><br />
    &empty;: <b>{field:avgPhone}</b><br />
    Max.: <b>{field:maxPhone}</b><br />
    &sum;: <b>{field:sumPhone}</b><br />
    )
    }
    }
    }
    
    filters {
    filterbox1 {
    filterConfigs {
    10 < plugin.tx_ptextlist.prototype.filter.string
    10 {
    filterIdentifier = filter1
    label = LLL:EXT:pt_extlist/Configuration/TypoScript/Demolist/locallang.xml:filter_nameField
    fieldIdentifier = name_local
    }
    
    11 < plugin.tx_ptextlist.prototype.filter.string
    11 {
    filterIdentifier = allFields
    label = All defined Fields
    fieldIdentifier = *
    }
    
    15 < plugin.tx_ptextlist.prototype.filter.max
    15 {
    filterIdentifier = filter15
    label = Max Phone
    fieldIdentifier = phone
    accessGroups = 3
    }
    
    20 < plugin.tx_ptextlist.prototype.filter.checkbox
    20 {
    filterIdentifier = filter2
    label = Continent
    fieldIdentifier = continent
    filterField = continent
    displayFields = continent
    showRowCount = 1
    submitOnChange = 0
    invert = 0
    invertable = 0
    
    excludeFilters = filterbox1.filter3
    }
    
    30 < plugin.tx_ptextlist.prototype.filter.select
    30 {
    filterIdentifier = filter3
    label = Subcontinent
    fieldIdentifier = subcontinent
    filterField = subcontinent
    displayFields = continent, subcontinent
    multiple = 0
    showRowCount = 1
    submitOnChange = 0
    inactiveOption = \[ALL]
    invert = 0
    invertable = 0
    }
    
    }
    }
    
    filterbox2 {
    showSubmit = 0
    showReset = 0
    filterConfigs {
    10 < plugin.tx_ptextlist.prototype.filter.firstLetter
    10 {
    filterIdentifier = filter4
    label = Capital
    fieldIdentifier = capital
    }
    }
    }
    }
    }
    }
    
    plugin.tx_ptextlist.settings.listConfig.demoListProxyFilter {
    backendConfig < plugin.tx_ptextlist.prototype.backend.typo3
    backendConfig {
    tables (
    static_territories
    )
    
    continent {
    table = static_territories
    field = tr_name_en
    }
    }
    
    fields {
    continent {
    table = static_territories
    field = tr_name_en
    }
    }
    
    filters {
    filterbox1 {
    filterConfigs {
    10 < plugin.tx_ptextlist.prototype.filter.select
    10 {
    filterIdentifier = continent
    label = Subcontinent
    fieldIdentifier = continent
    filterField = continent
    displayFields = continent
    showRowCount = 0
    multiple = 0
    inactiveOption = \[ALL]
    
    renderObj = TEXT
    renderObj {
    dataWrap = {field:allDisplayFields}
    }
    }
    }
    }
    }
    }
    
    ################################
    # Localization Override
    ################################
    plugin.tx_ptextlist._LOCAL_LANG{
    default {
    emptyList = List is empty.
    }
    de {
    emptyList = Liste ist leer.
    }
    }

backendConfig section


Setting up a MySQL backend
----------------------------

Setting up a TYPO3 backend
----------------------------

Setting up a Extbase backend
----------------------------

fields section
----------------------------

Setting up fields for database backends
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Setting up fields for Extbase domain objects
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

columns section
----------------------------

filters section
----------------------------

pager section
----------------------------

aggregates
----------------------------

aggregateData section
^^^^^^^^^^^^^^^^^^^^^

aggregateRow section
^^^^^^^^^^^^^^^^^^^^

Localization override
----------------------------


Setting up widgets as content elements
--------------------------------------