####################################################
# This template configures a demolist for use
# with pt_extlist
#
# This demolist requires pt_extlist_special to be working!
# TODO put this into pt_extlist_special!
#
# @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################

plugin.tx_ptextlist.settings {

	listConfig.demolist_sorting < plugin.tx_ptextlist.prototype.listConfig.default
	listConfig.demolist_sorting {

		default.cacheRendering = 1

	    # If we set this to 1, list will be reset every time, we open up a page without an extlist action
	    # base.resetOnEmptySubmit = 1

		# Overwrite partial for different sorting header
		#headerPartial = EXT:pt_extlist_special/Resources/Private/Partials/SortingFieldsHeader.html

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
				st_continent.tr_name_en <> ''
				AND st_subcontinent.tr_name_en <> ''
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

		columns {

			10 {
				# choose a columnIdentifier
				columnIdentifier = nameColumn

				# This label will be displayed in the item list (use lll here)
				label = LLL:EXT:pt_extlist/Configuration/TypoScript/Demolist/locallang.xml:column_nameColumn

				# Set the dataDescriptions this columns refers to
				# the dataDescriptionIdentifier is a single reference to a dataDescription if type is default
				# or a csl list of referencs if type is "virtual"
				fieldIdentifier = name_en, countryuid, uno_member

				# a column is sortable if "isSortable" is set to 1 _and_
				# all sortingColumns entries this column refers to are sortable too
				isSortable = 1

				# sortingDataDescription is a reference to one dataDescription that will be used for sorting
				# if not set, the first entry in dataDescriptionIdentifier will be used
				sorting = name_en

				sortingFields {
				    10 {
				        field = name_en
				        label = LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_ptextlist_demolist.name_local
				        direction = asc
				        forceDirection = 0
				    }
                    20 {
                        field = name_local
                        label = Name (lokal)
                        direction = asc
                        forceDirection = 0
                    }
                    30 {
                        field = uno_member
                        label = LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_ptextlist_demolist.uno_member
                        direction = asc
                        forceDirection = 0
                    }
				}

				renderObj = COA
				renderObj {
					5 = IMAGE
					5.if {
						value.data = field:uno_member
						equals = 1
					}
					5.file = EXT:pt_extlist/Resources/Public/Images/un.gif
					5.stdWrap.typolink.parameter = http://www.un.org
					5.stdWrap.typolink.ATagParams = class="un-link"

					10 = TEXT
					10.data = field:name_en

					/*
					10.append.noTrimWrap = | (|)|
					10.wrap3 = |&nbsp;

					20 = TEXT
					20.value = Details
					20.typolink.parameter = 1
					20.typolink.additionalParams.dataWrap = &tx_unseretolleextension_controller_details[countryuid]={field:countryuid}
					*/
				}
			}

			11 {
				label = Capital
				headerThCssClass = testclass
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
				# default columns can only operate on _one_ dataDescription
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
			# methods: sum, min, max, avg
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
			    # Use this configuration if you want to do a redirect after a filterbox submits
			    #redirectOnSubmit{
			    #    pageId = 10
			    #    controller = testController
			    #    action = testAction
			    #}

				filterConfigs {
					10 < plugin.tx_ptextlist.prototype.filter.string
					10 {
						filterIdentifier = contryNameFilter
						# defaultValue = a
						# resetFilterToDefault = 0
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
						filterIdentifier = maxPhoneFilter
						label = Max Phone
						fieldIdentifier = phone
						accessGroups = 3
					}

					20 < plugin.tx_ptextlist.prototype.filter.checkbox
					20 {
						filterIdentifier = continentCheckboxFilter
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
						filterIdentifier = subContinentSelectFilter
						label = Subcontinent
						fieldIdentifier = subcontinent
						filterField = subcontinent
						displayFields = continent, subcontinent
						multiple = 1
						#resetToDefaultValue = 1
						#defaultValue = Caribbean
						showRowCount = 1
						#excludeFilters = filterbox1.filter1
						submitOnChange = 0
						inactiveOption = [ALL]
						invert = 0
						invertable = 0
					}

					35 < plugin.tx_ptextlist.prototype.filter.select
					35 {
						filterIdentifier = capitalFilter
						label = Capital
						fieldIdentifier = capital
						inactiveOption = [ALL]
						showRowCount = 0
					}

					#50 < plugin.tx_ptextlist.prototype.filter.proxy
					#50 {
					#	filterIdentifier = continentProxy
					#	proxyPath = demoListProxyFilter.filterbox1.continentCheckboxFilter
					#	fieldIdentifier = continent
					#}
				}
			}

			filterbox2 {
				showSubmit = 0
				showReset = 0
				filterConfigs {
					10 < plugin.tx_ptextlist.prototype.filter.firstLetter
					10 {
						filterIdentifier = firstLetterFilter
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
					filterIdentifier = continentProxyFilter
					label = Subcontinent
					fieldIdentifier = continent
					filterField = continent
					displayFields = continent
					showRowCount = 0
					multiple = 0
					inactiveOption = [ALL]

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
plugin.tx_ptextlist._LOCAL_LANG {
	default {
		emptyList = List is empty.
	}
	de {
		emptyList = Liste ist leer.
	}
}
