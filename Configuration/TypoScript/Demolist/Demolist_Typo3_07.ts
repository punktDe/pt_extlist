####################################################
#  List shows the usage of ajax filter
#
#
# @author Michael Knoll <knoll@punkt.de>, Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################

EXTLISTAJAX {
	typeNum = 230512

	10.10.settings {
		listIdentifier = demolist_ajaxFilter
	}
}

plugin.tx_ptextlist.settings {

	listConfig.demolist_ajaxFilter {

		controller.Filterbox.show.template = EXT:pt_extlist/Resources/Private/Templates/Filterbox/AjaxShow.html

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

		default.sortingColumn = continent
		
		fields {

			country_local {
				table = static_countries
				field = cn_short_local
				isSortable = 1
			}

			country_en {
				field = cn_short_en
				table = static_countries
				expandGroupRows = 1
			}

			uno_member {
				field = cn_uno_member
				table = static_countries
			}

			capital {
				table = static_countries
				field = cn_capital
				expandGroupRows = 1
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

			tld {
				table = static_countries
				field = cn_tldomain
				expandGroupRows = 1
			}

			isoNo {
				table = static_countries
				field = cn_currency_iso_nr
			}

			continent {
				table = st_continent
				field = tr_name_en
				isSortable = 1
			}

			subcontinent {
				table = st_subcontinent
				field = tr_name_en
			}

			countryuid {
				table = static_countries
				field = uid
			}

			countryname {
				table = static_countries
				field = cn_official_name_en
			}
		}

		columns {

			10 {
				label = Continent
				columnIdentifier = continent
				fieldIdentifier = continent
			}


			20 {
				label = Subcontinent
				columnIdentifier = subcontinent
				fieldIdentifier = subcontinent
			}

			30 {
				label = Country
				columnIdentifier = country
				fieldIdentifier = countryname
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
				filterConfigs {
					10 < plugin.tx_ptextlist.prototype.filter.string
					10 {
						filterIdentifier = filter1
						label = Local name
						fieldIdentifier = country_local
					}

					15 < plugin.tx_ptextlist.prototype.filter.max
					15 {
						filterIdentifier = filter15
						label = Max Phone
						fieldIdentifier = phone
					}

					20 < plugin.tx_ptextlist.prototype.filter.select
					20 {
						filterIdentifier = filter2
                    	label = Continent
                    	fieldIdentifier = continent
					}

					30 < plugin.tx_ptextlist.prototype.filter.select
					30 {
						filterIdentifier = filter3
						label = Subcontinent
						fieldIdentifier = subcontinent
						displayFields = continent, subcontinent
					}

					40 < plugin.tx_ptextlist.prototype.filter.select
					40 {
						filterIdentifier = filter4
						label = Country
						fieldIdentifier = countryuid
						displayFields = continent, countryname
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