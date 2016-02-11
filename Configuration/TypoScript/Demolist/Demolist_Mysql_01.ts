####################################################
# This template configures a demolist for use
# with pt_extlist
#
# @author Daniel Lienert <lienert@punkt.de>
# @package Typo3
# @subpackage pt_extlist
####################################################

plugin.tx_ptextlist.settings {

	listConfig {

	   demolist_mysql_01 {

	       backendConfig < plugin.tx_ptextlist.prototype.backend.mysql
		   backendConfig {

        		dataSource {
        			username = t3ry21ptldev
        			password = upa8Shooyidom
        			databaseName = t3ry21ptldev
        		}

    			tables (
    				City,
    				Country
    			)

    			baseFromClause (
    				Country
    				INNER JOIN City ON City.CountryCode = Country.Code
    				INNER JOIN CountryLanguage ON CountryLanguage.CountryCode = Country.Code
    			)

    			baseWhereClause (
    			)

    			baseGroupByClause (
    				Country.Code, City.ID
    			)
    		}



    		fields {
    			continent_name {
    				table = Country
    				field = Continent
    			}

    			country_name {
    				table = Country
    				field = Name
    			}

    			country_localname {
    				table = Country
    				field = LocalName
    			}

    			city_name {
    				table = City
    				field = Name
    			}

    			city_population {
    				table = City
    				field = Population
    			}

    			country_population {
    				table = Country
    				field = Population
    			}

    			country_language {
    				special = group_concat(CountryLanguage.Language SEPARATOR ', ')
    			}
    		}

    		columns {

    			10 {
    				fieldIdentifier = continent_name
    				columnIdentifier = colContinentName
    				label = Continent Name
    			}

    			# Todo: allow multiple fields in one column
    			20 {
    				fieldIdentifier = country_name
    				columnIdentifier = colCountryName
    				label = Country Name
    			}

    			30 {
    				fieldIdentifier = country_population
    				columnIdentifier = colCountryPopulation
    				label = Country Population
    			}

    			40 {
    				fieldIdentifier = city_name
    				columnIdentifier = colCityName
    				label = City Name
    			}

    			50 {
    				fieldIdentifier = city_population
    				columnIdentifier = colCityPopulation
    				label = City Population
    			}


    			60 {
    				fieldIdentifier = country_language
    				columnIdentifier = colCountryLanguages
    				label = Spoken Languages
    			}

    		}

    		filters {
    			filterbox1 {
    			}
    		}

    		pager {
    			itemsPerPage = 30
    		}
    	}
    }
}